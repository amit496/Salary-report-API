<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    protected $fillable = ['employee_id', 'name', 'type', 'amount'];

    /**
     * Define the relationship with the employee.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
