<?php

namespace Bretto36\CspReporting\Exception;

class CspViolationReportException extends \Exception
{
    public function __construct(protected array $data, $message = 'CSP Violation Report Exception', $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get the exception's context information.
     *
     * @return array<string, mixed>
     */
    public function context(): array
    {
        return [...$this->data];
    }
}
