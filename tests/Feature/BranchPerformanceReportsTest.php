<?php

use App\Filament\Resources\BranchPerformanceReports\Pages\CreateBranchPerformanceReport;
use App\Filament\Resources\BranchPerformanceReports\Pages\EditBranchPerformanceReport;
use App\Filament\Resources\BranchPerformanceReports\Pages\ListBranchPerformanceReports;
use App\Models\Branch;
use App\Models\BranchPerformanceReport;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeBranchPerformanceReportsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createBranchPerformanceReportBranch(): Branch
{
    $company = Company::query()->create(['name' => 'BPR Co', 'code' => 'BPRCO1', 'status' => true]);
    return Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'BPR Branch', 'code' => 'BPRBR1', 'status' => true]);
}

it('redirects guests away from branch performance reports list', function (): void { get('/admin/branch-performance-reports')->assertRedirect('/admin/login'); });
it('redirects guests away from create branch performance report page', function (): void { get('/admin/branch-performance-reports/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a branch performance report', function (): void {
    actingAs(makeBranchPerformanceReportsAdminUser());
    Livewire::test(CreateBranchPerformanceReport::class)
        ->fillForm(['branch_id' => null, 'report_month' => null, 'score' => null])
        ->call('create')
        ->assertHasFormErrors(['branch_id' => 'required', 'report_month' => 'required', 'score' => 'required']);
});

it('performs branch performance report crud operations', function (): void {
    actingAs(makeBranchPerformanceReportsAdminUser());
    $branch = createBranchPerformanceReportBranch();

    Livewire::test(CreateBranchPerformanceReport::class)
        ->fillForm(['branch_id' => $branch->getKey(), 'report_month' => '2026-07-01', 'score' => 85])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = BranchPerformanceReport::query()->firstOrFail();
    Livewire::test(ListBranchPerformanceReports::class)->assertSuccessful()->assertSee('85');

    Livewire::test(EditBranchPerformanceReport::class, ['record' => $record->getKey()])
        ->fillForm(['branch_id' => $branch->getKey(), 'report_month' => '2026-08-01', 'score' => 90])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->score)->toBe(90.0);

    Livewire::test(EditBranchPerformanceReport::class, ['record' => $record->getKey()])->callAction('delete');
    expect(BranchPerformanceReport::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
