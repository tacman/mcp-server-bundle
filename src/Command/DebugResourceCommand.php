<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Command;

use Ecourty\McpServerBundle\Resource\AbstractResourceDefinition;
use Ecourty\McpServerBundle\Service\ResourceRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Command to display information about MCP resources.
 *
 * This command allows users to view details about specific MCP resources or all available resources.
 * It provides a table format for easy readability of resource attributes such as name, uri, description,
 * mimeType...
 */
#[AsCommand(
    name: 'debug:mcp-resources',
    description: 'Display current MCP resources',
)]
class DebugResourceCommand extends Command
{
    public function __construct(
        private readonly ResourceRegistry $resourceRegistry,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('resource', InputArgument::OPTIONAL, 'Resource URI');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $resourceName = $input->getArgument('resource');

        if ($resourceName !== null) {
            return $this->displaySingleResourceInformation($io, $resourceName);
        }

        return $this->displayAllResourcesInformation($io);
    }

    private function displaySingleResourceInformation(SymfonyStyle $io, string $resourceName): int
    {
        $resourceDefinition = $this->resourceRegistry->getResourceDefinition($resourceName);

        if ($resourceDefinition === null) {
            $io->error(\sprintf('Resource "%s" not found.', $resourceName));

            return self::FAILURE;
        }

        $io->table(
            ['Name', 'URI', 'Title', 'Description', 'MimeType', 'Size'],
            [
                [
                    $resourceDefinition->name,
                    $resourceDefinition->uri,
                    $resourceDefinition->title,
                    $resourceDefinition->description,
                    $resourceDefinition->mimeType,
                    $resourceDefinition->size ?? null,
                ],
            ],
        );

        return self::SUCCESS;
    }

    private function displayAllResourcesInformation(SymfonyStyle $io): int
    {
        $io->title('MCP Resources Debug Information');

        $resourceDefinitions = $this->resourceRegistry->getResourceDefinitions();

        if (empty($resourceDefinitions) === true) {
            $io->warning('No resources found.');

            return self::SUCCESS;
        }

        $io->table(
            ['Name', 'URI', 'Title', 'Description', 'MimeType', 'Size'],
            array_map(static function (AbstractResourceDefinition $resourceDefinition) {
                return [
                    $resourceDefinition->name,
                    $resourceDefinition->uri,
                    $resourceDefinition->title,
                    $resourceDefinition->description,
                    $resourceDefinition->mimeType,
                    $resourceDefinition->size ?? null,
                ];
            }, $resourceDefinitions),
        );

        return self::SUCCESS;
    }
}
