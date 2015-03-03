<?php

namespace Ekyna\Bundle\MailingBundle\Controller\Admin;

use Doctrine\ORM\QueryBuilder;
use Ekyna\Bundle\AdminBundle\Controller\Context;
use Ekyna\Bundle\MailingBundle\Entity\Execution;
use Ekyna\Bundle\MailingBundle\Model\ExecutionTypes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ExecutionController
 * @package Ekyna\Bundle\MailingBundle\Controller\Admin
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ExecutionController extends RecipientsSubjectController
{
    /**
     * Renders the execution controls.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function controlsAction(Request $request)
    {
        $context = $this->loadContext($request);
        /** @var \Ekyna\Bundle\MailingBundle\Entity\Execution $execution */
        $execution = $context->getResource();

        $this->isGranted('VIEW', $execution);

        $response = new Response();
        $response->setLastModified($execution->getUpdatedAt());

        if ($response->isNotModified($request)) {
            return $response;
        }

        // TODO return json and use jms/twig ?

        return $this->render('EkynaMailingBundle:Admin/Execution:controls.html.twig', array(
            'execution' => $execution,
        ), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function createRecipientsTable(Context $context)
    {
        /** @var \Ekyna\Bundle\MailingBundle\Entity\Execution $execution */
        $execution = $context->getResource();

        $qb = $this->getRepository()->createQueryBuilder('e');
        $results = $qb
            ->select(array('r.id'))
            ->join('e.recipients', 'r')
            ->where($qb->expr()->eq('e.id', $execution->getId()))
            ->groupBy('r.id')
            ->getQuery()
            ->getScalarResult()
        ;

        if (empty($results)) {
            $executionIds = array(0);
        } else {
            $executionIds = array_map(function ($e) {
                return $e['id'];
            }, $results);
        }

        return $this->getTableFactory()
            ->createBuilder('ekyna_mailing_recipient', array(
                'name' => 'ekyna_mailing.recipient',
                'customize_qb' => function (QueryBuilder $qb, $alias) use ($executionIds) {
                    $qb
                        ->andWhere($alias.'.id IN (:ids)')
                        ->setParameter('ids', $executionIds);
                },
                'delete_button' => array(
                    'label' => 'ekyna_core.button.unlink',
                    'class' => 'danger',
                    'route_name' => 'ekyna_mailing_execution_admin_recipients_unlink',
                    'route_parameters' => $context->getIdentifiers(true),
                    'route_parameters_map' => array('recipientId' => 'id'),
                )
            ))
            ->getTable($context->getRequest());
    }

    /**
     * Lock action.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function lockAction(Request $request)
    {
        $context = $this->loadContext($request);
        /** @var \Ekyna\Bundle\MailingBundle\Entity\Execution $execution */
        $execution = $context->getResource();

        $this->isGranted('EDIT', $execution);

        /** @var \Ekyna\Bundle\MailingBundle\Operator\ExecutionOperator $operator */
        $operator = $this->getOperator();
        $event = $operator->lock($execution);
        $event->toFlashes($this->getFlashBag());

        return $this->redirectToShowExecution($execution);
    }

    /**
     * Unlock action.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function unlockAction(Request $request)
    {
        $context = $this->loadContext($request);
        /** @var \Ekyna\Bundle\MailingBundle\Entity\Execution $execution */
        $execution = $context->getResource();

        $this->isGranted('EDIT', $execution);

        /** @var \Ekyna\Bundle\MailingBundle\Operator\ExecutionOperator $operator */
        $operator = $this->getOperator();
        $event = $operator->unlock($execution);
        $event->toFlashes($this->getFlashBag());

        return $this->redirectToShowExecution($execution);
    }

    /**
     * Start action.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function startAction(Request $request)
    {
        $context = $this->loadContext($request);
        /** @var \Ekyna\Bundle\MailingBundle\Entity\Execution $execution */
        $execution = $context->getResource();

        $this->isGranted('EDIT', $execution);

        if ($execution->getType() === ExecutionTypes::TYPE_AUTO) {
            $this->addFlash('ekyna_mailing.execution.message.start_auto', 'warning');
        } else {
            /** @var \Ekyna\Bundle\MailingBundle\Operator\ExecutionOperator $operator */
            $operator = $this->getOperator();
            $event = $operator->start($execution);
            $event->toFlashes($this->getFlashBag());
        }

        return $this->redirectToShowExecution($execution);
    }

    /**
     * Stop action.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function stopAction(Request $request)
    {
        $context = $this->loadContext($request);
        /** @var \Ekyna\Bundle\MailingBundle\Entity\Execution $execution */
        $execution = $context->getResource();

        $this->isGranted('EDIT', $execution);

        /** @var \Ekyna\Bundle\MailingBundle\Operator\ExecutionOperator $operator */
        $operator = $this->getOperator();
        $event = $operator->stop($execution);
        $event->toFlashes($this->getFlashBag());

        return $this->redirectToShowExecution($execution);
    }

    /**
     * Returns a redirect response to the show execution page.
     *
     * @param Execution $execution
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function redirectToShowExecution(Execution $execution)
    {
        return $this->redirect($this->generateUrl('ekyna_mailing_execution_admin_show', array(
            'campaignId' => $execution->getCampaign()->getId(),
            'executionId' => $execution->getId(),
        )));
    }
}
