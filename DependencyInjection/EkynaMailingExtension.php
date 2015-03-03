<?php

namespace Ekyna\Bundle\MailingBundle\DependencyInjection;

use Ekyna\Bundle\AdminBundle\DependencyInjection\AbstractExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class EkynaMailingExtension
 * @package Ekyna\Bundle\MailingBundle\DependencyInjection
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class EkynaMailingExtension extends AbstractExtension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->configure($configs, 'ekyna_mailing', new Configuration(), $container);

        $container->setParameter('ekyna_mailing.templates', $config['templates']);
        $container->setParameter('ekyna_mailing.default_list', $config['default_list']);
        $container->setParameter('ekyna_mailing.tracker_config', $config['tracker']);

        $def = $container->getDefinition('ekyna_mailing.execution.operator');
        $def->addMethodCall('setFactory', array(new Reference('sm.factory')));
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        parent::prepend($container);

        $bundles = $container->getParameter('kernel.bundles');

        if (array_key_exists('AsseticBundle', $bundles)) {
            $this->configureAsseticBundle($container);
        }
        if (array_key_exists('FOSElasticaBundle', $bundles)) {
            $this->configureFOSElasticaBundle($container);
        }
    }

    /**
     * Configures the assetic bundle.
     *
     * @param ContainerBuilder $container
     */
    protected function configureAsseticBundle(ContainerBuilder $container)
    {
        $container->prependExtensionConfig('assetic', array(
            'bundles' => array('EkynaMailingBundle')
        ));
    }

    /**
     * Configures the FOS elastica bundle.
     *
     * @param ContainerBuilder $container
     */
    protected function configureFOSElasticaBundle(ContainerBuilder $container)
    {
        $container->prependExtensionConfig('fos_elastica', array(
            'indexes' => array(
                'search' => array(
                    'types' => array(
                        'ekyna_mailing_recipient' => array(
                            'mappings' => array(
                                'firstName' => array('search_analyzer' => 'fr_search', 'index_analyzer' => 'fr_index'),
                                'lastName' => array('search_analyzer' => 'fr_search', 'index_analyzer' => 'fr_index'),
                                'email' => array('search_analyzer' => 'fr_search', 'index_analyzer' => 'fr_index'),
                            ),
                            'persistence' => array(
                                'driver' => 'orm',
                                'model' => '%ekyna_mailing.recipient.class%',
                                'provider' => null,
                                'listener' => array(
                                    'immediate' => null,
                                ),
                                'finder' => null,
                                'repository' => 'Ekyna\Bundle\MailingBundle\Search\RecipientRepository',
                            ),
                        ),
                    ),
                ),
            ),
        ));
    }
}
