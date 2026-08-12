<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceBranchTenantInput
{
    public function handle(Request $request, Closure $next): Response
    {
        $panel = Filament::getCurrentPanel();

        if ($panel?->getId() !== 'branch') {
            return $next($request);
        }

        if (! $request->isMethod('post') && ! $request->isMethod('put') && ! $request->isMethod('patch')) {
            return $next($request);
        }

        $tenantId = Filament::getTenant()?->getKey();

        if (! $tenantId && auth()->check()) {
            $tenantId = auth()->user()?->branches()->value('branches.id');
        }

        if (! $tenantId) {
            return $next($request);
        }

        $request->replace($this->forceBranchInPayload($request->all(), $tenantId));

        return $next($request);
    }

    protected function forceBranchInPayload(array $payload, int|string $tenantId): array
    {
        if (array_key_exists('branch_id', $payload)) {
            $payload['branch_id'] = $tenantId;
        }

        if (array_key_exists('from_branch_id', $payload)) {
            $payload['from_branch_id'] = $tenantId;
        }

        foreach ($payload as $key => $value) {
            if (is_array($value)) {
                $payload[$key] = $this->forceBranchInPayload($value, $tenantId);
            }
        }

        return $payload;
    }
}
