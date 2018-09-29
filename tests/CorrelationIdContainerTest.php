<?php

declare(strict_types=1);

namespace ManoManoTech\CorrelationId\Tests;

use ManoManoTech\CorrelationId\CorrelationIdContainer;
use PHPUnit\Framework\TestCase;

/** @covers \ManoManoTech\CorrelationId\CorrelationIdContainer */
final class CorrelationIdContainerTest extends TestCase
{
    /** @dataProvider provideDataForConstructor */
    public function testConstructor(string $current, ?string $parent, ?string $root): void
    {
        $object = new CorrelationIdContainer($current, $parent, $root);

        static::assertSame($current, $object->current());
        static::assertSame($parent, $object->parent());
        static::assertSame($root, $object->root());
    }

    public function testReplace(): void
    {
        // init
        $object = new CorrelationIdContainer('foo', 'bar', 'baz');
        $objectReplacement = new CorrelationIdContainer('foo1', 'bar1', 'baz1');
        $objectReplacement2 = new CorrelationIdContainer('foo2', 'bar2', 'baz2');

        // first run: classic replacement
        $object->replace($objectReplacement);

        // test
        static::assertSame('foo1', $object->current());
        static::assertSame('bar1', $object->parent());
        static::assertSame('baz1', $object->root());

        // second run: ensure $objectReplacement is not affected
        $object->replace($objectReplacement2);

        static::assertSame('foo2', $object->current());
        static::assertSame('bar2', $object->parent());
        static::assertSame('baz2', $object->root());
        static::assertSame('foo1', $objectReplacement->current(), 'provided object must not be affected');
        static::assertSame('bar1', $objectReplacement->parent(), 'provided object must not be affected');
        static::assertSame('baz1', $objectReplacement->root(), 'provided object must not be affected');
    }

    public function provideDataForConstructor(): array
    {
        return [
            'valid string should have no effect' => [
                'current',
                'parent',
                'root',
            ],
            'parent is null' => [
                'current',
                null,
                'root',
            ],
            'root is null' => [
                'current',
                'parent',
                null,
            ],
            'both root and parent are null' => [
                'current',
                null,
                null,
            ],
        ];
    }
}
