<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('auth:api'); //يجب ان يكون المستخدم مسجل الدخول
        $this->middleware('CheckDateMiddleware');
        $this->middleware('userStatus'); //يجب ان تكون حالة المستخدم نشطة
        $this->middleware('admin');
    }

}
