<?php

declare(strict_types=1);

namespace ManoManoTech\CorrelationId\Tests\Generator;

use LogicException;
use ManoManoTech\CorrelationId\Generator\RamseyUuidGenerator;
use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\TestCase;

/** @covers \ManoManoTech\CorrelationId\Generator\RamseyUuidGenerator */
final class RamseyUuidGeneratorTest extends TestCase
{
    use PHPMock;

    public function testGenerateUniqueIdentifierWhenPackageDoesNotExists(): void
    {
        // init
        $object = new RamseyUuidGenerator();

        $classExists = $this->getFunctionMock('ManoManoTech\CorrelationId\Generator', 'class_exists');
        $classExists->expects(static::any())->willReturn(false);

        // test
        $this->expectException(LogicException::class);

        // run
        $object->generateUniqueIdentifier();
    }

    public function testGenerateUniqueIdentifier(): void
    {
        // init
        $object = new RamseyUuidGenerator();

        // run
        $result1 = $object->generateUniqueIdentifier();
        $result2 = $object->generateUniqueIdentifier();

        // test
        static::assertNotSame($result1, $result2, 'Consecutive call should not output the same result');
    }
}
