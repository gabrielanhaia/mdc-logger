<?php

namespace MDCLoggerTests;

use MDCLogger\MDCLogger;
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

    public function testEmergency(): void
    {
        $logMessage = 'emergency test';

        $mdcLogger = $this->getMDCLogger();
        $mdcLogger->emergency($logMessage, self::LOCAL_CONTEXT);

        $mdcLogger->addGlobalContext(self::GLOBAL_CONTEXT_KEY, self::GLOBAL_CONTEXT_VALUE);

        $expected = [
            'mdc_context' => [
                self::GLOBAL_CONTEXT_KEY => self::GLOBAL_CONTEXT_VALUE
            ],
            'local_context' => self::LOCAL_CONTEXT
        ];

        $this->logger->expects($this->once())
            ->method('emergency')
            ->with($logMessage, $expected);

        $mdcLogger->emergency($logMessage, self::LOCAL_CONTEXT);
    }

    public function testAlert(): void
    {
        $logMessage = 'alert test';

        $mdcLogger = $this->getMDCLogger();
        $mdcLogger->alert($logMessage, self::LOCAL_CONTEXT);

        $mdcLogger->addGlobalContext(self::GLOBAL_CONTEXT_KEY, self::GLOBAL_CONTEXT_VALUE);

        $expected = [
            'mdc_context' => [
                self::GLOBAL_CONTEXT_KEY => self::GLOBAL_CONTEXT_VALUE
            ],
            'local_context' => self::LOCAL_CONTEXT
        ];

        $this->logger->expects($this->once())
            ->method('alert')
            ->with($logMessage, $expected);

        $mdcLogger->alert($logMessage, self::LOCAL_CONTEXT);
    }

    public function testCritical(): void
    {
        $logMessage = 'critical test';

        $mdcLogger = $this->getMDCLogger();
        $mdcLogger->critical($logMessage, self::LOCAL_CONTEXT);

        $mdcLogger->addGlobalContext(self::GLOBAL_CONTEXT_KEY, self::GLOBAL_CONTEXT_VALUE);

        $expected = [
            'mdc_context' => [
                self::GLOBAL_CONTEXT_KEY => self::GLOBAL_CONTEXT_VALUE
            ],
            'local_context' => self::LOCAL_CONTEXT
        ];

        $this->logger->expects($this->once())
            ->method('critical')
            ->with($logMessage, $expected);

        $mdcLogger->critical($logMessage, self::LOCAL_CONTEXT);
    }

    public function testError(): void
    {
        $logMessage = 'error test';

        $mdcLogger = $this->getMDCLogger();
        $mdcLogger->error($logMessage, self::LOCAL_CONTEXT);

        $mdcLogger->addGlobalContext(self::GLOBAL_CONTEXT_KEY, self::GLOBAL_CONTEXT_VALUE);

        $expected = [
            'mdc_context' => [
                self::GLOBAL_CONTEXT_KEY => self::GLOBAL_CONTEXT_VALUE
            ],
            'local_context' => self::LOCAL_CONTEXT
        ];

        $this->logger->expects($this->once())
            ->method('error')
            ->with($logMessage, $expected);

        $mdcLogger->error($logMessage, self::LOCAL_CONTEXT);
    }

    public function testWarning(): void
    {
        $logMessage = 'warning test';

        $mdcLogger = $this->getMDCLogger();
        $mdcLogger->warning($logMessage, self::LOCAL_CONTEXT);

        $mdcLogger->addGlobalContext(self::GLOBAL_CONTEXT_KEY, self::GLOBAL_CONTEXT_VALUE);

        $expected = [
            'mdc_context' => [
                self::GLOBAL_CONTEXT_KEY => self::GLOBAL_CONTEXT_VALUE
            ],
            'local_context' => self::LOCAL_CONTEXT
        ];

        $this->logger->expects($this->once())
            ->method('warning')
            ->with($logMessage, $expected);

        $mdcLogger->warning($logMessage, self::LOCAL_CONTEXT);
    }

    private function getMDCLogger(): MDCLogger
    {
        return new MDCLogger($this->logger);
    }
}