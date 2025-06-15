<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Service;

/**
 * InputSanitizer is a service that sanitizes input data to prevent injection attacks.
 *
 * It recursively sanitizes arrays and objects, ensuring that all string values are cleaned.
 * The maximum recursion depth can be configured to prevent excessive nesting.
 */
class InputSanitizer
{
    private const int MAX_DEPTH = 512;

    public function sanitize(mixed $value, int $maxDepth = self::MAX_DEPTH): mixed
    {
        try {
            return $this->sanitizeValue(value: $value, depth: 0, maxDepth :$maxDepth);
        } catch (\OverflowException) {
            return null;
        }
    }

    private function sanitizeValue(mixed $value, int $depth, int $maxDepth): mixed
    {
        if ($depth > $maxDepth) {
            throw new \OverflowException('Maximum depth exceeded.');
        }

        if (\is_array($value) === true) {
            $sanitized = [];
            foreach ($value as $key => $val) {
                $sanitized[$this->sanitizeValue(value: $key, depth: $depth + 1, maxDepth: $maxDepth)] = $this->sanitizeValue(value: $val, depth: $depth + 1, maxDepth: $maxDepth);
            }

            return $sanitized;
        }

        if (\is_object($value) === true) {
            foreach (get_object_vars($value) as $key => $val) {
                $value->$key = $this->sanitizeValue(value: $val, depth: $depth + 1, maxDepth: $maxDepth);
            }

            return $value;
        }

        if (\is_string($value) === true) {
            return $this->sanitizeString($value);
        }

        if (\is_bool($value) === true || \is_int($value) === true || \is_float($value) === true || $value === null) {
            return $value;
        }

        return null;
    }

    private function sanitizeString(string $value): string
    {
        $value = mb_trim($value);
        $value = strip_tags($value);
        $value = htmlspecialchars($value, \ENT_NOQUOTES, 'UTF-8');
        $value = preg_replace('/[\p{C}]/u', '', $value);

        return (string) $value;
    }
}
