{{--                    <div class="box-option">--}}
{{--                        <div class="option-1"><a href="http://cuuam.gosu.vn/cuu-am-bao-dien.html" target="_blank"></a>--}}
{{--                        </div>--}}
{{--                        <div class="option-2"><a href="cuu-am-bao-dien/gioi-thieu/mon-phai-the-luc.html"--}}
{{--                                                 target="_blank"></a></div>--}}
{{--                        <div class="option-3"><a href="cuu-am-bao-dien/tan-thu.html" target="_blank"></a></div>--}}
{{--                        <div class="option-4"><a href="http://cuuam.gosu.vn/vohoc" target="_blank"></a></div>--}}
{{--                        <div class="option-5"><a href="http://cuuam.gosu.vn/pvc" target="_blank"></a></div>--}}
{{--                    </div><!-- END Bảo Điển, Tân thủ -->--}}
<div class="box-fanpage" style="margin-top: 0">
    <!--<div class="title-link">Fanpage</div>-->
    <ul class="fanpage-content">
        <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2F9dzplay.Ver.02&tabs=timeline&width=340&height=500&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId" width="240" height="500" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>    </ul>
</div><!-- END fanpage -->
<?php

$topServer = \App\Models\NDV01CharacState::orderBy('inner_level','desc')->with('NDV01Charac')->limit(10)->get();

?>
{{--<div class="box-special">--}}
{{--    <div class="title-link">BXH Top Level</div>--}}

{{--    <div class="boxTop">--}}
{{--        <table style="width:100%">--}}
{{--            @foreach($topServer as $key => $value)--}}
{{--                <tr>--}}
{{--                    <td>{{$key + 1}}</td>--}}
{{--                    <td>{{$value->NDV01Charac->chr_name}}</td>--}}
{{--                    <td>{{$value->inner_level}}</td>--}}
{{--                </tr>--}}
{{--            @endforeach--}}

{{--        </table>--}}
{{--    </div>--}}
{{--    <div style="    text-align: center;--}}
{{--    padding-bottom: 21px;    margin-top: 22px;">--}}
{{--        <a style="    color: #fff;--}}
{{--                                font-family: 'UVNThanhPho_R';--}}
{{--                                background: #982a17;--}}
{{--                                border: 0;--}}
{{--                                font-size: 18px;--}}
{{--                                    padding: 3px 13px;" href="{{route('frontend.topServer')}}">Xem thêm</a>--}}
{{--    </div>--}}
{{--</div><!-- END đặc sắc -->--}}


<!-- End block banner_banner-event -->
@section('css')
    <style type="text/css">


        .boxTop tr td {
            color: #fff;
            font-family: 'UVNThanhPho_R';
        }

        .boxTop {
            width: 200px;
            margin-left: 32px;
        }
    </style>
@endsection
