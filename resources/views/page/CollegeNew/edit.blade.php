@extends('layouts.master')
@section('css')
    <div style="display: none">
        @toastr_css</div>
@endsection

@section('title')
    تعديل خبر {{ $CollegeNew->title }}
@endsection
@section('page-header')
    <!-- breadcrumb -->
@section('PageTitle')
    الخبر
@endsection

@section('page-header')
    الخبر
@endsection
@section('sub-page-header')
    تعديل خبر {{ $CollegeNew->title }}
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
                            <form action="{{ route('College-New.update', 'test') }}" method="post"
                                enctype="multipart/form-CollegeNew">
                                @method('PUT')
                                @csrf
                                <input id="id" type="number" name="id" value="{{ $CollegeNew->id }}" readonly
                                hidden>
                                <div class="form-row">
                                    <div class="col-12">
                                        <label for="title">اسم الخبر
                                            <span class="text-danger">*
                                                @error('title')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </label>
                                        <input id="title" type="text" name="title" class="form-control"
                                            value="{{ old('title') ?? ($CollegeNew->title ?? '') }}"
                                            placeholder="أدخل اسم الخبر " required="الحقل مطلوب">
                                    </div>
                                    <div class="col-12">
                                        <label for="description">وصف الخبر
                                            <span class="text-danger">*
                                                @error('description')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </label>
                                        <textarea id="description" type="text" name="description" class="form-control" value=""
                                            placeholder="أدخل وصف الخبر " required="الحقل مطلوب" cols="30" rows="10">{{ old('description') ?? ($CollegeNew->description ?? '') }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label for="file">صوره الخبر
                                            <span class="text-danger">*
                                                @error('file')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </label>
                                        <input id="file" type="file" name="file" class="form-control"
                                            value="{{ old('file') }}" placeholder="أدخل صورة الخبر ">
                                    </div>
                                </div>
                                <br>

                                <button class="btn btn-primary btn-sm nextBtn btn-lg pull-right" type="submit"
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
