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
{{--                <form action="" method="GET">--}}
{{--                    <input type="text" name="name" value="{{ Request::get('name') }}" class="form-control"--}}
{{--                           style="width: 20%;display: inline-block" placeholder=" Tìm kiếm tên User ">--}}
{{--                    <input type="submit" class="btn btn-success" value="Tìm kiếm"--}}
{{--                           style="display: inline-block;height: 34px;position: relative;top: -3px;border-radius: 0 !important;">--}}
{{--                </form>--}}
                <div class="box-footer clearfix" style="margin-bottom: 20px;margin-top: 10px">
                    {{--                    <a href="{{route('admin.product.add')}}" class="btn btn-sm btn-info btn-flat pull-left"><i--}}
                    {{--                                class="fa fa-plus " style="margin-right: 5px"></i>Thêm mới sản phẩm</a>--}}
                    {{--                    @if(Request::get('type'))--}}
                    {{--                        <button class="btn btn-sm btn-danger btn-flat pull-left">Tổng coin tiêu dùng : {{$total}}Coin--}}
                    {{--                        </button>--}}
                    {{--                    @endif--}}

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
                                    <th>Tên sản phẩm</th>
                                    <th>Ngày quay</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($showHistory as $key => $value)
                                    <tr>
                                        <td>{{$key + 1}}</td>
                                        <td>{{$value->user->userid}}</td>
                                        <td>{{$value->product->prd_name}}</td>
                                        <td>{{$value->created_at}}</td>
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