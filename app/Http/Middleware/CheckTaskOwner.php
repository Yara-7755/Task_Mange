<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckTaskOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        $task = $request->route('task');

        if ($task->user_id !== Auth::id()) {
            abort(403, 'This task does not belong to you.');
        }

        return $next($request);
    }
}
