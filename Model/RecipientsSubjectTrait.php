<?php

namespace Ekyna\Bundle\MailingBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Ekyna\Bundle\MailingBundle\Entity\Recipient;

/**
 * Trait RecipientsSubjectTrait
 * @package Ekyna\Bundle\MailingBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
trait RecipientsSubjectTrait
{
    /**
     * @var ArrayCollection|Recipient[]
     */
    protected $recipients;

    /**
     * Returns the recipients.
     *
     * @return ArrayCollection|Recipient[]
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * Returns whether the recipient subject has the given recipient or not.
     *
     * @param Recipient $recipient
     * @return bool
     */
    public function hasRecipient(Recipient $recipient)
    {
        return $this->recipients->contains($recipient);
    }

    /**
     * Returns whether the recipient subject has the given recipient or not.
     *
     * @param string $email
     * @return bool
     */
    public function hasRecipientByEmail($email)
    {
        foreach ($this->recipients as $recipient) {
            if ($email === $recipient->getEmail()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Adds the recipient.
     *
     * @param Recipient $recipient
     * @return RecipientsSubjectInterface|$this
     */
    public function addRecipient(Recipient $recipient)
    {
        if (!$this->hasRecipient($recipient)) {
            $this->recipients->add($recipient);
        }
        return $this;
    }

    /**
     * Removes the the recipient.
     *
     * @param Recipient $recipient
     * @return RecipientsSubjectInterface|$this
     */
    public function removeRecipient(Recipient $recipient)
    {
        if ($this->hasRecipient($recipient)) {
            $this->recipients->removeElement($recipient);
        }
        return $this;
    }

    /**
     * Sets the recipients.
     *
     * @param ArrayCollection $recipients
     * @return RecipientsSubjectInterface|$this
     */
    public function setRecipients(ArrayCollection $recipients)
    {
        $this->recipients = $recipients;
        return $this;
    }
}
