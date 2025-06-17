<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle;

use Ecourty\McpServerBundle\DependencyInjection\CompilerPass\MethodHandlerPass;
use Ecourty\McpServerBundle\DependencyInjection\CompilerPass\ToolPass;
use Ecourty\McpServerBundle\DependencyInjection\McpServerBundleExtension;
use Ecourty\McpServerBundle\MethodHandler\InitializeMethodHandler;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class McpServerBundle extends AbstractBundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new McpServerBundleExtension();
    }

    public function getPath(): string
    {
        return __DIR__;
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new MethodHandlerPass());
        $container->addCompilerPass(new ToolPass());
    }
}
