<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Lang;

class ThrottleLoginAttempts
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next, $maxAttempts = 5, $decayMinutes = 1)
    {
        $key = $this->resolveRequestSignature($request);

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            $this->limiter->clear($key);
            throw ValidationException::withMessages([
                'throttle' => Lang::get('auth.throttle', ['minutes' => $decayMinutes]),
            ])->status(429);
        }

        $this->limiter->hit($key, $decayMinutes);

        return $next($request);
    }

    protected function resolveRequestSignature($request)
    {
        if ($user = $request->user()) {
            return sha1($user->getAuthIdentifier());
        }

        if ($route = $request->route()) {
            return sha1($route->getDomain().'|'.$request->ip());
        }

        return sha1($request->ip());
    }
}
