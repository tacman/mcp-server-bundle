<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ecourty\McpServerBundle\Controller\EntrypointController;

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $services
        ->defaults()
        ->autoconfigure()
        ->autowire();

    $services
        ->load('Ecourty\\McpServerBundle\\', __DIR__ . '/../../*')
        ->exclude([
            __DIR__ . '/../../DependencyInjection',
            __DIR__ . '/../../Attribute',
            __DIR__ . '/../../IO',
            __DIR__ . '/../../Enum',
            __DIR__ . '/../../Event',
            __DIR__ . '/../../Exception',
            __DIR__ . '/../../HttpFoundation',
            __DIR__ . '/../../Prompt',
            __DIR__ . '/../../Resource',
            __DIR__ . '/../../Resources',
            __DIR__ . '/../../Tool',
            __DIR__ . '/../../McpServerBundle.php',
        ])
        ->public();

    $services
        ->set('mcp_server.entrypoint_controller', EntrypointController::class)
        ->autoconfigure()
        ->autowire()
        ->public();
};
