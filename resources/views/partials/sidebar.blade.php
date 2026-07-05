<header class="main-nav">
    <div class="sidebar-user text-center">
        <a class="setting-primary" href="javascript:void(0)">
            <i data-feather="settings"></i>
        </a>

        <img class="img-90 rounded-circle"
            src="https://laravel.pixelstrap.com/viho/assets/images/dashboard/1.png"
            alt="">

        <div class="badge-bottom">
            <span class="badge badge-primary">New</span>
        </div>

        <a href="user-profile">
            <h6 class="mt-3 f-14 f-w-600">Emay Walter</h6>
        </a>

        <p class="mb-0 font-roboto">Administrator</p>
    </div>

    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow">
                <i data-feather="arrow-left"></i>
            </div>

            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">

                    <!-- Dashboard -->
                    <li class="dropdown">
                        <a class="nav-link menu-title active" href="javascript:void(0)">
                            <i data-feather="home"></i>
                            <span>Dashboard</span>
                        </a>

                        <ul class="nav-submenu menu-content" style="display:block;">
                            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        </ul>
                    </li>

                    <!-- Users -->
                    <li class="dropdown">
                        <a class="nav-link menu-title" href="javascript:void(0)">
                            <i data-feather="users"></i>
                            <span>Users</span>
                        </a>

                        <ul class="nav-submenu menu-content">
                            <li><a href="#">User List</a></li>
                            <li><a href="#">Add User</a></li>
                            <li><a href="#">Roles</a></li>
                        </ul>
                    </li>

                    <!-- Institutions -->
                    <li class="dropdown">
                        <a class="nav-link menu-title" href="javascript:void(0)">
                            <i data-feather="briefcase"></i>
                            <span>Institutions</span>
                        </a>

                        <ul class="nav-submenu menu-content">
                            <li><a href="#">Institution List</a></li>
                        </ul>
                    </li>

                    <!-- Academic -->
                    <li class="dropdown">
                        <a class="nav-link menu-title" href="javascript:void(0)">
                            <i data-feather="book-open"></i>
                            <span>Academic</span>
                        </a>

                        <ul class="nav-submenu menu-content">
                            <li><a href="#">Class</a></li>
                            <li><a href="#">Section</a></li>
                            <li><a href="#">Subject</a></li>
                            <li><a href="#">Session</a></li>
                        </ul>
                    </li>

                    <!-- Question Bank -->
                    <li class="dropdown">
                        <a class="nav-link menu-title" href="javascript:void(0)">
                            <i data-feather="file-text"></i>
                            <span>Question Bank</span>
                        </a>

                        <ul class="nav-submenu menu-content">
                            <li><a href="#">Questions</a></li>
                            <li><a href="#">Categories</a></li>
                        </ul>
                    </li>

                    <!-- Blueprint -->
                    <li class="dropdown">
                        <a class="nav-link menu-title" href="javascript:void(0)">
                            <i data-feather="grid"></i>
                            <span>Blueprint</span>
                        </a>

                        <ul class="nav-submenu menu-content">
                            <li><a href="#">Blueprint List</a></li>
                        </ul>
                    </li>

                    <!-- Examination -->
                    <li class="dropdown">
                        <a class="nav-link menu-title" href="javascript:void(0)">
                            <i data-feather="clipboard"></i>
                            <span>Examination</span>
                        </a>

                        <ul class="nav-submenu menu-content">
                            <li><a href="#">Exam List</a></li>
                            <li><a href="#">Schedule</a></li>
                        </ul>
                    </li>

                    <!-- Result -->
                    <li class="dropdown">
                        <a class="nav-link menu-title" href="javascript:void(0)">
                            <i data-feather="award"></i>
                            <span>Result</span>
                        </a>

                        <ul class="nav-submenu menu-content">
                            <li><a href="#">Publish Result</a></li>
                            <li><a href="#">Result List</a></li>
                        </ul>
                    </li>

                    <!-- Reports -->
                    <li class="dropdown">
                        <a class="nav-link menu-title" href="javascript:void(0)">
                            <i data-feather="bar-chart-2"></i>
                            <span>Reports</span>
                        </a>

                        <ul class="nav-submenu menu-content">
                            <li><a href="#">Exam Report</a></li>
                            <li><a href="#">Student Report</a></li>
                        </ul>
                    </li>

                    <!-- Settings -->
                    <li class="dropdown">
                        <a class="nav-link menu-title" href="javascript:void(0)">
                            <i data-feather="settings"></i>
                            <span>Settings</span>
                        </a>

                        <ul class="nav-submenu menu-content">
                            <li><a href="#">General Settings</a></li>
                            <li><a href="#">Profile</a></li>
                        </ul>
                    </li>

                </ul>
            </div>

            <div class="right-arrow" id="right-arrow">
                <i data-feather="arrow-right"></i>
            </div>
        </div>
    </nav>
</header>