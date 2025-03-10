@extends('frontend.layout.main')
@section('script')
    {{--    <script type="text/javascript">--}}
    {{--        // $(document).ready(function(){--}}
    {{--        console.log(111, isEmpty({{Session::has('checkPopupRegister')}}))--}}
    {{--        console.log(222, isEmpty({{Session::has('checkPopupLogin')}}))--}}
    {{--        if (isEmpty({{Session::has('checkPopupRegister')}}) === false) {--}}
    {{--            $('#myModal').modal('show')--}}
    {{--        }--}}
    {{--        if (isEmpty({{Session::has('checkPopupLogin')}}) === false) {--}}
    {{--            $('#myModalLogin').modal('show')--}}
    {{--        }--}}

    {{--        function isEmpty(str) {--}}
    {{--            return (!str || 0 === str.length);--}}
    {{--        }--}}

    {{--        // });--}}
    {{--    </script>--}}
@endsection
@section('content')


    <div class="content">

{{--        <img src="frontend/images/base/ktc.png" style="position: absolute;top: 84px;width: 1200px;z-index: 9;">--}}
{{--        <div style="position: absolute;--}}
{{--    top: 115px;">--}}
{{--            <img src="/frontend/images/base/ktc.png" style="position: absolute;--}}
{{--    top: 0;--}}
{{--    width: 1210px;--}}
{{--    z-index: 9;--}}
{{--    left: -38px;--}}
{{--    height: 530px;">--}}
        </div>
        <img src="frontend/images/base/bg2.png" style="position: absolute;width: 1150px;height: 1500px;top: 392px;display: none">
        <div id="sessions-2">

            <div class="session-left">
                {{--                    <div class="guide-link">--}}
                {{--                        <div class="guide-blog"><a href="http://cuuam.gosu.vn/cuu-am-lenh" target="_blank"></a></div>--}}
                {{--                        <div class="guide-vip"><a href="http://vip.gosu.vn/" target="_blank"></a></div>--}}
                {{--                        <div class="guide-setup"><a href="cuu-am-bao-dien/tan-thu/co-ban/tai-va-cai-dat-game.html"--}}
                {{--                                                    target="_blank"></a></div>--}}
                {{--                    </div>--}}

                <div class="news-link">
                    <ul class='tabs-news' style="    text-align: center;
    font-family: 'UVNThanhPho_R';
    font-size: 36px;
    color: #fff;">
                        Giỏ hàng

                    </ul>
                    <div class="news-content-tab" style="background: #fff;padding: 35px 15px;">
                        <div class="container">
                            @if(count($cart) > 0)
                                <table class="table table-shopCart">
                                    <thead>
                                    <tr class="shoppingCart-title">
                                        <th class="shopping-cart-img">Ảnh</th>
                                        <th>Tên vật phẩm</th>
                                        <th class="shopping-cart-price">Giá</th>
                                        <th class="shopping-cart-quantity" style="text-align: center;">Số lượng</th>
                                        <th>Tổng</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody class="shopCart-bottom">
                                    @foreach($cart as $item)
                                        <tr class="cart-product">
                                            <td class="shopping-cart-img">
                                                <a href="#"><img
                                                            src="{{asset('/uploads/imgProduct/'.$item->options->image)}}"
                                                            alt="ShoppingCart_01"></a>
                                            </td>
                                            <td class="cart-product-one">{{$item->name}}</td>
                                            <td class="shopping-cart-price"><span class="price-shCart">
                                                             {{$item->price}} <b class="icon-price"></b>
                                                        </span></td>
                                            <td class="cart-product-two shopping-cart-quantity">
                                                <div class="pd-c-quantity quantity add-card add-card-product"
                                                     style="position: relative;">
                                                    <input type="number" min="1" max="100" step="1"
                                                           value="{{$item->qty}}"
                                                           disabled>
                                                    <a href="{{route('frontend.cart.decrease',$item->id)}}"
                                                       class="quantity-button quantity-down">
                                                        <span><i class="fa fa-minus-circle"
                                                                 aria-hidden="true"></i></span>
                                                    </a>
                                                    <a href="{{route('frontend.cart.increment',$item->id)}}"
                                                       class="quantity-button quantity-up">
                                                        <span><i class="fa fa-plus-circle"
                                                                 aria-hidden="true"></i></span>
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="price-total"><span class="price-shCart">{{$item->qty * $item->price}} <b
                                                            class="icon-price"></b></span></td>
                                            <td><a href="#"> <a href="{{route('frontend.cart.delete',$item->rowId)}}"
                                                                type="button" class="btn-close">×</a> </a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <hr>
                                <div style="text-align: right;
                                                font-size: 20px;
                                                font-weight: 600;">
                                    Tổng coin : {{(int) Cart::subtotal()}} <b class="icon-price"></b>
                                </div>
                            @else
                                Bạn chưa chọn vật phẩm
                            @endif
                            <hr>
                            <div class="clearfix" style="margin-bottom: 10px;">
                                <a type="button" class="btn btn-default" style="float: left;    color: #777986;
    border: 1px solid;" href="{{route('frontend.productCate')}}"><i
                                            class="fa fa-arrow-left"></i> Tiếp tục mua sắm
                                </a>
                                @if(count($cart) > 0)
                                    <a type="button" class="btn btn-danger" href="{{route('frontend.cart.payment')}}"
                                       style="background: #b70f0b;float: right">Thanh toán <i
                                                class="fa fa-arrow-right"></i></a>
                                @endif

                            </div>

                        </div>

                    </div>


                </div>
            </div><!-- session-left -->

           <!-- <div class="session-right">
                <div class="box-fanpage" style="margin-top: 0;margin-bottom: 7px">
                    <div class="title-link">Thông tin tài khoản</div>
                    <ul class="fanpage-content">
                        <div style="text-align: center;color: #fff;font-family: 'UVNThanhPho_R';font-size: 30px;margin-bottom: 26px;margin-top: 35px;">
                            <span style="font-size: 20px">{{Auth::user()->userid}}</span> <br>
                            <span style="font-size: 20px">Coin: {{Auth::user()->coin}}<b class="icon-price"></b></span>
                        </div>-->
                    </ul>
                </div><!-- END fanpage -->
            @include('frontend.layout.session-right')

            <!-- End block banner_banner-event -->

            </div><!-- session-right -->

        </div><!-- session 2 -->
    </div>


@endsection