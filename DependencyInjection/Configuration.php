<?php

namespace Ekyna\Bundle\MailingBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Ekyna\Bundle\MailingBundle\DependencyInjection
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ekyna_mailing');

        $this->addGlobalSection($rootNode);
        $this->addPoolsSection($rootNode);

        return $treeBuilder;
    }

    /**
     * Adds the global section.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addGlobalSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('default_list')->defaultValue('Newsletter')->cannotBeEmpty()->end()
                ->arrayNode('tracker')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('open_param')->defaultValue('mreo-token')->cannotBeEmpty()->end()
                        ->scalarNode('visit_param')->defaultValue('mrev-token')->cannotBeEmpty()->end()
                    ->end()
                ->end()
                ->arrayNode('templates')
                    ->defaultValue(array(
                        'default' => array(
                            'label' => 'ekyna_mailing.default_template',
                            'path'  => 'EkynaMailingBundle::default_template.html.twig',
                        ),
                    ))
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('label')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('path')->isRequired()->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * Adds the admin pool sections.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addPoolsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('pools')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('campaign')
                            ->isRequired()
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('templates')->defaultValue(array(
                                    '_form.html' => 'EkynaMailingBundle:Admin/Campaign:_form.html',
                                    'show.html'  => 'EkynaMailingBundle:Admin/Campaign:show.html',
                                ))->end()
                                ->scalarNode('parent')->end()
                                ->scalarNode('entity')->defaultValue('Ekyna\Bundle\MailingBundle\Entity\Campaign')->end()
                                ->scalarNode('controller')->defaultValue('Ekyna\Bundle\MailingBundle\Controller\Admin\CampaignController')->end()
                                ->scalarNode('repository')->defaultValue('Ekyna\Bundle\MailingBundle\Entity\CampaignRepository')->end()
                                ->scalarNode('form')->defaultValue('Ekyna\Bundle\MailingBundle\Form\Type\CampaignType')->end()
                                ->scalarNode('table')->defaultValue('Ekyna\Bundle\MailingBundle\Table\Type\CampaignType')->end()
                                ->scalarNode('event')->end()
                            ->end()
                        ->end()
                        ->arrayNode('execution')
                            ->isRequired()
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('templates')->defaultValue('EkynaMailingBundle:Admin/Execution')->end()
                                ->scalarNode('parent')->defaultValue('ekyna_mailing.campaign')->end()
                                ->scalarNode('entity')->defaultValue('Ekyna\Bundle\MailingBundle\Entity\Execution')->end()
                                ->scalarNode('controller')->defaultValue('Ekyna\Bundle\MailingBundle\Controller\Admin\ExecutionController')->end()
                                ->scalarNode('operator')->defaultValue('Ekyna\Bundle\MailingBundle\Operator\ExecutionOperator')->end()
                                ->scalarNode('repository')->defaultValue('Ekyna\Bundle\MailingBundle\Entity\ExecutionRepository')->end()
                                ->scalarNode('form')->defaultValue('Ekyna\Bundle\MailingBundle\Form\Type\ExecutionType')->end()
                                ->scalarNode('table')->defaultValue('Ekyna\Bundle\MailingBundle\Table\Type\ExecutionType')->end()
                                ->scalarNode('event')->defaultValue('Ekyna\Bundle\MailingBundle\Event\ExecutionEvent')->end()
                            ->end()
                        ->end()
                        ->arrayNode('recipientList')
                            ->isRequired()
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('templates')->defaultValue(array(
                                    '_form.html' => 'EkynaMailingBundle:Admin/RecipientList:_form.html',
                                    'show.html'  => 'EkynaMailingBundle:Admin/RecipientList:show.html',
                                ))->end()
                                ->scalarNode('parent')->end()
                                ->scalarNode('entity')->defaultValue('Ekyna\Bundle\MailingBundle\Entity\RecipientList')->end()
                                ->scalarNode('controller')->defaultValue('Ekyna\Bundle\MailingBundle\Controller\Admin\RecipientListController')->end()
                                ->scalarNode('repository')->end()
                                ->scalarNode('form')->defaultValue('Ekyna\Bundle\MailingBundle\Form\Type\RecipientListType')->end()
                                ->scalarNode('table')->defaultValue('Ekyna\Bundle\MailingBundle\Table\Type\RecipientListType')->end()
                                ->scalarNode('event')->end()
                            ->end()
                        ->end()
                        ->arrayNode('recipient')
                            ->isRequired()
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('templates')->defaultValue(array(
                                    '_form.html' => 'EkynaMailingBundle:Admin/Recipient:_form.html',
                                    'show.html'  => 'EkynaMailingBundle:Admin/Recipient:show.html',
                                ))->end()
                                ->scalarNode('parent')->end()
                                ->scalarNode('entity')->defaultValue('Ekyna\Bundle\MailingBundle\Entity\Recipient')->end()
                                ->scalarNode('controller')->end()
                                ->scalarNode('repository')->defaultValue('Ekyna\Bundle\MailingBundle\Entity\RecipientRepository')->end()
                                ->scalarNode('form')->defaultValue('Ekyna\Bundle\MailingBundle\Form\Type\RecipientType')->end()
                                ->scalarNode('table')->defaultValue('Ekyna\Bundle\MailingBundle\Table\Type\RecipientType')->end()
                                ->scalarNode('event')->defaultValue('Ekyna\Bundle\MailingBundle\Event\RecipientEvent')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
