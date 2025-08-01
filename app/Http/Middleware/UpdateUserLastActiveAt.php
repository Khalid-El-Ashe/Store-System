<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

// هذا الملف هو جزء من مشروع Laravel ويحتوي على Middleware لتحديث وقت آخر نشاط للمستخدم
// Middleware يقوم بتحديث حقل 'last_active_at' في جدول المستخدمين عند كل طلب
class UpdateUserLastActiveAt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user instanceof User) {
            // Update the last active timestamp
            $user->forceFill(['last_active_at' => Carbon::now()])->save();
        }
        return $next($request);
    }
}
