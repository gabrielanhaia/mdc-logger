<?php

declare(strict_types=1);

namespace MDCLogger;

use Psr\Log\LoggerInterface;

interface MDCLoggerInterface extends LoggerInterface
{
    public function addGlobalContext(string $key, string $value): void;

    public function clearGlobalContext(): void;

    public function formatLogContext(array $context): array;
}