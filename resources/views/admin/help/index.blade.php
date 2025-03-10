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
                                <a href="index.html">Help</a>
                            </li>
                        </ul>
                    </div>
                    <form action="" method="GET">
                        <input type="text" name="name" value="{{ Request::get('name') }}" class="form-control" style="width: 20%;display: inline-block" placeholder=" Tìm kiếm tên ">
                        <select name="category_help" id="" class="form-control" style="width: 200px;float: left;margin-right: 5px">
                            <option value="" >-- Chọn --</option>
                            @foreach($category as $key => $value)
                                <option value="{{$value->id}}" {{Request::get('category_help') == $value->id ? 'selected' :''}}>{{$value->ch_name}}</option>
                            @endforeach
                        </select>
                        <input type="submit" class="btn btn-success" value="Tìm kiếm" style="display: inline-block;height: 34px;position: relative;top: -3px;border-radius: 0 !important;">
                    </form>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix" style="margin-bottom: 20px;margin-top: 10px">
                        <a href="{{route('admin.help.add')}}" class="btn btn-sm btn-info btn-flat pull-left"><i class="fa fa-plus " style="margin-right: 5px"></i>Thêm mới</a>
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
                                            <th>Tiêu đề</th>
                                            <th>Danh mục</th>
                                            <th>Hình ảnh</th>
                                            <th>Trạng thái</th>
                                            <th>Sửa</th>
                                            <th>Xóa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($showBlog as $key => $value)
                                        <tr>
                                            <td>{{$key + 1}}</td>
                                            <td>{{$value->h_name}}</td>
                                            <td>{{$value->categoryHelp->ch_name}}</td>
                                            <td><img src="uploads/imgHelp/{{$value->h_thunbar}}" alt="" style="width: 80px;height: 80px"></td>
                                            @if($value->h_status == 1)
                                                <td>
                                                    <a href="{{route('admin.help.status',[$value->id,$value->h_status])}}"><span class="btn btn-xs btn-success" style="margin-top: 10px;">Hiển thị</span></a>
                                                </td>
                                            @else
                                                <td>
                                                    <a href="{{route('admin.help.status',[$value->id,$value->h_status])}}"><span class="btn btn-xs btn-default" style="margin-top: 10px;">Ẩn</span></a>
                                                </td>
                                            @endif
                                            <td>
                                                <a href="{{route('admin.help.edit',$value->id)}}" class="btn btn-xs btn-warning" style="margin-top: 10px;">Sửa </a>
                                            </td> 
                                            <td>
                                                <a href="{{route('admin.help.delete',$value->id)}}" class="btn btn-xs btn-danger" style="margin-top: 10px;">Xóa </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {!! $showBlog->appends($query)->links() !!}
                            
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