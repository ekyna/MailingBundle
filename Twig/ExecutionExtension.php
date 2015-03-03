<?php

namespace Ekyna\Bundle\MailingBundle\Twig;

use Ekyna\Bundle\MailingBundle\Entity\Execution;
use Ekyna\Bundle\MailingBundle\Model\ExecutionStates;
use Ekyna\Bundle\MailingBundle\Model\ExecutionTypes;
use SM\Factory\Factory;

/**
 * Class ExecutionExtension
 * @package Ekyna\Bundle\MailingBundle\Twig
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ExecutionExtension extends \Twig_Extension
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var \Twig_Template
     */
    protected $template;

    /**
     * Constructor.
     *
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $twig)
    {
        $this->template = $twig->loadTemplate('EkynaMailingBundle:Admin:_controls.html.twig');
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('mail_execution_type', array($this, 'renderExecutionType'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('mail_execution_state', array($this, 'renderExecutionState'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('mail_execution_btn', array($this, 'renderExecutionButton'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('mail_execution_progress', array($this, 'renderExecutionProgress'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('mail_progress_bar', array($this, 'renderProgressBar'), array('is_safe' => array('html'))),
        );
    }

    /**
     * Renders the execution state.
     *
     * @param Execution $execution
     * @return string
     */
    public function renderExecutionType(Execution $execution)
    {
        $type = $execution->getType();
        return $this->template->renderBlock('execution_state', array(
            'state' => ExecutionTypes::getLabel($type),
            'theme' => ExecutionTypes::getTheme($type),
        ));
    }

    /**
     * Renders the execution state.
     *
     * @param Execution $execution
     * @return string
     */
    public function renderExecutionState(Execution $execution)
    {
        $state = $execution->getState();
        return $this->template->renderBlock('execution_state', array(
            'state' => ExecutionStates::getLabel($state),
            'theme' => ExecutionStates::getTheme($state),
        ));
    }

    /**
     * Renders the execution controls.
     *
     * @param Execution $execution
     * @param string    $action
     * @return string
     */
    public function renderExecutionButton(Execution $execution, $action)
    {
        return $this->template->renderBlock('execution_button', array(
            'execution' => $execution,
            'action'    => $action,
            'label'     => $this->getLabelForAction($action),
            'enabled'   => $this->factory->get($execution)->can($action),
            'theme'     => $this->getThemeForAction($action),
            'icon'      => $this->getIconForAction($action),
        ));
    }

    /**
     * Renders the execution controls.
     *
     * @param Execution $execution
     * @return string
     */
    public function renderExecutionProgress(Execution $execution)
    {
        /*$percent = 0;
        if (0 < $total = $execution->getTotal()) {
            $percent = round(($execution->getSent() + $execution->getFailed()) * 100 / $total, 2);
        }*/

        return $this->renderProgressBar(array(
            'value' => $execution->getSent() + $execution->getFailed(),
            'max'   => $execution->getTotal(),
            'theme' => ExecutionStates::getTheme($execution->getState()),
        ));
    }

    /**
     * Renders the execution controls.
     *
     * @param array $params
     * @return string
     */
    public function renderProgressBar(array $params)
    {
        $params = array_merge(array(
            'min'     => 0,
            'value'   => 0,
            'max'     => 100,
            'percent' => 0,
            'append'  => '',
            'theme'   => 'default',
            'striped' => false,
        ), $params);

        $current = abs($params['value'] - $params['min']);
        $total = abs($params['max'] - $params['min']);

        if (0 < $total) {
            $params['percent'] = floor($current * 100 / $total);
            if (0 < $current) {
                $params['append'] = sprintf(' (%d/%d)', $current, $total);
            }
        }

        return $this->template->renderBlock('execution_progress', $params);
    }

    /**
     * Returns the theme for the given action.
     *
     * @param $action
     * @return string
     */
    private function getThemeForAction($action)
    {
        switch ($action) {
            case 'lock' :
            case 'unlock':
                return 'primary';
            case 'start' :
                return 'success';
            case 'stop' :
                return 'danger';
        }
        return 'default';
    }

    /**
     * Returns the icon for the given action.
     *
     * @param $action
     * @return string
     */
    private function getIconForAction($action)
    {
        if ($action === 'start') {
            return 'play';
        }
        return $action;
    }

    /**
     * Returns the label for the given action.
     *
     * @param $action
     * @return string
     */
    private function getLabelForAction($action)
    {
        if ($action === 'start') {
            return 'play';
        }
        return $action;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_mailing_execution';
    }
}
