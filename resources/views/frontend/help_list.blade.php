@extends('frontend.layout.main')
@section('header_detail_content')
    <h1>{{!empty($categoryHelp)?$categoryHelp->ch_name:'Tất cả tin tức'}}</h1>
    <ul class="bread-crumb clearfix">
        <li><a href="{{route('frontend.index')}}">Trang chủ</a></li>
        <li>{{!empty($categoryHelp)?$categoryHelp->ch_name:'Tất cả tin tức'}}</li>
    </ul>
@endsection
@section('content')

    <div class="sidebar-page-container">
        <div class="auto-container">
            <div class="row clearfix">

                <div class="services-column col-lg-9 col-md-12 col-sm-12">
                    <div class="inner-content">

                        <div class="row clearfix">
                            <!-- Service Block -->
                            @foreach($postList as $key => $value)
                            <div class="service-block col-lg-4 col-md-6 col-sm-12">
                                <div class="inner-box">
                                    <div class="image-box">
                                        <figure class="image"><a href="{{route('frontend.helpDetail',$value->id)}}"><img src="{{asset('/uploads/imgHelp/'.$value->h_thunbar)}}" alt=""></a></figure>
                                    </div>
                                    <div class="lower-content">
                                        <h5 ><a href="{{route('frontend.helpDetail',$value->id)}}" style="overflow: hidden;
                                        display: -webkit-box;
                                        -webkit-line-clamp: 2;
                                        -webkit-box-orient: vertical;">{{$value->h_name}}</a></h5>
                                        <div class="text " style="overflow: hidden;
                                        display: -webkit-box;
                                        -webkit-line-clamp: 3;
                                        -webkit-box-orient: vertical;">{!! $value->h_description !!}</div>
{{--                                        <div class="link-box"><a href="{{route('frontend.blogDetail',$value->id)}}">Xem thêm</a></div>--}}
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>
                        <div style="position: relative">
                            <div style=" position: absolute;top: 50%; right: 50%;transform: translate(50%,-50%);">
                                {{$postList->links()}}
                            </div>
                        </div>

                    </div>
                </div>

                <!--Sidebar Side-->
                <div class="sidebar-side col-xl-3 col-lg-4 col-md-12 col-sm-12">
                    <aside class="sidebar">

{{--                        <!-- Search -->--}}
{{--                        <div class="sidebar-widget search-box">--}}
{{--                            <form method="post" action="contact.html">--}}
{{--                                <div class="form-group">--}}
{{--                                    <input type="search" name="search-field" value="" placeholder="Tìm kiếm ..." required="">--}}
{{--                                    <button type="submit"><span class="icon flaticon-search"></span></button>--}}
{{--                                </div>--}}
{{--                            </form>--}}
{{--                        </div>--}}

                        <!--Blog Category Widget-->
                        <div class="sidebar-widget sidebar-blog-category">
                            <div class="sidebar-title">
                                <h4>Danh mục</h4>
                            </div>
                            <ul class="cat-list">
                                @foreach($cateHelp as $key => $value)
                                    <li>
                                        <a href="{{route('frontend.helpCate',$value->id)}}">{{$value->ch_name}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="sidebar" style="margin-top: 40px">
                            <div class="sidebar-widget popular-posts">
                                <div class="sidebar-title">
                                    <h4>Sản phẩm nổi bật</h4>
                                </div>
                                @foreach($productHot as $key => $value)
                                    <article class="post">

                                        <div class="row">
                                            <div class="col-md-4" style="padding-right: 0">
                                                <img src="{{asset('/uploads/imgProduct/'.$value->prd_thunbar)}}" alt="">
                                            </div>
                                            <div class="col-md-8">
                                                <div class="text"><a href="{{route('frontend.productDetail',$value->id)}}">{!! $value->prd_name !!}</a></div>
                                            </div>
                                        </div>
                                    </article>

                                @endforeach
                            </div>
                        </div>


                    </aside>
                </div>
            </div>
        </div>
    </div>
@endsection