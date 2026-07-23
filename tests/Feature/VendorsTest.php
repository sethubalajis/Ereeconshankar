<?php

use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

it('redirects guests away from vendors list', function (): void {
    get('/admin/vendors')->assertRedirect('/admin/login');
});

it('redirects guests away from create vendor page', function (): void {
    get('/admin/vendors/create')->assertRedirect('/admin/login');
});
