<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Provider;

use InvalidArgumentException;
use Temkaa\SimpleCollections\Model\ConditionInterface;
use Temkaa\SimpleCollections\Processor\Condition\CompareProcessor;
use Temkaa\SimpleCollections\Processor\Condition\ExactlyProcessor;
use Temkaa\SimpleCollections\Processor\Condition\ProcessorInterface;

/**
 * @internal
 */
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
