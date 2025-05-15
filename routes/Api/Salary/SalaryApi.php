<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Salary\SalaryReportController;

Route::controller([SalaryReportController::class])->prefix('salray')->group(function (){
    Route::get('/report', [SalaryReportController::class, 'index']);
});
