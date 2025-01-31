<?php

use Bretto36\CspReporting\Http\Controllers\CspReportingController;
use Illuminate\Support\Facades\Route;

Route::group(config('csp-reporting.route', []), function () {
    Route::post('report', CspReportingController::class)->name('csp-reporting.report');
});
