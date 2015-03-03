<?php

namespace Ekyna\Bundle\MailingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class ImportRecipients
 * @package Ekyna\Bundle\MailingBundle\Validator\Constraints
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ImportRecipients extends Constraint
{
    public $sameColNum = 'ekyna_mailing.recipient_provider.import.file.same_col_num';
    public $emptyFirstNameColNum = 'ekyna_mailing.recipient_provider.import.file.empty_first_name_col_num';
    public $emptyLastNameColNum = 'ekyna_mailing.recipient_provider.import.file.empty_last_name_col_num';

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}