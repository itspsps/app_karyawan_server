<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Model\ActivityLog;

class LogActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);

        if ($request->user() && $request->method() != 'GET') {
            // $activity = new ActivityLog();
            // $activity->user_id = $request->user()->id;
            // $activity->activity = $request->method();
            // $activity->description = $request->fullUrl();
            // $activity->save();

            ActivityLog::create([                
                'user_id' => $request->user()->id,
                'activity' => $request->method(),
                'description' => $request->fullUrl(),
            ]);
        }

        return $response;
    }
}
