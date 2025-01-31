<?php

namespace Bretto36\CspReporting\Event;

class CspViolationReportReceived
{
    public bool $shouldReport = true;

    public function __construct(public array $violationReport)
    {
    }
}
