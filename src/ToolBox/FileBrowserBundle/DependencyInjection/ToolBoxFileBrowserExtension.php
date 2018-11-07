<?php

namespace ToolBox\FileBrowserBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use ToolBox\FileBrowserBundle\ToolBoxFileBrowserBundle;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class ToolBoxFileBrowserExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $fileBrowserServiceDefintion = $container->getDefinition( 'tool_box_file_browser.file_tree' );
        $fileBrowserServiceDefintion->addMethodCall( 'setConfig', array($config));

        $fileBrowserTypeDefintion = $container->getDefinition( 'tool_box_file_browser.file_browser_type' );
        $fileBrowserTypeDefintion->addMethodCall( 'setConfig', array($config));

    }
}
