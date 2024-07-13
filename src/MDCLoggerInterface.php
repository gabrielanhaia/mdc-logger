<?php

declare(strict_types=1);

namespace MDCLogger;

use Psr\Log\LoggerInterface;

interface MDCLoggerInterface extends LoggerInterface
{
    public const DEFAULT_MDC_CONTEXT_KEY = 'mdc_context';

    public const DEFAULT_LOCAL_CONTEXT_KEY = 'local_context';

    public function addGlobalContext(string $key, string $value): void;

    public function getGlobalContext(): array;

    public function clearGlobalContext(): void;
}