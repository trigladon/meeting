<?php

namespace Common\Service;

use Zend\Log\Logger;

class ErrorLogger
{

    protected $logger = null;

    function __construct($logger = null)
    {
        $this->logger = $logger;
    }

    function logException(\Exception $e)
    {
        $trace = $e->getTraceAsString();
        $i     = 1;

        do {
            $messages[] = $i++ . ": " . $e->getMessage();
        } while ($e = $e->getPrevious());

        $prefix = '----------------------------------------------------------/';
        $log = "/" . date('Y-m-d H:i:s') . ":Exception:$prefix\n"
            . implode('', $messages)
            . "\nTrace:\n" . $trace . "\n\n";

        if ($this->logger instanceof Logger) {
            $this->logger->err($log);
        } else {
            error_log($log, 3,
                DIR_ROOT . '/data/logs/' . 'log_' . date('Y.m.d') . '.txt'
            );
        }

    }

}