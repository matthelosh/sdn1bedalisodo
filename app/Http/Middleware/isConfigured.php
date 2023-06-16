<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Config;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isConfigured
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $siteConfig = Config::first();
        if ($siteConfig->status == 'preconfigured') {
            return redirect(route('site.install'));
        }
        return $next($request);
    }
}
