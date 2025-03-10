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
                        Đổi mật khẩu

                    </ul>
                    {{--                        <li class="details_news"><a class="tbnews" href='news/tin-moi2679.html' target="_blank">+</a>--}}
                    {{--                        </li>--}}
                    <div class="news-content-tab" style="background: #fff">
                        <div class="container">
                            <div class="block hyhy" style="padding: 60px 70px 40px 70px;">

                                <div class="row">
                                   <div class="col-md-2"></div>
                                   <div class="col-md-8">
                                       <p style="color: red">Chú ý: Nếu không tìm thấy thư đổi mật khẩu, mời bạn vào hòm thư Spam (More -> Spam), đọc thư và làm theo hướng dẫn. </p>
{{--                                       <p style="color: red"> Chức năng tạm thời bị khóa. Bạn liên hệ với Admin để lấy lại mật khẩu </p>--}}

                                       <form method="POST" action="{{route('postSendMail')}}">
                                           <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                           <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                               <input class="form-control input-md" name="email" placeholder="Nhập email"
                                                      autocomplete="off"
                                                      type="text" value="{{old('email')}}">
                                               @if($errors->first('email'))
                                                   <span class="text-danger">{{ $errors->first('email') }}</span>
                                               @endif
                                           </div>
                                           <div class="send-messages1 text-center">
                                               <div style="text-align: center">
                                                   <button style="   color: #fff;
                                            font-family: 'UVNThanhPho_R';
                                            background: #982a17;
                                            border: 0;
                                            font-size: 18px;
                                            padding: 3px 13px;" type="submit">Đổi mật khẩu
                                                   </button>
                                               </div>
                                           </div>

                                       </form>
                                   </div>
                               </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div><!-- session-left -->


        </div><!-- session 2 -->
    </div>


@endsection