@extends('layouts.master')
@section('css')
    <div style="display: none">@toastr_css</div>
@endsection

@section('title')
    الكلية 
@endsection
@section('page-header')
    الكلية 
@endsection
@section('sub-page-header')
    {{ $title }}
@endsection

@section('PageTitle')
    الكلية 
@endsection
<!-- breadcrumb -->
@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <a href="{{ route('teacher.create') }}" class="btn btn-primary btn-sm" role="button"
                        aria-pressed="true">
                        <i class="ti-plus"></i>
                        اضافة
                        مدرس جديد
                    </a><br><br>
                    <div class="table-responsive">
                        <table id="datatable" class="table  table-hover table-sm table-bordered p-0" data-page-length="50"
                            style="text-align: center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th> المؤهل الدراسي</th>
                                    <th>رقم الهاتف</th>
                                    <th>الجنس</th>
                                    <th>السكن</th>
                                    <th>العمليات</th>
                                
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $teacher)
                                    <tr>

                                        <td>{{ $teacher->id }}</td>
                                        <td>{{ $teacher->name }}</td>
                                        <td>{{ $teacher->qualification }}</td>
                                        <td>{{ $teacher->phone_number }}</td>
                                        <td>{{ $teacher->gender }}</td>                                        
                                        <td>{{ $teacher->address }}</td>
                                        <td>
                                            <a href="{{ route('teacher.edit', $teacher->id) }}"
                                                class="btn btn-info btn-sm" role="button" aria-pressed="true"
                                                title="تعديل"><i class="fa fa-edit"></i></a>
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#delete_teacher{{ $teacher->id }}"
                                                title="ارشفة"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>

                                    @include('page.teacher.destroy')
                                @empty
                                    <tr>
                                        <td colspan="7">لا توجد بيانات</td>
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
