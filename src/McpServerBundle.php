<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle;

use Ecourty\McpServerBundle\DependencyInjection\CompilerPass\MethodHandlerPass;
use Ecourty\McpServerBundle\DependencyInjection\CompilerPass\PromptPass;
use Ecourty\McpServerBundle\DependencyInjection\CompilerPass\ToolPass;
use Ecourty\McpServerBundle\DependencyInjection\McpServerBundleExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
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
        $container->addCompilerPass(new PromptPass());
    }
}
