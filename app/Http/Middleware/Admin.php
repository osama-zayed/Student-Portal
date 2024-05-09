<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    public function handle(Request $request, Closure $next): Response
    {
        $userType = auth()->user()->user_type;
        if ($userType != "admin") {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        return $next($request);
    }
}
