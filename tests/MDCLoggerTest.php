<?php

namespace MDCLoggerTests;

use MDCLogger\MDCLogger;
use MDCLogger\MDCLoggerInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class MDCLoggerTest extends TestCase
{
    private const GLOBAL_CONTEXT_KEY = 'key';

    private const GLOBAL_CONTEXT_VALUE = 'value';

    private const LOCAL_CONTEXT = ['local_key' => 'local_value'];

    private LoggerInterface|MockObject $logger;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);

        parent::setUp();
    }

    public function testAddGlobalContext(): void
    {
        $mdcLogger = $this->getMDCLogger();
        $mdcLogger->addGlobalContext(self::GLOBAL_CONTEXT_KEY, self::GLOBAL_CONTEXT_VALUE);

        $expected = [self::GLOBAL_CONTEXT_KEY => self::GLOBAL_CONTEXT_VALUE];

        $this->assertEquals($expected, $mdcLogger->getGlobalContext());
    }

    public function testClearGlobalContext(): void
    {
        $mdcLogger = $this->getMDCLogger();
        $mdcLogger->addGlobalContext(self::GLOBAL_CONTEXT_KEY, self::GLOBAL_CONTEXT_VALUE);
        $mdcLogger->clearGlobalContext();

        $this->assertEmpty($mdcLogger->getGlobalContext());
    }

    /**
     * @dataProvider logContextDataProvider
     */
    public function testLogging(string $methodName): void
    {
        $logMessage = 'test';

        $mdcLogger = $this->getMDCLogger();
        $mdcLogger->$methodName($logMessage, self::LOCAL_CONTEXT);

        $mdcLogger->addGlobalContext(self::GLOBAL_CONTEXT_KEY, self::GLOBAL_CONTEXT_VALUE);

        $expected = [
            MDCLoggerInterface::DEFAULT_MDC_CONTEXT_KEY => [
                self::GLOBAL_CONTEXT_KEY => self::GLOBAL_CONTEXT_VALUE
            ],
            MDCLoggerInterface::DEFAULT_LOCAL_CONTEXT_KEY => self::LOCAL_CONTEXT
        ];

        $this->logger->expects($this->once())
            ->method($methodName)
            ->with($logMessage, $expected);

        $mdcLogger->$methodName($logMessage, self::LOCAL_CONTEXT);
    }

    public function testCustomMDCKey(): void
    {
        $customMDCKey = 'custom_key';
        $logMessage = 'test message';
        $logLocalContext = ['test_local_key' => 'test_local_value'];

        $mdcLogger = new MDCLogger($this->logger, $customMDCKey);
        $mdcLogger->addGlobalContext(self::GLOBAL_CONTEXT_KEY, self::GLOBAL_CONTEXT_VALUE);

        $expected = [
            $customMDCKey => [self::GLOBAL_CONTEXT_KEY => self::GLOBAL_CONTEXT_VALUE],
            MDCLoggerInterface::DEFAULT_LOCAL_CONTEXT_KEY => $logLocalContext
        ];



        $this->logger->expects($this->once())
            ->method('emergency')
            ->with($logMessage, $expected);

        $mdcLogger->emergency($logMessage, $logLocalContext);
    }

    public static function logContextDataProvider(): \Generator
    {
        yield 'Emergency' => ['emergency'];
        yield 'Alert' => ['alert'];
        yield 'Critical' => ['critical'];
        yield 'Error' => ['error'];
        yield 'Warning' => ['warning'];
    }

    private function getMDCLogger(): MDCLogger
    {
        return new MDCLogger($this->logger);
    }
}