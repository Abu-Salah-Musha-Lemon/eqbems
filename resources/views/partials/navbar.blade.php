<div class="page-main-header">
    <div class="main-header-right row m-0">

        <!-- Logo -->
        <div class="main-header-left">
            <div class="logo-wrapper">
                <a href="{{ route('dashboard') }}">
                    <img class="img-fluid" src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo">
                </a>
            </div>

            <div class="dark-logo-wrapper">
                <a href="{{ route('dashboard') }}">
                    <img class="img-fluid" src="{{ asset('assets/images/logo/dark-logo.png') }}" alt="Dark Logo">
                </a>
            </div>

            <div class="toggle-sidebar">
                <i class="status_toggle middle" data-feather="align-center" id="sidebar-toggle"></i>
            </div>
        </div>

        <!-- Search -->
        <div class="left-menu-header col">
            <form class="form-inline search-form">
                <div class="search-bg">
                    <i class="fa fa-search"></i>
                    <input class="form-control-plaintext" type="search" placeholder="Search...">
                </div>
            </form>
        </div>

        <!-- Right Menu -->
        <div class="nav-right col pull-right right-menu p-0">
            <ul class="nav-menus">

                <!-- Notifications -->
                <li class="onhover-dropdown">
                    <div class="notification-box">
                        <i data-feather="bell"></i>
                        <span class="badge badge-danger">3</span>
                    </div>

                    <ul class="notification-dropdown onhover-show-div">
                        <li>
                            <p class="mb-0 fw-bold">
                                Notifications
                            </p>
                        </li>

                        <li>
                            <a href="#">
                                New user registered.
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                Exam schedule updated.
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                New result published.
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Dark Mode -->
                <li>
                    <div class="mode">
                        <i class="fa fa-moon-o"></i>
                    </div>
                </li>

                <!-- Profile -->
                <li class="onhover-dropdown">
                    <div class="media profile-media">
                        <img class="rounded-circle" width="35"
                            src="{{ asset('assets/images/user/user.png') }}"
                            alt="Profile">

                        <div class="media-body ms-2">
                            <span>{{ auth()->user()->name ?? 'Admin' }}</span>
                        </div>
                    </div>

                    <ul class="profile-dropdown onhover-show-div">
                        <li>
                            <a href="{{ route('profile.edit') }}">
                                <i data-feather="user"></i>
                                Profile
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i data-feather="log-out"></i>
                                Logout
                            </a>

                            <form id="logout-form"
                                  action="{{ route('logout') }}"
                                  method="POST"
                                  class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>

        <div class="d-lg-none mobile-toggle pull-right w-auto">
            <i data-feather="more-horizontal"></i>
        </div>

    </div>
</div>