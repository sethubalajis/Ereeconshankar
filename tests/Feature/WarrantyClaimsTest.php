<?php

use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

it('redirects guests away from warranty claims list', function (): void {
    get('/admin/warranty-claims')->assertRedirect('/admin/login');
});

it('redirects guests away from create warranty claim page', function (): void {
    get('/admin/warranty-claims/create')->assertRedirect('/admin/login');
});
