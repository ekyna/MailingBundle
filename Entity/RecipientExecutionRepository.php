<?php

namespace Ekyna\Bundle\MailingBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Ekyna\Bundle\MailingBundle\Model\RecipientExecutionStates;

/**
 * Class RecipientExecutionRepository
 * @package Ekyna\Bundle\MailingBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class RecipientExecutionRepository extends EntityRepository
{
    /**
     * Creates a new recipient execution.
     *
     * @param Recipient $recipient
     * @param Execution $execution
     * @return RecipientExecution
     */
    public function createNew(Recipient $recipient, Execution $execution)
    {
        do {
            $token = hash('md5', uniqid());
        } while(null !== $this->findOneBy(['token' => $token]));

        $recipientExecution = new RecipientExecution();

        $recipientExecution
            ->setRecipient($recipient)
            ->setExecution($execution)
            ->setToken($token)
            ->setState(RecipientExecutionStates::STATE_PENDING)
        ;

        return $recipientExecution;
    }
}
