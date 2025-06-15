<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Service;

use Ecourty\McpServerBundle\Contract\MethodHandlerInterface;
use Ecourty\McpServerBundle\DependencyInjection\CompilerPass\MethodHandlerPass;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;
use Symfony\Component\DependencyInjection\ServiceLocator;

/**
 * Registry for method handlers.
 *
 * This class provides a way to retrieve method handlers by their name.
 * It uses a service locator to manage the method handler instances.
 *
 * @see MethodHandlerPass
 */
class MethodHandlerRegistry
{
    /**
     * @param ServiceLocator<MethodHandlerInterface> $methodHandlerLocator
     */
    public function __construct(
        #[AutowireLocator(services: MethodHandlerInterface::class, indexAttribute: MethodHandlerInterface::KEY_NAME)]
        private readonly ServiceLocator $methodHandlerLocator,
    ) {
    }

    public function getMethodHandler(string $name): ?MethodHandlerInterface
    {
        if ($this->methodHandlerLocator->has($name) === false) {
            return null;
        }

        return $this->methodHandlerLocator->get($name);
    }
}
