<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Service;

/**
 * Matches a resource URI pattern against a given URI and extracts named parameters.
 */
class ResourceUriMatcher
{
    public function match(string $uriPattern, string $uri): array
    {
        // Transforms the pattern into regex: database://user/{id} => #^database://user/(?<id>[^/]+)$#
        $regex = preg_replace_callback('#\{(\w+)\}#', function ($matches) {
            return '(?<' . $matches[1] . '>[^/]+)';
        }, $uriPattern);

        $regex = '#^' . $regex . '$#';

        if (preg_match($regex, $uri, $matches) !== false) {
            if (empty($matches)) {
                return []; // No matches found
            }

            if (\count($matches) === 1 && $matches[0] === $uri) { // Exact match without captures
                return [$uri => $uri];
            }

            // Only return named captures as an associative array
            return array_filter($matches, 'is_string', \ARRAY_FILTER_USE_KEY);
        }

        return [];
    }
}
