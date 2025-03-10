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
                                <a href="index.html">Liên hệ</a>
                            </li>
                        </ul>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="myModal1" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Nội dung</h4>
                                </div>
                                <div class="modal-body ">
                                    <p class="model-contact">Some text in the modal.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>

                        </div>
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
                                            <th>Email</th>
                                            <th>Số điện thoại</th>
                                            <th>Ngày gửi</th>
                                            <th>Xem</th>
                                            <th>Xóa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($showContact as $key => $value)
                                        <tr>
                                            <td>{{$key + 1}}</td>
                                            <td>{{$value->c_name}}</td>
                                            <td>{{$value->c_email}}</td>
                                            <td>{{$value->c_phone}}</td>
                                            <td>{{$value->created_at}}</td>
                                            <td>
                                                <a class="btn btn-xs btn-warning msContact"  data-id-contact="{{$value->id}}" data-url-contact="{{route('admin.contact.dislay')}}" data-bs-toggle="modal" data-bs-target="#myModal1">Xem</a>
                                            </td> 
                                            <td>
                                                <a href="{{route('admin.blogs.delete',$value->id)}}" class="btn btn-xs btn-danger">Xóa</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{$showContact->links()}}

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