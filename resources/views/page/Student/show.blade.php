@extends('layouts.master')
@section('css')
    <div style="display: none">@toastr_css</div>
@endsection

@section('title')
    بيانات الطلاب
@endsection
@section('PageTitle')
    بيانات الطلاب
@endsection
@section('page-header')
    الطلاب
@endsection
@section('sub-page-header')
    بيانات الطالب {{ $Student->full_name }}
@endsection
<!-- breadcrumb -->
@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <div class="card-body">
                        <div class="tab nav-border">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active show" id="home-02-tab" data-toggle="tab" href="#home-02"
                                        role="tab" aria-controls="home-02" aria-selected="true">البيانات الشخصية</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-02-tab" data-toggle="tab" href="#profile-02"
                                        role="tab" aria-controls="profile-02" aria-selected="false">النتائج</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade active show" id="home-02" role="tabpanel"
                                    aria-labelledby="home-02-tab">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="card mb-4">
                                                <div class="card-body text-center">
                                                    <img src="{{ asset($Student->image) }}" alt="avatar"
                                                        class="rounded-circle img-fluid" style="width: 150px;">
                                                    <h5 style="font-family: Cairo" class="my-3" id="student_id"
                                                        data-Student-id="{{ $Student->id }}">{{ $Student->full_name }}
                                                    </h5>
                                                    <div class="mb-2">رقم البطاقة الشخصية : {{ $Student->personal_id }}
                                                    </div>
                                                    <div class="mb-2">رقم الطالب الاكاديمي : {{ $Student->academic_id }}
                                                    </div>
                                                    <div class="mb-2">رقم الهاتف : {{ $Student->phone_number }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <table class="table table-bordered table-striped">
                                                        <tr>
                                                            <th>العنوان</th>
                                                            <th>البيانات</th>
                                                        </tr>
                                                        <tr>
                                                            <td>رقم هاتف احد الاقارب</td>
                                                            <td>{{ $Student->relative_phone_number }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>تاريخ الميلاد</td>
                                                            <td>{{ $Student->date_of_birth }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>تاريخ التسجيل</td>
                                                            <td>{{ $Student->place_of_birth }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>الجنس</td>
                                                            <td>{{ $Student->gender }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>الجنسية</td>
                                                            <td>{{ $Student->nationality }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>الموهل الاكاديمي</td>
                                                            <td>{{ $Student->educational_qualification }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>المعدل</td>
                                                            <td>{{ $Student->high_school_grade }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>تاريخ التخرج من الثانوية</td>
                                                            <td>{{ $Student->school_graduation_date }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>التخفيض</td>
                                                            <td>{{ $Student->discount_percentage }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>الكلية</td>
                                                            <td>{{ $Student->College->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>التخصص</td>
                                                            <td id="specialization_id">
                                                                {{ $Student->Specialization->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>الترم الدراسي</td>
                                                            <td>{{ $Student->semester_num }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="profile-02" role="tabpanel" aria-labelledby="profile-02-tab">
                                    <div class="card card-statistics">
                                        <div class="card-body">
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th>الترم</th>
                                                    <th>عملي</th>
                                                    <th>نظري</th>
                                                </tr>
                                                @for ($i = 1; $i <= $Student->semester_num; $i++)
                                                    <tr>
                                                        <th>{{ $i }}</th>
                                                        <th>
                                                            <button type="button"
                                                                class="btn btn-primary btn-sm btn-semester-task"
                                                                id="btn-semester-task"
                                                                data-semester-num="{{ $i }}" data-toggle="modal"
                                                                data-target="#semesterTask{{ $Student->id }}">
                                                                عرض
                                                            </button>
                                                        </th>
                                                        <th>
                                                            <button type="button"
                                                                class="btn btn-primary btn-sm btn-ResultData"
                                                                id="btn-ResultData"
                                                                data-ResultData-num="{{ $i }}"
                                                                data-toggle="modal"
                                                                data-target="#ResultData{{ $Student->id }}">
                                                                عرض
                                                            </button>
                                                        </th>
                                                    </tr>
                                                @endfor

                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            @include('page.Student.semesterTask')
            @include('page.Student.Result')
            <input type="number" hidden name="specialization_id" value="{{ $Student->Specialization->id }}">
            <input type="number" hidden name="student_id" value="{{ $Student->id }}">
            <!-- row closed -->
        @endsection
        @section('js')
            @toastr_js
            @toastr_render
            <script>
                $(document).ready(function() {
                    $("#btn-semester-task").click(function() {
                        var semesterNum = $(this).data("semester-num");
                        var specialization_id = $("input[name='specialization_id']").val();
                        var student_id = $("input[name='student_id']").val();
                        getSemesterTask(semesterNum, specialization_id, student_id);
                    });

                    function getSemesterTask(semesterNum, specialization_id, student_id) {
                        var baseUrl = window.location.origin;
                        var url = baseUrl + '/getSemesterTask?specialization_id=' + encodeURIComponent(
                                specialization_id) + '&student_id=' + encodeURIComponent(student_id) + '&semesterNum=' +
                            encodeURIComponent(semesterNum);
                        $.get(url, function(response) {
                            if (response.Status) {
                                var tableBody = $("#semesterTask");
                                tableBody.empty();
                                $.each(response.data, function(index, task) {
                                    const link =
                                    baseUrl + `/SemesterTask/create?specialization_id=${specialization_id}&semester_num=${semesterNum}&course_id=${task.course_id}&student_id=${student_id}`;
                                    var row = "<tr>" +
                                        `<td><a href='${link}' class="w-100 h-100 d-inline-block" >${task.id}</a></td>` +
                                        `<td><a href='${link}' class="w-100 h-100 d-inline-block" >${task.course_name}</a></td>` +
                                        `<td><a href='${link}' class="w-100 h-100 d-inline-block" >${task.semester_num}</a></td>` +
                                        `<td><a href='${link}' class="w-100 h-100 d-inline-block" >${task.academic_work_grade}</a></td>` +
                                        `<td><a href='${link}' class="w-100 h-100 d-inline-block" >${task.attendance}</a></td>` +
                                        `<td><a href='${link}' class="w-100 h-100 d-inline-block" >${task.midterm_grade}</a></td>` +
                                        `<td><a href='${link}' class="w-100 h-100 d-inline-block" >${task.practicality_grade}</a></td>` +
                                        `<td><a href='${link}' class="w-100 h-100 d-inline-block" >${task.final_grade}</a></td>` +
                                        "</tr>";
                                    tableBody.append(row);
                                });
                            } else {
                                alert(response.Message);
                            }
                        }).fail(function() {
                            alert("حدث خطأ أثناء استرداد البيانات.");
                        });
                    }
                    $("#btn-ResultData").click(function() {
                        var semesterNum = $(this).attr("data-ResultData-num");
                        var specialization_id = $("input[name='specialization_id']").val();
                        var student_id = $("input[name='student_id']").val();
                        getResultData(semesterNum, specialization_id, student_id);
                    });

                    function getResultData(semesterNum, specialization_id, student_id) {
                        var baseUrl = window.location.origin;

                        var url = baseUrl + '/ResultData?specialization_id=' + encodeURIComponent(
                                specialization_id) + '&student_id=' + encodeURIComponent(student_id) + '&semesterNum=' +
                            encodeURIComponent(semesterNum);
                        console.log(url);
                        $.get(url, function(response) {
                            if (response.Status) {
                                var tableBody = $("#Result");
                                tableBody.empty();
                                $.each(response.data, function(index, task) {
                                    const link =
                                    baseUrl + `/Result/create?specialization_id=${specialization_id}&semester_num=${semesterNum}&course_id=${task.course_id}&student_id=${student_id}`;
                                    var row = "<tr>" +
                                        `<td><a href='${link}' class="w-100 h-100 d-inline-block" >${task.id}</a></td>` +
                                        `<td><a href='${link}' class="w-100 h-100 d-inline-block" >${task.course_name}</a></td>` +
                                        `<td><a href='${link}' class="w-100 h-100 d-inline-block" >${task.semester_num}</a></td>` +
                                        `<td><a href='${link}' class="w-100 h-100 d-inline-block" >${task.academic_work_grade}</a></td>` +
                                        `<td><a href='${link}' class="w-100 h-100 d-inline-block" >${task.final_exam_grade}</a></td>` +
                                        `<td><a href='${link}' class="w-100 h-100 d-inline-block" >${task.final_grade}</a></td>` +
                                        `<td><a href='${link}' class="w-100 h-100 d-inline-block" >${task.status}</a></td>` +
                                        "</tr>";
                                    tableBody.append(row);
                                });
                            } else {
                                alert(response.Message);
                            }
                        }).fail(function() {
                            alert("حدث خطأ أثناء استرداد البيانات.");
                        });
                    }
                });
            </Script>
        @endsection
