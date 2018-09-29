<?php

declare(strict_types=1);

namespace ManoManoTech\CorrelationId\Factory;

use ManoManoTech\CorrelationId\CorrelationIdContainerInterface;

interface CorrelationIdContainerFactoryInterface
{
    public function create(?string $parentCorrelationId, ?string $rootCorrelationId): CorrelationIdContainerInterface;
}
