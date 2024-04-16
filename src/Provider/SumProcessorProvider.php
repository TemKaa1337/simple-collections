<?php

declare(strict_types=1);

namespace SimpleCollections\Provider;

use InvalidArgumentException;
use SimpleCollections\Model\SumCriteriaInterface;
use SimpleCollections\Processor\Sum\ByFieldProcessor;
use SimpleCollections\Processor\Sum\DefaultProcessor;
use SimpleCollections\Processor\Sum\ProcessorInterface;

final readonly class SumProcessorProvider
{
    public function __construct(
        private ?SumCriteriaInterface $criteria,
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
