<?php

namespace Ekyna\Bundle\MailingBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class RecipientProviderPass
 * @package Ekyna\Bundle\MailingBundle\DependencyInjection\Compiler
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class RecipientProviderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('ekyna_mailing.recipient_provider.registry')) {
            return;
        }

        $definition = $container->getDefinition(
            'ekyna_mailing.recipient_provider.registry'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'ekyna_mailing.recipient_provider'
        );
        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $definition->addMethodCall(
                    'addProvider',
                    array(new Reference($id), $attributes["alias"])
                );
            }
        }
    }
}
