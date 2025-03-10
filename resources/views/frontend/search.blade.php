@extends('frontend.layout.main')
@section('header_detail_content')
    <h1>Kết quả tìm kiếm</h1>
    <ul class="bread-crumb clearfix">
        <li><a href="{{route('frontend.index')}}">Trang chủ</a></li>
        <li>Kết quả tìm kiếm</li>
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

            <div class="row clearfix">
{{--                <div class="btns-column col-lg-3 col-md-12 col-sm-12">--}}
{{--                    <!--Tabs Box-->--}}
{{--                    <ul class="buttons-list clearfix">--}}
{{--                        <li>--}}
{{--                            <a href="{{route('frontend.productCate',0)}}">Tất cả sản phẩm</a>--}}
{{--                        </li>--}}
{{--                        @foreach($cateProduct as $key => $value)--}}
{{--                            <li>--}}
{{--                                <a href="{{route('frontend.productCate',$value->id)}}">{{$value->cpr_name}}</a>--}}
{{--                            </li>--}}
{{--                        @endforeach--}}
{{--                    </ul>--}}
{{--                    <!-- Popular Posts -->--}}
{{--                    <div class="sidebar" style="margin-top: 40px">--}}
{{--                        <div class="sidebar-widget popular-posts">--}}
{{--                            <div class="sidebar-title"><h3>Sản phẩm nổi bật</h3></div>--}}
{{--                            @foreach($productHot as $key => $value)--}}
{{--                                <article class="post">--}}

{{--                                    <div class="row">--}}
{{--                                        <div class="col-md-4" style="padding-right: 0">--}}
{{--                                            <img src="{{asset('/uploads/imgProduct/'.$value->prd_thunbar)}}" alt="">--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-8">--}}
{{--                                            <div class="text"><a href="{{route('frontend.productDetail',$value->id)}}">{!! $value->prd_name !!}</a></div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </article>--}}

{{--                            @endforeach--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    @include('frontend.layout.contact')--}}

{{--                </div>--}}
                @if($showproduct->total() == 0)
                    <div style="text-align: center;width: 300px">
                        <p style="color: red">Không tìm thấy kết quả phù hợp</p>
                    </div>
                    @else
                    <div class="services-column col-lg-12 col-md-12 col-sm-12">
                        <div class="inner-content">

                            <div class="row clearfix" style="margin-right: 10px;margin-left: 10px">
                                @foreach($showproduct as $key => $value)
                                    <div class="service-block col-lg-3 col-md-6 col-sm-12" style="padding-left: 10px;padding-right: 10px">
                                        <div class="inner-box">
                                            <div class="image-box">
                                                <figure class="image"><a href="{{route('frontend.productDetail',$value->id)}}"><img src="{{asset('/uploads/imgProduct/'.$value->prd_thunbar)}}" alt=""></a></figure>
                                            </div>
                                            <div class="lower-content">
                                                <h5 ><a href="{{route('frontend.productDetail',$value->id)}}" style="overflow: hidden;
                                        display: -webkit-box;
                                        -webkit-line-clamp: 2;
                                        -webkit-box-orient: vertical;">{{$value->prd_name}}</a></h5>
                                                <div class="text " style="overflow: hidden;
                                        display: -webkit-box;
                                        -webkit-line-clamp: 3;
                                        -webkit-box-orient: vertical;">{!! $value->	prd_description !!}</div>
                                                {{--                                            <div class="link-box"><a href="{{route('frontend.productDetail',$value->id)}}">Xem thêm</a></div>--}}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach


                            </div>
                            <div style="position: relative">
                                <div style=" position: absolute;top: 50%; right: 50%;transform: translate(50%,-50%);">
                                    {{$showproduct->links()}}
                                </div>
                            </div>

                        </div>
                    </div>

                @endif
            </div>

        </div>
    </section>
    <!--End Services Section -->
@endsection