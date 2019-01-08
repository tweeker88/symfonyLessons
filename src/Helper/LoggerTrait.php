<?php

namespace App\Helper;

use Psr\Log\LoggerInterface;

trait LoggerTrait
{
    /**
     * @var LoggerInterface|null
     */
    private $logger;

    /**
     * @required
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger) :void
    {
        $this->logger = $logger;
    }

    private function logInfo(string $message, array $context = []) :void
    {
        if ($this->logger) {
            $this->logger->info($message, $context);
        }
    }
}