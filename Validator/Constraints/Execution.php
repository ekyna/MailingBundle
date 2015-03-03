<?php

namespace Ekyna\Bundle\MailingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class Execution
 * @package Ekyna\Bundle\MailingBundle\Validator\Constraints
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Execution extends Constraint
{
    public $invalidType = 'ekyna_mailing.execution.invalid_type';
    public $mandatoryStartDate = 'ekyna_mailing.execution.mandatory_start_date';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}