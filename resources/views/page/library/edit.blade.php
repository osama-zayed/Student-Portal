@extends('layouts.master')
@section('css')
    <div style="display: none">
        @toastr_css</div>
@endsection

@section('title')
    تعديل كلية {{ $library->name }}
@endsection
@section('page-header')
    <!-- breadcrumb -->
@section('PageTitle')
    الكلية
@endsection

@section('page-header')
    الكلية
@endsection
@section('sub-page-header')
    تعديل كلية {{ $library->name }}
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
                            <form action="{{ route('library_Book.update', 'test') }}" method="post"
                                enctype="multipart/form-library">
                                @method('PUT')
                                @csrf
                                <div class="form-row">
                                    <div class="col-12 mt-2">
                                        <label for="name">اسم الكتاب
                                            <span class="text-danger">*
                                                @error('name')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </label>
                                        <input id="name" type="text" name="name" class="form-control"
                                            value="{{ old('name') ?? ($library->name ?? '') }}"
                                            placeholder="أدخل اسم الكتاب" required="الحقل مطلوب">
                                    </div>
                                    <input id="id" type="number" name="id" value="{{ $library->id }}" readonly
                                        hidden>
                                    <div class="col-12 mt-2">
                                        <label for="description">وصف الكتاب
                                            <span class="text-danger">*
                                                @error('description')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </label>
                                        <textarea id="description" type="text" name="description" class="form-control" value=""
                                            placeholder="أدخل وصف الكتاب" required="الحقل مطلوب" cols="30" rows="10">{{ old('description') ?? ($library->description ?? '') }}</textarea>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <label for="Image">صورة الكتاب
                                            <span class="text-danger">*
                                                @error('Image')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </label>
                                        <input id="Image" type="file" name="Image" class="form-control"
                                            value="{{ old('Image') }}" placeholder="أدخل صورة الكتاب">
                                    </div>
                                    <div class="col-12 mt-2">
                                        <label for="file">الكتاب
                                            <span class="text-danger">*
                                                @error('file')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </label>
                                        <input id="file" type="file" name="file" class="form-control"
                                            value="{{ old('file') }}" placeholder="أدخل الكتاب">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">تعديل</button>
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
