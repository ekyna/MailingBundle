<?php

namespace Ekyna\Bundle\MailingBundle\Table\Type;

use Ekyna\Bundle\AdminBundle\Table\Type\ResourceTableType;
use Ekyna\Component\Table\TableBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RecipientType
 * @package Ekyna\Bundle\MailingBundle\Table\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class RecipientType extends ResourceTableType
{
    /**
     * {@inheritdoc}
     */
    public function buildTable(TableBuilderInterface $builder, array $options)
    {
        $deleteButton = $options['delete_button'];
        if (empty($deleteButton)) {
            $deleteButton = [
                'label' => 'ekyna_core.button.remove',
                'class' => 'danger',
                'route_name' => 'ekyna_mailing_recipient_admin_remove',
                'route_parameters_map' => [
                    'recipientId' => 'id'
                ],
            ];
        }

        $builder
            ->addColumn('email', 'anchor', [
                'label' => 'ekyna_core.field.email',
                'sortable' => true,
                'route_name' => 'ekyna_mailing_recipient_admin_show',
                'route_parameters_map' => [
                    'recipientId' => 'id'
                ],
            ])
            ->addColumn('firstName', 'text', [
                'label' => 'ekyna_core.field.first_name',
                'sortable' => true,
            ])
            ->addColumn('lastName', 'text', [
                'label' => 'ekyna_core.field.last_name',
                'sortable' => true,
            ])
            ->addColumn('actions', 'admin_actions', [
                'buttons' => [
                    [
                        'label' => 'ekyna_core.button.edit',
                        'class' => 'warning',
                        'route_name' => 'ekyna_mailing_recipient_admin_edit',
                        'route_parameters_map' => [
                            'recipientId' => 'id'
                        ]
                    ],
                    $deleteButton,
                ],
            ])
            ->addFilter('email', 'text', [
                'label' => 'ekyna_core.field.email',
            ])
            ->addFilter('firstName', 'text', [
                'label' => 'ekyna_core.field.first_name',
            ])
            ->addFilter('lastName', 'text', [
                'label' => 'ekyna_core.field.last_name',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'delete_button' => null,
            ])
            ->setAllowedTypes('delete_button', ['null', 'array'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_mailing_recipient';
    }
}
