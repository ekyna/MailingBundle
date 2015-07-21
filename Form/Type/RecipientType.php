<?php

namespace Ekyna\Bundle\MailingBundle\Form\Type;

use Ekyna\Bundle\AdminBundle\Form\Type\ResourceFormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class RecipientType
 * @package Ekyna\Bundle\MailingBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class RecipientType extends ResourceFormType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'text', array(
                'label' => 'ekyna_core.field.email',
                'required' => true,
            ))
            ->add('firstName', 'text', array(
                'label' => 'ekyna_core.field.first_name',
                'required' => false,
            ))
            ->add('lastName', 'text', array(
                'label' => 'ekyna_core.field.last_name',
                'required' => false,
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_mailing_recipient';
    }
}
