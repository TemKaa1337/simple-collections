<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Model\Unique;

use Temkaa\SimpleCollections\Model\UniqueCriteriaInterface;

final readonly class ByField implements UniqueCriteriaInterface
{
    public function __construct(
        public string $field,
    ) {
    }
}
