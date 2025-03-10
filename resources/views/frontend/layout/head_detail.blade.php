{{--<!-- Preloader -->--}}
{{--<div class="preloader"></div>--}}

<!-- Main Header -->
<header class="main-header">
    <div class="header-top">
        <div class="auto-container clearfix">
            <div class="top-left pull-left">
                <div class="text">{!! $settingOptions['slogan'] !!}</div>
            </div>

            <div class="top-right pull-right">
                <div class="text">Liên hệ : <a href="tel:{!! $settingOptions['telephone'] !!}">{!! $settingOptions['telephone'] !!}</a></div>
            </div>
        </div>
    </div>

    <!-- Header Upper -->
    <div class="header-lower">
        <div class="auto-container">
            <div class="inner-container clearfix">
                <div class="9d_logo-outer clearfix">
                    <div class="9d_logo"><a href="{{route('frontend.index')}}"><img src="/frontend/images/9d_9d_logo.png" alt="" title=""></a></div>
                </div>

                <div class="nav-outer clearfix">
                    <!--Mobile Navigation Toggler-->
                    <div class="mobile-nav-toggler"><span class="icon flaticon-menu-button"></span></div>

                    <div class="nav-inner">
                        <!-- Main Menu -->
                        <nav class="main-menu navbar-expand-md navbar-light">
                            <div class="navbar-header">
                                <!-- Togg le Button -->
                                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="icon fa fa-bars"></span>
                                </button>
                            </div>

                            <div class="collapse navbar-collapse clearfix" id="navbarSupportedContent">
                                <ul class="navigation clearfix">
                                    <li class=" dropdown"><a
                                                href="{{route('frontend.index')}}">Trang chủ</a>
                                    </li>
                                    <li class="dropdown "><a href="{{route('frontend.productCate',0)}}">Sản phẩm</a>
                                        <ul>
                                            <li>
                                                <a href="{{route('frontend.productCate',0)}}">Tất cả sản phẩm</a>
                                            </li>
                                            @foreach($cateProduct as $key => $value)
                                                <li>
                                                    <a href="{{route('frontend.productCate',$value->id)}}">{{$value->cpr_name}}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>

                                    <li class="dropdown "><a
                                                href="{{route('frontend.blogCate',0)}}">Tin tức</a>
                                        <ul>
                                            <li>
                                                <a href="{{route('frontend.blogCate',0)}}">Tất cả tin tức</a>
                                            </li>
                                            @foreach($cateBlog as $key => $value)
                                                <li>
                                                    <a href="{{route('frontend.blogCate',$value->id)}}">{{$value->cpo_name}}</a>
                                                </li>
                                            @endforeach

                                        </ul>
                                    </li>
                                    <li class="dropdown"><a
                                                href="{{route('frontend.helpCate',0)}}">Hỗ trợ</a>
                                        <ul>
                                            <li>
                                                <a href="{{route('frontend.helpCate',0)}}">Tất cả danh mục</a>
                                            </li>
                                            @foreach($cateHelp as $key => $value)
                                                <li>
                                                    <a href="{{route('frontend.helpCate',$value->id)}}">{{$value->ch_name}}</a>
                                                </li>
                                            @endforeach

                                        </ul>
                                    </li>
                                    <li class="dropdown "><a href="{{route('frontend.blogCate',\App\Models\CategoryBlog::where('fix',1)->value('id'))}}">Công trình</a>

                                    </li>
                                </ul>
                            </div>
                        </nav>
                        <!-- Main Menu End-->

                        <div class="outer-box">
                            <!--Search Box-->
                            <div class="search-box-outer">
                                <div class="dropdown">
                                    <button class="search-box-btn dropdown-toggle" type="button" id="dropdownMenu3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="flaticon-search"></span></button>
                                    <ul class="dropdown-menu pull-right search-panel" aria-labelledby="dropdownMenu3">
                                        <li class="panel-outer">
                                            <div class="form-container">
                                                <form action="{{route('frontend.search')}}" method="GET">
                                                    <div class="form-group">
                                                        <input type="search" name="name" value="{{ Request::get('name') }}" placeholder="Tên sản phẩm..." required>
                                                        <button type="submit" class="search-btn"><span class="fa fa-search"></span></button>
                                                    </div>
                                                </form>

                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End Header Upper-->

    <!-- Mobile Menu  -->
    <div class="mobile-menu">
        <div class="menu-backdrop"></div>
        <div class="close-btn"><span class="icon flaticon-cancel-music"></span></div>

        <!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header-->
        <nav class="menu-box">
            <div class="nav-9d_logo"><a href="{{route('frontend.index')}}"><img src="/frontend/images/9d_logo.png" alt="" title=""></a></div>

            <ul class="navigation clearfix"><!--Keep This Empty / Menu will come through Javascript--></ul>
        </nav>
    </div><!-- End Mobile Menu -->

</header>
<!-- End Main Header -->

<!--Page Title-->
<section class="page-title" style="background-image:url(/frontend/images/background/6.jpg);">
    <div class="auto-container">
        @yield('header_detail_content')

        <div class="image-box wow fadeInRight" data-wow-delay="600ms"><figure class="image"><img src="/frontend/images/resource/laptop.png" alt=""></figure></div>
    </div>
</section>
<!--End Page Title-->