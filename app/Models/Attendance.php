<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['employee_id', 'date', 'status'];

    /**
     * Define the relationship with the employee.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
