<?php

namespace Ekyna\Bundle\MailingBundle\Table\Type;

use Ekyna\Bundle\AdminBundle\Table\Type\ResourceTableType;
use Ekyna\Component\Table\TableBuilderInterface;

/**
 * Class CampaignType
 * @package Ekyna\Bundle\MailingBundle\Table\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class CampaignType extends ResourceTableType
{
    /**
     * {@inheritdoc}
     */
    public function buildTable(TableBuilderInterface $builder, array $options)
    {
        $builder
            ->addColumn('name', 'anchor', array(
                'label' => 'ekyna_core.field.name',
                'sortable' => true,
                'route_name' => 'ekyna_mailing_campaign_admin_show',
                'route_parameters_map' => array(
                    'campaignId' => 'id',
                ),
            ))
            ->addColumn('actions', 'admin_actions', array(
                'buttons' => array(
                    array(
                        'label' => 'ekyna_core.button.edit',
                        'class' => 'warning',
                        'route_name' => 'ekyna_mailing_campaign_admin_edit',
                        'route_parameters_map' => array(
                            'campaignId' => 'id'
                        ),
                        'permission' => 'edit',
                    ),
                    array(
                        'label' => 'ekyna_core.button.remove',
                        'class' => 'danger',
                        'route_name' => 'ekyna_mailing_campaign_admin_remove',
                        'route_parameters_map' => array(
                            'campaignId' => 'id'
                        ),
                        'permission' => 'delete',
                    ),
                ),
            ))
            ->addFilter('name', 'text')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_mailing_campaign';
    }
}
