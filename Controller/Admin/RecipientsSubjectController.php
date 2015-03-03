<?php

namespace Ekyna\Bundle\MailingBundle\Controller\Admin;

use Ekyna\Bundle\AdminBundle\Controller\Context;
use Ekyna\Bundle\AdminBundle\Controller\ResourceController;
use Ekyna\Bundle\MailingBundle\Entity\Execution;
use Ekyna\Component\Table\Table;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class RecipientsSubjectController
 * @package Ekyna\Bundle\MailingBundle\Controller\Admin
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class RecipientsSubjectController extends ResourceController
{
    /**
     * @param Context $context
     * @return Table
     */
    abstract public function createRecipientsTable(Context $context);

    /**
     * {@inheritdoc}
     */
    protected function buildShowData(array &$data, Context $context)
    {
        // Recipients table
        $table = $this->createRecipientsTable($context);
        $data['recipients'] = $table->createView();

        /** @var \Ekyna\Bundle\MailingBundle\Model\RecipientsSubjectInterface $resource */
        $resource = $context->getResource();

        if ($resource instanceof Execution) {
            /** @var \Ekyna\Bundle\MailingBundle\Entity\Execution $resource */
            if ($resource->getLocked()) {
                return null;
            }
        }

        $action = $this->getResourceHelper()->generateResourcePath($resource);

        $providers = $this->get('ekyna_mailing.recipient_provider.registry')->getProviders();
        foreach ($providers as $provider) {
            $provider->buildForm($action);

            if (false !== $recipients = $provider->handleRequest($context->getRequest())) {
                $count = 0;
                foreach ($recipients as $recipient) {
                    if (!$resource->hasRecipientByEmail($recipient->getEmail())) {
                        $resource->addRecipient($recipient);
                        $count++;
                    }
                }

                // TODO use ResourceManager
                $event = $this->getOperator()->update($resource);
                $event->toFlashes($this->getFlashBag());
                if (0 < $count && !$event->isPropagationStopped()) {
                    $this->addFlash($this->getTranslator()->trans(
                        'ekyna_mailing.recipient_provider.added_count',
                        array('%count%' => $count)
                    ), 'info');
                }

                return $this->redirect($action);
            }
        }

        $data['providers'] = [];
        foreach ($providers as $provider) {
            $data['providers'][] = $provider->getView();
        }

        return null;
    }

    /**
     * Creates the add recipient form.
     *
     * @param Context $context
     * @return \Symfony\Component\Form\FormInterface|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function createAddRecipientForm(Context $context)
    {
        /** @var \Ekyna\Bundle\MailingBundle\Model\RecipientsSubjectInterface $resource */
        $resource = $context->getResource();

        $action = $this->getResourceHelper()->generateResourcePath($resource);

        $form = $this->createForm('ekyna_mailing_add_recipient', null, array(
            'action' => $action,
            'attr' => array('class' => 'form-horizontal'),
        ))
        ->add('actions', 'form_actions', [
            'buttons' => [
                'save' => ['type' => 'submit', 'options' => ['label' => 'ekyna_core.button.save']],
            ]
        ]);

        $form->handleRequest($context->getRequest());
        if ($form->isValid()) {
            /** @var \Ekyna\Bundle\MailingBundle\Entity\Recipient $recipient */
            $recipient = $form->get('recipient')->getData();

            $resource->addRecipient($recipient);

            // TODO use ResourceManager
            $event = $this->getOperator()->update($resource);
            $event->toFlashes($this->getFlashBag());

            return $this->redirect($action);
        }

        return $form;
    }

    /**
     * Creates the new recipient form.
     *
     * @param Context $context
     * @return \Symfony\Component\Form\FormInterface|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function createNewRecipientForm(Context $context)
    {
        /** @var \Ekyna\Bundle\MailingBundle\Model\RecipientsSubjectInterface $resource */
        $resource = $context->getResource();

        $action = $this->getResourceHelper()->generateResourcePath($resource);

        $form = $this->createForm('ekyna_mailing_recipient', null, array(
            'action' => $action,
            'attr' => array('class' => 'form-horizontal'),
        ))
        ->add('actions', 'form_actions', [
            'buttons' => [
                'save' => ['type' => 'submit', 'options' => ['label' => 'ekyna_core.button.save']],
            ]
        ]);

        $form->handleRequest($context->getRequest());
        if ($form->isValid()) {
            /** @var \Ekyna\Bundle\MailingBundle\Entity\Recipient $recipient */
            $recipient = $form->getData();
            $resource->addRecipient($recipient);

            // TODO use ResourceManager
            $event = $this->getOperator()->update($resource);
            $event->toFlashes($this->getFlashBag());

            return $this->redirect($action);
        }

        return $form;
    }

    /**
     * Unlink recipient action.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function unlinkRecipientAction(Request $request)
    {
        $context = $this->loadContext($request);

        /** @var \Ekyna\Bundle\MailingBundle\Model\RecipientsSubjectInterface $resource */
        if (null === $resource = $context->getResource()) {
            throw new NotFoundHttpException('Recipients subject not found.');
        }

        $recipientId = $request->attributes->get('recipientId');
        /** @var \Ekyna\Bundle\MailingBundle\Entity\Recipient $recipient */
        if (null === $recipient = $this->get('ekyna_mailing.recipient.repository')->find($recipientId)) {
            throw new NotFoundHttpException('Recipient not found.');
        }

        $resource->removeRecipient($recipient);
        if ($this->get('validator')->validate($resource)) {
            $event = $this->getOperator()->update($resource);

            $event->toFlashes($this->getFlashBag());
        } else {
            // TODO validation error flash
        }

        return $this->redirect(
            $this->getResourceHelper()->generateResourcePath($resource)
        );
    }
}
