<?php

namespace Ekyna\Bundle\MailingBundle\Model;

/**
 * Class ExecutionStates
 * @package Ekyna\Bundle\MailingBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
final class ExecutionStates
{
    const STATE_DESIGN  = 'design';
    const STATE_PENDING = 'pending';
    const STATE_STARTED = 'started';
    const STATE_RUNNING = 'running';
    const STATE_PAUSED  = 'paused';
    const STATE_ABORT   = 'abort';
    const STATE_DONE    = 'done';

    /**
     * Returns whether the given type is a valid execution type or not.
     *
     * @param string $type
     * @return bool
     */
    public static function isValid($type)
    {
        return in_array($type, array(
            self::STATE_DESIGN,
            self::STATE_PENDING,
            self::STATE_STARTED,
            self::STATE_RUNNING,
            self::STATE_PAUSED,
            self::STATE_ABORT,
            self::STATE_DONE,
        ));
    }

    /**
     * Returns the label for the given execution type.
     *
     * @param string $state
     * @return string
     */
    public static function getLabel($state)
    {
        if (self::isValid($state)) {
            return self::getChoices()[$state];
        }
        throw new \InvalidArgumentException(sprintf('Unknown execution state "%s".', $state));
    }

    /**
     * Returns the theme for the given state.
     *
     * @param string $state
     * @return string
     */
    public static function getTheme($state)
    {
        switch ($state) {
            case self::STATE_DESIGN:
                return 'primary';
            case self::STATE_PENDING:
            case self::STATE_STARTED :
            case self::STATE_PAUSED :
                return 'warning';
            case self::STATE_RUNNING :
                return 'info';
            case self::STATE_DONE :
                return 'success';
            case self::STATE_ABORT :
                return 'danger';
        }
        return 'default';
    }

    /**
     * Returns the choices.
     *
     * @return array
     */
    public static function getChoices()
    {
        $translationBase = 'ekyna_mailing.execution.state.';

        return array(
            self::STATE_DESIGN  => $translationBase.self::STATE_DESIGN,
            self::STATE_PENDING => $translationBase.self::STATE_PENDING,
            self::STATE_STARTED => $translationBase.self::STATE_STARTED,
            self::STATE_RUNNING => $translationBase.self::STATE_RUNNING,
            self::STATE_PAUSED  => $translationBase.self::STATE_PAUSED,
            self::STATE_ABORT   => $translationBase.self::STATE_ABORT,
            self::STATE_DONE    => $translationBase.self::STATE_DONE,
        );
    }
}
