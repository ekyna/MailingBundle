<?php

namespace Ekyna\Bundle\MailingBundle\Controller\Admin;

use Doctrine\ORM\QueryBuilder;
use Ekyna\Bundle\AdminBundle\Controller\Context;

/**
 * Class RecipientListController
 * @package Ekyna\Bundle\MailingBundle\Controller\Admin
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class RecipientListController extends RecipientsSubjectController
{
    /**
     * {@inheritdoc}
     */
    public function createRecipientsTable(Context $context)
    {
        /** @var \Ekyna\Bundle\MailingBundle\Entity\RecipientList $recipientList */
        $recipientList = $context->getResource();

        return $this->getTableFactory()
            ->createBuilder('ekyna_mailing_recipient', array(
                'name' => 'ekyna_mailing.recipient',
                'customize_qb' => function (QueryBuilder $qb, $alias) use ($recipientList) {
                    $qb
                        ->join($alias.'.recipientLists', 'rl')
                        ->andWhere('rl.id = :resource')
                        ->setParameter('resource', $recipientList);
                },
                'delete_button' => array(
                    'label' => 'ekyna_core.button.unlink',
                    'class' => 'danger',
                    'route_name' => 'ekyna_mailing_recipientList_admin_recipients_unlink',
                    'route_parameters' => $context->getIdentifiers(true),
                    'route_parameters_map' => array('recipientId' => 'id'),
                )
            ))
            ->getTable($context->getRequest());
    }
}
