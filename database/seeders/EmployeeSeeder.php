<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\Commission;
use App\Models\Attendance;
use Faker\Factory as Faker;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 100; $i++) {
            $employee = Employee::create([
                'name' => $faker->name,
                'basic_salary' => $faker->randomFloat(2, 30000, 100000),
            ]);

            $numAllowances = rand(0, 3);
            for ($j = 0; $j < $numAllowances; $j++) {
                Allowance::create([
                    'employee_id' => $employee->id,
                    'name' => $faker->word,
                    'type' => $faker->randomElement(['fixed', 'percentage']),
                    'amount' => $faker->randomFloat(2, 1000, 5000),
                ]);
            }

            $numDeductions = rand(0, 2);
            for ($j = 0; $j < $numDeductions; $j++) {
                Deduction::create([
                    'employee_id' => $employee->id,
                    'name' => $faker->word,
                    'type' => $faker->randomElement(['fixed', 'percentage']),
                    'amount' => $faker->randomFloat(2, 1000, 3000),
                ]);
            }

            $numCommissions = rand(0, 1);
            for ($j = 0; $j < $numCommissions; $j++) {
                Commission::create([
                    'employee_id' => $employee->id,
                    'amount' => $faker->randomFloat(2, 1000, 5000),
                    'date' => $faker->date(),
                ]);
            }

            for ($j = 0; $j < 30; $j++) {
                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $faker->dateTimeBetween('-1 month', 'now'),
                    'status' => $faker->randomElement(['present', 'leave']),
                ]);
            }
        }
    }
}
