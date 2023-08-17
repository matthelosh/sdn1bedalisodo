<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;

class isLegal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('token');
    // return response()->json(['status' => $token]);
        $key = Hash::make('mandita');
        if(!Hash::check($token, $key)) {
            return response()->json(['status' => 'Unauthorized'], 403);
        }
        return $next($request);
    }
}
