<?php

namespace Ekyna\Bundle\MailingBundle\Entity;

use Doctrine\DBAL\Types\Type;
use Ekyna\Bundle\AdminBundle\Doctrine\ORM\ResourceRepository;
use Ekyna\Bundle\MailingBundle\Model\ExecutionStates;
use Ekyna\Bundle\MailingBundle\Model\ExecutionTypes;

/**
 * Class ExecutionRepository
 * @package Ekyna\Bundle\MailingBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ExecutionRepository extends ResourceRepository
{
    /**
     * Creates a new execution.
     *
     * @return Execution
     */
    public function createNew()
    {
        $execution = new Execution();

        // Default start tonight
        $startDate = new \DateTime();
        $startDate
            ->modify('+ 1 day')
            ->setTime(2, 0, 0)
        ;

        $execution
            ->setType(ExecutionTypes::TYPE_MANUAL)
            ->setLocked(false)
            ->setState(ExecutionStates::STATE_DESIGN)
            ->setTotal(0)
            ->setFailed(0)
            ->setSent(0)
            ->setOpened(0)
            ->setVisited(0)
        ;

        return $execution;
    }

    /**
     * Returns the number of running automated executions.
     *
     * @return int
     */
    public function countRunningAutomated()
    {
        $qb = $this->createQueryBuilder('e');

        $query = $qb
            ->select('COUNT(e.id)')
            ->andWhere($qb->expr()->eq('e.type', $qb->expr()->literal(ExecutionTypes::TYPE_AUTO)))
            ->andWhere($qb->expr()->eq('e.state', $qb->expr()->literal(ExecutionStates::STATE_RUNNING)))
            ->getQuery()
        ;

        return $query->getSingleScalarResult();
    }

    /**
     * Returns the first (start date) paused automated execution.
     *
     * @return Execution|null
     */
    public function findOnePausedAutomated()
    {
        $qb = $this->createQueryBuilder('e');

        $query = $qb
            ->andWhere($qb->expr()->eq('e.type', $qb->expr()->literal(ExecutionTypes::TYPE_AUTO)))
            ->andWhere($qb->expr()->eq('e.state', $qb->expr()->literal(ExecutionStates::STATE_PAUSED)))
            ->orderBy('e.startDate', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
        ;

        return $query
            ->getOneOrNullResult()
        ;
    }

    /**
     * Returns the first (start date) pending automated execution with a past start date.
     *
     * @return Execution|null
     */
    public function findOnePendingAutomated()
    {
        $qb = $this->createQueryBuilder('e');

        $query = $qb
            ->andWhere($qb->expr()->eq('e.type', $qb->expr()->literal(ExecutionTypes::TYPE_AUTO)))
            ->andWhere($qb->expr()->eq('e.state', $qb->expr()->literal(ExecutionStates::STATE_PENDING)))
            ->andWhere($qb->expr()->lte('e.startDate', ':now'))
            ->orderBy('e.startDate', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
        ;

        return $query
            ->setParameter('now', new \DateTime(), Type::DATETIME)
            ->getOneOrNullResult()
        ;
    }
}
