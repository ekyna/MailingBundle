<?php

namespace Ekyna\Bundle\MailingBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Ekyna\Bundle\MailingBundle\Entity\Recipient;

/**
 * Interface RecipientsSubjectInterface
 * @package Ekyna\Bundle\MailingBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface RecipientsSubjectInterface
{

    /**
     * Returns the recipients.
     *
     * @return ArrayCollection|Recipient[]
     */
    public function getRecipients();

    /**
     * Returns whether the recipient list has the given recipient or not.
     *
     * @param Recipient $recipient
     * @return bool
     */
    public function hasRecipient(Recipient $recipient);

    /**
     * Adds the recipient list.
     *
     * @param Recipient $recipient
     * @return RecipientsSubjectInterface|$this
     */
    public function addRecipient(Recipient $recipient);

    /**
     * Removes the the recipient list.
     *
     * @param Recipient $recipient
     * @return RecipientsSubjectInterface|$this
     */
    public function removeRecipient(Recipient $recipient);

    /**
     * Sets the recipients.
     *
     * @param ArrayCollection $recipients
     * @return RecipientsSubjectInterface|$this
     */
    public function setRecipients(ArrayCollection $recipients);
}
