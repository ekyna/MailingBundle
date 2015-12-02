<?php

namespace Ekyna\Bundle\MailingBundle\Table\Type;

use Ekyna\Bundle\AdminBundle\Table\Type\ResourceTableType;
use Ekyna\Component\Table\TableBuilderInterface;

/**
 * Class RecipientListType
 * @package Ekyna\Bundle\MailingBundle\Table\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class RecipientListType extends ResourceTableType
{
    /**
     * {@inheritdoc}
     */
    public function buildTable(TableBuilderInterface $builder, array $options)
    {
        $builder
            ->addColumn('name', 'anchor', [
                'label' => 'ekyna_core.field.name',
                'sortable' => true,
                'route_name' => 'ekyna_mailing_recipient_list_admin_show',
                'route_parameters_map' => [
                    'recipientListId' => 'id',
                ],
            ])
            ->addColumn('actions', 'admin_actions', [
                'buttons' => [
                    [
                        'label' => 'ekyna_core.button.edit',
                        'class' => 'warning',
                        'route_name' => 'ekyna_mailing_recipient_list_admin_edit',
                        'route_parameters_map' => [
                            'recipientListId' => 'id'
                        ],
                        'permission' => 'edit',
                    ],
                    [
                        'label' => 'ekyna_core.button.remove',
                        'class' => 'danger',
                        'route_name' => 'ekyna_mailing_recipient_list_admin_remove',
                        'route_parameters_map' => [
                            'recipientListId' => 'id'
                        ],
                        'permission' => 'delete',
                    ],
                ],
            ])
            ->addFilter('name', 'text')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_mailing_recipient_list';
    }
}
