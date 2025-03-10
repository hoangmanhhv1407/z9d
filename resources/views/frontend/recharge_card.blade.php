@extends('frontend.layout.main')

@section('script')
    <script>
        function closePopup(e) {
            var r = confirm(
                'Lưu ý: Nếu chưa Xác nhận giao dịch sau khi quét mã QR, quý khách sẽ không được cộng số dư vào tài khoản'
            );
            if (r === true) {
                $(e).modal('hide')
            }
        }
        $(document).ready(function() {
            $('.button-momo-surcharge-submit').on('click', function() {
                const tranId = $('input[name="tranId"]').val().trim();
                if (tranId) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.post('/rechargeCardPost', {
                            tranId
                        })
                        .done(function(data) {
                            if (data === 'success') {
                                toastr.success(
                                    'Giao dịch thành công, bạn hãy vào tài khoản để kiểm tra',
                                    'Thành công');
                            } else {
                                toastr.error('Lấy mã giao dịch thất bại, vui lòng thử lại sau 30s',
                                    'Lỗi');
                            }
                        })
                        .fail(function() {
                            toastr.error('Lấy mã giao dịch thất bại, vui lòng thử lại sau 30s', 'Lỗi');
                        });
                    return;
                }
                toastr.error('Bạn chưa nhập mã giao dịch', 'Lỗi');
            });
        });
    </script>
