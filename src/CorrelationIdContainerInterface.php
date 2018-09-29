<?php

declare(strict_types=1);

namespace ManoManoTech\CorrelationId;

interface CorrelationIdContainerInterface
{
    /** Uniquely identifies this application's current execution. */
    public function current(): string;

    /** Uniquely identifies the parent application that caused this application's current execution. */
    public function parent(): ?string;

    /** Uniquely identifies the root application that caused this application's current execution. */
    public function root(): ?string;

    /** Replace values with the one specified in arguments. */
    public function replace(self $other): void;
}
