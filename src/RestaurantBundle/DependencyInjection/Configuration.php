<?php

namespace RestaurantBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('restaurant');

        $rootNode->children()
            ->arrayNode('email_handler')
                ->isRequired()
                ->children()
                    ->arrayNode('menu_validation_request')
                        ->children()
                            ->scalarNode('subject')
                                ->isRequired()
                                ->end()
                            ->scalarNode('template')
                                ->isRequired()
                                ->end()
                        ->end()
                    ->end()
                    ->arrayNode('plat_validation_request')
                        ->children()
                            ->scalarNode('subject')
                                ->isRequired()
                                ->end()
                            ->scalarNode('template')
                                ->isRequired()
                                ->end()
                        ->end()
                    ->end()
                    ->arrayNode('menu_publication_notification')
                        ->children()
                            ->scalarNode('subject')
                                ->isRequired()
                                ->end()
                            ->scalarNode('template')
                                ->isRequired()
                                ->end()
                        ->end()
                    ->end()
                    ->arrayNode('plat_publication_notification')
                        ->children()
                            ->scalarNode('subject')
                                ->isRequired()
                                ->end()
                            ->scalarNode('template')
                                ->isRequired()
                                ->end()
                        ->end()
                    ->end()
                    ->arrayNode('booking_confirmation')
                        ->children()
                            ->scalarNode('subject')
                                ->isRequired()
                                ->end()
                            ->scalarNode('template')
                                ->isRequired()
                                ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
