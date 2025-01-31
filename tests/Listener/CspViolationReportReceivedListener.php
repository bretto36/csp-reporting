<?php

namespace Bretto36\CspReporting\Tests\Listener;

use Bretto36\CspReporting\Event\CspViolationReportReceived;

class CspViolationReportReceivedListener
{
    public function handle(CspViolationReportReceived $event): void
    {
        if ($event->violationReport['script-sample'] === 'alert(1)') {
            $event->shouldReport = false;
        }
    }
}
