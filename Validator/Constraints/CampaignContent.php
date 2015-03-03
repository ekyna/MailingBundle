<?php

namespace Ekyna\Bundle\MailingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class CampaignContent
 * @package Ekyna\Bundle\MailingBundle\Validator\Constraints
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class CampaignContent extends Constraint
{
    public $relativeUrls = 'ekyna_mailing.campaign.content.relative_urls';
}
