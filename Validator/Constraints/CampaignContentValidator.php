<?php

namespace Ekyna\Bundle\MailingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\UrlValidator;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class CampaignContentValidator
 * @package Ekyna\Bundle\MailingBundle\Validator\Constraints
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class CampaignContentValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($content, Constraint $constraint)
    {
        if (!$constraint instanceof CampaignContent) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\CampaignContent');
        }

        /**
         * @var CampaignContent $constraint
         */
        $urlRegex = sprintf(UrlValidator::PATTERN, 'https?|ftp');
        $emailRegex = '~^mailto:.+\@.+\..+$~';

        if (preg_match_all('`<a\s[^>]*href="([^"]*?)"[^>]*>(.*)</a>`i', $content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                if (!preg_match($urlRegex, $match[1]) && !preg_match($emailRegex, $match[1])) {
                    $this->context->addViolation($constraint->relativeUrls);
                    break;
                }
            }
        }
    }
}
