<?php

declare(strict_types=1);

namespace ManoManoTech\CorrelationId\Tests;

use ManoManoTech\CorrelationId\CorrelationEntryName;
use PHPUnit\Framework\TestCase;

/** @covers \ManoManoTech\CorrelationId\CorrelationEntryName */
final class CorrelationEntryNameTest extends TestCase
{
    public function testConstructor(): void
    {
        $this->baseTest(new CorrelationEntryName('c', 'p', 'r'), 'c', 'p', 'r');
    }

    public function testSimple(): void
    {
        $this->baseTest(CorrelationEntryName::simple(), 'current', 'parent', 'root');
    }

    public function testPrefixed(): void
    {
        $this->baseTest(
            CorrelationEntryName::prefixed(),
            'correlation-id-current',
            'correlation-id-parent',
            'correlation-id-root'
        );
    }

    public function testPrefixedWithArgument(): void
    {
        $this->baseTest(CorrelationEntryName::prefixed('test'), 'testcurrent', 'testparent', 'testroot');
    }

    public function testSuffixed(): void
    {
        $this->baseTest(
            CorrelationEntryName::suffixed(),
            'current-correlation-id',
            'parent-correlation-id',
            'root-correlation-id'
        );
    }

    public function testSuffixedWithArgument(): void
    {
        $this->baseTest(CorrelationEntryName::suffixed('test'), 'currenttest', 'parenttest', 'roottest');
    }

    private function baseTest(
        CorrelationEntryName $objectUnderTest,
        string $current,
        string $parent,
        string $root
    ): void {
        static::assertSame($current, $objectUnderTest->current());
        static::assertSame($parent, $objectUnderTest->parent());
        static::assertSame($root, $objectUnderTest->root());
    }
}
