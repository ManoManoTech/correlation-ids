<?php

declare(strict_types=1);

namespace ManoManoTech\CorrelationId\Tests\Factory;

use ManoManoTech\CorrelationId\CorrelationEntryNameInterface;
use ManoManoTech\CorrelationId\Factory\HeaderCorrelationIdContainerFactory;
use ManoManoTech\CorrelationId\Generator\UniqueIdGeneratorInterface;
use PHPUnit\Framework\TestCase;

/** @covers \ManoManoTech\CorrelationId\Factory\HeaderCorrelationIdContainerFactory */
final class HeaderCorrelationIdContainerFactoryTest extends TestCase
{
    /**
     * @dataProvider provideDataForCreate
     *
     * @param mixed[] $headers
     */
    public function testCreate(array $headers, ?string $expectedParentValue, ?string $expectedRootValue): void
    {
        // init
        $generator = $this->createMock(UniqueIdGeneratorInterface::class);
        $generator->expects(self::once())
                  ->method('generateUniqueIdentifier')
                  ->willReturn('foo');

        $correlationEntryName = $this->createMock(CorrelationEntryNameInterface::class);
        $correlationEntryName->expects(self::once())
                              ->method('parent')
                              ->willReturn('Parent-Correlation-Id');
        $correlationEntryName->expects(self::once())
                              ->method('root')
                              ->willReturn('Root-Correlation-Id');

        $object = new HeaderCorrelationIdContainerFactory($generator, $correlationEntryName);

        // run
        $result = $object->create($headers);

        // test
        static::assertSame('foo', $result->current());
        static::assertSame($expectedParentValue, $result->parent());
        static::assertSame($expectedRootValue, $result->root());
    }

    public function provideDataForCreate(): array
    {
        return [
            'nothing in headers' => [
                'headers' => [
                    'Accept' => '*/*',
                    'Accept-Language' => 'en-us',
                    'Accept-Encoding' => 'gzip, deflate',
                    'User-Agent' => 'Mozilla/4.0',
                    'Host' => 'www.example.com',
                    'Connection' => 'Keep-Alive',
                ],
                'parent' => null,
                'root' => null,
            ],
            'only parent header exist' => [
                'headers' => [
                    'Accept' => '*/*',
                    'Accept-Language' => 'en-us',
                    'Accept-Encoding' => 'gzip, deflate',
                    'Parent-Correlation-Id' => 'bar',
                    'User-Agent' => 'Mozilla/4.0',
                    'Host' => 'www.example.com',
                    'Connection' => 'Keep-Alive',
                ],
                'parent' => 'bar',
                'root' => null,
            ],
            'only root header exist' => [
                'headers' => [
                    'Accept' => '*/*',
                    'Accept-Language' => 'en-us',
                    'Accept-Encoding' => 'gzip, deflate',
                    'Root-Correlation-Id' => 'baz',
                    'User-Agent' => 'Mozilla/4.0',
                    'Host' => 'www.example.com',
                    'Connection' => 'Keep-Alive',
                ],
                'parent' => null,
                'root' => 'baz',
            ],
            'both root and parent headers exist' => [
                'headers' => [
                    'Accept' => '*/*',
                    'Accept-Language' => 'en-us',
                    'Accept-Encoding' => 'gzip, deflate',
                    'Parent-Correlation-Id' => 'bar',
                    'Root-Correlation-Id' => 'baz',
                    'User-Agent' => 'Mozilla/4.0',
                    'Host' => 'www.example.com',
                    'Connection' => 'Keep-Alive',
                ],
                'parent' => 'bar',
                'root' => 'baz',
            ],
            'both root and parent headers exist in another case' => [
                'headers' => [
                    'Accept' => '*/*',
                    'Accept-Language' => 'en-us',
                    'Accept-Encoding' => 'gzip, deflate',
                    mb_strtoupper('Parent-Correlation-Id') => 'bar',
                    mb_strtolower('Root-Correlation-Id') => 'baz',
                    'User-Agent' => 'Mozilla/4.0',
                    'Host' => 'www.example.com',
                    'Connection' => 'Keep-Alive',
                ],
                'parent' => 'bar',
                'root' => 'baz',
            ],
        ];
    }
}
