<?php

namespace Bretto36\CspReporting\Event;

use Symfony\Component\HttpFoundation\InputBag;

class CspViolationReportReceived
{
    public bool $shouldReport = true;

    public function __construct(public InputBag $violationReport)
    {
    }
}
