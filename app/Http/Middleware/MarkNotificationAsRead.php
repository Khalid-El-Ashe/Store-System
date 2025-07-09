<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MarkNotificationAsRead
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // i need to check if i have to the request parameter id
        $notification_id = $request->query('notification_id');
        if($notification_id) {
            $user = auth()->user();
            if($user) {
               $nofication = $user->unreadNotifications()->find($notification_id);
                if ($nofication) {
                    $nofication->markAsRead(true); // هذا يعني ان الاشعار مقروء
                }
            }
        }
        return $next($request);
    }
}
