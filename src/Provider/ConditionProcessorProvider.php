<?php

declare(strict_types=1);

namespace SimpleCollections\Provider;

use InvalidArgumentException;
use SimpleCollections\Model\ConditionInterface;
use SimpleCollections\Processor\Condition\CompareProcessor;
use SimpleCollections\Processor\Condition\ExactlyProcessor;
use SimpleCollections\Processor\Condition\ProcessorInterface;

final readonly class ConditionProcessorProvider
{
    public function __construct(
        private ConditionInterface $condition,
    ) {
    }

    public function provide(): ProcessorInterface
    {
        foreach ($this->getProcessors() as $processor) {
            if ($processor->supports($this->condition)) {
                return $processor;
            }
        }

        throw new InvalidArgumentException(
            sprintf(
                'Could not find suitable processor for condition: "%s".',
                $this->condition::class,
            ),
        );
    }

    /**
     * @return ProcessorInterface[]
     */
    private function getProcessors(): array
    {
        return [
            new CompareProcessor(),
            new ExactlyProcessor(),
        ];
    }
}
