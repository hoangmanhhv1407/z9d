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
                            <a href="index.html">Gửi quà theo tài khoản</a>
                        </li>
                    </ul>
                </div>
                <div class="inbox">
                    <div class="box">
                        <div class="box-body ">
                            <div class="box box-primary clearfix">
                                <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="box-body clearfix">
                                        <div class="col-sm-2">
                                        </div>
                                        <div class="col-sm-8">
                                            {{-- <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-2 control-label"></label>
                                                <div class="col-sm-10">
                                                    <p style="color: red">* Lưu ý: chỉ được chọn cấp Vip hoặc tài khoản
                                                        để thêm lượt quay</p>
                                                </div>
                                            </div> --}}
                                            <div class="form-group">
                                                <label for="product" class="col-sm-2 control-label">Quà</label>
                                                <div class="col-sm-10">
                                                    <select name="gift" id="product" class="form-control"
                                                        style="margin-right: 5px">
                                                        <option value="">-- Chọn vật phẩm--</option>
                                                        @foreach ($productList as $key => $item)
                                                            <option value="{{ $item->id }}">{{ $item->prd_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-check-label for-user-label  col-sm-2 control-label"
                                                    for="forAllUser">Gửi tất cả</label>
                                                <div class="col-sm-10">
                                                    <input data-toggle="switch" data-on-color="primary"
                                                        data-off-color="primary" type="checkbox" name="all" id="forAllUser">
                                                </div>
                                            </div>
                                            <div class="form-group account-select">
                                                <label for="account" class="col-sm-2 control-label">Tài
                                                    khoản </label>
                                                <div class="col-sm-10">
                                                    <select class="form-control" id="account" name="user"
                                                        style="width: 100%">
                                                        <option value="">-- Chọn --</option>
                                                        @foreach ($user as $value)
                                                            <option value="{{ $value->id }}">{{ $value->userid }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-2 control-label">Lượt
                                                    quay</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="turn" value="" class="form-control"
                                                        style="width: 100%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <!-- /.box-body -->
                                    <div class="box-footer text-center">
                                        <a href="{{ route('admin.giftSendHistory.index') }}" class="btn btn-danger">Quay
                                            lại</a>
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
            $("#product").select2({
                width: 'resolve' // need to override the changed default
            });
            $("#account").select2({
                width: 'resolve' // need to override the changed default
            });
            $("#forAllUser").bootstrapSwitch().on('switchChange.bootstrapSwitch', function(event, state) {
                if (state == true) {
                    $(".account-select").hide();
                } else {
                    $(".account-select").show();
                }
            });
        });
    </script>
@endsection
