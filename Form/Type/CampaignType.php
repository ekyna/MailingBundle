<?php

namespace Ekyna\Bundle\MailingBundle\Form\Type;

use Ekyna\Bundle\AdminBundle\Form\Type\ResourceFormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CampaignType
 * @package Ekyna\Bundle\MailingBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class CampaignType extends ResourceFormType
{
    /**
     * @var array
     */
    protected $templates;

    /**
     * Sets the templates.
     *
     * @param array $templates
     */
    public function setTemplates(array $templates)
    {
        $tmp = [];
        foreach ($templates as $name => $config) {
            $tmp[$name] = $config['label'];
        }
        $this->templates = $tmp;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'ekyna_core.field.name',
                'required' => true,
            ))
            ->add('fromEmail', 'text', array(
                'label' => 'ekyna_mailing.campaign.field.from_email',
                'required' => true,
            ))
            ->add('fromName', 'text', array(
                'label' => 'ekyna_mailing.campaign.field.from_name',
                'required' => true,
            ))
            ->add('subject', 'text', array(
                'label' => 'ekyna_core.field.subject',
                'required' => true,
            ))
            ->add('template', 'choice', array(
                'label' => 'ekyna_mailing.campaign.field.template',
                'choices' => $this->templates,
                'empty_value' => 'ekyna_core.value.choose',
                'attr' => array(
                    'placeholder' => 'ekyna_core.value.choose',
                ),
                'multiple' => false,
                'expanded' => false,
                'required' => true,
            ))
            ->add('content', 'textarea', array(
                'label' => 'ekyna_core.field.content',
                'required' => true,
                'attr' => array(
                    'class' => 'tinymce',
                    'data-theme' => 'advanced',
                    'data-config' => json_encode(['remove_script_host' => false])
                ),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_mailing_campaign';
    }
}
