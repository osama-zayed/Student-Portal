@extends('layouts.master')
@section('css')
    <div style="display: none">@toastr_css</div>
@endsection
@section('page-header')
    الطلاب
@endsection
@section('sub-page-header')
    اضافة بيانات طالب جديد
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
                        <form action="{{ route('Student.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">

                                <div class="col-lg-4 col-md-6 mb-10">
                                    <label for="full_name">الاسم
                                        <span class="text-danger">*
                                            @error('full_name')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <input type="text" name="full_name" class="form-control"
                                        value="{{ old('full_name') }}" required>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-10">
                                    <label for="personal_id">رقم البطاقة الشخصيه
                                        <span class="text-danger">*
                                            @error('personal_id')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <input type="number" name="personal_id" class="form-control"
                                        value="{{ old('personal_id') }}" required>
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
                                    <label for="relative_phone_number">رقم هاتف احد الاقرباء
                                        <span class="text-danger">*
                                            @error('relative_phone_number')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <input type="number" name="relative_phone_number" class="form-control"
                                        pattern="[0-9]+(\.[0-9]+)?" title="يرجى إدخال أرقام فقط"
                                        value="{{ old('relative_phone_number') }}" required>
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
                                    <label for="date_of_birth">تاريخ الميلاد
                                        <span class="text-danger">*
                                            @error('date_of_birth')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <input type="date" name="date_of_birth" class="form-control h-65"
                                        value="{{ old('date_of_birth', date('Y-m-d', strtotime('-17 years'))) }}"
                                        max="{{ date('Y-m-d', strtotime('-17 years')) }}" required>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-10">
                                    <label for="place_of_birth">محل الميلاد
                                        <span class="text-danger">*
                                            @error('place_of_birth')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <input type="text" name="place_of_birth" class="form-control"
                                        value="{{ old('place_of_birth') }}" required>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-10">
                                    <label for="nationality">الجنسية
                                        <span class="text-danger">*
                                            @error('nationality')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <input type="text" name="nationality" class="form-control"
                                        value="{{ old('nationality') }}" required>
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
                                    <label for="educational_qualification">المؤهل الدراسي
                                        <span class="text-danger">*
                                            @error('educational_qualification')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <select class="form-control h-65" name="educational_qualification"
                                        aria-placeholder="المؤهل مطلوب" required>
                                        <option value="" disabled selected>اختر المؤهل</option>
                                        <option value="ثانوية عامة علمي" @if (old('educational_qualification') == 'ثانوية عامة علمي') selected @endif>
                                            ثانوية عامة علمي
                                        <option value="ثانوية عامة ادبي" @if (old('educational_qualification') == 'ثانوية عامة ادبي') selected @endif>
                                            ثانوية عامة ادبي
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-10">
                                    <label for="high_school_grade">معدل الثانوية
                                        <span class="text-danger">*
                                            @error('high_school_grade')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <input type="number" name="high_school_grade" class="form-control" max="100"
                                        min="50" value="{{ old('high_school_grade') }}" required>
                                </div>
              
                                <div class="col-lg-4 col-md-6 mb-10">
                                    <label for="school_graduation_date">تاريخ الحصول عليها
                                        <span class="text-danger">*
                                            @error('school_graduation_date')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <input type="date" name="school_graduation_date" class="form-control h-65"
                                        value="{{ old('school_graduation_date') ?? date('Y-m-d', strtotime('-1 month')) }}"
                                        max="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-10">
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
                                                @if ($data->id == old('college_id')) selected @endif>
                                                {{ $data['name'] }}</option>
                                        @empty
                                            <option value="">لا يوجد كليات</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-10">
                                    <label for="specialization_id">التخصص
                                        <span class="text-danger">*
                                            @error('specialization_id')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <select class="form-control h-65" name="specialization_id"
                                        aria-placeholder="اختر تخصص" required>
                                        <option value="" disabled selected>اختر تخصص من القائمة</option>
                                        @forelse (\App\Models\Specialization::get() as $data)
                                            <option value="{{ $data['id'] }}"
                                                @if ($data->id == old('specialization_id')) selected @endif>
                                                {{ $data['name'] }}</option>
                                        @empty
                                            <option value="">لا يوجد تخصصات</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-10">
                                    <label for="discount_percentage">نسبة التخفيض
                                        <span class="text-danger">*
                                            @error('discount_percentage')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <input type="number" name="discount_percentage" class="form-control" max="50"
                                        min="30" value="{{ old('discount_percentage') }}" required>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-10">
                                    <label for="image">الصورة الشخصية
                                        <span class="text-danger">
                                            @error('image')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <input type="file" name="image" class="form-control h-65"
                                        value="{{ old('image') }}">
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
