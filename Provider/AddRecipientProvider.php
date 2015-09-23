<?php

namespace Ekyna\Bundle\MailingBundle\Provider;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class AddRecipientProvider
 * @package Ekyna\Bundle\MailingBundle\Provider
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AddRecipientProvider extends AbstractRecipientProvider
{
    /**
     * {@inheritdoc}
     */
    public function buildForm($action)
    {
        $form = $this->formFactory
            ->create('ekyna_mailing_add_recipient', null, [
                'action' => $action,
                'attr' => ['class' => 'form-horizontal'],
            ])
            ->add('actions', 'form_actions', [
                'buttons' => [
                    'save' => ['type' => 'submit', 'options' => ['label' => 'ekyna_core.button.add']],
                ]
            ])
        ;

        $this->setForm($form);

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(Request $request)
    {
        $form = $this->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            return [$form->get('recipient')->getData()];
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormTemplate()
    {
        return 'EkynaMailingBundle:Admin/Provider:add_recipient_form.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'ekyna_mailing.recipient_provider.add';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'add_recipient_provider';
    }
}
