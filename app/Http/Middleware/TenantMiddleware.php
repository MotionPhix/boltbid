<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $host = $request->getHost();

    $tenant = Tenant::where('domain', $host)->firstOrFail();

    // Set the database connection for this tenant
    config(['database.connections.tenant.database' => $tenant->database]);
    DB::purge('tenant');

    // Make tenant available globally
    app()->instance('tenant', $tenant);

    return $next($request);
  }
}
