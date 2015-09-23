<?php

namespace Ekyna\Bundle\MailingBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
            ->add('file', 'file', [
                'label' => 'ekyna_mailing.recipient_provider.import.file',
            ])
            ->add('delimiter', 'text', [
                'label' => 'ekyna_mailing.recipient_provider.import.delimiter',
            ])
            ->add('enclosure', 'text', [
                'label' => 'ekyna_mailing.recipient_provider.import.enclosure',
            ])
            ->add('emailColNum', 'integer', [
                'label' => 'ekyna_mailing.recipient_provider.import.email_col_num',
            ])
            ->add('firstNameColNum', 'integer', [
                'label' => 'ekyna_mailing.recipient_provider.import.first_name_col_num',
                'required' => false,
            ])
            ->add('lastNameColNum', 'integer', [
                'label' => 'ekyna_mailing.recipient_provider.import.last_name_col_num',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => 'Ekyna\Bundle\MailingBundle\Model\ImportRecipients',
            ])
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
