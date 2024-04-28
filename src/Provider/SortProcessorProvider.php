<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Provider;

use InvalidArgumentException;
use Temkaa\SimpleCollections\Model\SortCriteriaInterface;
use Temkaa\SimpleCollections\Processor\Sort\ByCallbackProcessor;
use Temkaa\SimpleCollections\Processor\Sort\ByFieldProcessor;
use Temkaa\SimpleCollections\Processor\Sort\ByKeysProcessor;
use Temkaa\SimpleCollections\Processor\Sort\ByValuesProcessor;
use Temkaa\SimpleCollections\Processor\Sort\ProcessorInterface;

/**
 * @internal
 */
final readonly class SortProcessorProvider
{
    public function __construct(
        private ?SortCriteriaInterface $criteria,
    ) {
    }

    public function provide(): ProcessorInterface
    {
        foreach ($this->getProcessors() as $processor) {
            if ($processor->supports($this->criteria)) {
                return $processor;
            }
        }

        throw new InvalidArgumentException(
            sprintf(
                'Could not find suitable processor for criteria: "%s".',
                $this->criteria::class ?? 'null',
            ),
        );
    }

    /**
     * @return ProcessorInterface[]
     */
    private function getProcessors(): array
    {
        return [
            new ByCallbackProcessor(),
            new ByFieldProcessor(),
            new ByKeysProcessor(),
            new ByValuesProcessor(),
        ];
    }
}
