<?php

namespace Ekyna\Bundle\MailingBundle\Table\Type;

use Ekyna\Bundle\AdminBundle\Table\Type\ResourceTableType;
use Ekyna\Component\Table\TableBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class RecipientType
 * @package Ekyna\Bundle\MailingBundle\Table\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class RecipientType extends ResourceTableType
{
    public function buildTable(TableBuilderInterface $builder, array $options)
    {
        $deleteButton = $options['delete_button'];
        if (empty($deleteButton)) {
            $deleteButton = array(
                'label' => 'ekyna_core.button.remove',
                'class' => 'danger',
                'route_name' => 'ekyna_mailing_recipient_admin_remove',
                'route_parameters_map' => array(
                    'recipientId' => 'id'
                ),
            );
        }

        $builder
            ->addColumn('email', 'anchor', array(
                'label' => 'ekyna_core.field.email',
                'sortable' => true,
                'route_name' => 'ekyna_mailing_recipient_admin_show',
                'route_parameters_map' => array(
                    'recipientId' => 'id'
                ),
            ))
            ->addColumn('firstName', 'text', array(
                'label' => 'ekyna_core.field.first_name',
                'sortable' => true,
            ))
            ->addColumn('lastName', 'text', array(
                'label' => 'ekyna_core.field.last_name',
                'sortable' => true,
            ))
            ->addColumn('actions', 'admin_actions', array(
                'buttons' => array(
                    array(
                        'label' => 'ekyna_core.button.edit',
                        'class' => 'warning',
                        'route_name' => 'ekyna_mailing_recipient_admin_edit',
                        'route_parameters_map' => array(
                            'recipientId' => 'id'
                        )
                    ),
                    $deleteButton,
                ),
            ))
            ->addFilter('email', 'text', array(
                'label' => 'ekyna_core.field.email',
            ))
            ->addFilter('firstName', 'text', array(
                'label' => 'ekyna_core.field.first_name',
            ))
            ->addFilter('lastName', 'text', array(
                'label' => 'ekyna_core.field.last_name',
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver
            ->setDefaults(array(
                'delete_button' => null,
            ))
            ->setAllowedTypes(array(
                'delete_button' => array('null', 'array'),
            ))
        ;
    }

    public function getName()
    {
        return 'ekyna_mailing_recipient';
    }
}
