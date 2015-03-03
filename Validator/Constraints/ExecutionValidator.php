<?php

namespace Ekyna\Bundle\MailingBundle\Validator\Constraints;

use Ekyna\Bundle\MailingBundle\Model\ExecutionTypes;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class ExecutionValidator
 * @package Ekyna\Bundle\MailingBundle\Validator\Constraints
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ExecutionValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($execution, Constraint $constraint)
    {
        if (!$constraint instanceof Execution) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\Execution');
        }

        /**
         * @var Execution $constraint
         * @var \Ekyna\Bundle\MailingBundle\Entity\Execution $execution
         */
        if (!ExecutionTypes::isValid($execution->getType())) {
            $this->context->addViolationAt('type', $constraint->invalidType);
        } elseif ($execution->getType() === ExecutionTypes::TYPE_AUTO && null === $execution->getStartDate()) {
            $this->context->addViolationAt('startDate', $constraint->mandatoryStartDate);
        }
    }
}
