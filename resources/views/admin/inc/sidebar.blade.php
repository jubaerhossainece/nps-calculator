<!--- Sidemenu -->
<div id="sidebar-menu">

    <div class="user-box">

        <div class="float-left">
            <img src="{{auth()->user()->image ? Storage::url('public/admin/'. auth()->user()->image) : asset('assets/images/7074311_3551739.jpg')}}" alt="" class="avatar-md rounded-circle">
        </div>
        <div class="user-info">
            <div class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ auth()->user()->name }} <i class="mdi mdi-chevron-down"></i>
                </a>
                <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 29px, 0px); top: 0px; left: 0px; will-change: transform;">
                    <li><a href="{{route('admin.profile.show')}}" class="dropdown-item"><i class="mdi mdi-face-profile mr-2"></i> Profile<div class="ripple-wrapper"></div></a></li>
                    <li><a href="{{route('admin.password.edit')}}" class="dropdown-item"><i class="mdi mdi-settings mr-2"></i> Settings</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="mdi mdi-power-settings mr-2"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                        {{--                                    <a href="javascript:void(0)" class="dropdown-item"><i class="mdi mdi-power-settings mr-2"></i> Logout</a>--}}
                    </li>
                </ul>
            </div>
            <p class="font-13 text-muted m-0"> {{  strlen(auth()->user()->email) >= 15 ? substr( auth()->user()->email, 0, 2) . '...' . substr( auth()->user()->email, -10) : auth()->user()->email }} </p>
        </div>
    </div>

    <ul class="metismenu" id="side-menu">

        <li>
            <a href="{{ route('dashboard.index') }}" class="waves-effect">
            <i class="fas fa-columns"></i>
                <span> Dashboard </span>
            </a>
        </li>

        <li>
            
            <a href="{{route('users')}}"><i class="fas fa-users"></i> Users</a>
        </li>

        <li>
            
            <a href="{{route('abuse-reports')}}"><i class="fas fa-flag-checkered"></i> Report abuses </a>
        </li>
            
        <li>
            <a href="{{route('admin.profile.show')}}"><i class="fas fa-user-circle"></i> Profile</a>
        </li>
            
        <li>
            <a href="{{route('admin.password.edit')}}"><i class="fas fa-cog"></i> Settings</a>
        </li>
        <li>
            <a href="{{route('twoFa.index')}}"><i class="fas fa-shield"></i> 2FA Setting</a>
        </li>

        <li>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="dropdown-item notify-item">
                   <i class="fas fa-sign-out-alt mr-3"></i>
                    <span>Logout</span>
                </button>
            </form>
        </li>

    </ul>

</div>
<!-- End Sidebar -->
