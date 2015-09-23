<?php

namespace Ekyna\Bundle\MailingBundle\Table\Type;

use Ekyna\Bundle\AdminBundle\Table\Type\ResourceTableType;
use Ekyna\Component\Table\TableBuilderInterface;

/**
 * Class ExecutionType
 * @package Ekyna\Bundle\MailingBundle\Table\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ExecutionType extends ResourceTableType
{
    /**
     * {@inheritdoc}
     */
    public function buildTable(TableBuilderInterface $builder, array $options)
    {
        $builder
            ->addColumn('name', 'anchor', [
                'label' => 'ekyna_core.field.name',
                //'sortable' => true,
                'route_name' => 'ekyna_mailing_execution_admin_show',
                'route_parameters_map' => [
                    'campaignId' => 'campaign.id',
                    'executionId' => 'id',
                ],
            ])
            ->addColumn('type', 'ekyna_mailing_execution_type', [
                'label' => 'ekyna_core.field.type',
                //'sortable' => true,
            ])
            ->addColumn('state', 'ekyna_mailing_execution_state', [
                'label' => 'ekyna_core.field.status',
                //'sortable' => true,
            ])
            ->addColumn('startDate', 'datetime', [
                'label' => 'ekyna_core.field.start_date',
                'date_format' => 'short',
                'time_format' => 'short',
                //'sortable' => true,
            ])
            ->addColumn('startedAt', 'datetime', [
                'label' => 'ekyna_core.field.started_at',
                'date_format' => 'short',
                'time_format' => 'short',
                //'sortable' => true,
            ])
            ->addColumn('completedAt', 'datetime', [
                'label' => 'ekyna_core.field.completed_at',
                'date_format' => 'short',
                'time_format' => 'short',
                //'sortable' => true,
            ])
            ->addColumn('actions', 'admin_actions', [
                'buttons' => [
                    [
                        'label' => 'ekyna_core.button.edit',
                        'icon' => 'pencil',
                        'class' => 'warning',
                        'route_name' => 'ekyna_mailing_execution_admin_edit',
                        'route_parameters_map' => [
                            'campaignId' => 'campaign.id',
                            'executionId' => 'id',
                        ],
                        'disable_property_path' => 'locked',
                        'permission' => 'edit',
                    ],
                    [
                        'label' => 'ekyna_core.button.remove',
                        'icon' => 'trash',
                        'class' => 'danger',
                        'route_name' => 'ekyna_mailing_execution_admin_remove',
                        'route_parameters_map' => [
                            'campaignId' => 'campaign.id',
                            'executionId' => 'id',
                        ],
                        'disable_property_path' => 'locked',
                        'permission' => 'delete',
                    ],
                ],
            ])
            /*->addFilter('name', 'text', array(
                'label' => 'ekyna_core.field.name',
            ))
            ->addFilter('startedAt', 'datetime', array(
                'label' => 'ekyna_core.field.started_at',
            ))
            ->addFilter('completedAt', 'datetime', array(
                'label' => 'ekyna_core.field.completed_at',
            ))*/
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_mailing_execution';
    }
}
