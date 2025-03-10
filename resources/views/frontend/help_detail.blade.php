@extends('frontend.layout.main')
@section('header_detail_content')
    <h1>{{\App\Models\CategoryHelp::where('id',$postDetail->h_category_id)->value('ch_name')}}</h1>
    <ul class="bread-crumb clearfix">
        <!--<li><a href="{{route('frontend.index')}}">Trang chủ</a></li>-->
        <li>{!! $postDetail->h_name !!}</li>
    </ul>
@endsection
@section('content')

    <div class="sidebar-page-container">
        <div class="auto-container">
            <div class="row clearfix">

                <div class="content-side col-xl-9 col-lg-8 col-md-12 col-sm-12">
                    <div class="blog-single padding-right">
                        <!--News Block Two-->
                        <div class="news-block-two">
                            <div class="inner-box">
                                <div class="image-box">
                                    <figure class="image wow fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">
                                        <img src="{{asset('/uploads/imgHelp/'.$postDetail->h_thunbar)}}" alt="">
                                    </figure>
                                    <div class="posted-date">{{$postDetail->created_at}}</div>
                                </div>
                                <div class="lower-content">
                                    <h3>{!! $postDetail->h_name !!}</h3>
                                    <ul class="post-meta clearfix">
                                        <li><span class="fa fa-user"></span> By: Admin</li>
                                        <li><span class="fa fa-tag"></span>{{\App\Models\CategoryHelp::where('id',$postDetail->h_category_id)->value('ch_name')}}</li>
{{--                                        <li><span class="far fa-comments"></span> Comments: 7</li>--}}
                                    </ul>
                                    {!! $postDetail->h_content !!}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!--Sidebar Side-->
                <div class="sidebar-side col-xl-3 col-lg-4 col-md-12 col-sm-12">
                    <aside class="sidebar">


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