@section('content')
        <div class="container">
            <div class="header-nav">
                 <link rel="stylesheet" href="/frontend/css/style.css">
            </header>


                    <div id="home4" class="app-right__ul12">
                        <ul class="app-right__ul1">
                            <li class="app-right__ul1-li1">Nạp Xu:</li>
                            <!-- Nội dung nạp Xu -->
                        </ul>
                        
                            <div class="app_payment">

                              <div id="app_payment_qr" style="display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
                                            
                                            <!-- Your QR code goes here -->
                                <div class="app_payment_qr items-center bg-white p-4 mt-4">         
                                        <span class="text-center text-xl text-black">
                                        <strong>Cách 1:</strong> Chuyển khoản bằng mã QR
                                        Mở App Ngân hàng quét mã QRCode và nhập số tiền cần chuyển
                                    </span>
                                    <span class="app_payment_qr_span lazy-load-image-background blur lazy-load-image-loaded" style="color: transparent; display: inline-block;">
                                        <img id="qr_images" class="h-auto w-auto py-4" src="https://img.vietqr.io/image/MB-90511239999-compact.png?addInfo=NAP9D{{Auth::user()->id}}" alt="qr of a qr">
                                    </span>
                                            <p id="countdown"></p>
                                            <button id="closePopup">Close</button>
                                        </div>
                                 </div>

                                <div class="app_payment_info items-center rounded-xl bg-white p-4 mt-4">
                                    <span class="text-center text-xl text-black">
                                        <strong>Cách 2:</strong> Chuyển khoản thủ công theo thông tin
                                    </span>
                                    <img class="my-4" src="/frontend/images/bank_logo.png" style="height: 6rem;width: 12rem; margin: auto; " >
                                    <span class="pt-4 text-center text-xl">
                                        Chủ tài khoản: <strong>LE VIET ANH</strong>
                                    </span>
                                    <hr class="my-4 md:min-w-full">
                                    <span class="pt-4 text-center text-xl " >
                                        Số Tài khoản: <strong style="color: blue;">90511239999</strong>
                                    </span>
                                    <hr class="my-4 md:min-w-full">
                                    <span class="bank_noidung pt-4 text-center text-xl" >
                                        Nội dung chuyển tiền: <strong style="color: red;">NAP9D{{Auth::user()->id}}</strong>
                                    </span>
                                    <hr class="my-4 md:min-w-full">
                                    <span class="pt-4 text-center text-base">
                                        Ngân hàng: Quân đội (MB)
                                    </span>
                                </div>
                            </div>
                      
                    </div>



    <div id="sessions-2" class="">
        <div class="news-link">
            <h2 class="text-center">
                Chuyển khoản qua App Momo
            </h2>
            <div class="news-content-tab" style="background: #fff">
                <div class="container">
                    <div class="row" style="margin-bottom: 50px;margin-top: 50px">
                            @foreach ($listCharge as $key => $value)
							  <div class="col-md-6 text-center">
                                <div class="content2 col-md-10" style="">
                                    <a type="button" class="btn col-md-10 bg-secondary" style="margin-top: 10px;font-size: 20px;"
                                        href="#" data-bs-toggle="modal" data-bs-target="#myPay{{$key}}">
                                        <div class="m-des">Gói <span style="color: #1dc343;">{{ number_format($value*1000, 0, ',','.') }}
                                                VND </span> <span>{{ $value }}<b
                                                    class="icon-price"></b></span></div>
                                        <span style="position: relative;top: 2px;">Thanh toán</span>
                                        <img src="frontend/images/Momo.png" alt="" style="width: 43px;margin-left: 10px;">
                                    </a>
                                </div>
                                <div class="modal fade" id="myPay{{$key}}" role="dialog">
                                    <div class="modal-dialog modal-md" style="max-width: 600px">
                                        <div class="modal-content">
                                            <div class="modal-header"
                                                style="background:url(frontend/images/news-tab.png) no-repeat center center;border-radius: 0;">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                    &times;
                                                </button>
                                                <p style="    position: absolute;
                                                                                        color: #fff;
                                                                                        font-family: 'UVNThanhPho_R';
                                                                                        text-align: center;
                                                                                        font-size: 21px;
                                                                                        top: 12px;">Bước 1. Quét mã QR</p>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center" style="padding: 0 30px;">
                                                    <p style="font-size: 14px;">Vui lòng mở <b>App Momo</b> để
                                                        nhập số điện thoại dưới đây và chuyển tiền</p>
                                                    <img width="300"
                                                        src="https://momofree.apimienphi.com/api/QRCode?phone=0968658145&amount={{ $value*1000}}&%20note={{ number_format($value * 1000, 0, ',', '.') }}">
                                                    <p>Số điện thoại: <b>0968658145</b></p>
                                                    <p>Tên tài khoản: <b style="color: #7b65f9;">NGUYEN DAT KHAIT</b></p>
                                                    <p>Số tiền cần thanh toán:
                                                        {{-- <b>{{  number_format((((($value['coin'] * 1) / 100) + $value['coin'])*1000), 0, ',','.')  }} --}}
                                                        {{-- VNĐ</b> --}}
                                                        <b>{{ number_format($value * 1000, 0, ',', '.') }}
                                                            VNĐ</b>
                                                    </p>
                                                    <p class="text-left" style="color:red;    font-size: 14px;">
                                                        * <b>LƯU
                                                            Ý:</b>
                                                        {{-- <br>- Nội dung chuyển tiền ( Bắt buộc ) : Tên Tài Khoản + Số tiền
																		Ví Dụ : 9dlerna 500k --}}
                                                        {{-- là {{  number_format((($value['coin'])*1000), 0, ',','.')  }} --}}
                                                        {{-- VNĐ --}}
                                                        <br>- Sau khi chuyển tiền , anh/chị bấm Xác Nhận Giao Dịch Để Cộng Tiền, và nhập Mã Giao Dịch Momo
                                                        . Nếu sau 30 phút không nhận được coins phiền Anh/Chị liên hệ
                                                      
                                                   
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <div style="margin: 0 auto;">
                                                    <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                                        data-bs-target="#myPaykk"
                                                        onclick="$('#myPay{{ $value['id'] }}').modal('hide')">
                                                        Xác
                                                        nhận giao dịch để cộng tiền
                                                    </button>
                                                    <button type="button" class="btn btn-default "
                                                        onclick="closePopup('#myPay{{ $value['id'] }}')">Tôi
                                                        chưa
                                                        chuyển
                                                        tiền
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
							</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="myPaykk" role="dialog">
            <div class="modal-dialog modal-md" style="max-width: 600px">
                <div class="modal-content">
                    <div class="modal-header"
                        style="background:url(frontend/images/news-tab.png) no-repeat center center;border-radius: 0;">
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                            &times;
                        </button>
                        <p style="    position: absolute;
                                                                color: #fff;
                                                                font-family: 'UVNThanhPho_R';
                                                                text-align: center;
                                                                font-size: 21px;
                                                                top: 12px;">Bước 2. Xác nhận mã giao dịch</p>
                    </div>
                    <div class="modal-body">
                        <div class="form-horizontal">
                            <div class="text-center" style="padding: 0 30px;">
                                <img src="https://i.imgur.com/E2nR9DG.jpg"
                                    alt="Hướng dẫn Momo" style="width: 50%;">
                                <p style="margin-top: 20px;">Sau khi thanh toán xong,
                                    bạn
                                    vui lòng xác nhận nhập
                                    <b>Mã
                                        giao dịch</b> vào ô dưới đây rồi nhấn <b>Xác
                                        nhận</b>.
                                </p>
                            </div>
                            <div class="input-group "
                                style=" width: 255px;margin: 0 auto;text-align: center;display: block;">
                                <input type="text" class="form-control" name="tranId" placeholder="Mã giao dịch"
                                    style="text-align: left; background: #fff;width: 100%;float: left;border-radius: 0;margin-bottom: 10px"
                                    autofocus="true" required>
                                <input type="hidden" value="{{ $value['id'] }}" name="packageId">
                                <button class="btn btn-info button-momo-surcharge-submit">
                                    Xác nhận
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div style="margin: 0 auto;">
                            <button type="button" class="btn btn-default " onclick="closePopup('#myPaykk')">Tôi
                                chưa
                                chuyển
                                tiền
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<h2 class="text-center">
                Chuyển khoản qua Ngân Hàng
            </h2>
			<p style="text-align: justify; margin-left: 125px;"><span style="font-size: 18px;"><span style="font-weight: 700;"><font color="#085294">&nbsp;- Ngân hàng : Techcombank</font></span></span></p><p style="text-align: justify; margin-left: 125px;"><span style="font-size: 18px;"><span style="font-weight: 700;"><font color="#085294">&nbsp;- STK :&nbsp;</font></span></span><font color="#085294"><span style="font-size: 18px;"><span style="font-weight: 700;">19035716824011</span></span></font></p><p style="text-align: justify; margin-left: 125px;"><font color="#085294"><span style="font-size: 18px;"><span style="font-weight: 700;">&nbsp;- Tên: NGUYEN DAT KHAI</span></span></font></p><p style="text-align: justify; margin-left: 125px;"><span style="font-size: 14px; font-style: italic; font-weight: bold; color: rgb(255, 0, 0);">* Lưu ý : Nội dung chuyển tiền : Tên đăng nhập + Số tiền . Ví dụ : 9dlerna1 100k</span></p><p style="text-align: justify; margin-left: 125px;"><span style="font-size: 14px; font-style: italic; font-weight: bold; color: rgb(255, 0, 0);">&nbsp;Nếu đúng nội dung Xu sẽ được cộng vào tài khoản của anh/chị sau 5 - 10 phút</span></p><p style="text-align: justify; margin-left: 125px;"><span style="font-size: 14px; font-style: italic; font-weight: bold; color: rgb(255, 0, 0);">Nếu sau 5-10 phút chưa nhận được Xu xin phiền liên hệ fanpage ,&nbsp;</span><font color="#ff0000"><span style="font-size: 14px;"><span style="font-weight: 700;"><i>https://www.facebook.com/9dlerna</i></span></span></font></p>
			<div class="session-right">
        
    </div>
    </div>
@endsection
