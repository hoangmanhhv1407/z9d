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
                                <a href="index.html">Gift code</a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.box-body -->
                    <form action="" method="GET">
                        <input type="text" name="name" value="{{ Request::get('name') }}" class="form-control" style="width: 20%;display: inline-block" placeholder=" Tìm kiếm giftcode">
{{--                        <select name="category_blog" id="" class="form-control" style="width: 200px;float: left;margin-right: 5px">--}}
{{--                            <option value="" >-- Chọn --</option>--}}
{{--                            @foreach($category as $key => $value)--}}
{{--                                <option value="{{$value->id}}" {{Request::get('category_blog') == $value->id ? 'selected' :''}}>{{$value->cpo_name}}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
                        <input type="submit" class="btn btn-success" value="Tìm kiếm" style="display: inline-block;height: 34px;position: relative;top: -3px;border-radius: 0 !important;">
                    </form>
                    <div class="box-footer clearfix" style="margin-bottom: 20px;margin-top: 10px">
                        <a href="{{route('admin.giftCode.add')}}" class="btn btn-sm btn-info btn-flat pull-left"><i class="fa fa-plus " style="margin-right: 5px"></i>Thêm mới Giftcode</a>
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
                                            <th>Tên giftcode</th>
                                            <th>Mã giftcode</th>
                                            <th>Số lượng</th>
                                            <th>Trạng thái</th>
                                            <th>Thông tin</th>
                                            <th>Loại giftCode</th>
                                            <th>Ngày tạo</th>
                                            <th>Sửa</th>
{{--                                            <th>Xóa</th>--}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($giftCode as $key => $value)
                                        <tr>
                                            <td>{{$key + 1}}</td>
                                            <td>
                                                @foreach($value->nameGift as $val)
                                                    - {{$val['name']}} <br>
                                                @endforeach
                                            </td>
                                            <td>{{$value->gift_code}}</td>
                                            <td>{{$value->qty}}</td>
                                            @if($value->status === 1)
                                                <td style="color: #1c7430">Hoạt động</td>
                                            @else
                                                <td style="color: red">Hết hạn</td>
                                            @endif
                                            <td>{!! $value->content !!}</td>
                                            @if($value->type === 1)
                                                <td style="color: #1c7430">1 người dùng</td>
                                            @else
                                                <td style="color: red">Nhiều người dùng</td>
                                            @endif
                                            <td>{{$value->created_at}}</td>

                                            <td>
                                                <a href="{{route('admin.giftCode.edit',$value->id)}}" class="btn btn-xs btn-warning" style="margin-top: 10px;">Sửa </a>
                                            </td> 
{{--                                            <td>--}}
{{--                                                <a href="{{route('admin.giftCode.delete',$value->id)}}" class="btn btn-xs btn-danger" style="margin-top: 10px;">Xóa </a>--}}
{{--                                            </td>--}}
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {!! $giftCode->appends($query)->links() !!}
                            
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