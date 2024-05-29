<div class="container-fluid">
    <div class="row">
        <!-- Left Sidebar start-->
        <div class="side-menu-fixed">
            <div class="scrollbar ">
                <ul class="nav navbar-nav side-menu " id="sidebarnav">
                    <!-- menu item Dashboard-->
                    <div class="d-flex justify-content-center align-items-center mt-10">
                        <div class="rounded-circle mb-20" style="width: 100px; height: 100px; overflow: hidden;">
                            <img class="img-fluid" src="{{ asset('assets/images/favicon.ico') }}" alt=""
                                style="object-fit: cover; width: 100%; height: 100%;">
                        </div>
                    </div>
                    <div style="text-align: center; " class="mb-20">
                        <h6 style="color: #ffffff; ">{{ auth()->user()->name }}</h6>
                    </div>
                    <li>
                        <a href="{{ route('home') }}">
                            <div class="pull-left"><i class=" ti-view-grid"></i><span class="right-nav-text">الرئيسية
                                </span>
                            </div>
                            <div class="clearfix"></div>
                        </a>
                    </li>
                    <!-- menu title -->
                    @if (auth()->user()->user_type == 'admin')
                        <li class="mt-10 mb-10 text-muted pl-4 font-medium menu-title">المستخدمين</li>
                        <li>
                            <a href="javascript:void(0);" data-toggle="collapse" data-target="#authentication">
                                <div class="pull-left"><i class="ti-id-badge"></i><span
                                        class="right-nav-text">المستخدمين</span></div>
                                <div class="pull-right"><i class="ti-plus"></i></div>
                                <div class="clearfix"></div>
                            </a>
                            <ul id="authentication" class="collapse" data-parent="#sidebarnav">
                                <li> <a href="{{ route('user.index') }}">قائمة المستخدمين</a> </li>
                                <li> <a href="{{ route('user.create') }}">اضافة مستخدم</a> </li>
                                <li> <a href="{{ route('Activity') }}">سجل الانشطة</a> </li>
                            </ul>
                        </li>
                    @endif
                    <li class="mt-10 mb-10 text-muted pl-4 font-medium menu-title">الادارة</li>
                    <li>
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#College">
                            <div class="pull-left"><i class="fa fa-bar-chart-o highlight-icon"></i><span
                                    class="right-nav-text">الكلية</span></div>
                            <div class="pull-right"><i class="ti-plus"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="College" class="collapse" data-parent="#sidebarnav">
                            <li><a href="{{ route('College.index') }}">عرض الكليات</a></li>
                            <li><a href="{{ route('Specialization.index') }}">عرض التخصصات</a></li>
                            <li><a href="{{ route('SchoolYear.index') }}">العام الدراسي</a></li>

                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#teacher">
                            <div class="pull-left"> <i class="ti-user"></i> <span
                                    class="right-nav-text">المدرسين</span></div>
                            <div class="pull-right"><i class="ti-plus"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="teacher" class="collapse" data-parent="#sidebarnav">
                            <li> <a href="{{ route('teacher.index') }}">قائمة المدرسين</a> </li>
                            <li> <a href="{{ route('teacher.create') }}">اضافة مدرس</a> </li>

                        </ul>
                    </li>
                    <li class="mt-10 mb-10 text-muted pl-4 font-medium menu-title">الطلاب</li>
                    <li>
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#Security-wanted">
                            <div class="pull-left"><i class="fa fa-graduation-cap highlight-icon"></i><span class="right-nav-text">
                                    الطلاب</span></div>
                            <div class="pull-right"><i class="ti-plus"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="Security-wanted" class="collapse" data-parent="#sidebarnav">
                            <li> <a href="{{ route('Student.create') }}">اضافة طالب</a> </li>
                            <li> <a href="{{ route('Student.index') }}">قائمة الطلاب</a> </li>
                            <li> <a href="{{ route('SemesterTask.index') }}">الاعمال الفصلية</a> </li>
                            <li> <a href="{{ route('Result.index') }}">النتيجة النهائية</a> </li>
                            <li> <a href="{{ route('Promotion.create') }}">ترقية الطلاب</a> </li>
                            <li><a href="{{ route('studentInquirie.index', ['inquirie_type' => 'inquiry']) }}">استفسارات الطلاب</a></li>
                            <li> <a href="{{ route('studentInquirie.index') }}">شكاوي الطلاب</a> </li>
                    </li>
                </ul>
                </li>
                <li> <a href="{{ route('library_Book.index') }}">
                        <div class="pull-left"><i class="ti-book"></i><span class="right-nav-text">
                                المكتبة</span></div>
                        <div class="clearfix"></div>
                    </a> </li>
                </li>
                <li> <a href="{{ route('College-New.index') }}">
                        <div class="pull-left"><i class="ti-text"></i><span class="right-nav-text">
                                اخر الاخبار</span></div>
                        <div class="clearfix"></div>
                    </a>
                </li>
                </ul>
            </div>
        </div>
