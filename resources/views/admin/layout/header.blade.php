<div class="page-header -i navbar navbar-fixed-top" style="position: relative;">
    <div class="page-header-inner">
        <div class="page-logo">
            <a href="index.html">
            {{--<img src="admin/images/logo.png" alt="logo" class="logo-default"/>--}}
            </a>
            <div class="menu-toggler sidebar-toggler hide">
            </div>
        </div>
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-bs-toggle="collapse" data-bs-target=".navbar-collapse">
        </a>
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <li class="dropdown dropdown-user">
                    <a href="javascript:;" class="dropdown-toggle" data-bs-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                    <img alt="" class="img-circle" src="admin/images/avatar3.jpg"/>
                    @if(Auth::check())
    <span class="username username-hide-on-mobile">
        {{ Auth::user()->name }}
    </span>
@else
    <span class="username username-hide-on-mobile">Khách</span>
@endif
                    <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                       {{--  <li>
                            <a href="extra_profile.html">
                            <i class="icon-user"></i> My Profile </a>
                        </li> --}}
                        
                        <li>
                            <a href="{{url('logoutAdmin')}}">
                            <i class="icon-key"></i> Log Out </a>
                        </li>
                    </ul>
                </li>
               
            </ul>
        </div>
    </div>
</div>
