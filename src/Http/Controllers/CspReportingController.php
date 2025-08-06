<?php

namespace Bretto36\CspReporting\Http\Controllers;

use Bretto36\CspReporting\Event\CspViolationReportReceived;
use Bretto36\CspReporting\Exception\CspViolationReportException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Event;

class CspReportingController extends Controller
{
    public function __invoke(Request $request)
    {
        Event::dispatch($event = new CspViolationReportReceived($request->json('csp-report')));

        // If the CspViolationReportReceived event tells it to report, throw an exception
        report_if($event->shouldReport, new CspViolationReportException($event->violationReport));

        return 'ok';
    }
}
