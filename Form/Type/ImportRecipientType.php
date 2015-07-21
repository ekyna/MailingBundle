<?php

namespace Ekyna\Bundle\MailingBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints;

/**
 * Class ImportRecipientType
 * @package Ekyna\Bundle\MailingBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ImportRecipientType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', array(
                'label' => 'ekyna_mailing.recipient_provider.import.file',
            ))
            ->add('delimiter', 'text', array(
                'label' => 'ekyna_mailing.recipient_provider.import.delimiter',
            ))
            ->add('enclosure', 'text', array(
                'label' => 'ekyna_mailing.recipient_provider.import.enclosure',
            ))
            ->add('emailColNum', 'integer', array(
                'label' => 'ekyna_mailing.recipient_provider.import.email_col_num',
            ))
            ->add('firstNameColNum', 'integer', array(
                'label' => 'ekyna_mailing.recipient_provider.import.first_name_col_num',
                'required' => false,
            ))
            ->add('lastNameColNum', 'integer', array(
                'label' => 'ekyna_mailing.recipient_provider.import.last_name_col_num',
                'required' => false,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class' => 'Ekyna\Bundle\MailingBundle\Model\ImportRecipients',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_mailing_import_recipient';
    }
}
