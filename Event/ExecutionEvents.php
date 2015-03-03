<?php

namespace Ekyna\Bundle\MailingBundle\Event;

/**
 * Class ExecutionEvents
 * @package Ekyna\Bundle\MailingBundle\Event
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
final class ExecutionEvents
{
    const PRE_CREATE     = 'ekyna_mailing.execution.pre_create';
    const POST_CREATE    = 'ekyna_mailing.execution.post_create';

    const PRE_UPDATE     = 'ekyna_mailing.execution.pre_update';
    const POST_UPDATE    = 'ekyna_mailing.execution.post_update';

    const PRE_DELETE     = 'ekyna_mailing.execution.pre_delete';
    const POST_DELETE    = 'ekyna_mailing.execution.post_delete';

    const PRE_LOCK       = 'ekyna_mailing.execution.pre_lock';
    const POST_LOCK      = 'ekyna_mailing.execution.post_lock';

    const PRE_UNLOCK     = 'ekyna_mailing.execution.pre_unlock';
    const POST_UNLOCK    = 'ekyna_mailing.execution.post_unlock';

    const PRE_START      = 'ekyna_mailing.execution.pre_start';
    const POST_START     = 'ekyna_mailing.execution.post_start';

    const PRE_STOP       = 'ekyna_mailing.execution.pre_stop';
    const POST_STOP      = 'ekyna_mailing.execution.post_stop';
}
