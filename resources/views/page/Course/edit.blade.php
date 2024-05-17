@extends('layouts.master')
@section('css')
    <div style="display: none">
        @toastr_css</div>
@endsection

@section('title')
    تعديل مقرر {{ $Course->name }}
@endsection
@section('page-header')
    <!-- breadcrumb -->
@section('PageTitle')
    المقررات الدراسية
@endsection

@section('page-header')
المقررات الدراسية
@endsection
@section('sub-page-header')
    تعديل مقرر {{ $Course->name }}
@endsection

<!-- breadcrumb -->
@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <div class="col-xs-12">
                        <div class="col-md-12">
                            <br>
                            <form action="{{ route('Course.update', 'test') }}" method="post" enctype="multipart/form-Course">
                                @method('PUT')
                                @csrf
                                <div class="form-row">
                                    <div class="col">
                                        <label for="name">اسم المقررات الدراسية :
                                            <span class="text-danger">* @error('name')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </label>
                                        <input type="text" name="name" value="{{ old('name') ?? $Course->name }}"
                                            class="form-control">

                                        <input type="hidden" name="id" value="{{ $Course->id }}"
                                            class="form-control">
                                    </div>
                                    <div class="col">
                                        <label for="hours">عدد الساعات
                                            <span class="text-danger">*
                                                @error('hours')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </label>
                                        <input id="hours" type="number" name="hours" class="form-control"
                                            min="1" max="4" value="{{ old('hours') ?? $Course->hours }}"
                                            placeholder="أدخل عدد الساعات" required="الحقل مطلوب">
                                    </div>
                                </div>
                                <br>

                                <button class="btn btn-primary btn-sm nextBtn btn-lg pull-right" type="submit"
                                    title="تعديل"> تعديل
                                    البيانات</button>
                            </form>
                        </div>
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
