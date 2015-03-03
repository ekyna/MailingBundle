<?php

namespace Ekyna\Bundle\MailingBundle\EventListener;

use Ekyna\Bundle\AdminBundle\Event\ResourceMessage;
use Ekyna\Bundle\MailingBundle\Event\ExecutionEvent;
use Ekyna\Bundle\MailingBundle\Event\ExecutionEvents;
use Ekyna\Bundle\MailingBundle\Execution\Locker;
use Ekyna\Bundle\MailingBundle\Model\ExecutionStates;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Process;

/**
 * Class ExecutionListener
 * @package Ekyna\Bundle\MailingBundle\EventListener
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ExecutionListener implements EventSubscriberInterface
{
    /**
     * @var Locker
     */
    protected $locker;

    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * Constructor.
     *
     * @param Locker $locker
     * @param KernelInterface $kernel
     */
    public function __construct(Locker $locker, KernelInterface $kernel)
    {
        $this->locker = $locker;
        $this->kernel = $kernel;
    }

    /**
     * Pre create event handler.
     *
     * @param ExecutionEvent $event
     */
    public function onPreCreate(ExecutionEvent $event)
    {
        $execution = $event->getExecution();
        if (0 === strlen($execution->getName())) {
            $date = $execution->getStartDate();
            if (null === $date) {
                $date = new \DateTime();
            }
            $name = sprintf('%s [%s] %s',
                $execution->getCampaign()->getName(),
                $execution->getType(),
                $date->format('d/m/Y')
            );
            $execution->setName($name);
        }
    }

    /**
     * Post create event handler.
     *
     * @param ExecutionEvent $event
     */
    public function onPostCreate(ExecutionEvent $event)
    {

    }

    /**
     * Pre update event handler.
     *
     * @param ExecutionEvent $event
     */
    public function onPreUpdate(ExecutionEvent $event)
    {
        if ($event->getExecution()->getLocked()) {
            $event->addMessage(new ResourceMessage(
                'ekyna_mailing.execution.message.locked_update',
                ResourceMessage::TYPE_ERROR
            ));
        }
    }

    /**
     * Post update event handler.
     *
     * @param ExecutionEvent $event
     */
    public function onPostUpdate(ExecutionEvent $event)
    {

    }

    /**
     * Pre delete event handler.
     *
     * @param ExecutionEvent $event
     */
    public function onPreDelete(ExecutionEvent $event)
    {
        if ($event->getExecution()->getLocked()) {
            $event->addMessage(new ResourceMessage(
                'ekyna_mailing.execution.message.locked_delete',
                ResourceMessage::TYPE_ERROR
            ));
        }
    }

    /**
     * Post delete event handler.
     *
     * @param ExecutionEvent $event
     */
    public function onPostDelete(ExecutionEvent $event)
    {

    }

    /**
     * Pre lock event handler.
     *
     * @param ExecutionEvent $event
     */
    public function onPreLock(ExecutionEvent $event)
    {
        $this->locker->lock($event->getExecution());
    }

    /**
     * Post update event handler.
     *
     * @param ExecutionEvent $event
     */
    public function onPostLock(ExecutionEvent $event)
    {

    }

    /**
     * Pre unlock event handler.
     *
     * @param ExecutionEvent $event
     */
    public function onPreUnlock(ExecutionEvent $event)
    {
        $this->locker->unlock($event->getExecution());
    }

    /**
     * Post update event handler.
     *
     * @param ExecutionEvent $event
     */
    public function onPostUnlock(ExecutionEvent $event)
    {

    }

    /**
     * Pre start event handler.
     *
     * @param ExecutionEvent $event
     */
    public function onPreStart(ExecutionEvent $event)
    {
        $execution = $event->getExecution();

        if (0 == $execution->getTotal()) {
            $event->addMessage(new ResourceMessage(
                'ekyna_mailing.execution.message.no_recipient',
                ResourceMessage::TYPE_ERROR
            ));
            return;
        }

        if (null === $execution->getStartedAt()) {
            $execution->setStartedAt(new \DateTime());
        }
    }

    /**
     * Post update event handler.
     *
     * @param ExecutionEvent $event
     */
    public function onPostStart(ExecutionEvent $event)
    {
        $execution = $event->getExecution();
        if ($execution->getState() === ExecutionStates::STATE_STARTED) {
            $command = sprintf(
                'php console ekyna:mailing:run %d -e %s --no-interaction -q',
                $execution->getId(),
                $this->kernel->getEnvironment()
            );
            $process = new Process($command);
            $process->setWorkingDirectory($this->kernel->getRootDir());
            $process->start();
        }
    }

    /**
     * Pre stop event handler.
     *
     * @param ExecutionEvent $event
     */
    public function onPreStop(ExecutionEvent $event)
    {
    }

    /**
     * Post update event handler.
     *
     * @param ExecutionEvent $event
     */
    public function onPostStop(ExecutionEvent $event)
    {

    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            ExecutionEvents::PRE_CREATE     => array('onPreCreate',     0),
            ExecutionEvents::POST_CREATE    => array('onPostCreate',    0),
            ExecutionEvents::PRE_UPDATE     => array('onPreUpdate',     0),
            ExecutionEvents::POST_UPDATE    => array('onPostUpdate',    0),
            ExecutionEvents::PRE_DELETE     => array('onPreDelete',     0),
            ExecutionEvents::POST_DELETE    => array('onPostDelete',    0),

            ExecutionEvents::PRE_LOCK       => array('onPreLock',      -1024),
            ExecutionEvents::POST_LOCK      => array('onPostLock',      1024),
            ExecutionEvents::PRE_UNLOCK     => array('onPreUnlock',    -1024),
            ExecutionEvents::POST_UNLOCK    => array('onPostUnlock',    1024),
            ExecutionEvents::PRE_START      => array('onPreStart',     -1024),
            ExecutionEvents::POST_START     => array('onPostStart',     1024),
            ExecutionEvents::PRE_STOP       => array('onPreStop',      -1024),
            ExecutionEvents::POST_STOP      => array('onPostStop',      1024),
        );
    }
}
