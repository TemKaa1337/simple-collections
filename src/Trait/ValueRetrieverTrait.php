<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Trait;

use ReflectionClass;
use ReflectionException;

/**
 * @internal
 */
trait ValueRetrieverTrait
{
    /**
     * @throws ReflectionException
     */
    private function retrieve(mixed $source, array|string $field): mixed
    {
        if (!$field) {
            return $source;
        }

        $path = is_array($field) ? $field : explode('.', $field);
        $currentKey = array_shift($path);

        if (is_array($source)) {
            $value = $source[$currentKey] ?? null;

            return $value !== null ? $this->retrieve($value, $path) : null;
        }

        if (!is_object($source)) {
            return $source;
        }

        $reflection = new ReflectionClass($source);
        if (!$reflection->hasProperty($currentKey)) {
            $propertyValue = $source->{$currentKey};

            return $propertyValue !== null ? $this->retrieve($propertyValue, $path) : null;
        }

        $property = $reflection->getProperty($currentKey);
        $propertyValue = $property->isInitialized($source) ? $property->getValue($source) : null;

        return $propertyValue !== null ? $this->retrieve($propertyValue, $path) : null;
    }

    private function retrieveCallable(string $field): callable
    {
        return fn (mixed $value): mixed => $this->retrieve($value, $field);
    }
}
