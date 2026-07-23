<?php

use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

it('redirects guests away from warehouse zones list', function (): void {
    get('/admin/warehouse-zones')->assertRedirect('/admin/login');
});

it('redirects guests away from create warehouse zone page', function (): void {
    get('/admin/warehouse-zones/create')->assertRedirect('/admin/login');
});
