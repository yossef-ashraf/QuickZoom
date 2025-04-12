<?php
namespace QuickZoom\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifyZoomWebhook
{
    public function handle(Request $request, Closure $next)
    {
        if (!config('quickzoom.webhook_secret')) {
            return $next($request);
        }

        $signature = $request->header('x-zm-signature');
        $timestamp = $request->header('x-zm-request-timestamp');
        $webhookToken = config('quickzoom.webhook_secret');
        
        if (!$signature || !$timestamp) {
            Log::warning('QuickZoom webhook verification failed: Missing headers', [
                'ip' => $request->ip(),
                'headers' => $request->headers->all()
            ]);
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        $message = 'v0:' . $timestamp . ':' . $request->getContent();
        $hashForVerify = 'v0=' . hash_hmac('sha256', $message, $webhookToken);
        
        if (!hash_equals($signature, $hashForVerify)) {
            Log::warning('QuickZoom webhook verification failed: Invalid signature', [
                'ip' => $request->ip(),
                'received' => $signature,
                'expected' => $hashForVerify
            ]);
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        return $next($request);
    }
}