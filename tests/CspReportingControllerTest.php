<?php

namespace Bretto36\CspReporting\Tests;

use Bretto36\CspReporting\Event\CspViolationReportReceived;
use Bretto36\CspReporting\Exception\CspViolationReportException;
use Bretto36\CspReporting\Tests\Listener\CspViolationReportReceivedListener;
use Illuminate\Support\Facades\Event;

class CspReportingControllerTest extends TestCase
{
    public function test_csp_report_is_reported()
    {
        $this->expectException(CspViolationReportException::class);

        $this->withoutExceptionHandling()->postJson(route('csp-reporting.report'), json_decode('{
          "age": 53531,
          "body": {
            "blockedURL": "inline",
            "columnNumber": 39,
            "disposition": "enforce",
            "documentURL": "https://example.com/csp-report",
            "effectiveDirective": "script-src-elem",
            "lineNumber": 121,
            "originalPolicy": "default-src \'self\'; report-to csp-endpoint-name",
            "referrer": "https://www.google.com/",
            "sample": "console.log(\"lo\")",
            "sourceFile": "https://example.com/csp-report",
            "statusCode": 200
          },
          "type": "csp-violation",
          "url": "https://example.com/csp-report",
          "user_agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36"
        }', true));

        Event::assertDispatched(CspViolationReportReceived::class);
    }

    public function test_csp_report_is_skipped()
    {
        Event::listen(CspViolationReportReceived::class, CspViolationReportReceivedListener::class);

        $this->postJson(route('csp-reporting.report'), json_decode('{
          "age": 53531,
          "body": {
            "blockedURL": "inline",
            "columnNumber": 39,
            "disposition": "enforce",
            "documentURL": "https://example.com/csp-report",
            "effectiveDirective": "script-src-elem",
            "lineNumber": 121,
            "originalPolicy": "default-src \'self\'; report-to csp-endpoint-name",
            "referrer": "https://www.google.com/",
            "sample": "console.log(\"lo\")",
            "sourceFile": "https://example.com/csp-report",
            "statusCode": 200
          },
          "type": "csp-violation",
          "url": "https://example.com/csp-report",
          "user_agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36"
        }', true))
            ->assertOk();
    }
}
