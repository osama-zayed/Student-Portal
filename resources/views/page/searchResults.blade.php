@extends('layouts.master')
@section('css')
    <div style="display: none">@toastr_css</div>
@endsection

@section('title')
    البحث
@endsection
@section('page-header')
    البحث
@endsection
@section('sub-page-header')
    نتائج البحث
@endsection

@section('PageTitle')
    البحث
@endsection
<!-- breadcrumb -->
@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="m-10">البحث</h5>

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
