<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyBillingApiKey
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $expectedKey = env('BILLING_API_KEY', 'EAI-SECRET-TOKEN-2026-X99');

        $providedKey = $request->header('X-API-KEY');
        if (!$providedKey) {
            $bearer = $request->header('Authorization');
            if ($bearer && str_starts_with($bearer, 'Bearer ')) {
                $providedKey = substr($bearer, 7);
            }
        }

        if (!$providedKey || !hash_equals($expectedKey, $providedKey)) {
            return response()->json([
                'status' => 'error',
                'code' => 401,
                'message' => 'Unauthorized EAI Access: Invalid or missing API Key.',
            ], 401);
        }

        return $next($request);
    }
}
