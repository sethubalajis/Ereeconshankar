<?php

use App\Models\User;
use Filament\Auth\Pages\Login;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use function Pest\Laravel\get;

beforeEach(function (): void {
    Schema::dropIfExists('users');

    Schema::create('users', function (Blueprint $table): void {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->rememberToken();
        $table->timestamps();
    });
});

it('loads the admin login page', function (): void {
    get('/admin/login')->assertOk();
});

it('allows a valid user to log in from admin login', function (): void {
    $password = 'ValidPass123!';

    $user = User::factory()->create([
        'password' => $password,
    ]);

    Livewire::test(Login::class)
        ->fillForm([
            'email' => $user->email,
            'password' => $password,
            'remember' => false,
        ])
        ->call('authenticate')
        ->assertHasNoFormErrors()
        ->assertRedirect('/admin');

    expect(auth()->check())->toBeTrue();
    expect(auth()->id())->toBe($user->getKey());
});

it('rejects invalid credentials from admin login', function (): void {
    $user = User::factory()->create([
        'password' => 'ValidPass123!',
    ]);

    Livewire::test(Login::class)
        ->fillForm([
            'email' => $user->email,
            'password' => 'WrongPassword123!',
            'remember' => false,
        ])
        ->call('authenticate')
        ->assertHasFormErrors(['email']);

    expect(auth()->check())->toBeFalse();
});
