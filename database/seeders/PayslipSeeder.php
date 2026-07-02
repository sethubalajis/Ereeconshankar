<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payslip;
use App\Models\PayrollProcessing;
use Illuminate\Support\Facades\DB;

class PayslipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Payslip::truncate();

        $processings = PayrollProcessing::all();

        if ($processings->isEmpty()) {
            $this->command->warn('No payroll processings found. Please run PayrollProcessingSeeder first.');
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return;
        }

        foreach ($processings as $index => $processing) {
            $number = 'PS-' . date('Ym') . '-' . str_pad($processing->id, 4, '0', STR_PAD_LEFT);
            
            Payslip::create([
                'payroll_processing_id' => $processing->id,
                'payslip_number'        => $number,
                'document_path'         => 'payslips/' . $number . '.pdf',
            ]);
        }

        $this->command->info('PayslipSeeder completed successfully. Seeded ' . Payslip::count() . ' records.');
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
