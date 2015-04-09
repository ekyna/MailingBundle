<?php

namespace Ekyna\Bundle\MailingBundle\Subscriber;

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
     * @param UserInterface $user
     */
    public function synchronizeByUser(UserInterface $user);

    /**
     * Synchronizes the user's data with the given recipient.
     *
     * @param Recipient $recipient
     */
    public function synchronizeByRecipient(Recipient $recipient);
}
