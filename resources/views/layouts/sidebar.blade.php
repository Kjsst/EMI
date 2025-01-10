<div class="sidebar-sec">
    <div class="toogle-btn mob-navbar">
        <div class="toggle-logo">
            <button id="toggle-btn" onclick="toggleSidebar()">☰</button>
            <img src="{{asset('assets/img/logo.jpg')}}" alt="" width="50px" height="50px">
        </div>
        <div class="not-dropdown" style="display: none;">
            <div class="notification-bell"><img src="{{asset('assets/img/notification.svg')}}" alt=""></div>
            <div class="profile-dropdown">
                <img src="{{asset('assets/img/user outlined 1.svg')}}" alt="">
            </div>
        </div>
    </div>
    <div id="sidebar">
        <img src="{{asset('assets/img/logo.jpg')}}" alt="">
        <div class="sidebar-item">
            <a href="{{route('dashboard')}}"
               @if(\Illuminate\Support\Facades\Request::segment(1) == "dashboard") class="active" @endif><img
                    src="{{asset('assets/img/home.svg')}}" alt=""> Dashboard</a>

            @if(auth()->user()->hasRole('admin'))
                {{--<a href="{{route('setting')}}"  @if(\Illuminate\Support\Facades\Request::segment(1) == "setting") class="active" @endif><img src="{{asset('assets/img/settings 1.svg')}}" alt=""> Setting</a>--}}

                <a
                    @if(
                        \Illuminate\Support\Facades\Request::segment(1) == "setting"
                        || \Illuminate\Support\Facades\Request::segment(1) == "qrlsetting"
                    ) class="active drop" @else class="drop" @endif
                ><img src="{{asset('assets/img/settings 1.svg')}}" alt="">
                    Settings
                    <i style=" padding-left: 50%;" class="fa fa-caret-left"></i>
                </a>
                <div class="dropdown-container" style="display: none">
                    <a href="{{route('setting')}}"
                       @if(\Illuminate\Support\Facades\Request::segment(1) == "setting") class="active" @endif><i
                            class="fa fa-caret-right"></i>Setting</a>
                    <a href="{{route('qrlsetting')}}"
                       @if(\Illuminate\Support\Facades\Request::segment(1) == "qrlsetting") class="active" @endif><i
                            class="fa fa-caret-right"></i>QRL Setting</a>
                </div>

                <a href="{{route('banner')}}"
                   @if(\Illuminate\Support\Facades\Request::segment(1) == "banner") class="active" @endif><img
                        src="{{asset('assets/img/home.svg')}}" alt=""> Banner
                </a>
                {{--                <a href="{{route('device')}}"  @if(\Illuminate\Support\Facades\Request::segment(1) == "device") class="active" @endif><img src="{{asset('assets/img/device.png')}}" alt=""> device</a>--}}
            @endif
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('super admin'))
                <a href="{{route('device.list')}}"
                   @if(\Illuminate\Support\Facades\Request::segment(1) == "device") class="active" @endif><img
                        src="{{asset('assets/img/device.png')}}" alt=""> Device</a>
            @endif
            @if(auth()->user()->hasRole('admin'))
                <a
                    @if(
                        \Illuminate\Support\Facades\Request::segment(2) == "contact-us"
                        || \Illuminate\Support\Facades\Request::segment(2) == "tollfreenumber"
                        || \Illuminate\Support\Facades\Request::segment(2) == "terms"
                        || \Illuminate\Support\Facades\Request::segment(2) == "privacy-policy"
                        || \Illuminate\Support\Facades\Request::segment(2) == "about-us"
                    ) class="active drop" @else class="drop" @endif
                ><img src="{{asset('assets/img/pages.png')}}" alt="">
                    Pages
                    <i style=" padding-left: 56%;" class="fa fa-caret-left"></i>
                </a>
                <div class="dropdown-container" style="display: none">
                    <a href="{{route('contact')}}"
                       @if(\Illuminate\Support\Facades\Request::segment(2) == "contact-us") class="active" @endif><i
                            class="fa fa-caret-right"></i>Contact</a>
                    <a href="{{route('tollfreenumber')}}"
                       @if(\Illuminate\Support\Facades\Request::segment(2) == "tollfreenumber") class="active" @endif><i
                            class="fa fa-caret-right"></i>Toll Free Number</a>
                    <a href="{{route('terms')}}"
                       @if(\Illuminate\Support\Facades\Request::segment(2) == "terms") class="active" @endif><i
                            class="fa fa-caret-right"></i>Terms & Condition</a>
                    <a href="{{route('privacy')}}"
                       @if(\Illuminate\Support\Facades\Request::segment(2) == "privacy-policy") class="active" @endif><i
                            class="fa fa-caret-right"></i>Privacy Policy</a>
                    <a href="{{route('aboutUs')}}"
                       @if(\Illuminate\Support\Facades\Request::segment(2) == "about-us") class="active" @endif><i
                            class="fa fa-caret-right"></i>About Us</a>
                </div>
            @endif
            {{--            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('employee'))--}}
            <a
                @if(
                    \Illuminate\Support\Facades\Request::segment(1) == "customer-report"
                ) class="active drop" @else class="drop" @endif
            ><img src="{{asset('assets/img/report.png')}}" alt="">
                Report
                <i style=" padding-left: 56%;" class="fa fa-caret-left"></i>
            </a>
            <div class="dropdown-container" style="display: none">
                <a href="{{route('customer.report')}}"
                   @if(\Illuminate\Support\Facades\Request::segment(1) == "customer-report") class="active" @endif><i
                        class="fa fa-caret-right"></i>Account Report</a>
                {{--                <a href="{{route('terms')}}"--}}
                {{--                   @if(\Illuminate\Support\Facades\Request::segment(2) == "coin") class="active" @endif><i--}}
                {{--                        class="fa fa-caret-right"></i></a>--}}
            </div>
            {{--            @endif--}}
            <a
                @if(
                    \Illuminate\Support\Facades\Request::segment(1) == "user"
                    ||\Illuminate\Support\Facades\Request::segment(1) == "merchant"
                    || \Illuminate\Support\Facades\Request::segment(1) == "customer"
                    || \Illuminate\Support\Facades\Request::segment(1) == "customer-reports"
                    || \Illuminate\Support\Facades\Request::segment(1) == "master-distributor"
                    || \Illuminate\Support\Facades\Request::segment(1) == "distributor"
                    || \Illuminate\Support\Facades\Request::segment(1) == "employee"
                ) class="active drop" @else class="drop" @endif
            ><img src="{{asset('assets/img/users.png')}}" alt="">
                Users
                <i style=" padding-left: 56%;" class="fa fa-caret-left"></i>
            </a>
            <div class="dropdown-container" style="display: none">
                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('super admin'))
                    <a href="{{route('user.create')}}"
                       @if(\Illuminate\Support\Facades\Request::segment(1) == "user") class="active" @endif><i
                            class="fa fa-caret-right"></i> Create User</a>
                    <a href="{{route('super-admin')}}"
                       @if(\Illuminate\Support\Facades\Request::segment(1) == "super-admin") class="active" @endif><i
                            class="fa fa-caret-right"></i> Super admin</a>
                    <a href="{{route('employees')}}"
                       @if(\Illuminate\Support\Facades\Request::segment(1) == "employee") class="active" @endif><i
                            class="fa fa-caret-right"></i> Employee</a>
                @endif
                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('super admin') || auth()->user()->hasRole('employee'))
                    <a href="{{route('masterDistributors')}}"
                       @if(\Illuminate\Support\Facades\Request::segment(1) == "master-distributor") class="active" @endif><i
                            class="fa fa-caret-right"></i> Master Distributors</a>
                @endif
                @if(auth()->user()->hasRole('master distributor') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('employee'))
                    <a href="{{route('distributors')}}"
                       @if(\Illuminate\Support\Facades\Request::segment(1) == "distributor") class="active" @endif><i
                            class="fa fa-caret-right"></i> Distributors</a>
                @endif
                @if(auth()->user()->hasRole('distributor') || auth()->user()->hasRole('admin'))
                    <a href="{{route('merchant')}}"
                       @if(\Illuminate\Support\Facades\Request::segment(1) == "merchant") class="active" @endif><i
                            class="fa fa-caret-right"></i> Merchants</a>
                @endif
                @if(auth()->user()->hasRole('admin'))
                    <a href="{{route('customer')}}"
                       @if(\Illuminate\Support\Facades\Request::segment(1) == "customer") class="active" @endif><i
                            class="fa fa-caret-right"></i> Customers</a>
                @endif
            </div>

            @if(!auth()->user()->hasRole('employee'))
                {{--<a href="{{route('coins')}}"
                   @if(\Illuminate\Support\Facades\Request::segment(1) == "coins") class="active" @endif><img
                        src="{{asset('assets/img/coins (1) 1.svg')}}" alt=""> Coins
                </a>--}}

                <a
                    @if(
                        \Illuminate\Support\Facades\Request::segment(1) == "coins"
                        || \Illuminate\Support\Facades\Request::segment(1) == "coinoffer"
                    ) class="active drop" @else class="drop" @endif
                ><img src="{{asset('assets/img/coins (1) 1.svg')}}" alt="">
                    Coins
                    <i style=" padding-left: 56%;" class="fa fa-caret-left"></i>
                </a>
                <div class="dropdown-container" style="display: none">
                    <a href="{{route('coins')}}"
                       @if(\Illuminate\Support\Facades\Request::segment(1) == "coins") class="active" @endif><i
                            class="fa fa-caret-right"></i>Transfer Coins</a>
                    <a href="{{route('coinoffer')}}"
                       @if(\Illuminate\Support\Facades\Request::segment(1) == "coinoffer") class="active" @endif><i
                            class="fa fa-caret-right"></i>Coins Offer</a>
                </div>
            @endif
            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                <img src="{{asset('assets/img/logout.svg')}}" alt="">
                Logout Account
            </a>
        </div>
    </div>
</div>
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
