<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\DependencyInjection\CompilerPass;

use Ecourty\McpServerBundle\Attribute\AsMethodHandler;
use Ecourty\McpServerBundle\Contract\MethodHandlerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * MethodHandlerPass is a compiler pass that processes JSON-RPC method handlers.
 *
 * It validates the class, checks for the AsMethodHandler attribute, and adds the method handler tag.
 */
class MethodHandlerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getDefinitions() as $definition) {
            $class = $definition->getClass();

            try {
                if ($class === null || class_exists($class) === false) {
                    continue;
                }
            } catch (\Throwable) {
                continue;
            }

            $refClass = new \ReflectionClass($class);

            $attributes = $refClass->getAttributes(AsMethodHandler::class);
            if (\count($attributes) === 0) {
                continue;
            }

            if (\count($attributes) > 1) {
                throw new \LogicException(\sprintf(
                    'Multiple AsMethodHandler attributes found on class "%s". Only one is allowed.',
                    $class,
                ));
            }

            /** @var AsMethodHandler|null $attr */
            $attr = $attributes[0]->newInstance();
            if ($attr === null) {
                throw new \LogicException(\sprintf(
                    'Failed to instantiate AsMethodHandler attribute for class "%s".',
                    $class,
                ));
            }

            $definition
                ->addTag(MethodHandlerInterface::class, [
                    MethodHandlerInterface::KEY_NAME => $attr->methodName,
                ]);
        }
    }
}
