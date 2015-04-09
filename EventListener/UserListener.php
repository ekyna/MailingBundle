<?php

namespace Ekyna\Bundle\MailingBundle\EventListener;

use Ekyna\Bundle\MailingBundle\Subscriber\SubscriberInterface;
use Ekyna\Bundle\UserBundle\Event\UserEvent;
use Ekyna\Bundle\UserBundle\Event\UserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class UserListener
 * @package Ekyna\Bundle\MailingBundle\EventListener
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UserListener implements EventSubscriberInterface
{
    /**
     * @var SubscriberInterface
     */
    protected $subscriber;

    /**
     * Constructor.
     *
     * @param SubscriberInterface $subscriber
     */
    public function __construct(SubscriberInterface $subscriber)
    {
        $this->subscriber = $subscriber;
    }

    /**
     * Post create event handler.
     *
     * @param UserEvent $event
     */
    public function onPostCreate(UserEvent $event)
    {
        $this->subscriber->synchronizeByUser($event->getUser(), $event);
    }

    /**
     * Post update event handler.
     *
     * @param UserEvent $event
     */
    public function onPostUpdate(UserEvent $event)
    {
        $this->subscriber->synchronizeByUser($event->getUser(), $event);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            UserEvents::POST_CREATE => array('onPostCreate', 0),
            UserEvents::POST_UPDATE => array('onPostUpdate', 0),
        );
    }
}
