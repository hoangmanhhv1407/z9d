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
                            <a href="index.html">Lịch sử quà tặng</a>
                        </li>
                    </ul>
                </div>
                <!-- /.box-body -->
                <form action="" method="GET">
                    <input type="text" name="name" value="{{ Request::get('name') }}" class="form-control"
                        style="width: 20%;display: inline-block" placeholder=" Tìm kiếm tên User ">
                    <input type="submit" class="btn btn-success" value="Tìm kiếm"
                        style="display: inline-block;height: 34px;position: relative;top: -3px;border-radius: 0 !important;">
                </form>
                <div class="box-footer clearfix" style="margin-bottom: 20px;margin-top: 10px">
                    <a href="{{ route('admin.giftSendHistory.addLuckyIndex') }}"
                        class="btn btn-sm btn-info btn-flat pull-left"><i class="fa fa-plus "
                            style="margin-right: 5px"></i>Thêm lượt quay</a>
                    <a href="{{ route('admin.giftSendHistory.addGiftVip') }}"
                        class="btn btn-sm btn-info btn-flat pull-left"><i class="fa fa-plus "
                            style="margin-right: 5px"></i>Gửi quà theo cấp vip</a>
                    <a href="{{ route('admin.giftSendHistory.addGiftForUser') }}"
                        class="btn btn-sm btn-info btn-flat pull-left"><i class="fa fa-plus "
                            style="margin-right: 5px"></i>Gửi quà theo tài khoản</a>
                            <a href="{{ route('admin.giftSendHistory.addAccuForUser') }}"
                        class="btn btn-sm btn-info btn-flat pull-left"><i class="fa fa-plus "
                            style="margin-right: 5px"></i>Nạp tích luỹ cho tài khoản</a>
                    {{-- @if (Request::get('type')) --}}
                    {{-- <button class="btn btn-sm btn-danger btn-flat pull-left">Tổng coin tiêu dùng : {{$total}}Coin --}}
                    {{-- </button> --}}
                    {{-- @endif --}}

                </div>

                <!-- /.box-footer -->
                <div class="inbox">
                    <div class="box box-info">
                        <!-- /.box-header -->
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tên tài khoản</th>
                                        <th>Số lượt quay</th>
                                        <th>Mốc thưởng</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($showHistory as $key => $value)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ isset($value->user) ? $value->user->userid : '' }}</td>
                                            <td>{{ isset($value->user) ? $value->user->luckyNumber : '' }}</td>
                                            <td>
                                                @if ($value->giftCoin === 100)
                                                    100 - 199 (VIP 1)
                                                @endif
                                                @if ($value->giftCoin === 200)
                                                    200 - 499 (VIP 2)
                                                @endif
                                                @if ($value->giftCoin === 500)
                                                    500 - 999 (VIP 3)
                                                @endif
                                                @if ($value->giftCoin === 1000)
                                                    1000 - 1999 (VIP 4)
                                                @endif
                                                @if ($value->giftCoin === 2000)
                                                    2000 - 4999 (VIP 5)
                                                @endif
                                                @if ($value->giftCoin === 5000)
                                                    5000 - 9999 (VIP 6)
                                                @endif
                                                @if ($value->giftCoin === 10000)
                                                    trên 10000 (VIP 7)
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($value->product->prd_name))
                                                    {{ $value->product->prd_name }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($value->status === 1)
                                                    <p style="color: #1c7430">Đã gửi</p>
                                                @elseif($value->status === 2)
                                                    <p style="color: red">Đã hủy</p>
                                                @else
                                                    <p style="color: blue">Đang chờ</p>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($value->status === 3)
                                                    <a href="{{ route('admin.giftSendHistory.send', [$value->id, 1, $value->luckyNumber]) }}"
                                                        class="btn btn-xs btn-warning" style="margin-top: 10px;">Gửi quà
                                                    </a>
                                                    <a href="{{ route('admin.giftSendHistory.send', [$value->id, 2, $value->luckyNumber]) }}"
                                                        class="btn btn-xs btn-danger" style="margin-top: 10px;">Bỏ quà </a>
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {!! $showHistory->appends($query)->links() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-footer">
        <div class="page-footer-inner">
        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </div>
@endsection
