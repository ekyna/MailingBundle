<?php

namespace Ekyna\Bundle\MailingBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AddRecipientType
 * @package Ekyna\Bundle\MailingBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AddRecipientType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('recipient', 'ekyna_entity_search', array(
                'label' => 'ekyna_mailing.recipient.label.singular',
                'required' => true,
                'entity'   => 'Ekyna\Bundle\MailingBundle\Entity\Recipient',
                'search_route' => 'ekyna_mailing_recipient_admin_search',
                'find_route'   => 'ekyna_mailing_recipient_admin_find',
                'allow_clear'  => false,
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_mailing_add_recipient';
    }
}
