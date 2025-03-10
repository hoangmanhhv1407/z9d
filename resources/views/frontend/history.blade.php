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
@section('css')
    <style type="text/css">
        .content .block {
            width: 100%;
            float: left;
        }

        .content .block h1 {
            width: 100%;
            float: left;
            font-size: 25px;
            font-weight: bold;
            text-indent: 40px;
            color: #34322f;
            text-transform: uppercase;
            background: url(frontend/images/i_h1.png) no-repeat left center;
        }

        .content .block .title {
            width: 160px;
            height: 50px;
            padding-top: 6px;
            margin-left: 20px;
            margin-top: 20px;
            float: left;
            font-size: 25px;
            font-weight: bold;
            text-align: center;
            color: #e9eae7;
            text-transform: uppercase;
            background: url(frontend/images/bg_btn_content.jpg) no-repeat left center;
        }

        .content .block h2 {
            width: 100%;
            float: left;
            font-size: 15px;
            padding-top: 2px;
            margin-top: 10px;
            color: #34322f;
            font-weight: bold;
            text-indent: 20px;
            margin-left: 20px;
            background: url(frontend/images/i_h2.png) no-repeat left center;
        }

        .content .block .col.note {
            color: red;
            text-indent: 20px;
            font-weight: bold;
        }

        .content .block .col {
            width: 100%;
            float: left;
        }

        .content .block .col span {
            width: 50%;
            float: left;
            text-align: left;
        }
        .content .block table {
            width: 100%;
            float: left;
            border-collapse: collapse;
            text-align: center;
            margin-top: 10px;
        }
        .content .block table thead tr {
            background-color: #d7d8d9;
        }
        .content .block table tr {
            border-bottom: 1px solid #d7d8d9;
        }
        .content .block table th,  .content .block table td {
            border-left: 1px solid #c3c4c4;
            padding: 10px 0;
        }
        .content .block table tr {
            border-bottom: 1px solid #d7d8d9;
        }
        .content .block table th:first-child,  .content .block table td:first-child {
            border: none;
        }
        .content .block table th,  .content .block table td {
            border-left: 1px solid #c3c4c4;
            padding: 10px 0;
        }
    </style>
@endsection
@section('content')


    <div class="content">


        <div id="sessions-2">

            <div class="session-left" style="width: 100%;">
                {{--                    <div class="guide-link">--}}
                {{--                        <div class="guide-blog"><a href="http://cuuam.gosu.vn/cuu-am-lenh" target="_blank"></a></div>--}}
                {{--                        <div class="guide-vip"><a href="http://vip.gosu.vn/" target="_blank"></a></div>--}}
                {{--                        <div class="guide-setup"><a href="cuu-am-bao-dien/tan-thu/co-ban/tai-va-cai-dat-game.html"--}}
                {{--                                                    target="_blank"></a></div>--}}
                {{--                    </div>--}}

                <div class="news-link">
                    <ul class='tabs-news' style="text-align: center;
    font-family: 'UVNThanhPho_R';
    font-size: 36px;
    color: #fff;
    width: 100%;
    background-size: cover;
    margin-left: 0;">
                        Lịch sử giao dịch

                    </ul>
                    {{--                        <li class="details_news"><a class="tbnews" href='news/tin-moi2679.html' target="_blank">+</a>--}}
                    {{--                        </li>--}}
                    <div class="news-content-tab" style="background: #fff">
                        <div class="container" style="position: relative;">
                            <div class="block download2 hyhy" style="    padding: 60px 0 91px 0;">
                                <div style="padding: 0 15px">
                                    <table style="border-right: 1px solid #d7d8d9;border-left: 1px solid #d7d8d9;">
                                        <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Tên vật phẩm</th>
                                            <th>Số lượng</th>
                                            <th>Coin thanh toán</th>
                                            <th>Mã giao dịch</th>
                                            <th>Số điện thoại</th>
                                            <th>Hình thức</th>
                                            <th>Ngày giao dịch</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($showHistory as $key => $value)

                                            <tr>
                                                <td>{{$key + 1}}</td>
                                                <td>{{$value->type == 2 && $value->product !== null? $value->product->prd_name: ''}}</td>
                                                <td>{{$value->type == 2 ?$value->qty: ''}}</td>
                                                @if($value->type == 1)
                                                    <td style="color: #1c7430">
                                                        + {{$value->coin}} coin
                                                    </td>
                                                @endif
                                                @if($value->type == 2)

                                                    <td style="color: red">
                                                        - {{$value->coin}} coin
                                                    </td>
                                                @endif
                                                @if($value->type == 3)

                                                    <td style="color: #1c7430">
                                                        + {{$value->coin}} coin
                                                    </td>
                                                @endif
                                                <td>{{$value->type == 1 ?$value->code : ''}} </td>
                                                <td>{{$value->type == 1 ?$value->phone : ''}} </td>
                                                @if($value->type == 1)
                                                    <td style="color: #1c7430">
                                                        Nạp MoMo
                                                    </td>
                                                @endif
                                                @if($value->type == 2)
                                                    <td style="color: red">
                                                        Mua vật phẩm
                                                    </td>
                                                @endif
                                                @if($value->type == 3)
                                                    <td style="color: blue">
                                                        Admin nạp
                                                    </td>
                                                @endif
                                                <td>
                                                    {{$value->created_at}}
                                                </td>
                                            </tr>

                                        </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            @if($showHistory->total() > 10)
{{--                                <div style="position: relative;margin-top: 25px;height: 60px;">--}}
                                    <div style="    position: absolute;
                                        bottom: -16px;
                                        right: 50%;
                                        transform: translate(50%,-50%);">
                                        {{$showHistory->links()}}
                                    </div>
{{--                                </div>--}}
                            @endif
                        </div>
                    </div>


                </div>
            </div><!-- session-left -->


        </div><!-- session 2 -->
    </div>


@endsection