<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;
    public function __construct()
    {
        $this->middleware('auth'); //يجب ان يكون المستخدم مسجل الدخول
        $this->middleware('CheckDateMiddleware');
        $this->middleware('userStatus'); //يجب ان تكون حالة المستخدم نشطة
        $this->middleware('admin');
    }
}
