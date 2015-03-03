<?php

namespace Ekyna\Bundle\MailingBundle\Model;

/**
 * Class ExecutionTypes
 * @package Ekyna\Bundle\MailingBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
final class ExecutionTypes
{
    const TYPE_MANUAL = 'manual';
    const TYPE_AUTO   = 'auto';

    /**
     * Returns whether the given type is a valid execution type or not.
     *
     * @param string $type
     * @return bool
     */
    public static function isValid($type)
    {
        return in_array($type, array(self::TYPE_MANUAL, self::TYPE_AUTO));
    }

    /**
     * Returns the label for the given execution type.
     *
     * @param string $type
     * @return string
     */
    public static function getLabel($type)
    {
        if (self::isValid($type)) {
            return self::getChoices()[$type];
        }
        throw new \InvalidArgumentException(sprintf('Unknown execution type "%s".'));
    }

    /**
     * Returns the theme for the given execution type.
     *
     * @param string $type
     * @return string
     */
    public static function getTheme($type)
    {
        switch ($type) {
            case self::TYPE_MANUAL:
                return 'primary';
            case self::TYPE_AUTO :
                return 'info';
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
        $translationBase = 'ekyna_mailing.execution.type.';

        return array(
            self::TYPE_MANUAL => $translationBase.self::TYPE_MANUAL,
            self::TYPE_AUTO   => $translationBase.self::TYPE_AUTO,
        );
    }
}
