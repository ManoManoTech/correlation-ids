<?php

declare(strict_types=1);

namespace ManoManoTech\CorrelationId;

final class CorrelationEntryName implements CorrelationEntryNameInterface
{
    /** @var string */
    private $current;
    /** @var string */
    private $parent;
    /** @var string */
    private $root;

    public function __construct(string $current, string $parent, string $root)
    {
        $this->current = $current;
        $this->parent = $parent;
        $this->root = $root;
    }

    public static function simple(): self
    {
        return new self('current', 'parent', 'root');
    }

    public static function prefixed(string $prefix = 'correlation-id-'): self
    {
        return new self("${prefix}current", "${prefix}parent", "${prefix}root");
    }

    public static function suffixed(string $suffix = '-correlation-id'): self
    {
        return new self("current${suffix}", "parent${suffix}", "root${suffix}");
    }

    public function current(): string
    {
        return $this->current;
    }

    public function parent(): string
    {
        return $this->parent;
    }

    public function root(): string
    {
        return $this->root;
    }
}
