<?php

namespace Konkurencia\CommonBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('konkurencia_common');

        $rootNode
            ->children()
                ->scalarNode('builder_class')->cannotBeEmpty()->end()
                ->scalarNode('api_base_url')->cannotBeEmpty()->end()

                ->arrayNode('resources')
                    ->prototype('array')
                    ->children()
                        ->arrayNode('list_field_mapping')
                            ->children()
                                ->arrayNode('keys')->prototype('scalar')->end()->end()
                                ->arrayNode('values')->prototype('scalar')->end()->end()
                                ->arrayNode('foreigns')
                                    ->prototype('array')
                                    ->children()
                                        ->scalarNode('resource')->end()
                                        ->scalarNode('resourceUrl')->defaultNull()->end()
                                        ->booleanNode('isList')->defaulttrue()->end()
                                        ->arrayNode('field_mapping')
                                            ->children()
                                                ->arrayNode('keys')->prototype('scalar')->end()->end()
                                                ->arrayNode('values')->prototype('scalar')->end()->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->end()
                        ->end()
                        ->arrayNode('detail_field_mapping')
                            ->children()
                                ->arrayNode('keys')->prototype('scalar')->end()->end()
                                ->arrayNode('values')->prototype('scalar')->end()->end()
                                ->arrayNode('foreigns')
                                    ->prototype('array')
                                    ->children()
                                        ->scalarNode('resource')->end()
                                        ->scalarNode('resourceUrl')->defaultNull()->end()
                                        ->booleanNode('isList')->defaulttrue()->end()
                                        ->arrayNode('field_mapping')
                                            ->children()
                                                ->arrayNode('keys')->prototype('scalar')->end()->end()
                                                ->arrayNode('values')->prototype('scalar')->end()->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

            ->end()
            ;

        return $treeBuilder;
    }
}
