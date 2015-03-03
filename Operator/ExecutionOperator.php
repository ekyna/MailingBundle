<?php

namespace Ekyna\Bundle\MailingBundle\Operator;

use Ekyna\Bundle\AdminBundle\Event\ResourceMessage;
use Ekyna\Bundle\AdminBundle\Operator\ResourceOperator;
use Ekyna\Bundle\MailingBundle\Entity\Execution;
use Ekyna\Bundle\MailingBundle\Event\ExecutionEvent;
use SM\Factory\Factory;

/**
 * Class ExecutionOperator
 * @package Ekyna\Bundle\MailingBundle\Operator
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ExecutionOperator extends ResourceOperator
{
    /**
     * @var Factory
     */
    private $factory;


    /**
     * Sets the state machine factory.
     *
     * @param Factory $factory
     */
    public function setFactory(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Locks the execution.
     *
     * @param Execution $execution
     * @return ExecutionEvent
     */
    public function lock(Execution $execution)
    {
        return $this->applyTransition($execution, 'lock');
    }

    /**
     * Unlocks the execution.
     *
     * @param Execution $execution
     * @return ExecutionEvent
     */
    public function unlock(Execution $execution)
    {
        return $this->applyTransition($execution, 'unlock');
    }

    /**
     * Starts the execution.
     *
     * @param Execution $execution
     * @return ExecutionEvent
     */
    public function start(Execution $execution)
    {
        return $this->applyTransition($execution, 'start');
    }

    /**
     * Stops the execution.
     *
     * @param Execution $execution
     * @return ExecutionEvent
     */
    public function stop(Execution $execution)
    {
        return $this->applyTransition($execution, 'stop');
    }

    /**
     * Applies the state transition to the execution.
     *
     * @param Execution $execution
     * @param string $transition
     * @return ExecutionEvent
     */
    protected function applyTransition(Execution $execution, $transition)
    {
        $event = new ExecutionEvent($execution);

        $sm = $this->factory->get($execution);
        if (!$sm->can($transition)) {
            $event->addMessage(new ResourceMessage(
                'ekyna_mailing.execution.transition.'.$transition.'.failed',
                ResourceMessage::TYPE_ERROR
            ));
            return $event;
        }

        $this->dispatcher->dispatch($this->config->getEventName('pre_'.$transition), $event);

        if (!$event->isPropagationStopped()) {
            $sm->apply($transition);
            $this->persistResource($event);

            $this->dispatcher->dispatch($this->config->getEventName('post_'.$transition), $event);

            if (!$event->isPropagationStopped()) {
                $event->addMessage(new ResourceMessage(
                    'ekyna_mailing.execution.transition.'.$transition.'.succeed',
                    ResourceMessage::TYPE_SUCCESS
                ));
            }
        }

        return $event;
    }
}
