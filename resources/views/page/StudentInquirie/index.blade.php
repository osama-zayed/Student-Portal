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
    {{ $title }} الطلاب
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
                    <div class="table-responsive">
                        <table id="datatable" class="table  table-hover table-sm table-bordered p-0" data-page-length="50"
                            style="text-align: center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم الطالب</th>
                                    <th>الكلية</th>
                                    <th>التخصص</th>
                                    <th>عنوان {{ $title }}</th>
                                    <th>الحالة</th>
                                    <th>تاريخ حل المشكلة</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $studentInquirie)
                                    <tr>
                                        <td>{{ $studentInquirie->id }}</td>
                                        <td>{{ $studentInquirie->student->full_name }}</td>
                                        <td>{{ $studentInquirie->student->college->name }}</td>
                                        <td>{{ $studentInquirie->student->specialization->name }}</td>
                                        <td>{{ $studentInquirie->subject }}</td>
                                        <td>{{ $studentInquirie->status }}</td>
                                        @if (empty($studentInquirie->resolved_at))
                                            <td>
                                                <button class="btn btn-danger btn-sm">لم يتم الحل</button>
                                            </td>
                                        @else
                                            <td>{{ $studentInquirie->resolved_at }}</td>
                                        @endif
                                        <td>
                                            <a type="button"class="btn btn-primary btn-sm text-light"
                                                class="btn btn-primary btn-sm" role="button" data-toggle="modal"
                                                data-target="#studentInquirie{{ $studentInquirie->id }}" aria-pressed="true" title="حل المشكلة"><i
                                                    class="fa fa-eye"></i></a>

                                    </tr>
                                    @include('page.studentInquirie.create')
                                @empty
                                    <tr>
                                        <td colspan="8">لا توجد بيانات</td>
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
