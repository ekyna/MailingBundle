<?php

namespace Ekyna\Bundle\MailingBundle\Provider;

use Ekyna\Bundle\MailingBundle\Entity\Recipient;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface RecipientProviderInterface
 * @package Ekyna\Bundle\MailingBundle\Provider
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface RecipientProviderInterface
{
    /**
     * Builds the form.
     *
     * @param string $action
     * @return FormInterface
     */
    public function buildForm($action);

    /**
     * Submits the form.
     *
     * @param Request $request
     * @return Recipient[]|false
     */
    public function handleRequest(Request $request);

    /**
     * Returns the provider view.
     *
     * @return RecipientProviderView
     */
    public function getView();

    /**
     * Returns the provider form.
     *
     * @return FormInterface
     */
    public function getForm();

    /**
     * Returns the provider form template.
     *
     * @return string
     */
    public function getFormTemplate();

    /**
     * Returns the provider label.
     *
     * @return string
     */
    public function getLabel();

    /**
     * Returns the provider name.
     *
     * @return string
     */
    public function getName();
}
