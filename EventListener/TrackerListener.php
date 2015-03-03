<?php

namespace Ekyna\Bundle\MailingBundle\EventListener;

use Doctrine\ORM\EntityManager;
use SM\Factory\Factory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class TrackerListener
 * @package Ekyna\Bundle\MailingBundle\EventListener
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class TrackerListener implements EventSubscriberInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @var string
     */
    private $reClass;

    /**
     * @var string
     */
    private $config;

    /**
     * @var bool
     */
    private $clearResponseCacheDirectives;


    /**
     * Constructor.
     *
     * @param EntityManager $em
     * @param Factory $factory
     * @param string $reClass
     * @param array $config
     */
    public function __construct(EntityManager $em, Factory $factory, $reClass, array $config)
    {
        $this->em = $em;
        $this->factory = $factory;
        $this->reClass = $reClass;
        $this->config  = $config;

        $this->clearResponseCacheDirectives = false;
    }

    /**
     * Kernel request event handler.
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();
        if (!$request->isMethodSafe()) {
            return;
        }

        // Mailing recipient execution visit tracking token
        if (0 < strlen($token = $request->query->get($this->config['visit_param']))) {
            $this->clearResponseCacheDirectives = true;
            $recipientExecution = $this->findRecipientExecutionByToken($token);
            if (null !== $recipientExecution) {
                $sm = $this->factory->get($recipientExecution);
                $updated = false;
                if (!$sm->can('visit') && $sm->can('open')) {
                    $sm->apply('open');
                    $updated = true;
                }
                if ($sm->can('visit')) {
                    $sm->apply('visit');
                    $updated = true;
                }
                if ($updated) {
                    $this->em->persist($recipientExecution);
                    $this->em->flush();
                    $this->em->clear();
                }
            }
        }
    }

    /**
     * Kernel response event handler.
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if ($this->clearResponseCacheDirectives) {
            $event->getResponse()
                ->setPrivate()
                ->headers->removeCacheControlDirective('s-maxage')
            ;
        }
    }

    /**
     * Returns the recipient execution by his token.
     *
     * @param string $token
     * @return \Ekyna\Bundle\MailingBundle\Entity\RecipientExecution|null
     */
    private function findRecipientExecutionByToken($token)
    {
        return $this->em->getRepository($this->reClass)->findOneBy([
            'token' => $token,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST  => array('onKernelRequest', 0),
            KernelEvents::RESPONSE => array('onKernelResponse', -1024),
        );
    }
}
