@extends('layouts.master')
@section('css')
    <div style="display: none">@toastr_css</div>
@endsection
@section('page-header')
    الطلاب
@endsection
@section('sub-page-header')
    ترقية الطلاب
@endsection
@section('title')
    الطلاب
@endsection
@section('PageTitle')
    الطلاب
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
                        <form action="{{ route('Promotion.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <h5>العام الدراسي القديم</h5>
                            <div class="form-row">
                                <div class="col-lg-4 col-md-6 mb-10">
                                    <label for="from_specialization_id">التخصص
                                        <span class="text-danger">*
                                            @error('from_specialization_id')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <select class="form-control h-65" name="from_specialization_id" aria-placeholder="اختر تخصص"
                                        required>
                                        <option value="" disabled selected>اختر تخصص من القائمة</option>
                                        @forelse (\App\Models\Specialization::get() as $data)
                                            <option value="{{ $data['id'] }}"
                                                @if ($data->id == old('from_specialization_id')) selected @endif>
                                                {{ $data['name'] }}</option>
                                        @empty
                                            <option value="">لا يوجد تخصصات</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-10">
                                    <label for="academic_year">العام الدراسي
                                        <span class="text-danger">*
                                            @error('academic_year')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <select class="form-control h-65" name="academic_year" aria-placeholder="اختر كلية"
                                        required>
                                        <option value="" disabled selected>اختر عام دراسي من القائمة</option>
                                        @forelse (\App\Models\SchoolYear::get() as $data)
                                            <option value="{{ $data['id'] }}"
                                                @if ($data->id == old('academic_year')) selected @endif>
                                                {{ $data['end_date'] }}/{{ $data['start_date'] }}</option>
                                        @empty
                                            <option value="">لا يوجد اعوام دراسية</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-10">
                                    <label for="from_semester_num">الترم الدراسي
                                        <span class="text-danger">*
                                            @error('from_semester_num')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <select class="form-control h-65" id="from_semester_num" name="from_semester_num"
                                        aria-placeholder="اختر الترم">
                                        <option value="" disabled selected>اختر ترم دراسي من القائمة</option>
                                        @forelse (\App\Models\semesterNumber::get() as $data)
                                            <option value="{{ $data['id'] }}"
                                                @if ($data['id'] == old('from_semester_num')) selected @endif>
                                                {{ $data['name'] }}
                                            </option>
                                        @empty
                                            <option value="">لا يوجد كليات</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <h5>العام الدراسي الجديد</h5>
                            <div class="form-row">
                                <div class="col-lg-4 col-md-6 mb-10">
                                    <label for="to_specialization_id">التخصص
                                        <span class="text-danger">*
                                            @error('to_specialization_id')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <select class="form-control h-65" name="to_specialization_id" aria-placeholder="اختر تخصص"
                                        required>
                                        <option value="" disabled selected>اختر تخصص من القائمة</option>
                                        @forelse (\App\Models\Specialization::get() as $data)
                                            <option value="{{ $data['id'] }}"
                                                @if ($data->id == old('to_specialization_id')) selected @endif>
                                                {{ $data['name'] }}</option>
                                        @empty
                                            <option value="">لا يوجد تخصصات</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-10">
                                    <label for="academic_year_new">العام الدراسي
                                        <span class="text-danger">*
                                            @error('academic_year_new')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <select class="form-control h-65" name="academic_year_new" aria-placeholder="اختر كلية"
                                        required>
                                        <option value="" disabled selected>اختر عام دراسي من القائمة</option>
                                        @forelse (\App\Models\SchoolYear::get() as $data)
                                            <option value="{{ $data['id'] }}"
                                                @if ($data->id == old('academic_year_new')) selected @endif>
                                                {{ $data['end_date'] }}/{{ $data['start_date'] }}</option>
                                        @empty
                                            <option value="">لا يوجد اعوام دراسية</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-10">
                                    <label for="to_semester_num">الترم الدراسي
                                        <span class="text-danger">*
                                            @error('to_semester_num')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <select class="form-control h-65" id="to_semester_num" name="to_semester_num"
                                        aria-placeholder="اختر الترم">
                                        <option value="" disabled selected>اختر ترم دراسي من القائمة</option>
                                        @forelse (\App\Models\semesterNumber::get() as $data)
                                            <option value="{{ $data['id'] }}"
                                                @if ($data['id'] == old('to_semester_num')) selected @endif>
                                                {{ $data['name'] }}
                                            </option>
                                        @empty
                                            <option value="">لا يوجد كليات</option>
                                        @endforelse
                                    </select>
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
