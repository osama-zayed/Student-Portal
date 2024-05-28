<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Cache\RateLimiter;
use App\Http\Requests\UserRequest\login as userRequest;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return "username";
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password', 'member');
    }

    public function login(userRequest $request)
    {
        $username = $request->input($this->username());

        $rateLimiter = app(RateLimiter::class);
        $rateLimiter->hit($username);

        if ($rateLimiter->tooManyAttempts($username, 5)) {
            toastr()->warning("لقد قمت بمحاولة تسجيل الدخول عددًا كبيرًا من المرات. يرجى الانتظار لمدة دقيقة قبل المحاولة مرة أخرى.");
            return back()->withInput($request->only($this->username()));
        }

        $credentials = $request->validated();

        if (Auth::attempt($credentials,true)) {
            $rateLimiter->clear($username);
            $request->session()->regenerate();
            return redirect()->intended($this->redirectTo);
        }

        return back()->withErrors([
            $this->username() => trans('البريد الاكتروني أو كلمة المرور غير صحيحة.'),
        ])->withInput($request->only($this->username()));
    }
}
