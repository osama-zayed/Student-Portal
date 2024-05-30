        <!--=================================
 header start-->
        <nav class="admin-header navbar navbar-default col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <!-- logo -->
            <div class="text-left navbar-brand-wrapper">
                <a class="navbar-brand brand-logo" href="{{ route('home') }}"><img src="assets/images/logo-dark.png"
                        alt=""></a>
                <a class="navbar-brand brand-logo-mini" href="{{ route('home') }}"><img
                        src="{{ asset('assets/images/logo-icon-dark.png') }}" alt=""></a>
            </div>
            <!-- Top bar  -->
            <ul class="nav navbar-nav mr-auto">
                <li class="nav-item">
                    <a id="button-toggle" class="button-toggle-nav inline-block ml-20 pull-left"
                        href="javascript:void(0);"><i class="zmdi zmdi-menu ti-align-right"></i></a>
                </li>
                <li class="nav-item">
                    <div class="search">
                        <a class="search-btn not_click" href="javascript:void(0);"></a>
                        <form action="{{ route('searchById') }}" method="GET">
                            <div class="search-box not-click">
                                <input type="text" class="not-click form-control" placeholder="بحث" value=""
                                    name="search" required>
                                <button class="search-button" type="submit"><i
                                        class="fa fa-search not-click"></i></button>
                            </div>
                        </form>
                    </div>
                </li>
            </ul>
            <!-- top bar right -->
            
            <ul class="nav navbar-nav ml-auto">
                <li class="nav-item dropdown ">
                <li class="nav-item fullscreen">
                    <a id="btnFullscreen" href="#" class="nav-link"><i class="ti-fullscreen"></i></a>
                </li>
                <li class="nav-item dropdown mr-30">
                    <a class="nav-link nav-pill user-avatar" data-toggle="dropdown" href="#" role="button"
                        aria-haspopup="true" aria-expanded="false">
                        <img src="{{ asset('assets/images/favicon.ico') }}" alt="avatar">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header">
                            <div class="media">
                                <div class="media-body">
                                    <h5 class="mt-0 mb-0">{{ auth()->user()->name }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('user.show', auth()->user()->id) }}"><i
                                class="text-secondary ti-reload"></i>سجل أنشطتي</a>
                        <a class="dropdown-item" href="{{ route('user.edit', auth()->user()->id) }}"><i
                                class="text-warning ti-user"></i>الملف الشخصي</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item"href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();"><i
                                class="text-danger ti-unlock"></i>تسجيل الخروج</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </nav>

        <!--=================================
 header End-->
