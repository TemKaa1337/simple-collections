<?php

declare(strict_types=1);

namespace SimpleCollections\Provider;

use InvalidArgumentException;
use SimpleCollections\Model\SortCriteriaInterface;
use SimpleCollections\Processor\Sort\ByCallbackProcessor;
use SimpleCollections\Processor\Sort\ByFieldProcessor;
use SimpleCollections\Processor\Sort\ByKeysProcessor;
use SimpleCollections\Processor\Sort\ByValuesProcessor;
use SimpleCollections\Processor\Sort\ProcessorInterface;

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
