<?php

use App\Filament\Resources\EmployeeDocuments\Pages\CreateEmployeeDocument;
use App\Filament\Resources\EmployeeDocuments\Pages\EditEmployeeDocument;
use App\Filament\Resources\EmployeeDocuments\Pages\ListEmployeeDocuments;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeEmployeeDocumentsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createEmployeeDocumentsEmployee(): Employee
{
    return Employee::query()->create(['first_name' => 'Doc', 'last_name' => 'Employee', 'status' => 'active']);
}

it('redirects guests away from employee documents list', function (): void { get('/admin/employee-documents')->assertRedirect('/admin/login'); });
it('redirects guests away from create employee document page', function (): void { get('/admin/employee-documents/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating an employee document', function (): void {
    actingAs(makeEmployeeDocumentsAdminUser());
    Livewire::test(CreateEmployeeDocument::class)
        ->fillForm(['employee_id' => null, 'document_type' => null, 'document_path' => null, 'is_verified' => null])
        ->call('create')
        ->assertHasFormErrors(['employee_id' => 'required', 'document_type' => 'required', 'document_path' => 'required', 'is_verified' => 'required']);
});

it('performs employee document crud operations', function (): void {
    actingAs(makeEmployeeDocumentsAdminUser());
    Storage::fake('public');
    $employee = createEmployeeDocumentsEmployee();
    $file = UploadedFile::fake()->create('id-proof.pdf', 64, 'application/pdf');

    Livewire::test(CreateEmployeeDocument::class)
        ->fillForm(['employee_id' => $employee->getKey(), 'document_type' => 'Identity Proof', 'document_path' => $file, 'is_verified' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = EmployeeDocument::query()->firstOrFail();
    Livewire::test(ListEmployeeDocuments::class)->assertSuccessful()->assertSee('Identity Proof');

    Livewire::test(EditEmployeeDocument::class, ['record' => $record->getKey()])
        ->fillForm(['employee_id' => $employee->getKey(), 'document_type' => 'Address Proof', 'is_verified' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->document_type)->toBe('Address Proof');

    Livewire::test(EditEmployeeDocument::class, ['record' => $record->getKey()])->callAction('delete');
    expect(EmployeeDocument::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
