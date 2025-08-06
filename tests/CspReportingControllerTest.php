<?php

namespace Bretto36\CspReporting\Tests;

use Bretto36\CspReporting\Event\CspViolationReportReceived;
use Bretto36\CspReporting\Exception\CspViolationReportException;
use Bretto36\CspReporting\Tests\Listener\CspViolationReportReceivedListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Exceptions;

class CspReportingControllerTest extends TestCase
{
    public function test_csp_report_is_reported()
    {
        Exceptions::fake();

        Event::fake();

        $this->withoutExceptionHandling()->postJson(route('csp-reporting.report'), json_decode('{
            "csp-report": {
                "document-uri": "https://yoursite.com/vulnerablepage",
                "referrer": "",
                "violated-directive": "script-src-attr",
                "effective-directive": "script-src-attr",
                "original-policy": "default-src \'self\'; script-src \'report-sample\' \'self\' https://www.google-analytics.com/analytics.js https://www.googletagmanager.com/gtag/js; style-src \'report-sample\' \'self\'; report-uri https://yoursite.com/csp-reporting/report;",
                "disposition": "enforced",
                "blocked-uri": "inline",
                "line-number": 1,
                "source-file": "https://yoursite.com/vulnerablepage",
                "status-code": 0,
                "script-sample": "alert(1)"
            }
        }', true), ['Content-Type' => 'application/csp-report']);

        Event::assertDispatched(CspViolationReportReceived::class);

        // This only works for Laravel 11
        Exceptions::assertReported(CspViolationReportException::class);
    }

    public function test_csp_report_is_skipped()
    {
        Event::listen(CspViolationReportReceived::class, CspViolationReportReceivedListener::class);

        $this->postJson(route('csp-reporting.report'), json_decode('{
            "csp-report": {
                "document-uri": "https://yoursite.com/vulnerablepage",
                "referrer": "",
                "violated-directive": "script-src-attr",
                "effective-directive": "script-src-attr",
                "original-policy": "default-src \'self\'; script-src \'report-sample\' \'self\' https://www.google-analytics.com/analytics.js https://www.googletagmanager.com/gtag/js; style-src \'report-sample\' \'self\'; report-uri https://yoursite.com/csp-reporting/report;",
                "disposition": "enforced",
                "blocked-uri": "inline",
                "line-number": 1,
                "source-file": "https://yoursite.com/vulnerablepage",
                "status-code": 0,
                "script-sample": "alert(1)"
            }
        }', true), ['Content-Type' => 'application/csp-report'])
            ->assertOk();
    }

    public function test_csp_report_is_not_skipped()
    {
        Event::listen(CspViolationReportReceived::class, CspViolationReportReceivedListener::class);

        $this->postJson(route('csp-reporting.report'), json_decode('{
            "csp-report": {
                "document-uri": "https://yoursite.com/vulnerablepage",
                "referrer": "",
                "violated-directive": "script-src-attr",
                "effective-directive": "script-src-attr",
                "original-policy": "default-src \'self\'; script-src \'report-sample\' \'self\' https://www.google-analytics.com/analytics.js https://www.googletagmanager.com/gtag/js; style-src \'report-sample\' \'self\'; report-uri https://yoursite.com/csp-reporting/report;",
                "disposition": "enforced",
                "blocked-uri": "inline",
                "line-number": 1,
                "source-file": "https://yoursite.com/vulnerablepage",
                "status-code": 0,
                "script-sample": "alert(\'hello\')"
            }
        }', true), ['Content-Type' => 'application/csp-report'])
            ->assertOk();
    }
}
