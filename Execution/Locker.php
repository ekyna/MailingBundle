<?php

namespace Ekyna\Bundle\MailingBundle\Execution;

use Doctrine\ORM\EntityManager;
use Ekyna\Bundle\MailingBundle\Entity\Execution;
use Ekyna\Bundle\MailingBundle\Entity\Recipient;
use Ekyna\Bundle\MailingBundle\Entity\RecipientExecution;
use Ekyna\Bundle\MailingBundle\Entity\RecipientExecutionRepository;
use Ekyna\Bundle\MailingBundle\Model\RecipientExecutionStates;

/**
 * Class Locker
 * @package Ekyna\Bundle\MailingBundle\Execution
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Locker
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
     * @var RecipientExecutionRepository
     */
    protected $reRepo;


    /**
     * Constructor.
     *
     * @param EntityManager $em
     * @param string        $reClass
     */
    public function __construct(EntityManager $em, $reClass)
    {
        $this->em = $em;
        $this->reClass = $reClass;

        $this->reRepo = $this->em->getRepository($this->reClass);
    }

    /**
     * Locks the execution.
     *
     * @param Execution $execution
     */
    public function lock(Execution $execution)
    {
        $count = 1;
        $reIds = [];
        foreach ($execution->getRecipientLists() as $recipientList) {
            foreach ($recipientList->getRecipients() as $recipient) {
                if (!in_array($recipient->getId(), $reIds)) {
                    $this->createRecipientExecution($recipient, $execution);
                    $reIds[] = $recipient->getId();
                    $count++;
                }
                if ($count % 20 === 0) {
                    $this->em->flush();
                    $this->em->clear($this->reClass);
                }
            }
        }
        foreach ($execution->getRecipients() as $recipient) {
            if (!in_array($recipient->getId(), $reIds)) {
                $this->createRecipientExecution($recipient, $execution);
                $reIds[] = $recipient->getId();
                $count++;
            }
            if ($count % 20 === 0) {
                $this->em->flush();
                $this->em->clear($this->reClass);
            }
        }

        $this->em->flush();
        $this->em->clear($this->reClass);
    }

    /**
     * Unlocks the execution.
     *
     * @param Execution $execution
     */
    public function unlock(Execution $execution)
    {
        $qb = $this->em->createQueryBuilder();
        $qb
            ->delete($this->reClass, 're')
            ->andWhere($qb->expr()->eq('re.state', ':state'))
            ->andWhere($qb->expr()->eq('re.execution', ':execution'))
            ->getQuery()
            ->setParameters(array(
                'state'     => RecipientExecutionStates::STATE_PENDING,
                'execution' => $execution,
            ))
            ->execute()
        ;
    }

    /**
     * Creates the recipient execution if not exists.
     *
     * @param Recipient $recipient
     * @param Execution $execution
     */
    protected function createRecipientExecution(Recipient $recipient, Execution $execution)
    {
        if (null === $this->findRecipientExecution($recipient, $execution)) {
            $re = $this->reRepo->createNew($recipient, $execution);
            $this->em->persist($re);
            $this->em->flush($re);
        }
    }

    /**
     * Finds the recipient execution.
     *
     * @param Recipient $recipient
     * @param Execution $execution
     * @return RecipientExecution|null
     */
    protected function findRecipientExecution(Recipient $recipient, Execution $execution)
    {
        return $this->reRepo->findOneBy(array(
            'recipient' => $recipient,
            'execution' => $execution,
        ));
    }
}
