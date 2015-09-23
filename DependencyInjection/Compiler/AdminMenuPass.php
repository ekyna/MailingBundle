<?php

namespace Ekyna\Bundle\MailingBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Class AdminMenuPass
 * @package Ekyna\Bundle\MailingBundle\DependencyInjection\Compiler
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AdminMenuPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('ekyna_admin.menu.pool')) {
            return;
        }

        $pool = $container->getDefinition('ekyna_admin.menu.pool');

        $pool->addMethodCall('createGroup', [[
            'name'     => 'mailing',
            'label'    => 'ekyna_mailing.menu',
            'icon'     => 'envelope',
            'position' => 70,
        ]]);
        $pool->addMethodCall('createEntry', ['mailing', [
            'name'     => 'campaigns',
            'route'    => 'ekyna_mailing_campaign_admin_home',
            'label'    => 'ekyna_mailing.campaign.label.plural',
            'resource' => 'ekyna_mailing_campaign',
            'position' => 1,
        ]]);
        $pool->addMethodCall('createEntry', ['mailing', [
            'name'     => 'recipientLists',
            'route'    => 'ekyna_mailing_recipientList_admin_home',
            'label'    => 'ekyna_mailing.recipientList.label.plural',
            'resource' => 'ekyna_mailing_recipientList',
            'position' => 2,
        ]]);
    }
}
