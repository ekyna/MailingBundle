<?php

namespace Ekyna\Bundle\MailingBundle\Model;

/**
 * Class RecipientExecutionStates
 * @package Ekyna\Bundle\MailingBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
final class RecipientExecutionStates
{
    const STATE_PENDING = 'pending';
    const STATE_ERROR   = 'error';
    const STATE_SENT    = 'sent';
    const STATE_OPENED  = 'opened';
    const STATE_VISITED = 'visited';
}
