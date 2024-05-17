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
                            
                            <form action="{{ route('Course.update', 'test') }}" method="post" enctype="multipart/form-Course">
                                @method('PUT')
                                @csrf
                                <div class="form-row">
                                    <div class="col-md-6 mt-3">
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
                                    <div class="col-md-6 mt-3">
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
                                    <input id="specialization_id" type="hidden" name="specialization_id"
                                        class="form-control" value="{{ $Course->specialization_id }}"
                                        placeholder="أدخل رقم التخصص" required="الحقل مطلوب">
                                    <div class="col-md-6 mt-3 ">
                                        <label for="semester_num">الترم الدراسي
                                            <span class="text-danger">*
                                                @error('semester_num')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </label>
                                        <input id="semester_num" type="number" name="semester_num" class="form-control"
                                            min="1" max="14"
                                            value="{{ old('semester_num') ?? $Course->semester_num }}"
                                            placeholder="أدخل رقم الترم الدراسي" required="الحقل مطلوب">
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label for="teachers_id">مدرس المقرر
                                            <span class="text-danger">*
                                                @error('teachers_id')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </label>
                                        <select class="form-control h-65" name="teachers_id" aria-placeholder="اختر مدرس">
                                            <option value="" disabled selected>اختر مدرس المقرر من القائمة</option>
                                            @forelse (\App\Models\Teacher::get() as $data)
                                                <option value="{{ $data['id'] }}"
                                                    @if ($data->id == old('teachers_id') ?? $Course->teachers_id) selected @endif>
                                                    {{ $data['name'] }}</option>
                                            @empty
                                                <option value="">لا يوجد مدرسين</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                

                                <button class="btn btn-primary mt-3 btn-sm nextBtn btn-lg pull-right" type="submit"
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
