@extends('layouts.master')
@section('css')
    <div style="display: none">@toastr_css</div>
@endsection

@section('title')
قائمة التخصصات الدراسية 
@endsection
@section('page-header')
التخصصات
@endsection
@section('sub-page-header')
قائمة التخصصات الدراسية 
@endsection
@section('PageTitle')
قائمة التخصصات الدراسية 
@endsection
<!-- breadcrumb -->
@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <a type="button"class="btn btn-primary btn-sm text-light" role="button" data-toggle="modal"
                        data-target="#create" aria-pressed="true" title="اضافة تخصص جديد">
                        <i class="ti-plus"></i>
                        اضافة
                        تخصص</a>
                    <br><br>
                    @include('page.Specialization.create')

                    <div class="table-responsive">
                        <table id="datatable" class="table  table-hover table-sm table-bordered p-0" data-page-length="50"
                            style="text-align: center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم التخصص</th>
                                    <th>الكلية</th>
                                    <th>عدد السنين الدراسية</th>
                                    <th>المؤهل المطلوب</th>
                                    <th>اقل معدل للقبول</th>
                                    <th>السعر</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $Specialization)
                                    <tr>
                                        <td>{{ $Specialization['id'] }}</td>
                                        <td>{{ $Specialization['name'] }}</td>
                                        <td>{{ $Specialization->college['name'] }}</td>
                                        <td>{{ $Specialization['Number_of_years_of_study'] }}</td>
                                        <td>{{ $Specialization['educational_qualification'] }}</td>
                                        <td>{{ $Specialization['lowest_acceptance_rate'] }}%</td>
                                        <td>{{ $Specialization['Price'] }}$</td>
                                        <td>
                                            <a href="{{ route('Specialization.edit', $Specialization['id']) }}"
                                                class="btn btn-info btn-sm" role="button" aria-pressed="true"
                                                title="تعديل"><i class="fa fa-edit"></i></a>
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#delete_Specialization{{ $Specialization['id'] }}" title="حذف"><i
                                                    class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    @include('page.Specialization.destroy')
                                @empty
                                    <tr>
                                        <td colspan="8">لا توجد بيانات</td>
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
