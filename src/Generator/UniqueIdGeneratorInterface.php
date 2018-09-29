<?php

declare(strict_types=1);

namespace ManoManoTech\CorrelationId\Generator;

/**
 * Interface that your generator must implement to provide a way to generate unique identifiers.
 * It is used to generate a unique identifier for the current application execution.
 * The generated string must be unique across every application.
 */
interface UniqueIdGeneratorInterface
{
    /** Generates a unique identifier each time the function is called. */
    public function generateUniqueIdentifier(): string;
}
