<?php

namespace App\Http\Factory;

trait LoggerTrait
{
    /** @var string The Log channel to which logs should output to. Defaults to Laravel's daily log channel */
    protected string $logChannel = '';

    /** @var LogFactory The controller's default logger. Will output to wherever $logChannel points to */
    protected LogFactory $logger;

    /**
     * Prepare the Log Factory with all the context and configuration required.
     *
     * @see LogFactory
     * @return LogFactory
     */
    protected function log(): LogFactory
    {
        if (empty($this->logger)) {
            $this->logger = new LogFactory(get_class($this), null, $this->logChannel);
        }

        $callingMethod = debug_backtrace(!DEBUG_BACKTRACE_PROVIDE_OBJECT|DEBUG_BACKTRACE_IGNORE_ARGS,2)[1]['function'];
        $this->logger->setMethod($callingMethod);
        return $this->logger;
    }
}
