@extends('layouts.master')
@section('css')
    <div style="display: none">
        @toastr_css</div>
@endsection

@section('title')
    تعديل تخصص {{ $Specializations->name }}
@endsection
@section('page-header')
    <!-- breadcrumb -->
@section('PageTitle')
    تعديل تخصص {{ $Specializations->name }}
@endsection
@section('page-header')
    التخصصات
@endsection
@section('sub-page-header')
    تعديل تخصص {{ $Specializations->name }}

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
                            <form action="{{ route('Specialization.update', 'test') }}" method="post"
                                enctype="multipart/form-Specializations">
                                @method('PUT')
                                @csrf
                                <input id="id" type="number" hidden readonly name="id" class="form-control"
                                value="{{ $Specializations->id }}"
                                required="الحقل مطلوب">
                                <div class="form-row">
                                    <div class="col-12">
                                        <label for="name">اسم التخصص
                                            <span class="text-danger">*
                                                @error('name')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </label>
                                        <input id="name" type="text" name="name" class="form-control"
                                            value="{{ old('name') ?? ($Specializations->name ?? '') }}"
                                            placeholder="أدخل اسم التخصص" required="الحقل مطلوب">
                                    </div>
                                    <div class="col-12 mt-10">
                                        <label for="college_id">الكلية
                                            <span class="text-danger">*
                                                @error('college_id')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </label>
                                        <select class="form-control h-65" name="college_id" aria-placeholder="اختر كلية"
                                            required>
                                            <option value="" disabled selected>اختر كلية من القائمة</option>
                                            @forelse (\App\Models\College::get() as $data)
                                                <option value="{{ $data['id'] }}"
                                                    @if ($data->id == old('college_id') || $data->id == $Specializations->college_id) selected @endif>
                                                    {{ $data['name'] }}</option>
                                            @empty
                                                <option value="">لا يوجد كليات</option>
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col-12 mt-10">
                                        <label for="educational_qualification">المؤهل المطلوب
                                            <span class="text-danger">*
                                                @error('educational_qualification')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </label>
                                        <select class="form-control h-65" name="educational_qualification"
                                            aria-placeholder="المؤهل مطلوب" required>
                                            <option value="" disabled selected>اختر المؤهل</option>
                                            <option value="ثانوية عامة علمي"
                                                @if (old('educational_qualification') == 'ثانوية عامة علمي' ||
                                                        'ثانوية عامة علمي' == $Specializations->educational_qualification) selected @endif>
                                                ثانوية عامة علمي
                                            <option value="ثانوية عامة ادبي"
                                                @if (old('educational_qualification') == 'ثانوية عامة ادبي' ||
                                                        'ثانوية عامة ادبي' == $Specializations->educational_qualification) selected @endif>
                                                ثانوية عامة ادبي
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-12 mt-10">
                                        <label for="lowest_acceptance_rate">اقل معدل للقبول
                                            <span class="text-danger">*
                                                @error('lowest_acceptance_rate')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </label>
                                        <input id="lowest_acceptance_rate" type="number" name="lowest_acceptance_rate"
                                            class="form-control" min="50"
                                            value="{{ old('lowest_acceptance_rate') ?? ($Specializations->lowest_acceptance_rate ?? '') }}"
                                            placeholder="اقل معدل للقبول" required="الحقل مطلوب">
                                    </div>
                                    <div class="col-12 mt-10">
                                        <label for="Price">السعر
                                            <span class="text-danger">*
                                                @error('Price')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </label>
                                        <input id="Price" type="number" name="Price" class="form-control"
                                            min="1" value="{{ old('Price') ?? ($Specializations->Price ?? '') }}"
                                            placeholder="السعر" required="الحقل مطلوب">
                                    </div>
                                    <div class="col-12 mt-10">
                                        <label for="Number_of_years_of_study">عدد السنين الدراسية
                                            <span class="text-danger">*
                                                @error('Number_of_years_of_study')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </label>
                                        <input id="Number_of_years_of_study" type="number" name="Number_of_years_of_study"
                                            class="form-control" min="1"
                                            value="{{ old('Number_of_years_of_study') ?? ($Specializations->Number_of_years_of_study ?? '') }}"
                                            placeholder="أدخل عدد السنين الدراسية" required="الحقل مطلوب">
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
