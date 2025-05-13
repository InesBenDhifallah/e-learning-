<?php

namespace App\Bundle\ChartBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('chart');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('options')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('responsive')->defaultTrue()->end()
                        ->booleanNode('maintainAspectRatio')->defaultFalse()->end()
                        ->arrayNode('plugins')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('legend')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('position')->defaultValue('bottom')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('colors')
                    ->scalarPrototype()->end()
                    ->defaultValue([
                        '#3B82F6',
                        '#10B981',
                        '#F59E0B',
                        '#EF4444',
                        '#8B5CF6',
                        '#EC4899',
                        '#6366F1'
                    ])
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
} 