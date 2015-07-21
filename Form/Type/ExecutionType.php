<?php

namespace Ekyna\Bundle\MailingBundle\Form\Type;

use Ekyna\Bundle\MailingBundle\Model\ExecutionTypes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ExecutionType
 * @package Ekyna\Bundle\MailingBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ExecutionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'ekyna_core.field.name',
                'required' => false,
            ))
            ->add('type', 'choice', array(
                'label' => 'ekyna_core.field.type',
                'choices' => ExecutionTypes::getChoices(),
            ))
            ->add('startDate', 'datetime', array(
                'label' => 'ekyna_core.field.start_date',
                'required' => false,
            ))
            ->add('recipientLists', 'entity', array(
                'label' => 'ekyna_mailing.recipientList.label.plural',
                'class' => 'Ekyna\Bundle\MailingBundle\Entity\RecipientList',
                'property' => 'name',
                'multiple' => true,
                'required' => false,
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_mailing_execution';
    }
}
