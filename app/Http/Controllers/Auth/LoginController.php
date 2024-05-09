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

    public function email()
    {
        return "email";
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->email(), 'password', 'member');
    }

    public function login(userRequest $request)
    {
        $email = $request->input($this->email());

        $rateLimiter = app(RateLimiter::class);
        $rateLimiter->hit($email);

        if ($rateLimiter->tooManyAttempts($email, 5)) {
            toastr()->warning("لقد قمت بمحاولة تسجيل الدخول عددًا كبيرًا من المرات. يرجى الانتظار لمدة دقيقة قبل المحاولة مرة أخرى.");
            return back()->withInput($request->only($this->email()));
        }

        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $rateLimiter->clear($email);
            $request->session()->regenerate();
            return redirect()->intended($this->redirectTo);
        }

        return back()->withErrors([
            $this->email() => trans('البريد الاكتروني أو كلمة المرور غير صحيحة.'),
        ])->withInput($request->only($this->email()));
    }
}
