<?php

namespace Ekyna\Bundle\MailingBundle\EventListener;

use Ekyna\Bundle\MailingBundle\Event\RecipientEvent;
use Ekyna\Bundle\MailingBundle\Event\RecipientEvents;
use Ekyna\Bundle\MailingBundle\Subscriber\SubscriberInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class RecipientListener
 * @package Ekyna\Bundle\MailingBundle\EventListener
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class RecipientListener implements EventSubscriberInterface
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
     * @param RecipientEvent $event
     */
    public function onPostCreate(RecipientEvent $event)
    {
        $this->subscriber->synchronizeByRecipient($event->getRecipient(), $event);
    }

    /**
     * Post update event handler.
     *
     * @param RecipientEvent $event
     */
    public function onPostUpdate(RecipientEvent $event)
    {
        $this->subscriber->synchronizeByRecipient($event->getRecipient(), $event);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            RecipientEvents::POST_CREATE => array('onPostCreate', 0),
            RecipientEvents::POST_UPDATE => array('onPostUpdate', 0),
        );
    }
}
