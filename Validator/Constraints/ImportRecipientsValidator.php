<?php

namespace Ekyna\Bundle\MailingBundle\Validator\Constraints;

use Ekyna\Bundle\MailingBundle\Model\ExecutionTypes;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class ImportRecipientsValidator
 * @package Ekyna\Bundle\MailingBundle\Validator\Constraints
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ImportRecipientsValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($import, Constraint $constraint)
    {
        if (!$constraint instanceof ImportRecipients) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\ImportRecipients');
        }

        /**
         * @var ImportRecipients $constraint
         * @var \Ekyna\Bundle\MailingBundle\Model\ImportRecipients $import
         */
        $emailColNum = intval($import->getEmailColNum());

        $firstNameColNum = $import->getFirstNameColNum();
        $firstNameColNum = null !== $firstNameColNum ? intval($firstNameColNum) : false;

        $lastNameColNum = $import->getLastNameColNum();
        $lastNameColNum = null !== $lastNameColNum ? intval($lastNameColNum) : false;

        // Both first name and last name column numbers or none.
        if (false !== $lastNameColNum && false === $firstNameColNum) {
            $this->context->addViolationAt('firstNameColNum', $constraint->emptyFirstNameColNum);
        }
        if (false !== $firstNameColNum && false === $lastNameColNum) {
            $this->context->addViolationAt('lastNameColNum', $constraint->emptyLastNameColNum);
        }
        // First name column number not same as email or last name.
        if (false !== $firstNameColNum && ($firstNameColNum === $emailColNum || $firstNameColNum === $lastNameColNum)) {
            $this->context->addViolationAt('firstNameColNum', $constraint->sameColNum);
        }
        if (false !== $lastNameColNum && ($lastNameColNum === $emailColNum || $lastNameColNum === $firstNameColNum)) {
            $this->context->addViolationAt('lastNameColNum', $constraint->sameColNum);
        }
    }
}
