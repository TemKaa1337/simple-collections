<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Provider;

use InvalidArgumentException;
use Temkaa\SimpleCollections\Model\SumCriteriaInterface;
use Temkaa\SimpleCollections\Processor\Sum\ByFieldProcessor;
use Temkaa\SimpleCollections\Processor\Sum\DefaultProcessor;
use Temkaa\SimpleCollections\Processor\Sum\ProcessorInterface;

/**
 * @internal
 */
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
