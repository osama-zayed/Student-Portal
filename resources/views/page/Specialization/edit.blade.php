@extends('layouts.master')
@section('css')
    <div style="display: none">
        @toastr_css</div>
@endsection

@section('title')
    تعديل تخصص {{ $Specializations->name }}
@endsection
@section('page-header')
    <!-- breadcrumb -->
@section('PageTitle')
    تعديل تخصص {{ $Specializations->name }}
@endsection
@section('page-header')
المراكز
@endsection
@section('sub-page-header')
تعديل تخصص {{ $Specializations->name }}

@endsection
<!-- breadcrumb -->
@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <div class="col-xs-12">
                        <div class="col-md-12">
                            <br>
                            <form action="{{ route('Specialization.update', 'test') }}" method="post"
                                enctype="multipart/form-Specializations">
                                @method('PUT')
                                @csrf
                                <div class="form-row">
                                    <div class="col">
                                        <label for="name">اسم التخصص :
                                            <span class="text-danger">* @error('name')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </label>
                                        <input type="text" name="name"
                                            value="{{ $Specializations->name . old('name') }}"
                                            class="form-control">
                                        
                                    </div>
                                    <div class="col">
                                        <label for="name"> رقم الهاتف :
                                            <span class="text-danger">* @error('name')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </label>
                                        <input type="number" name="phone_number" pattern="[0-9]+(\.[0-9]+)?" title="يرجى إدخال أرقام فقط"
                                            value="{{ $Specializations->phone_number . old('phone_number') }}"
                                            class="form-control">
                                        <input type="hidden" name="id" value="{{ $Specializations->id }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <br>

                              
                                <button class="btn btn-success btn-sm nextBtn btn-lg pull-right" type="submit"
                                    title="تعديل"> تعديل
                                    البيانات</button>
                            </form>
                        </div>
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