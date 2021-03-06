<?php

namespace Heffe\SUFAPIBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('heffe_sufapi');

        $rootNode->children()
            ->scalarNode('webapi_key')
                ->defaultValue('')
                ->info('A valid API key to access the Steam WebAPI service')
            ->end()
            ->integerNode('personaname_cache_duration')
                ->info('The number of minutes for which persona names are cached')
                ->defaultValue(10)
                ->min(1)
                ->max(59)
            ->end()
        ->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
