<?php

namespace App\Http\Controllers\Api\Salary;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalaryReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month');
        $perPage = $request->get('per_page', 20);

        $employees = Employee::with(['allowances', 'deductions', 'commissions', 'attendances'])
            ->paginate($perPage);

        $report = $employees->getCollection()->map(function ($employee) use ($month) {
            return $this->generateEmployeeReport($employee, $month);
        });

        return $this->generateResponse($employees, $report, $month);
    }

    private function generateEmployeeReport($employee, $month)
    {
        $basicSalary = $employee->basic_salary;

        $attendances = $this->filterAttendances($employee->attendances, $month);
        $commissions = $this->filterCommissions($employee->commissions, $month);

        $presentDays = $attendances->where('status', 'present')->count();
        $totalDays = $attendances->count();
        $attendanceRatio = $totalDays > 0 ? $presentDays / $totalDays : 1;

        $allowance = $this->calculateAllowance($employee->allowances, $basicSalary);
        $deduction = $this->calculateDeduction($employee->deductions, $basicSalary);
        $commission = $commissions->sum('amount');

        $grossSalary = $basicSalary + $allowance + $commission;
        $netSalary = ($grossSalary - $deduction) * $attendanceRatio;

        return [
            'employee' => $employee->name,
            'basic_salary' => $basicSalary,
            'allowances' => $allowance,
            'deductions' => $deduction,
            'commission' => $commission,
            'attendance' => "$presentDays/$totalDays",
            'net_salary' => round($netSalary, 2),
        ];
    }

    private function filterAttendances($attendances, $month)
    {
        if ($month) {
            if (!$this->isValidMonth($month)) {
                return collect();
            }

            $month = Carbon::createFromFormat('Y-m', $month . '-01')->format('Y-m');

            return $attendances->filter(function ($attendance) use ($month) {
                return Carbon::parse($attendance->date)->format('Y-m') == $month;
            });
        }

        return $attendances;
    }

    private function filterCommissions($commissions, $month)
    {
        if ($month) {
            if (!$this->isValidMonth($month)) {
                return collect();
            }

            $month = Carbon::createFromFormat('Y-m', $month . '-01')->format('Y-m');

            return $commissions->filter(function ($commission) use ($month) {
                return Carbon::parse($commission->date)->format('Y-m') == $month;
            });
        }

        return $commissions;
    }

    private function calculateAllowance($allowances, $basicSalary)
    {
        return $allowances->sum(function ($allowance) use ($basicSalary) {
            return $allowance->type === 'fixed'
                ? $allowance->amount
                : ($allowance->amount / 100) * $basicSalary;
        });
    }

    private function calculateDeduction($deductions, $basicSalary)
    {
        return $deductions->sum(function ($deduction) use ($basicSalary) {
            return $deduction->type === 'fixed'
                ? $deduction->amount
                : ($deduction->amount / 100) * $basicSalary;
        });
    }

    private function isValidMonth($month)
    {
        return is_numeric($month) && $month >= 1 && $month <= 12;
    }

    private function generateResponse($employees, $report, $month)
    {
        return response()->json([
            'total_records' => $employees->total(),
            'current_page' => $employees->currentPage(),
            'last_page' => $employees->lastPage(),
            'per_page' => $employees->perPage(),
            'total_pages' => $employees->lastPage(),
            'requested_month' => $month ? $month : 'All months',
            'data' => $report,
        ]);
    }
}
