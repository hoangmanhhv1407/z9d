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
                                <a href="index.html">Client Says</a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix" style="margin-bottom: 20px">
                        <a href="{{route('admin.clientSays.add')}}" class="btn btn-sm btn-info btn-flat pull-left"><i class="fa fa-plus " style="margin-right: 5px"></i>Thêm mới</a>
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
                                            <th>Tên</th>
                                            <th>Chức vụ</th>
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
                                            <td>{{$value->cs_name}}</td>
                                            <td>{{$value->cs_service}}</td>
                                            <td><img src="uploads/imgClientSays/{{$value->cs_thunbar}}" alt="" style="width: 80px;height: 80px"></td>
                                            @if($value->cs_status == 1)
                                                <td>
                                                    <a href="{{route('admin.clientSays.status',[$value->id,$value->cs_status])}}"><span class="btn btn-xs btn-success" style="margin-top: 10px;">Hiển thị</span></a>
                                                </td>
                                            @else
                                                <td>
                                                    <a href="{{route('admin.clientSays.status',[$value->id,$value->cs_status])}}"><span class="btn btn-xs btn-default" style="margin-top: 10px;">Ẩn</span></a>
                                                </td>
                                            @endif
                                            <td>
                                                <a href="{{route('admin.clientSays.edit',$value->id)}}" class="btn btn-xs btn-warning" style="margin-top: 10px;">Sửa </a>
                                            </td> 
                                            <td>
                                                <a href="{{route('admin.clientSays.delete',$value->id)}}" class="btn btn-xs btn-danger" style="margin-top: 10px;">Xóa </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{$showBlog->links()}}
                            
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