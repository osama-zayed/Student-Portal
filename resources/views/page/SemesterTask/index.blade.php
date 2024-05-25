@extends('layouts.master')
@section('css')
    <div style="display: none">@toastr_css</div>
@endsection

@section('title')
    الاعمال الفصلية
@endsection
@section('page-header')
    الاعمال الفصلية
@endsection
@section('sub-page-header')
    {{ $title }}
@endsection

@section('PageTitle')
    الاعمال الفصلية
@endsection
@section('content')
    <!-- row -->
    <div class="card card-statistics">
        <div class="card-body">
            <div class="card-body">
                <form method="GET" action="{{ request()->fullUrlWithQuery }}" autocomplete="off">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-10">
                            <label for="college_id">الكلية
                                <span class="text-danger">*
                                    @error('college_id')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </label>
                            <select class="form-control h-65" id="college_id" name="college_id" aria-placeholder="اختر كلية"
                                required>
                                <option value="" disabled selected>اختر كلية من القائمة</option>
                                @forelse (\App\Models\College::get() as $data)
                                    <option value="{{ $data['id'] }}">
                                        {{ $data['name'] }}
                                    </option>
                                @empty
                                    <option value="">لا يوجد كليات</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-10">
                            <label for="specialization_id">التخصص
                                <span class="text-danger">*
                                    @error('specialization_id')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </label>
                            <select class="form-control h-65" id="specialization_id" name="specialization_id"
                                aria-placeholder="اختر تخصص" required>
                                <option value="" disabled selected>اختر تخصص من القائمة</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-10">
                            <label for="semester_num">الترم الدراسي
                                <span class="text-danger">*
                                    @error('semester_num')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </label>
                            <select class="form-control h-65" id="semester_num" name="semester_num"
                                aria-placeholder="اختر الترم" required>
                                <option value="" disabled selected>اختر ترم دراسي من القائمة</option>
                                <option value="1" @if (1 == request()->query('semester_num')) selected @endif>
                                    الاول
                                </option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-10">
                            <label for="course_id">اسم المقرر
                                <span class="text-danger">*
                                    @error('course_id')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </label>
                            <select class="form-control h-65" id="course_id" name="course_id" aria-placeholder="اختر مقرر"
                                required>
                                <option value="" disabled selected>اختر مقرر من القائمة</option>
                            </select>
                        </div>
                    </div>

                    <button class="btn btn-primary btn-sm nextBtn btn-lg pull-right h-10 mr-2" type="submit"
                        formaction="{{ route('SemesterTask.index') }}">عرض</button>
                    <button class="btn btn-primary btn-sm nextBtn btn-lg pull-right h-10" type="submit"
                        formaction="{{ route('SemesterTask.create') }}">اضافة</button>
                </form>
            </div>
        </div>
    </div>

    <br>
    <!-- row closed -->
    <div class="card card-statistics">
        <div class="table-responsive">
            <div class="card-body">
                <table id="datatable" class="table  table-hover table-sm table-bordered p-0" data-page-length="50"
                    style="text-align: center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>المادة</th>
                            <th>اسم الطالب</th>
                            <th>التخصص</th>
                            <th>الفصل الدراسي</th>
                            <th>درجة الأعمال الفصلية</th>
                            <th>الحضور</th>
                            <th>درجة الاختبار النصفي</th>
                            <th>درجة العملي</th>
                            <th>الدرجة النهائية</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($SemesterTask as $AcademicWork)
                            @php
                                $link =
                                    'SemesterTask/create?college_id=' .
                                    $AcademicWork->college_id .
                                    '&specialization_id=' .
                                    $AcademicWork->specialization->id .
                                    '&semester_num=' .
                                    $AcademicWork->semester_num .
                                    '&course_id=' .
                                    $AcademicWork->course->id .
                                    '&student_id=' .
                                    $AcademicWork->student->id;
                            @endphp
                            <tr>
                                <td><a class="w-100 h-100 d-inline-block"
                                        href="{{ $link }}">{{ $AcademicWork->id }}</a></td>
                                <td><a class="w-100 h-100 d-inline-block"
                                        href="{{ $link }}">{{ $AcademicWork->course->name }}</a></td>
                                <td><a class="w-100 h-100 d-inline-block"
                                        href="{{ $link }}">{{ $AcademicWork->student->full_name }}</a></td>
                                <td><a class="w-100 h-100 d-inline-block"
                                        href="{{ $link }}">{{ $AcademicWork->specialization->name }}</a></td>
                                <td><a class="w-100 h-100 d-inline-block"
                                        href="{{ $link }}">{{ $AcademicWork->semester_num }}</a></td>
                                <td><a class="w-100 h-100 d-inline-block"
                                        href="{{ $link }}">{{ $AcademicWork->academic_work_grade }}</a></td>
                                <td><a class="w-100 h-100 d-inline-block"
                                        href="{{ $link }}">{{ $AcademicWork->attendance }}</a></td>
                                <td><a class="w-100 h-100 d-inline-block"
                                        href="{{ $link }}">{{ $AcademicWork->midterm_grade }}</a></td>
                                <td><a class="w-100 h-100 d-inline-block"
                                        href="{{ $link }}">{{ $AcademicWork->practicality_grade }}</a></td>
                                <td><a class="w-100 h-100 d-inline-block"
                                        href="{{ $link }}">{{ $AcademicWork->final_grade }}</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">لا توجد بيانات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($totalPages > 1)
                <div class="col-xl-12 mb-3 d-flex justify-content-center align-items-center flex-row">
                    <a @if ($page < $totalPages) href="{{ request()->fullUrlWithQuery(['page' => $page + 1]) }}" @endif
                        class="btn mr-30 btn-success btn-sm text-center" role="button">
                        التالي
                    </a>
                    <a @if ($page != 1) href="{{ request()->fullUrlWithQuery(['page' => $page - 1]) }}" @endif
                        class="btn ml-30 btn-danger btn-sm text-center" role="button">
                        السابق
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
@section('js')
    @toastr_js
    @toastr_render
    <script>
        $(document).ready(function() {
            $('#college_id').on('change', function() {
                var collegeId = $(this).val();
                $.ajax({
                    url: '{{ route('get-specializations') }}',
                    type: 'GET',
                    data: {
                        college_id: collegeId
                    },
                    success: function(response) {
                        $('#specialization_id').empty();
                        $('#specialization_id').append(
                            '<option value="" disabled selected>اختر تخصص من القائمة</option>'
                        );
                        $.each(response, function(key, value) {
                            $('#specialization_id').append('<option value="' + value
                                .id + '">' + value.name + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });

            $('#specialization_id, #semester_num').on('change', function() {
                var specializationId = $('#specialization_id').val();
                var semesterNum = $('#semester_num').val();
                $.ajax({
                    url: '{{ route('get-courses') }}',
                    type: 'GET',
                    data: {
                        specialization_id: specializationId,
                        semester_num: semesterNum
                    },
                    success: function(response) {
                        $('#course_id').empty();
                        $('#course_id').append(
                            '<option value="" disabled selected>اختر مقرر من القائمة</option>'
                        );
                        $.each(response, function(key, value) {
                            $('#course_id').append('<option value="' + value.id + '">' +
                                value.name + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });
        // Add event listeners to the semester_num and course_id dropdowns
        document.getElementById('semester_num').addEventListener('change', updateAcademicWorkTable);
        document.getElementById('course_id').addEventListener('change', updateAcademicWorkTable);

        function updateAcademicWorkTable() {
            const semesterNum = document.getElementById('semester_num').value;
            const courseId = document.getElementById('course_id').value;

            // Use AJAX to fetch the data from the server
            $.ajax({
                url: '/getSemesterTaskData',
                data: {
                    semester_num: semesterNum,
                    course_id: courseId
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    updateTableBody(data);
                },
                error: function(error) {
                    console.error('Error retrieving semester task data:', error);
                }
            });
        }

        function updateTableBody(data) {
            const table = $('table');
            const tableBody = table.find('tbody');
            tableBody.empty();

            if (data.length === 0) {
                tableBody.append('<tr><td colspan="9">لا يوجد بيانات</td></tr>');
            } else {
                data.forEach(item => {
                    const link =
                        `SemesterTask/create?college_id=${item.college_id}&specialization_id=${item.specialization.id}&semester_num=${item.semester_num}&course_id=${item.course.id}&student_id=${item.student.id}`;
                    row = $(`<tr>`);
                    row.append(`<td><a href="${link}" class="w-100 h-100 d-inline-block">${item . id}</a></td>`);
                    row.append(
                        `<td><a href="${link}" class="w-100 h-100 d-inline-block" >${item . course . name}</a></td>`
                        );
                    row.append(
                        `<td><a href="${link}" class="w-100 h-100 d-inline-block" >${item . student . full_name}</a></td>`
                        );
                    row.append(
                        `<td><a href="${link}" class="w-100 h-100 d-inline-block" >${item . specialization . name}</a></td>`
                        );
                    row.append(
                        `<td><a href="${link}" class="w-100 h-100 d-inline-block" >${item . semester_num}</a></td>`
                        );
                    row.append(
                        `<td><a href="${link}" class="w-100 h-100 d-inline-block" >${item . academic_work_grade}</a></td>`
                        );
                    row.append(
                        `<td><a href="${link}" class="w-100 h-100 d-inline-block" >${item . attendance}</a></td>`
                        );
                    row.append(
                        `<td><a href="${link}" class="w-100 h-100 d-inline-block" >${item . midterm_grade}</a></td>`
                        );
                    row.append(
                        `<td><a href="${link}" class="w-100 h-100 d-inline-block" >${item . practicality_grade}</a></td>`
                        );
                    row.append(
                        `<td><a href="${link}" class="w-100 h-100 d-inline-block" >${item . final_grade}</a></td></tr>`
                        )

                    tableBody.append(row);
                });
            }
        }
    </script>
@endsection
