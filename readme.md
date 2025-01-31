# CSP Reporting Engine

This project is a Content Security Policy (CSP) Reporting engine built to work with Laravel. It receives CSP Violation reports from a report-uri and logs them using Laravel Exceptions.

## Installation

1. Clone the repository:
    ```sh
    composer require bretto36/csp-reporting
    ```

2. Publish the configuration file:
    ```sh
    php artisan vendor:publish --provider="Bretto36\CspReporting\ServiceProvider"
    ```

3. Configure the package
   Add the following environment variables to your `.env` file:
    ```env
    CSP_REPORTING_ENABLED=true
    ```

   The default route for the CSP Reporting engine is `/csp-reporting/csp-report`.

   To adjust the route suffix `csp-report` you can add an environment variable:
    ```env
    CSP_REPORTING_URI=/csp-report
    ```
   
   If you'd like to customise the route prefix or middleware you can do so in the configuration file.
    ```php
   'route' => [
       'prefix'     => 'csp-reporting', // Alter this to a different prefix
       'middleware' => ['web'], // Change the middleware you want to use
   ],
   ```
4. If using Spatie's Laravel CSP package, you can add the following to the `report-uri` directive in your CSP header:
    ```php
    'report-uri' => route('csp-reporting.csp-report')
    ```

5. To silence some CSP Reports you can add a Laravel Event Listener to listen to the CspViolationReportReceived Event
    ```php
    use Bretto36\CspReporting\Events\CspViolationReportReceived;
    use Illuminate\Support\Facades\Event;

    Event::listen(CspViolationReportReceived::class, function (CspViolationReportReceived $event) {
        if ($event->violationReport->data->blocked_uri === 'https://example.com') {
            $event->shouldReport = false;
        }
    });
    ```
   or for Laravel 11, with auto event discovery simply create a listener
    ```sh
    php artisan make:listener CspViolationReportReceivedListener
    ```
         
    ```php
    <?php

    namespace App\Listeners;
        
    use Bretto36\CspReporting\Event\CspViolationReportReceived;
        
    class CspViolationReportReceivedListener
    {
        /**
         * Handle the event.
         */
        public function handle(CspViolationReportReceived $event): void
        {
            // Apply logic here
            $event->shouldReport = false;
        }
    }
    ```

## Configuration

The configuration file is located at `config/csp-reporting.php`. You can customize the route prefix and middleware in this file.

```php
return [
    'enabled' => env('CSP_REPORTING_ENABLED', false),
    'uri' => env('CSP_REPORTING_URI', '/csp-report'),
    'route' => [
        'prefix'     => 'csp-reporting',
        'middleware' => ['web'],
    ],
];
```

## Usage
To send a CSP violation report, make a POST request to the configured URI (default is /csp-reporting/report). The report should be in JSON format.  Example:

```json
{
    "age": 53531,
    "body": {
        "blockedURL": "inline",
        "columnNumber": 39,
        "disposition": "enforce",
        "documentURL": "https://example.com/csp-report",
        "effectiveDirective": "script-src-elem",
        "lineNumber": 121,
        "originalPolicy": "default-src 'self'; report-to csp-endpoint-name",
        "referrer": "https://www.google.com/",
        "sample": "console.log(\"lo\")",
        "sourceFile": "https://example.com/csp-report",
        "statusCode": 200
    },
    "type": "csp-violation",
    "url": "https://example.com/csp-report",
    "user_agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36"
}
```
