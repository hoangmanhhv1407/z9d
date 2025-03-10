@extends('frontend.layout.main')
@section('header_detail_content')
    <h1>{{!empty($categoryProduct)?$categoryProduct->cpr_name:'Tất cả sản phẩm'}}</h1>
    <ul class="bread-crumb clearfix">
        <li><a href="{{route('frontend.index')}}">Trang chủ</a></li>
        <li>{{!empty($categoryProduct)?$categoryProduct->cpr_name:'Tất cả sản phẩm'}}</li>
    </ul>
@endsection
@section('content')
    <!-- Services Section -->
    <section class="services-section">
        <div class="auto-container">
            <!-- Sec Title -->
{{--            <div class="sec-title text-center">--}}
{{--                <h2>Computer Repair Services For Your Computer</h2>--}}
{{--                <div class="text">Compurox offers you tech services anywhere and anytime via the internet. Our techies are reliable, attentive and patient. We offer you reliable secure remote tech assistance. We have served more than 1000 computers in USA. We guarantee an outstanding experience. Customer's satisfaction is our ultimate goaal. Our specialist techies will diagnose and resolve your computer issues. </div>--}}
{{--            </div>--}}
            <div class="row clearfix" >
                <div class="btns-column col-lg-3 col-md-12 col-sm-12" >
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
                <div class="services-column col-lg-3 col-md-6 col-sm-12">
                    <div class="inner-content">
                        <div class="row clearfix" >
                        @foreach($postList as $key => $value)
                                <div class="service-block " >
                                    <div class="inner-box">
                                        <div class="image-box">
                                            <figure class="image"><a href="{{route('frontend.productDetail',$value->id)}}"><img src="{{asset('/uploads/imgProduct/'.$value->prd_thunbar)}}" alt=""></a></figure>
                                        </div>
                                        <div class="lower-content">
                                            <h5 ><a class="product-name " href="{{route('frontend.productDetail',$value->id)}}" >{{$value->prd_name}}</a></h5>
                                            <div class="text product-des" style="">{!! $value->	prd_description !!}</div>
{{--                                            <div class="link-box"><a href="{{route('frontend.productDetail',$value->id)}}">Xem thêm</a></div>--}}
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                        </div>
                        <div class="position-relative">
                            <div class="position-absolute text-center">
                                {{$postList->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End Services Section -->
@endsection