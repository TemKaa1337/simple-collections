<?php

declare(strict_types=1);

namespace SimpleCollections\Provider;

use InvalidArgumentException;
use SimpleCollections\Model\UniqueCriteriaInterface;
use SimpleCollections\Processor\Unique\ByFieldProcessor;
use SimpleCollections\Processor\Unique\DefaultProcessor;
use SimpleCollections\Processor\Unique\ProcessorInterface;

final readonly class UniqueProcessorProvider
{
    public function __construct(
        private ?UniqueCriteriaInterface $criteria,
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
            new DefaultProcessor(),
            new ByFieldProcessor(),
        ];
    }
}
