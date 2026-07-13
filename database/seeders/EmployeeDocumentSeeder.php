<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use Faker\Factory as Faker;
use App\Models\EmployeeDocument;

class EmployeeDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        $documentTypes = [
            'Aadhar Card',
            'PAN Card',
            'Voter ID',
            'Passport',
            'Resume',
            'Offer Letter',
            'Degree Certificate',
            'Experience Letter',
            'Bank Passbook'
        ];

        $employees = Employee::all();

        if ($employees->isEmpty()) {
            $this->command->info('No employees found. Please seed employees first.');
            return;
        }

        foreach ($employees as $employee) {
            // Give each employee 2 to 4 random documents
            $numDocs = rand(2, 4);
            $selectedTypes = $faker->randomElements($documentTypes, $numDocs);

            foreach ($selectedTypes as $type) {
                // Create a realistic path
                $fileName = strtolower(str_replace(' ', '_', $type)) . '_' . $employee->id . '_' . time() . '.pdf';
                $path = 'employee_documents/' . $employee->id . '/' . $fileName;

                EmployeeDocument::create([
                    'employee_id'   => $employee->id,
                    'document_type' => $type,
                    'document_path' => $path,
                    'is_verified'   => $faker->boolean(80), // 80% chance of being verified
                ]);
            }
        }
    }
}
