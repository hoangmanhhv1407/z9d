@extends('frontend.layout.main')
@section('header_detail_content')
    <h1>{{\App\Models\CategoryProduct::where('id',$productDetail->prd_category_product_id)->value('cpr_name')}}</h1>
    <ul class="bread-crumb clearfix">
        <li><a href="{{route('frontend.index')}}">Trang chủ</a></li>
        <li>{!! $productDetail->prd_name !!}</li>
    </ul>
@endsection
@section('content')
    <!-- Mission Section -->
    <section class="mission-section alternate services-section">
        <div class="auto-container">
            <div class="row clearfix">
                <div class="btns-column col-lg-3 col-md-12 col-sm-12">
                    <!--Tabs Box-->
                    <ul class="buttons-list clearfix">
                        <li>
                            <a href="{{route('frontend.productCate',0)}}">Tất cả sản phẩm</a>
                        </li>
                        @foreach($cateProduct as $key => $value)
                            <li>
                                <a href="{{route('frontend.productCate',$value->id)}}">{{$value->cpr_name}}</a>
                            </li>
                        @endforeach
                    </ul>
                    <!-- Popular Posts -->
                    <div class="sidebar" style="margin-top: 40px">
                        <div class="sidebar-widget popular-posts">
                            <div class="sidebar-title"><h3>Sản phẩm nổi bật</h3></div>
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
                    @include('frontend.layout.contact')

                </div>

                <div class="services-column col-lg-9 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="image-column col-lg-4 col-md-12 col-sm-12">
                            <div class="inner-column wow fadeIn" style="	border: 1px solid #ccc;
">
                                <figure class="image">

                                    <img src="{{asset('/uploads/imgProduct/'.$productDetail->prd_thunbar)}}" alt="">
                                </figure>
                            </div>
                        </div>

                        <div class="content-column col-lg-8 col-md-12 col-sm-12">
                            <div class="inner-column">
                                <h2>{!! $productDetail->prd_name !!}</h2>
                                <div class="btn-column" style="width: 170px;    margin-bottom: 20px;">
                                    <button type="button" class="theme-btn btn-style-one2" data-bs-toggle="modal" data-bs-target="#myModal">Liên hệ</button>
                                </div>
                                @if(!empty($productDetail->prd_code))
                                    <div class="sku_wrapper">Mã sản phẩm : <span class="sku" itemprop="sku">{!! $productDetail->prd_code !!}</span></div>
                                @endif
                                @if(!empty($productDetail->prd_manufacturer))
                                <div class="sku_wrapper">Hãng sản xuất : <span class="sku" style="color: #000000ab;">{!! $productDetail->prd_manufacturer !!}</span></div>
                                @endif

                                <div class="text-box">
                                    {!! $productDetail->prd_info !!}
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="text-box">
                        {!! $productDetail->prd_content !!}
                    </div>

                    </div>
                </div>
            </div>

        </div>
    </section>
    <!--End Mission Section -->

    <section class="services-section-three" style="    padding: 70px 0;">
        <div class="auto-container">
            <div class="sec-title style-two" style="margin-bottom: 20px;">
                <h2>Sản phẩm liên quan</h2>
{{--                <div class="text">Osed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque--}}
{{--                    porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci sed quia non--}}
{{--                    numquam qui.--}}
{{--                </div>--}}
            </div>

            <div class="carousel-outer">
                <div class="services-carousel-two owl-carousel owl-theme">
                @foreach($productList as $key => $value)
                    <!-- Service Block Two -->
                        <div class="service-block-two">
                            <div class="inner-box">
                                <div class="icon-box"><a href="{{route('frontend.productDetail',$value->id)}}"><img src="{{asset('/uploads/imgProduct/'.$value->prd_thunbar)}}" alt=""></a></div>
                                <h4><a href="{{route('frontend.productDetail',$value->id)}}" style="overflow: hidden;
                                        display: -webkit-box;
                                        -webkit-line-clamp: 2;
                                        -webkit-box-orient: vertical;">{{$value->prd_name}}</a>
                                </h4>
                                <div class="text" style="overflow: hidden;
                                    display: -webkit-box;
                                    -webkit-line-clamp: 2;
                                    -webkit-box-orient: vertical;">{!! $value->prd_description !!}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!--End Services Section -->


    @include('frontend.layout.contact2')

@endsection
@section('script')

@endsection
