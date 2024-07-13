<?php

declare(strict_types=1);

namespace MDCLogger;

use Psr\Log\LoggerInterface;

class MDCLogger implements MDCLoggerInterface
{
    private LoggerInterface $logger;

    private array $mdcContext = [];

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __destruct()
    {
        $this->clearGlobalContext();
    }

    public function addGlobalContext(string $key, string $value): void
    {
        $this->mdcContext[$key] = $value;
    }

    public function getGlobalContext(): array
    {
        return $this->mdcContext;
    }

    public function clearGlobalContext(): void
    {
        $this->mdcContext = [];
    }

    private function formatLogContext(array $context): array
    {
        return ['mdc_context' => $this->mdcContext, 'local_context' => $context];
    }

    public function emergency(\Stringable|string $message, array $context = []): void
    {
        $this->logger->emergency($message, $this->formatLogContext($context));
    }

    public function alert(\Stringable|string $message, array $context = []): void
    {
        $this->logger->alert($message, $this->formatLogContext($context));
    }

    public function critical(\Stringable|string $message, array $context = []): void
    {
        $this->logger->critical($message, $this->formatLogContext($context));
    }

    public function error(\Stringable|string $message, array $context = []): void
    {
        $this->logger->error($message, $this->formatLogContext($context));
    }

    public function warning(\Stringable|string $message, array $context = []): void
    {
        $this->logger->warning($message, $this->formatLogContext($context));
    }

    public function notice(\Stringable|string $message, array $context = []): void
    {
        $this->logger->notice($message, $this->formatLogContext($context));
    }

    public function info(\Stringable|string $message, array $context = []): void
    {
        $this->logger->info($message, $this->formatLogContext($context));
    }

    public function debug(\Stringable|string $message, array $context = []): void
    {
        $this->logger->debug($message, $this->formatLogContext($context));
    }

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $this->logger->log($level, $message, $this->formatLogContext($context));
    }
}
