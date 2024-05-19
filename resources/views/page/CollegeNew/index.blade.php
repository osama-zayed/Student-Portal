@extends('layouts.master')
@section('css')
    <div style="display: none">@toastr_css</div>
@endsection
@section('title')
    الاخبار
@endsection
@section('page-header')
    الاخبار
@endsection
@section('sub-page-header')
    قائمة الاخبار
@endsection
@section('PageTitle')
    الاخبار
@endsection
<!-- breadcrumb -->
@section('content')
    <!-- row -->
    <div class="row">

        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <a type="button"class="btn btn-primary btn-sm text-light" role="button" data-toggle="modal"
                        data-target="#create" aria-pressed="true" title="اضافة خبر جديد">
                        <i class="ti-plus"></i>
                        اضافة
                        خبر</a>
                    <br><br>
                    @include('page.CollegeNew.create')
                    <div class="table-responsive">
                        <table id="datatable" class="table  table-hover table-sm table-bordered p-0" data-page-length="50"
                            style="text-align: center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>عنوان الخبر</th>
                                    <th>تفاصيل الخبر</th>
                                    <th>صورة الخبر</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse ($data as $CollegeNew)
                                    <tr>
                                        <td>{{ $CollegeNew['id'] }}</td>
                                        <td>{{ $CollegeNew['title'] }}</td>
                                        <td>{{ $CollegeNew['description'] }}</td>
                                        <td><button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                                data-target="#show_image{{ $CollegeNew['id'] }}" title="عرض الصوره">عرض</button></td>
                                                    
                                        <td>
                                            <a href="{{ route('College-New.edit', $CollegeNew['id']) }}"
                                                class="btn btn-info btn-sm" role="button" aria-pressed="true"
                                                title="تعديل"><i class="fa fa-edit"></i></a>
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#delete_CollegeNew{{ $CollegeNew['id'] }}" title="حذف"><i
                                                    class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    @include('page.CollegeNew.destroy')
                                    @include('page.CollegeNew.show_image')
                                @empty
                                    <tr>
                                        <td colspan="5">لا توجد بيانات</td>
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
