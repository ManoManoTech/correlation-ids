<?php

declare(strict_types=1);

namespace ManoManoTech\CorrelationId;

interface CorrelationEntryNameInterface
{
    /** @return string correlation id header name of the current application's process */
    public function current(): string;

    /** @return string correlation id header name of the parent application's process */
    public function parent(): string;

    /** @return string correlation id header name of the root application's process */
    public function root(): string;
}
