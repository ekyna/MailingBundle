<?php

namespace Ekyna\Bundle\MailingBundle\Event;

use Ekyna\Bundle\AdminBundle\Event\ResourceEvent;
use Ekyna\Bundle\MailingBundle\Entity\Recipient;

/**
 * Class RecipientEvent
 * @package Ekyna\Bundle\MailingBundle\Event
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class RecipientEvent extends ResourceEvent
{
    /**
     * Constructor.
     *
     * @param Recipient $recipient
     */
    public function __construct(Recipient $recipient)
    {
        $this->setResource($recipient);
    }

    /**
     * Returns the user.
     *
     * @return Recipient
     */
    public function getRecipient()
    {
        return $this->getResource();
    }
}
