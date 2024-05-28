@extends('layouts.app')
@section('content')
    <!-- main-content -->
    <div class="content-wrapper">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-10">مرحبا بك</h3>
                    <h5 class="mb-20" style="color: #80828f; "> في بوابة الطالب الاكترونية جامعة سباء </ح>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
                    </ol>
                </div>
            </div>
        </div>
        <!-- widgets -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                <div class="card card-statistics h-100" style="background: #727f9f ;border-radius: 5px;overflow: hidden ">
                    <div class="card-body p-0 ">
                        <div class="clearfix ">
                            <div class="p-20 pb-30">
                                <h5 class="card-text text-light mb-10">عدد الكليات </h5>
                                <h4 class="text-light">{{ $data['CollegeCount'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                <div class="card card-statistics h-100" style="background: #01b9ff ;border-radius: 5px;overflow: hidden ">
                    <div class="card-body p-0 ">
                        <div class="clearfix ">
                            <div class="p-20 pb-30">
                                <h5 class="card-text text-light mb-10">عدد التخصصات </h5>
                                <h4 class="text-light">{{ $data['SpecializationCount'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                <div class="card card-statistics h-100" style="background: #354262 ;border-radius: 5px;overflow: hidden ">
                    <div class="card-body p-0 ">
                        <div class="clearfix ">
                            <div class="p-20 pb-30">
                                <h5 class="card-text text-light mb-10">عدد المدرسين </h5>
                                <h4 class="text-light">{{ $data['TeacherCount'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                <div class="card card-statistics h-100" style="background: #0361e7 ;border-radius: 5px;overflow: hidden ">
                    <div class="card-body p-0 ">
                        <div class="clearfix ">
                            <div class="p-20 pb-30">
                                <h5 class="card-text text-light mb-10">عدد الطلاب </h5>
                                <h4 class="text-light">{{ $data['StudentCount'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-30">
                <div class="card card-statistics ">
                    <div class="card-body">
                        <form method="post" action="{{ route('Notifications') }}" autocomplete="off">
                            @csrf
                            <h3 style="font-family: 'Cairo', sans-serif;">عمل اشعار</h3><br>
                            <div class="row">
                                <div class="col-lg-4 col-md-6 mb-10">
                                    <label for="college_id">الكلية
                                        <span class="text-danger">*
                                            @error('college_id')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <select class="form-control h-65" name="college_id" aria-placeholder="اختر كلية"
                                        required>
                                        <option value="" disabled selected>اختر كلية من القائمة</option>
                                        <option value="0" selected>الكل</option>
                                        @forelse (\App\Models\College::get() as $data)
                                            <option value="{{ $data['id'] }}"
                                                @if ($data->id == old('college_id')) selected @endif>
                                                {{ $data['name'] }}</option>
                                        @empty
                                            <option value="">لا يوجد كليات</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-10">
                                    <label for="specialization_id">التخصص
                                        <span class="text-danger">*
                                            @error('specialization_id')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <select class="form-control h-65" name="specialization_id" aria-placeholder="اختر تخصص"
                                        required>
                                        <option value="" disabled selected>اختر تخصص من القائمة</option>
                                        <option value="0" selected>الكل</option>
                                        @forelse (\App\Models\Specialization::get() as $data)
                                            <option value="{{ $data['id'] }}"
                                                @if ($data->id == old('specialization_id')) selected @endif>
                                                {{ $data['name'] }}</option>
                                        @empty
                                            <option value="">لا يوجد تخصصات</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-10">
                                    <label for="specialization_id">الطلاب
                                        <span class="text-danger">*
                                            @error('specialization_id')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <select searchable class="form-control h-65" name="Student_id"
                                        aria-placeholder="اختر طالب" required>
                                        <option value="" disabled selected>اختر طالب من القائمة</option>
                                        <option value="0" selected>الكل</option>
                                        @forelse (\App\Models\Student::get() as $data)
                                            <option value="{{ $data['id'] }}"
                                                @if ($data->id == old('Student_id')) selected @endif>
                                                {{ $data['full_name'] }}</option>
                                        @empty
                                            <option value="">لا يوجد طلاب</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-lg-12 col-md-12 mb-10">
                                    <label for="specialization_id">محتوى الاشعار
                                        <span class="text-danger">*
                                            @error('NoticeContent')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </label>
                                    <textarea class="form-control" name="NoticeContent" id="NoticeContent" cols="10" rows="3" required>

                                   </textarea>
                                </div>
                            </div>
                            <button class="btn btn-primary btn-sm nextBtn btn-lg pull-right" type="submit">ارسال</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-30">
                <div class="card card-statistics ">
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th>التقويم الجامعي</th>
                                <th>
                                    <button type="button" class="btn btn-primary btn-sm"
                                       data-toggle="modal" data-target="#ShowImageUniversityCalendar">
                                        عرض
                                    </button>
                                </th>
                                <th>
                                    <button type="button" class="btn btn-primary btn-sm"
                                         data-toggle="modal" data-target="#UpdateImageUniversityCalendar">
                                        تعديل
                                    </button>
                                </th>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-5 mb-30">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">نسبة احصائيات الطلاب</h5>
                        <div class="chart-wrapper" style="width: 100%; margin: 0 auto;">
                            <div id="canvas-holder">
                                <canvas id="canvas6" width="550"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-7 mb-30">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">نسبة احصائيات الطلاب</h5>
                        <div class="chart-wrapper" style="width: 100%; margin: 0 auto;">
                            <div id="canvas-holder">
                                <canvas id="canvas7" width="550"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @include('page.ShowImageUniversityCalendar')
    @include('page.UpdateImageUniversityCalendar')
@endsection
