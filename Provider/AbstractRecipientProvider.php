<?php

namespace Ekyna\Bundle\MailingBundle\Provider;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractRecipientProvider
 * @package Ekyna\Bundle\MailingBundle\Provider
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractRecipientProvider implements RecipientProviderInterface
{
    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var FormInterface
     */
    private $form;


    /**
     * Sets the formFactory.
     *
     * @param FormFactory $formFactory
     */
    public function setFormFactory($formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function buildForm($action);

    /**
     * {@inheritdoc}
     */
    abstract public function handleRequest(Request $request);

    /**
     * {@inheritdoc}
     */
    public function getView()
    {
        return new RecipientProviderView($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getForm()
    {
        if (null === $this->form) {
            throw new \RuntimeException('Please call buildForm() first.');
        }
        return $this->form;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getFormTemplate();

    /**
     * {@inheritdoc}
     */
    abstract public function getLabel();

    /**
     * {@inheritdoc}
     */
    abstract public function getName();

    /**
     * Sets the form.
     *
     * @param FormInterface $form
     */
    protected function setForm(FormInterface $form)
    {
        $this->form = $form;
    }
}
