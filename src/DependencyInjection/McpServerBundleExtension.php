<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\DependencyInjection;

use Ecourty\McpServerBundle\MethodHandler\InitializeMethodHandler;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

/**
 * This class loads and manages the bundle configuration.
 */
class McpServerBundleExtension extends Extension
{
    public function getConfiguration(array $config, ContainerBuilder $container): ConfigurationInterface
    {
        return new Configuration();
    }

    public function getAlias(): string
    {
        return 'mcp_server';
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        /** @var Configuration $configuration */
        $configuration = $this->getConfiguration($configs, $container);

        /**
         * @var array{server: array{name: string, title: string, version: string}} $config
         */
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.php');

        $container->getDefinition(InitializeMethodHandler::class)
            ->setArgument('$serverName', $config['server']['name'])
            ->setArgument('$serverTitle', $config['server']['title'])
            ->setArgument('$serverVersion', $config['server']['version']);
    }
}
