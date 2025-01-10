<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.header')
    @stack('header')
    <title>@yield('pageTitle')</title>
    <style>
        .loader {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            z-index: 9999;
        }

        .ring {
            display: inline-block;
            width: 64px;
            height: 64px;
            position: relative;
        }

        .ring:before,
        .ring:after {
            content: "";
            display: block;
            border: 4px solid #000;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            position: absolute;
            box-sizing: border-box;
            top: 0;
            left: 0;
            animation: ring-animation 1.5s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        }

        .ring:after {
            animation-delay: -0.75s;
        }

        @keyframes ring-animation {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .loader-span {
            display: block;
            margin-top: 10px;
        }
        .dropdown-container > a{
            padding-left: 30px !important;
        }
    </style>
</head>
<body>
<div class="loader" style="display: none;">
    <div class="ring"></div>
    <span class="loader-span">loading...</span>
</div>
<!-- Sidebar -->

@include('layouts.sidebar')

<!-- Topbar -->
<div class="topbar-section">
    <div class="topbar">
        <div class="left-side">
            <a href="#">@yield('pageTitle')</a>
        </div>
        <div class="right-side">
            <div class="desktop-notification">
{{--                <div class="notification-bell"><img src="./assets/img/notification.svg" alt=""></div>--}}
                @if(!auth()->user()->hasRole('admin'))
                    <div class="mt-3"><p> Rambaan Coin:<b>{{auth()->user()->rambaan_coin}}</b></p></div>
                    <div class="mt-3"><p> Brahmastra Coin:<b>{{auth()->user()->brahmastra_coin}}</b></p></div>
                @endif
                {{--                <div class="profile-dropdown">--}}
                {{--                    <a href="{{route('profile.edit')}}"> <img src="./assets/img/user outlined 1.svg" alt=""></a>--}}
                {{--                </div>--}}
                <div class="profile-dropdown">
                    <img src="{{asset(   '/assets/img/user outlined 1.svg')}}" alt="">
                    <div class="profile-notification">
                        <span>{{auth()->user()->name}}</span>
                        <button class="down-notification-btn" onclick="toggleDropdownbtn()"><img src="{{asset('/assets/img/expand_more.png')}}"></button>

                        <!-- SHOW TEAM MEMBER START -->

                        <div class="team-member-list-notification" id="myDropdown" style="display: none;">

                            <div class="team-member-details">
                                <div class="team-member-img">
                                    <img src="{{asset('assets/img/user outlined 1.svg')}}" alt="">
                                </div>
                                <div class="team-member-name">
                                    <a href="{{route('profile.edit')}}" style="text-decoration: none"><h4>Profile</h4></a>
                                </div>
                            </div>
                             <div class="team-member-details mt-3">
                                <div class="team-member-img">
                                    <img src="{{asset('assets/img/logout.svg')}}" alt="">
                                </div>
                                <div class="team-member-name">
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                                        <h4>Logout</h4>
                                    </a>
                                </div>
                            </div>

                        </div>

                        <!-- SHOW TEAM MEMBER END -->
                    </div>


                </div>
            </div>
        </div>

    </div>
</div>


<!-- Main Content -->

@yield('content')
<!-- Chartjs Script -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    /* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
    var dropdown = document.getElementsByClassName("drop");
    var i;
    for (i = 0; i < dropdown.length; i++) {
        dropdown[i].addEventListener("click", function() {
            this.classList.toggle("active");
            $(this).find('i').removeClass('fa fa-caret-left');
            $(this).find('i').addClass('fa fa-caret-down');
            var dropdownContent = this.nextElementSibling;
            if (dropdownContent.style.display === "block") {
                $(this).find('i').removeClass('fa fa-caret-down');
                $(this).find('i').addClass('fa fa-caret-left');
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "block";
            }
        });
    }

</script>
<script>
    @if(Session::has('success'))
    toastr.success("{{ Session::get('success') }}");
    toastr.options.timeOut = 10000;
    @endif
    @if(Session::has('info'))
    toastr.info("{{ Session::get('info') }}");
    toastr.options.timeOut = 10000;
    @endif
    @if(Session::has('warning'))
    toastr.warning("{{ Session::get('warning') }}");
    toastr.options.timeOut = 10000;
    @endif
    @if(Session::has('error'))
    toastr.error("{{ Session::get('error') }}");
    toastr.options.timeOut = 10000;
    @endif
</script>

<script
    type="text/javascript"
    src='{{asset("tinymce/js/tinymce/tinymce.min.js")}}'
    referrerpolicy="origin">
</script><script>
    tinymce.init({
        selector: 'textarea#content',
        menubar: 'file edit view insert format tools table help', // Add custom button to the menubar
        plugins: 'autolink lists link image charmap print preview code',
        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image',
    });
</script>
<script>
    function toggleDropdownbtn() {
        var dropdown = document.getElementById("myDropdown");
        if (dropdown.style.display === "block") {
            dropdown.style.display = "none";
        } else {
            dropdown.style.display = "block";
        }
    }
</script>
@stack('script')

</body>

</html>
