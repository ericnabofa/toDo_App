<?php

class ErrorHandler {
    private $maxRetries = 3;
    private $retryDelay = 100; // milliseconds
    private $errorLog;

    public function __construct($errorLog) {
        $this->errorLog = $errorLog;
    }

    public function handleError($exception) {
        $retryCount = 0;
        while ($retryCount < $this->maxRetries) {
            try {
                // Retry the operation
                return;
            } catch (Exception $e) {
                $retryCount++;
                usleep($this->retryDelay * 1000); // Exponential backoff
            }
        }
        // Log the error if retries are exhausted
        $this->logError($exception);
    }

    private function logError($exception) {
        $errorData = ['timestamp' => time(), 'error' => $exception->getMessage()];
        file_put_contents($this->errorLog, json_encode($errorData) . PHP_EOL, FILE_APPEND);
    }
}
