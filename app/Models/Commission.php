<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $fillable = ['employee_id', 'amount', 'date'];

    /**
     * Define the relationship with the employee.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
