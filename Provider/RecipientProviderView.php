<?php

namespace Ekyna\Bundle\MailingBundle\Provider;

use Symfony\Component\Form\FormView;

/**
 * Class RecipientProviderView
 * @package Ekyna\Bundle\MailingBundle\Provider
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class RecipientProviderView
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $template;

    /**
     * @var FormView
     */
    private $form;


    /**
     * Constructor.
     *
     * @param RecipientProviderInterface $provider
     */
    public function __construct(RecipientProviderInterface $provider)
    {
        $this->name     = $provider->getName();
        $this->label    = $provider->getLabel();
        $this->template = $provider->getFormTemplate();
        $this->form     = $provider->getForm()->createView();
    }

    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Returns the template.
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Returns the form.
     *
     * @return FormView
     */
    public function getForm()
    {
        return $this->form;
    }
}
