<?php

declare(strict_types=1);

namespace ManoManoTech\CorrelationId\Factory;

use ManoManoTech\CorrelationId\CorrelationEntryName;
use ManoManoTech\CorrelationId\CorrelationEntryNameInterface;
use ManoManoTech\CorrelationId\CorrelationIdContainer;
use ManoManoTech\CorrelationId\CorrelationIdContainerInterface;
use ManoManoTech\CorrelationId\Generator\UniqueIdGeneratorInterface;

/** Generates a CorrelationIdContainer from HTTP headers. */
final class HeaderCorrelationIdContainerFactory implements HeaderCorrelationIdContainerFactoryInterface
{
    /** @var CorrelationEntryNameInterface */
    private $correlationEntryName;

    /** @var UniqueIdGeneratorInterface */
    private $uniqueIdentifierGenerator;

    public function __construct(
        UniqueIdGeneratorInterface $uniqueIdentifierGenerator,
        CorrelationEntryNameInterface $correlationEntryName = null
    ) {
        $this->uniqueIdentifierGenerator = $uniqueIdentifierGenerator;
        $this->correlationEntryName = $correlationEntryName ?? CorrelationEntryName::suffixed();
    }

    /** @param string[] $requestHeaders list of all your request headers */
    public function create(array $requestHeaders): CorrelationIdContainerInterface
    {
        $requestHeaders = $this->sanitizeHeaderKeys($requestHeaders);
        $parentCorrelationIdHeaderName = $this->sanitizeHeaderKey($this->correlationEntryName->parent());
        $rootCorrelationIdHeaderName = $this->sanitizeHeaderKey($this->correlationEntryName->root());

        return new CorrelationIdContainer(
            $this->uniqueIdentifierGenerator->generateUniqueIdentifier(),
            $this->extractHeader($requestHeaders, $parentCorrelationIdHeaderName),
            $this->extractHeader($requestHeaders, $rootCorrelationIdHeaderName)
        );
    }

    private function sanitizeHeaderKeys(array $requestHeaders): array
    {
        $newRequestHeaders = [];
        foreach ($requestHeaders as $key => $value) {
            $newRequestHeaders[$this->sanitizeHeaderKey($key)] = $value;
        }

        return $newRequestHeaders;
    }

    private function sanitizeHeaderKey(string $key): string
    {
        // a header is case insensitive
        return mb_strtolower($key);
    }

    private function extractHeader(array $requestHeaders, string $headerName): ?string
    {
        if (!array_key_exists($headerName, $requestHeaders)) {
            return null;
        }
        $values = $requestHeaders[$headerName];

        return \is_array($values) ? implode(',', $values) : $values;
    }
}
