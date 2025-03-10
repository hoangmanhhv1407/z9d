@extends('admin.layout.main')
@section('content')
    @include('admin/layout/header')
    <div class="clearfix"></div>
    <div class="page-container">
        @include('admin/layout/sidebar')
        <div class="page-content-wrapper">
            <div class="page-content">
                @include('admin/layout/message')
                <div class="page-bar">
                    <ul class="page-breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="{{ route('admin.relogMomo.login') }}">Check Momo</a>
                        </li>
                    </ul>
                </div>
                <div class="text-center" style="margin-top:20px">
                    @if ($balance)
                        <div class="balance">
                            <h3> Số dư tài khoản: {{number_format($balance, 0, '.', ',') }} đ</h3>
                        </div>
                    @else
                        <div class="relog-container">
                            <h3>Hiện tài khoản momo chưa được liên kết</h3>
                            <button type="button" class="btn btn-danger relog-momo">Đăng nhập lại momo</button>
                            <div class="text-center col-xs-10 otp-form">
                                <input type="text" name="otp" class="form-control w-100" placeholder="Nhập mã otp">
                                <button type="button" class="btn btn-danger confirm-otp" style="margin-top:20px">Xác
                                    nhận</button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.otp-form').hide();
            $('.relog-momo').click(function() {
                $.get("/admins/relog-momo/login")
                    .done(function(data) {
                        if (data === 'success') {
                            toastr.info("Đang gửi mã otp, check điện thoại rồi gửi vô đây nhá");
                            $('.relog-momo').hide();
                            $('.otp-form').show();
                        } else {
                            toastr.error('Đã có lỗi xảy ra, vui lòng thử lại', 'Lỗi');
                        }
                    })
            });
            $('.confirm-otp').click(function() {
                const otp = $('input[name="otp"]').val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post("/admins/relog-momo/otp", {
                        otp: otp
                    })
                    .done(function(data) {
                        if (data === 'success') {
                            toastr.success("Đăng nhập thành công", 'Thành công');
                        } else {
                            toastr.error("Đã có lỗi xảy ra, vui lòng thử lại", 'Lỗi');
                        }
                    })
                    .fail(function(data) {
                        toastr.error('Đã có lỗi xảy ra, vui lòng thử lại', 'Lỗi');
                    });
            });
        });
    </script>
@endsection
