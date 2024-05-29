@extends('layouts.master')
@section('css')
    <div style="display: none">@toastr_css</div>
@endsection
@section('page-header')
    المدرسين
@endsection
@section('sub-page-header')
    اضافة بيانات مدرس جديد
@endsection
@section('title')
    المدرسين
@endsection
@section('PageTitle')
    المدرسين
@endsection
<!-- breadcrumb -->
@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <div class="col-xs-12">
                        <br>
                        <form action="{{ route('teacher.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">

                                <div class="col-lg-4 col-md-6 mb-10">
                                    <label for="name">الاسم
                                        <span class="text-danger">*
                                            @error('name')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                        required>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-10">
                                    <label for="phone_number">رقم الهاتف
                                        <span class="text-danger">*
                                            @error('phone_number')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <input type="number" name="phone_number" class="form-control"
                                        pattern="[0-9]+(\.[0-9]+)?" title="يرجى إدخال أرقام فقط"
                                        value="{{ old('phone_number') }}" required>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-10">
                                    <label for="gender">الجنس
                                        <span class="text-danger">*
                                            @error('gender')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <select class="form-control h-65" name="gender" aria-placeholder="الجنس مطلوب"
                                        required>
                                        <option value="" disabled selected>اختر الجنس</option>
                                        <option value="ذكر" @if (old('gender') == 'ذكر') selected @endif>ذكر
                                        </option>
                                        <option value="انثى" @if (old('gender') == 'انثى') selected @endif>انثى
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-10">
                                    <label for="qualification">المؤهل الدراسي
                                        <span class="text-danger">*
                                            @error('qualification')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <select class="form-control h-65" name="qualification" aria-placeholder="المؤهل مطلوب"
                                        required>
                                        <option value="" disabled selected>اختر المؤهل</option>
                                        <option value="دكتوراة" @if (old('qualification') == 'دكتوراة') selected @endif>دكتوراه
                                        <option value="ماجستير" @if (old('qualification') == 'ماجستير') selected @endif>ماجستير
                                        <option value="بكالريوس" @if (old('qualification') == 'بكالريوس') selected @endif>بكالريوس
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-10">
                                    <label for="address">الموقع
                                        <span class="text-danger">*
                                            @error('address')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <input type="text" name="address" class="form-control"  value="{{ old('address') }}" required>
                                </div>
                            </div>
                            <br>
                            <button class="btn btn-primary btn-sm nextBtn btn-lg pull-right" type="submit">حفظ
                                البيانات</button>
                        </form>


                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->
@endsection
@section('js')
    @toastr_js
    @toastr_render
@endsection
