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
                            <a href="index.html">Admin thêm xu</a>
                        </li>
                    </ul>
                </div>
                <div class="inbox">
                    <div class="box">
                        <div class="box-body ">
                            <!-- Horizontal Form -->
                            <div class="box box-primary clearfix">
                                <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="box-body clearfix">
                                        <div class="col-sm-2">
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-2 control-label">Tài khoản</label>
                                                <div class="col-sm-10">
                                                    <select class="js-example-responsive" name="user" style="width: 100%">
                                                        <option value="">-- Chọn --</option>
                                                        @foreach($user as $value)
                                                            <option value="{{$value->id}}">{{$value->userid}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-2 control-label">Số lượng xu</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="coin" value="" class="form-control" style="width: 100%">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <hr>
                                    <!-- /.box-body -->
                                    <div class="box-footer text-center">
                                        <a href="{{ route('admin.transactionHistory.index') }}" class="btn btn-danger">Quay lại</a>
                                        <button type="submit" class="btn btn-info">Xác nhận</button>
                                    </div>
                                    <!-- /.box-footer -->
                                </form>
                            </div>
                        </div>
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

@section('script')
    <script>
        $(document).ready(function() {
            $(".js-example-responsive").select2({
                width: 'resolve' // need to override the changed default
            });
        });
    </script>
@endsection