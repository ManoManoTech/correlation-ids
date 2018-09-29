<?php

declare(strict_types=1);

namespace ManoManoTech\CorrelationId\Tests\Factory;

use ManoManoTech\CorrelationId\Factory\CorrelationIdContainerFactory;
use ManoManoTech\CorrelationId\Generator\UniqueIdGeneratorInterface;
use PHPUnit\Framework\TestCase;

/** @covers \ManoManoTech\CorrelationId\Factory\CorrelationIdContainerFactory */
final class CorrelationIdContainerFactoryTest extends TestCase
{
    /** @dataProvider provideDataForCreate */
    public function testCreate(?string $expectedParentValue, ?string $expectedRootValue): void
    {
        // init
        $generator = $this->createMock(UniqueIdGeneratorInterface::class);
        $generator->expects(self::once())
                  ->method('generateUniqueIdentifier')
                  ->willReturn('foo');

        $object = new CorrelationIdContainerFactory($generator);

        // run
        $result = $object->create($expectedParentValue, $expectedRootValue);

        // test
        static::assertSame('foo', $result->current());
        static::assertSame($expectedParentValue, $result->parent());
        static::assertSame($expectedRootValue, $result->root());
    }

    public function provideDataForCreate(): array
    {
        return [
            'parent and root can be null' => [
                'parent' => null,
                'root' => null,
            ],
            'only parent is specified' => [
                'parent' => 'bar',
                'root' => null,
            ],
            'only root is specified' => [
                'parent' => null,
                'root' => 'baz',
            ],
            'both root and parent are specified' => [
                'parent' => 'bar',
                'root' => 'baz',
            ],
        ];
    }
}
