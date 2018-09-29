<?php

declare(strict_types=1);

namespace ManoManoTech\CorrelationId;

/** Determines the context of the application's current execution. */
final class CorrelationIdContainer implements CorrelationIdContainerInterface
{
    /** @var string */
    private $current;
    /** @var string|null */
    private $parent;
    /** @var string|null */
    private $root;

    public function __construct(string $current, ?string $parent, ?string $root)
    {
        $this->current = $current;
        $this->parent = $parent;
        $this->root = $root;
    }

    public function replace(CorrelationIdContainerInterface $other): void
    {
        $this->current = $other->current();
        $this->parent = $other->parent();
        $this->root = $other->root();
    }

    public function current(): string
    {
        return $this->current;
    }

    public function parent(): ?string
    {
        return $this->parent;
    }

    public function root(): ?string
    {
        return $this->root;
    }
}
