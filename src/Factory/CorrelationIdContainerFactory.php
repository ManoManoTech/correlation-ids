<?php

declare(strict_types=1);

namespace ManoManoTech\CorrelationId\Factory;

use ManoManoTech\CorrelationId\CorrelationIdContainer;
use ManoManoTech\CorrelationId\CorrelationIdContainerInterface;
use ManoManoTech\CorrelationId\Generator\UniqueIdGeneratorInterface;

/** Generates a CorrelationIdContainer by giving values manually. */
final class CorrelationIdContainerFactory implements CorrelationIdContainerFactoryInterface
{
    /** @var UniqueIdGeneratorInterface */
    private $uniqueIdentifierGenerator;

    public function __construct(UniqueIdGeneratorInterface $uniqueIdentifierGenerator)
    {
        $this->uniqueIdentifierGenerator = $uniqueIdentifierGenerator;
    }

    public function create(?string $parentCorrelationId, ?string $rootCorrelationId): CorrelationIdContainerInterface
    {
        return new CorrelationIdContainer(
            $this->uniqueIdentifierGenerator->generateUniqueIdentifier(),
            $parentCorrelationId,
            $rootCorrelationId
        );
    }
}
