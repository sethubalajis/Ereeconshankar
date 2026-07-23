<?php

use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

it('redirects guests away from warehouse transfers list', function (): void {
    get('/admin/warehouse-transfers')->assertRedirect('/admin/login');
});

it('redirects guests away from create warehouse transfer page', function (): void {
    get('/admin/warehouse-transfers/create')->assertRedirect('/admin/login');
});
