@extends('layouts.master')
@section('css')
    <div style="display: none">@toastr_css</div>
@endsection
@section('title')
المكتبة
@endsection
@section('page-header')
    المكتبة
@endsection
@section('sub-page-header')
    قائمة الكتب
@endsection
@section('PageTitle')
المكتبة
@endsection
<!-- breadcrumb -->
@section('content')
    <!-- row -->
    <div class="row">

        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <a type="button"class="btn btn-primary btn-sm text-light" role="button" data-toggle="modal"
                        data-target="#create" aria-pressed="true" title="اضافة كلية جديد">
                        <i class="ti-plus"></i>
                        اضافة
                        كتاب</a>
                    <br><br>
                    @include('page.library.create')
                    <div class="table-responsive">
                        <table id="datatable" class="table  table-hover table-sm table-bordered p-0" data-page-length="50"
                            style="text-align: center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم الكتاب</th>
                                    <th>الوصف</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse ($data as $library)
                                    <tr>
                                        <td>{{ $library['id'] }}</td>
                                        <td>{{ $library['name'] }}</td>
                                        <td>{{ $library['description'] }}</td>
                                        <td>
                                            <a href="{{ route('library_Book.edit', $library['id']) }}" class="btn btn-info btn-sm"
                                                role="button" aria-pressed="true" title="تعديل"><i
                                                    class="fa fa-edit"></i></a>
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#delete_library{{ $library['id'] }}" title="حذف"><i
                                                    class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    @include('page.library.destroy')
                                @empty
                                    <tr>
                                        <td colspan="3">لا توجد بيانات</td>
                                    </tr>
                                @endforelse
                        </table>
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
