<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employees';

    protected $fillable = ['name', 'basic_salary'];

    /**
     * Define relationship with allowances.
     */
    public function allowances()
    {
        return $this->hasMany(Allowance::class);
    }

    /**
     * Define relationship with deductions.
     */
    public function deductions()
    {
        return $this->hasMany(Deduction::class);
    }

    /**
     * Define relationship with commissions.
     */
    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    /**
     * Define relationship with attendance.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
