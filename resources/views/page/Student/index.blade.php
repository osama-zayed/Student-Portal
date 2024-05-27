@extends('layouts.master')
@section('css')
    <div style="display: none">@toastr_css</div>
@endsection

@section('title')
    الطلاب 
@endsection
@section('page-header')
    الطلاب 
@endsection
@section('sub-page-header')
    {{ $title }}
@endsection

@section('PageTitle')
    الطلاب 
@endsection
<!-- breadcrumb -->
@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <a href="{{ route('Student.create') }}" class="btn btn-primary btn-sm" role="button"
                        aria-pressed="true">
                        <i class="ti-plus"></i>
                        اضافة
                        طالب جديد
                    </a><br><br>
                    <div class="table-responsive">
                        <table id="datatable" class="table  table-hover table-sm table-bordered p-0" data-page-length="50"
                            style="text-align: center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>رقم الطالب الاكاديمي</th>
                                    <th>الاسم</th>
                                    <th>رقم البطاقة الشخصية</th>
                                    <th>رقم الهاتف</th>
                                    <th>رقم هاتف احد الاقارب</th>
                                    <th>الجنس</th>
                                    <th>الكلية</th>
                                    <th>التخصص</th>
                                    <th>الترم الدراسي</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $Student)
                                    <tr>
                            
                                        <td>{{ $Student->id }}</td>
                                        <td>{{ $Student->academic_id }}</td>
                                        <td>{{ $Student->full_name }}</td>
                                        <td>{{ $Student->personal_id }}</td>
                                        <td>{{ $Student->phone_number }}</td>
                                        <td>{{ $Student->relative_phone_number }}</td>
                                        <td>{{ $Student->gender }}</td>
                                        <td>{{ $Student->College['name'] }}</td>
                                        <td>{{ $Student->Specialization['name'] }}</td>
                                        <td>{{ $Student->semester_num }}</td>
                                        <td>
                                            <a href="{{ route('Student.show', $Student->id) }}"
                                                class="btn btn-primary btn-sm" role="button" aria-pressed="true"
                                                title="تعديل"><i class="fa fa-eye"></i></a>
                                            <a href="{{ route('Student.edit', $Student->id) }}"
                                                class="btn btn-info btn-sm" role="button" aria-pressed="true"
                                                title="تعديل"><i class="fa fa-edit"></i></a>
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#delete_Student{{ $Student->id }}"
                                                title="ارشفة"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>

                                    @include('page.Student.destroy')
                                @empty
                                    <tr>
                                        <td colspan="11">لا توجد بيانات</td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                    @if ($totalPages > 1)
                        <div class="col-xl-12  d-flex justify-content-center align-items-center flex-row">
                            <a @if ($page < $totalPages) href="{{ request()->fullUrlWithQuery(['page' => $page + 1]) }}" @endif
                                class="btn mr-30 btn-success btn-sm text-center" role="button">التالي</a>
                            <a @if ($page != 1) href="{{ request()->fullUrlWithQuery(['page' => $page - 1]) }}" @endif
                                class="btn ml-30 btn-danger btn-sm text-center" role="button">السابق</a>
                        </div>
                    @endif
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
