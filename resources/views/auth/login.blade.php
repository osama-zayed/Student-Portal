<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>تسجيل الدخول</title>
    @include('layouts.head')

</head>

<body>

    <div class="container">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <div class="container">
                    <div class="card shadow-lg border-0 rounded-lg mt-5">
                        <div class="card-body row justify-content-center">
                            <div class="col-lg-5 mb-20">
                                <h3 class="text-center font-weight-light my-4"> <img class="col-lg-5"
                                        src="{{ asset('assets/images/favicon.ico') }}" alt=""
                                        style="width: 70px" srcset="">
                                    برنامج ادارة مراكز الشرطة امانة
                                    العاصمة صنعاء</h3>
                                <h4 class="text-premary" style="color: #0361e7">مرحباً بك</h4>
                                <h6>تسجيل الدخول</h6>
                                <form method="POST" action="{{ route('login') }}" >
                                    @csrf
                                    <div class="form-floating mb-3">
                                        <label for="email">البريد الاكتروني</label>
                                        <input id="email" type="email" placeholder="البريد الالكتروني"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        @error('email')
                                            <span class="invalid-feedback " role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-floating mb-3">

                                        <label for="password">كلمة السر
                                        </label>
                                        <input id="password" type="password" placeholder="كلمة السر"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            required autocomplete="current-password">

                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">

                                        <button type="submit" class="btn w-100"
                                            style="background-color: #0361e7 ;color:white">تسجيل
                                            الدخول</button>
                                    </div>
                                </form>
                            </div>
                            <img class="col-lg-5" src="{{ asset('assets/images/favicon.ico') }}" alt=""
                                srcset="">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer-scripts')

</body>

</html>
