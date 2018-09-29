<?php

declare(strict_types=1);

namespace ManoManoTech\CorrelationId\Generator;

use LogicException;
use Ramsey\Uuid\Uuid;

final class RamseyUuidGenerator implements UniqueIdGeneratorInterface
{
    public function generateUniqueIdentifier(): string
    {
        if (!class_exists(Uuid::class)) {
            throw new LogicException(sprintf('%s requires ramsey/uuid library', __CLASS__));
        }

        return Uuid::uuid4()->toString();
    }
}
