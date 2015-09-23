<?php

namespace Ekyna\Bundle\MailingBundle\Subscriber;

use Ekyna\Bundle\AdminBundle\Event\ResourceEventInterface;
use Ekyna\Bundle\MailingBundle\Entity\Recipient;
use Ekyna\Bundle\UserBundle\Model\UserInterface;

/**
 * Interface SubscriberInterface
 * @package Ekyna\Bundle\MailingBundle\Subscriber
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface SubscriberInterface
{
    /**
     * Synchronizes the recipient's data with the given user.
     *
     * @param UserInterface          $user
     * @param ResourceEventInterface $event
     */
    public function synchronizeByUser(UserInterface $user, ResourceEventInterface $event = null);

    /**
     * Synchronizes the user's data with the given recipient.
     *
     * @param Recipient              $recipient
     * @param ResourceEventInterface $event
     */
    public function synchronizeByRecipient(Recipient $recipient, ResourceEventInterface $event = null);
}
