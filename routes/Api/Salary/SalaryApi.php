<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Salary\SalaryReportController;

Route::get('salary-report', [SalaryReportController::class, 'index']);
