<?php

namespace Ekyna\Bundle\MailingBundle\Execution;

use Doctrine\ORM\EntityManager;
use Ekyna\Bundle\MailingBundle\Entity\Execution;
use Ekyna\Bundle\MailingBundle\Entity\RecipientExecution;

/**
 * Class Transition
 * @package Ekyna\Bundle\MailingBundle\Execution
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Transition
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var string
     */
    protected $reClass;


    /**
     * Constructor.
     *
     * @param EntityManager $em
     * @param string $reClass
     */
    public function __construct(EntityManager $em, $reClass)
    {
        $this->em = $em;
        $this->reClass = $reClass;
    }

    /**
     * Before execution lock state transition handler.
     *
     * @param Execution $execution
     */
    public function onBeforeExecutionLock(Execution $execution)
    {
        $qb = $this->em->createQueryBuilder();

        $total = $query = $qb
            ->select('COUNT(re.id) as total')
            ->from($this->reClass, 're')
            ->where($qb->expr()->eq('re.execution', ':execution'))
            ->getQuery()
            ->setParameter('execution', $execution)
            ->getSingleScalarResult()
        ;

        $execution->setLocked(true)->setTotal($total);
    }

    /**
     * Before execution unlock state transition handler.
     *
     * @param Execution $execution
     */
    public function onBeforeExecutionUnlock(Execution $execution)
    {
        $execution->setLocked(false);
    }

    /**
     * Before execution start state transition handler.
     *
     * @param Execution $execution
     */
    public function onBeforeExecutionStart(Execution $execution)
    {
        if (null === $execution->getStartedAt()) {
            $execution->setStartedAt(new \DateTime());
        }
    }

    /**
     * Before execution terminate state transition handler.
     *
     * @param Execution $execution
     */
    public function onBeforeExecutionTerminate(Execution $execution)
    {
        $execution->setCompletedAt(new \DateTime());
    }

    /**
     * Before recipient execution send state transition handler.
     *
     * @param RecipientExecution $recipientExecution
     */
    public function onBeforeRecipientFail(RecipientExecution $recipientExecution)
    {
        $recipientExecution->getExecution()->incrementFailed();
    }

    /**
     * Before recipient execution send state transition handler.
     *
     * @param RecipientExecution $recipientExecution
     */
    public function onBeforeRecipientSend(RecipientExecution $recipientExecution)
    {
        $recipientExecution->getExecution()->incrementSent();
    }

    /**
     * Before recipient execution send state transition handler.
     *
     * @param RecipientExecution $recipientExecution
     */
    public function onBeforeRecipientOpen(RecipientExecution $recipientExecution)
    {
        $recipientExecution->getExecution()->incrementOpened();
    }

    /**
     * Before recipient execution send state transition handler.
     *
     * @param RecipientExecution $recipientExecution
     */
    public function onBeforeRecipientVisit(RecipientExecution $recipientExecution)
    {
        $recipientExecution->getExecution()->incrementVisited();
    }
}
