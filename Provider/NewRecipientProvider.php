<?php

namespace Ekyna\Bundle\MailingBundle\Provider;

use Ekyna\Bundle\MailingBundle\Entity\Recipient;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NewRecipientProvider
 * @package Ekyna\Bundle\MailingBundle\Provider
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class NewRecipientProvider extends AbstractRecipientProvider
{
    /**
     * {@inheritdoc}
     */
    public function buildForm($action)
    {
        $form = $this->formFactory
            ->create('ekyna_mailing_recipient', new Recipient(), array(
                'action' => $action,
                'attr' => array('class' => 'form-horizontal'),
            ))
            ->add('actions', 'form_actions', [
                'buttons' => [
                    'save' => ['type' => 'submit', 'options' => ['label' => 'ekyna_core.button.create']],
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
            return array($form->getData());
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormTemplate()
    {
        return 'EkynaMailingBundle:Admin/Provider:new_recipient_form.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'ekyna_mailing.recipient_provider.new';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'new_recipient_provider';
    }
}
