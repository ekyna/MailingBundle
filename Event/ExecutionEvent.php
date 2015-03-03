<?php

namespace Ekyna\Bundle\MailingBundle\Event;

use Ekyna\Bundle\AdminBundle\Event\ResourceEvent;
use Ekyna\Bundle\MailingBundle\Entity\Execution;

/**
 * Class ExecutionEvent
 * @package Ekyna\Bundle\MailingBundle\Event
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ExecutionEvent extends ResourceEvent
{
    /**
     * Constructor.
     *
     * @param Execution $execution
     */
    public function __construct(Execution $execution)
    {
        $this->setResource($execution);
    }

    /**
     * Returns the user.
     *
     * @return Execution
     */
    public function getExecution()
    {
        return $this->getResource();
    }
}
