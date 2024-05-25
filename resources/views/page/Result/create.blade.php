@extends('layouts.master')
@section('css')
    <div style="display: none">@toastr_css</div>
@endsection
@section('page-header')
    الاعمال فصلية
@endsection
@section('sub-page-header')
    درجات الطالب {{ $student->full_name }} في المقرر {{ $course->name }}
@endsection
@section('title')
    النتيجة النهائية
@endsection
@section('PageTitle')
    النتيجة النهائية
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
                        <form action="{{ route('Result.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">

                                <div class="col-lg-6 col-md-6 mb-10">
                                    <label for="full_name">الاسم
                                    </label>
                                    <input type="text" readonly name="full_name" class="form-control"
                                        value="{{ $student->full_name }}" required>
                                    <input type="number" name="student_id" class="form-control" value="{{ $student->id }}"
                                        hidden readonly required>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-10">
                                    <label for="course_name">المقرر
                                    </label>
                                    <input type="text" readonly name="course_name" class="form-control"
                                        value="{{ $course->name }}" required>
                                    <input type="number" name="course_id" class="form-control" value="{{ $course->id }}"
                                        hidden readonly required>
                                </div>

                                <input type="number" name="specialization_id" class="form-control"
                                    value="{{ request()->get('specialization_id') }}" hidden readonly required>
                                <input type="number" name="semester_num" class="form-control"
                                    value="{{ request()->get('semester_num') }}" hidden readonly required>
                                <div class="col-lg-12 col-md-6 mb-10">
                                    <label for="final_exam_grade">درجة الامتحان النهائي
                                        <span class="text-danger">*
                                            @error('final_exam_grade')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <input type="number" name="final_exam_grade" class="form-control"
                                        oninput="calculateTotalGrade()" min="0" max="20"
                                        value="{{ old('final_exam_grade') ?? ($Result->final_exam_grade ?? 0) }}" required>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-10">
                                    <label for="academic_work_grade">درجة الاعمال الفصلية
                                    </label>
                                    <input type="number" name="academic_work_grade" class="form-control"
                                        oninput="calculateTotalGrade()" min="0" max="20" readonly
                                        value="{{ old('academic_work_grade') ?? ($Result->semesterTasks->academic_work_grade ?? 0) }}"
                                        required>
                                </div>

                                <div class="col-lg-6 col-md-6 mb-10">
                                    <label for="final_grade">المجموع
                                    </label>

                                    <input type="number" name="final_grade" class="form-control"
                                        value="{{ old('final_grade') ?? ($Result->final_grade ?? 0) }}" max="100"
                                        min="0" readonly required>
                                </div>
                            </div>
                            <br>
                            <button class="btn btn-primary btn-sm nextBtn btn-lg pull-right mr-2" id="nextBtn"
                                type="submit">الطالب التالي</button>
                        </form>
                        <a href="{{ request()->fullUrlWithQuery(['student_id' => max(request()->get('student_id') - 1, 0)]) }}"
                            class="btn btn-primary btn-sm nextBtn btn-lg pull-right h-10 mr-2">
                            الطالب السابق
                        </a>

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
    <script>
        function calculateTotalGrade() {
            const academicWorkGrade = parseFloat(document.querySelector('input[name="academic_work_grade"]').value) || 0;
            const final_exam_gradeGrade = parseFloat(document.querySelector('input[name="final_exam_grade"]').value) || 0;

            const totalGrade = academicWorkGrade + final_exam_gradeGrade;

            document.querySelector('input[name="final_grade"]').value = totalGrade.toFixed(2);
        }
    </script>
@endsection
