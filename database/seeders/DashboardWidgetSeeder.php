<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DashboardWidgetSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('dashboard_widgets')->truncate();
        
        $widgets = [
            ['name' => 'Monthly Sales Overview', 'type' => 'chart', 'config' => json_encode(['chartType' => 'bar', 'metric' => 'sales']), 'status' => true],
            ['name' => 'Top Performing Products', 'type' => 'table', 'config' => json_encode(['limit' => 5, 'sort' => 'desc']), 'status' => true],
            ['name' => 'Pending Services', 'type' => 'stat', 'config' => json_encode(['metric' => 'open_tickets']), 'status' => true],
        ];
        
        foreach($widgets as $w) {
            DB::table('dashboard_widgets')->insert($w);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
