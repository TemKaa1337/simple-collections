<?php

declare(strict_types=1);

namespace SimpleCollections\Tests\Stub;

final readonly class ClassWithProperty
{
    public function __construct(
        private int $test,
    ) {
    }
}
