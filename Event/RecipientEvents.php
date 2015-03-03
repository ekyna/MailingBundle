<?php

namespace Ekyna\Bundle\MailingBundle\Event;

/**
 * Class RecipientEvents
 * @package Ekyna\Bundle\MailingBundle\Event
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
final class RecipientEvents
{
    const PRE_CREATE  = 'ekyna_mailing.recipient.pre_create';
    const POST_CREATE = 'ekyna_mailing.recipient.post_create';

    const PRE_UPDATE  = 'ekyna_mailing.recipient.pre_update';
    const POST_UPDATE = 'ekyna_mailing.recipient.pre_delete';

    const PRE_DELETE  = 'ekyna_mailing.recipient.pre_update';
    const POST_DELETE = 'ekyna_mailing.recipient.pre_delete';
}
