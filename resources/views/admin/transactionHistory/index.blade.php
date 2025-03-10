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
                            <a href="index.html">Lịch sử giao dịch</a>
                        </li>
                    </ul>
                </div>
                <!-- /.box-body -->
                <form action="" method="GET">
                    <input type="text" name="name" value="{{ Request::get('name') }}" class="form-control"
                           style="width: 20%;display: inline-block" placeholder=" Tìm kiếm tên tài khoản ">
                    <select name="type" id="" class="form-control" style="width: 200px;float: left;margin-right: 5px">
                        <option value="">-- Chọn --</option>
                        <option value="1" {{Request::get('type') == '1' ? 'selected' :''}}>-- Nạp MoMo --</option>
                        <option value="2" {{Request::get('type') == '2' ? 'selected' :''}}>-- Mua vật phẩm --</option>
                        <option value="3" {{Request::get('type') == '3' ? 'selected' :''}}>-- Admin tạo coin --</option>
                        <option value="4" {{Request::get('type') == '4' ? 'selected' :''}}>-- Admin trừ coin --</option>
                    </select>
                    <input type="submit" class="btn btn-success" value="Tìm kiếm"
                           style="display: inline-block;height: 34px;position: relative;top: -3px;border-radius: 0 !important;">
                </form>
                <div class="box-footer clearfix" style="margin-bottom: 20px;margin-top: 10px">
                    <a href="{{route('admin.transactionHistory.add')}}"
                       class="btn btn-sm btn-info btn-flat pull-left"><i
                                class="fa fa-plus " style="margin-right: 5px"></i>Nạp coin cho tài khoản</a>
                    <a href="{{route('admin.transactionHistory.edit')}}"
                       class="btn btn-sm btn-danger btn-flat pull-left"><i
                                class="fa fa-minus " style="margin-right: 5px"></i>Trừ coin cho tài khoản</a>
                    @if(Request::get('type'))
                        <button class="btn btn-sm btn-danger btn-flat pull-left">Tổng coin tiêu dùng : {{$total}}Coin
                        </button>
                    @endif

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
                                    <th>ID</th>
                                    <th>Tên tài khoản</th>
                                    <th>Tên vật phẩm</th>
                                    <th>Admin xử lý</th>
                                    <th>Số lượng</th>
                                    <th>Tiền nạp</th>
                                    <th>Coin thực</th>
                                    <th>Coin quy đổi</th>
                                    <th>Mã giao dịch</th>
                                    <th>SĐT</th>
                                    <th>Hình thức</th>
                                    <th>Ngày giao dịch</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($showHistory as $key => $value)
                                    <tr>
                                        <td>{{$key + 1}}</td>
                                        <td>{{$value->id}}</td>
                                        <td>{{$value->user !== null ? $value->user->userid : ''}}</td>
                                        <td>{{$value->type == 2 && $value->product !== null ? $value->product->prd_name : ''}}</td>
                                        <td>{{($value->type == 3 || $value->type == 4) && $value->userAdmin !== null ? $value->userAdmin->userid : ''}}</td>
                                        <td>{{$value->type == 2 ? $value->qty : ''}}</td>
                                        <td>{{$value->recharge ? $value->recharge : ''}}</td>
                                        <td>{{isset($value->raw_coin) ? $value->raw_coin : ''}}</td>
                                        @if($value->type == 1 || $value->type == 3)
                                            <td style="color: #1c7430">
                                                + {{$value->coin}} coin
                                            </td>
                                        @endif
                                        @if($value->type == 2 || $value->type == 4)
                                            <td style="color: red">
                                                - {{$value->coin}} coin
                                            </td>
                                        @endif
                                        <td>{{$value->code ? $value->code : ''}}</td>
                                        <td>{{$value->phone ? $value->phone : ''}}</td>
                                        <td>
                                            @if($value->type == 1)
                                                <span style="color: #1c7430">Nạp MoMo</span>
                                            @elseif($value->type == 2)
                                                <span style="color: red">Mua vật phẩm</span>
                                            @elseif($value->type == 3)
                                                <span style="color: blue">Admin tạo coin</span>
                                            @elseif($value->type == 4)
                                                <span style="color: #ff6600">Admin trừ coin</span>
                                            @else
                                                <span>Không xác định</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{$value->created_at}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {!! $showHistory->appends(Request::all())->links() !!}
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