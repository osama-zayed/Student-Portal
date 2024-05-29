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
    العام الدراسي
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
                    <a type="button"class="btn btn-primary btn-sm text-light" role="button" data-toggle="modal"
                        data-target="#create" aria-pressed="true" title="اضافة عام دراسي جديد">
                        <i class="ti-plus"></i>
                        اضافة
                        عام درسي جديد</a>
                    <br><br>
                    @include('page.SchoolYear.create')
                    <div class="table-responsive">
                        <table id="datatable" class="table  table-hover table-sm table-bordered p-0" data-page-length="50"
                            style="text-align: center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>تاريخ بداية العام</th>
                                    <th>تاريخ نهاية العام</th>
                                    <th>الحالة</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $SchoolYear)
                                    <tr>
                                        <td>{{ $SchoolYear['id'] }}</td>
                                        <td>{{ $SchoolYear['name'] }}</td>
                                        <td>{{ $SchoolYear['start_date'] }}</td>
                                        <td>{{ $SchoolYear['end_date'] }}</td>
                                        <td>
                                            @if ($SchoolYear['is_current'])
                                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal">
                                                    العام الحالي </button>
                                            @else
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal">
                                                    عام منتهي </button>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#delete_SchoolYear{{ $SchoolYear['id'] }}" title="حذف"><i
                                                    class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    @include('page.SchoolYear.destroy')
                                @empty
                                    <tr>
                                        <td colspan="6">لا توجد بيانات</td>
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
