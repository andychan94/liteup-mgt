<?php

namespace ToolBox\FileBrowserBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('tool_box_file_browser');

        $rootNode
            ->children()
                ->integerNode('max_file_size')->end()
                ->arrayNode('file_types')
                    ->info('Use this to define file mime types allowed across bundle.')
                    ->defaultValue(array())
                    ->prototype('scalar')->end()
                ->end()
                ->scalarNode('no_image_file')->end()
                ->scalarNode('default_upload_dir')
                    ->info('WIP')
                ->end()
            ->end()
        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
