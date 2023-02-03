<?php

namespace App\Http\Factory;

use App\Http\Controllers\AbstractRestController;
use Exception;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;

class LogFactory
{

    /**
     * Logging utility designed for REST controllers. The methods in this object will output logs to
     * a designated channel.
     *
     * This class will be instantiated by default by all REST Controllers. However, if you require a
     * custom integration, you may instantiate this object with your desired configurations.
     *
     * @see AbstractRestController::log()
     * @param string|null $class The class namespace calling the logger; if null, the class will guess where the log came from
     * @param string|null $method The method calling the logger; if null, the class will guess where the log came from
     * @param string|null $channel The logging channel; if null, the class will use the default Laravel channel
     */
    public function __construct(private ?string $class = null, private ?string $method = null, private ?string $channel = null)
    {}

    /**
     * Build a log from an exception object
     *
     * @param string $message Short message outlining what has gone wrong
     * @param Exception $exception The exception object thrown
     * @param array $context Additional context to include
     * @return void
     */
    public function exception(string $message, Exception $exception, array $context = []): void
    {
        $this->write(
            'error',
            $message,
            array_merge(
                [
                    'statusCode'   => $exception->getCode(),
                    'errorMessage' => $exception->getMessage(),
                    'errorLine'    => $exception->getLine(),
                    'stackTrace'   => $exception->getTraceAsString()
                ],
                $context
            )
        );
    }

    /**
     * Write a "ALERT" level Log
     *
     * @see Logger::alert()
     * @param string $message A simple message string outlining what occurred
     * @param array $context An array of data that brings context to the message of this log
     * @return void
     */
    public function alert(string $message, array $context = []): void
    {
        $this->write(__FUNCTION__, $message, $context);
    }

    /**
     * Write a "CRITICAL" level Log
     *
     * @see Logger::critical()
     * @param string $message A simple message string outlining what occurred
     * @param array $context An array of data that brings context to the message of this log
     * @return void
     */
    public function critical(string $message, array $context = []): void
    {
        $this->write(__FUNCTION__, $message, $context);
    }

    /**
     * Write a "DEBUG" level Log
     *
     * @see Logger::debug()
     * @param string $message A simple message string outlining what occurred
     * @param array $context An array of data that brings context to the message of this log
     * @return void
     */
    public function debug(string $message, array $context = []): void
    {
        $this->write(__FUNCTION__, $message, $context);
    }

    /**
     * Write a "EMERGENCY" level Log
     *
     * @see Logger::emergency()
     * @param string $message A simple message string outlining what occurred
     * @param array $context An array of data that brings context to the message of this log
     * @return void
     */
    public function emergency(string $message, array $context = []): void
    {
        $this->write(__FUNCTION__, $message, $context);
    }

    /**
     * Write a "ERROR" level Log
     *
     * @see Logger::error()
     * @param string $message A simple message string outlining what occurred
     * @param array $context An array of data that brings context to the message of this log
     * @return void
     */
    public function error(string $message, array $context = []): void
    {
        $this->write(__FUNCTION__, $message, $context);
    }

    /**
     * Write a "INFO" level Log
     *
     * @see Logger::info()
     * @param string $message A simple message string outlining what occurred
     * @param array $context An array of data that brings context to the message of this log
     * @return void
     */
    public function info(string $message, array $context = []): void
    {
        $this->write(__FUNCTION__, $message, $context);
    }

    /**
     * Write a "NOTICE" level Log
     *
     * @see Logger::notice()
     * @param string $message A simple message string outlining what occurred
     * @param array $context An array of data that brings context to the message of this log
     * @return void
     */
    public function notice(string $message, array $context = []): void
    {
        $this->write(__FUNCTION__, $message, $context);
    }

    /**
     * Write a "WARNING" level Log
     *
     * @see Logger::warning()
     * @param string $message A simple message string outlining what occurred
     * @param array $context An array of data that brings context to the message of this log
     * @return void
     */
    public function warning(string $message, array $context = []): void
    {
        $this->write(__FUNCTION__, $message, $context);
    }

    /**
     * @see Logger::writeLog()
     * @param string $level The log level (e.g. "warning", "error", "debug")
     * @param string $message A simple message string outlining what occurred
     * @param array $context An array of data that brings context to the message of this log
     * @return void
     */
    private function write(string $level, string $message, array $context = []): void
    {
        $prefix = $this->getPrefix();
        if (! empty($this->channel)) {
            Log::channel($this->channel)->log($level, $prefix . $message, $context);
        } else {
            Log::log($level, $prefix . $message, $context);
        }

        // TODO: Also log to CloudWatch
    }

    /**
     * Sets the calling method to be registered under this log
     *
     * @param string|null $method
     * @return void
     */
    public function setMethod(?string $method): void
    {
        $this->method = $method;
    }

    /**
     * Sets the calling class to be registered under this log
     *
     * @param string|null $class
     * @return void
     */
    public function setClass(?string $class): void
    {
        $this->class = $class;
    }

    /**
     * Sets the log channel to be registered under this log
     *
     * @param string|null $channel
     * @return void
     */
    public function setChannel(?string $channel): void
    {
        $this->channel = $channel;
    }

    /**
     * Sets the log channel. Use this method in static context.
     *
     * @see LogFactory::setChannel()
     * @param string|null $channel
     * @return LogFactory
     */
    public function channel(?string $channel): static
    {
        $this->setChannel($channel);
        return $this;
    }

    /**
     * Builds the prefix to be added to the Log's message.
     * If class or method name properties are empty, a guess will be made.
     *
     * @see LogFactory::write()
     * @return string
     */
    private function getPrefix(): string
    {
        if (empty($this->class) || empty($this->method)) {
            // Trace of the calling class and method
            $trace = debug_backtrace(!DEBUG_BACKTRACE_PROVIDE_OBJECT|DEBUG_BACKTRACE_IGNORE_ARGS,4)[3];

            // If being called from a static context, we need to go up the backtrace by 1 more instance
            if (str_contains($trace['class'], 'Facade')) {
                $trace = debug_backtrace(!DEBUG_BACKTRACE_PROVIDE_OBJECT|DEBUG_BACKTRACE_IGNORE_ARGS,5)[4];
            }

            $this->class = $trace['class'];
            $this->method = $trace['function'];
        }
        return $this->class . '@' . $this->method . ' — ';
    }
}
