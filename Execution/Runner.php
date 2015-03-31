<?php

namespace Ekyna\Bundle\MailingBundle\Execution;

use Doctrine\ORM\EntityManager;
use Ekyna\Bundle\MailingBundle\Entity\Execution;
use Ekyna\Bundle\MailingBundle\Entity\RecipientExecution;
use Ekyna\Bundle\MailingBundle\Entity\RecipientExecutionRepository;
use Ekyna\Bundle\MailingBundle\Model\ExecutionStates;
use Ekyna\Bundle\MailingBundle\Model\RecipientExecutionStates;
use SM\Factory\Factory;

/**
 * Class Runner
 * @package Ekyna\Bundle\MailingBundle\Execution
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Runner
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var Renderer
     */
    protected $renderer;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var string
     */
    protected $reClass;

    /**
     * @var RecipientExecutionRepository
     */
    protected $reRepo;


    /**
     * Constructor.
     *
     * @param EntityManager $em
     * @param Factory       $factory
     * @param Renderer      $renderer
     * @param \Swift_Mailer $mailer
     * @param string        $reClass
     */
    public function __construct(
        EntityManager $em,
        Factory $factory,
        Renderer $renderer,
        \Swift_Mailer $mailer,
        $reClass
    ) {
        $this->em         = $em;
        $this->factory    = $factory;
        $this->renderer   = $renderer;
        $this->mailer     = $mailer;
        $this->reClass    = $reClass;

        $this->reRepo = $this->em->getRepository($this->reClass);
    }

    /**
     * Runs the execution.
     *
     * @param Execution $execution
     * @throws \SM\SMException
     */
    public function run(Execution $execution)
    {
        $startTime = time();
        $maxTime = ini_get('max_execution_time');
        if (5 < $maxTime) {
            $maxTime -= 5; // We keep 5 seconds to update execution state
        }

        // Update state => running
        if ($this->applyTransition($execution, 'run')) {
            // Fetch next recipient
            while (null !== $re = $this->findNextRecipientExecution($execution)) {
                // Prevent to exceed max execution time
                if ((0 < $maxTime) && (time() - $startTime > $maxTime)) {
                    // Update state => paused
                    $this->applyTransition($execution, 'pause');
                    return;
                }

                // Send email
                $this->send($re);
                //sleep(4);

                // Checks that the execution has not been abort.
                $this->em->refresh($execution);
                if ($execution->getState() !== ExecutionStates::STATE_RUNNING) {
                    return;
                }
            }

            // Update state => done
            $this->applyTransition($execution, 'terminate');
        }
    }

    /**
     * Suspends the execution.
     *
     * @param Execution $execution
     * @throws \SM\SMException
     */
    protected function pause(Execution $execution)
    {
        $this->applyTransition($execution, 'pause');
    }

    /**
     * Terminates the execution.
     *
     * @param Execution $execution
     * @throws \SM\SMException
     */
    protected function terminate(Execution $execution)
    {
        $this->applyTransition($execution, 'terminate');
    }

    /**
     * Returns the next recipient execution.
     *
     * @param Execution $execution
     * @return RecipientExecution|null
     */
    protected function findNextRecipientExecution(Execution $execution)
    {
        return $this->reRepo->findOneBy([
            'execution' => $execution,
            'state' => RecipientExecutionStates::STATE_PENDING
        ]);
    }

    /**
     * Sends the email and update the recipient execution state.
     *
     * @param RecipientExecution $re
     */
    protected function send(RecipientExecution $re)
    {
        $campaign = $re->getExecution()->getCampaign();
        $recipient = $re->getRecipient();

        $content = $this->renderer->render($re);

        $message = new \Swift_Message();
        $message
            ->setFrom($campaign->getFromEmail(), $campaign->getFromName())
            ->setTo($recipient->getEmail(), $recipient->getName())
            ->setSubject($campaign->getSubject())
            ->setBody($content, 'text/html')
        ;

        if (1 == $this->mailer->send($message)) {
            $this->applyTransition($re, 'send');
        } else {
            $this->applyTransition($re, 'fail');
        }
    }

    /**
     * @param mixed $object
     * @param $transition
     * @return bool
     * @throws \SM\SMException
     */
    protected function applyTransition($object, $transition)
    {
        $sm = $this->factory->get($object);
        if ($sm->can($transition)) {
            $sm->apply($transition);
            $this->em->persist($object);
            $this->em->flush();

            return true;
        }

        return false;
    }
}
