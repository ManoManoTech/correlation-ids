<?php

declare(strict_types=1);

namespace ManoManoTech\CorrelationId\Factory;

use ManoManoTech\CorrelationId\CorrelationIdContainerInterface;

interface HeaderCorrelationIdContainerFactoryInterface
{
    /** @param string[] $requestHeaders list of all your request headers */
    public function create(array $requestHeaders): CorrelationIdContainerInterface;
}
