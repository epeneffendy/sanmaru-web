<!-- START TOP -->
<div id="top" class="clearfix">

    <!-- Start App Logo -->
    <div class="applogo">
        <img src="/img/profile.png" alt="img-profile"><b>{{ Auth::user()->name }}</b>
    </div>
    <!-- End App Logo -->

    <!-- Start Sidebar Show Hide Button -->
    <a href="#" class="sidebar-open-button"><i class="fa fa-bars"></i></a>
    <a href="#" class="sidebar-open-button-mobile"><i class="fa fa-bars"></i></a>
    <!-- End Sidebar Show Hide Button -->

    <!-- Start Top Right -->
    <ul class="top-right">

        <li class="dropdown link">
        <a href="#" data-toggle="dropdown" class="dropdown-toggle profilebox"><img src="/img/profileimg.png" alt="img"><b>{{ Auth::user()->name }}</b><span class="caret"></span></a>
            <ul class="dropdown-menu dropdown-menu-list dropdown-menu-right">
                <li><a href="#"><i class="fa falist fa-wrench"></i>Profile</a></li>
                <li class="divider"></li>
                <li><a href="{{ route('logout') }}"><i class="fa falist fa-power-off"></i> Logout</a></li>
            </ul>
        </li>

    </ul>
    <!-- End Top Right -->

</div>
<!-- END TOP -->
