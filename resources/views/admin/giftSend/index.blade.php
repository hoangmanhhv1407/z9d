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
                            <a href="index.html">Giftcode / Edit</a>
                        </li>
                    </ul>
                </div>
                <div class="inbox">
                    <div class="box">
                        <div class="box-body ">
                            <!-- Horizontal Form -->
                            <div class="box box-primary clearfix">
                                <form class="form-horizontal" action="{{route('admin.giftSend.update')}}" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="box-body clearfix">
                                        <div class="col-sm-2">
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-2 control-label"> Gói VIP </label>
                                                <div class="col-sm-4">
                                                    <label for="inputEmail3" class="col-sm-2 control-label" style="white-space: nowrap;"> Chọn quà </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label for="inputEmail3" class="col-sm-2 control-label" style="white-space: nowrap;"> Lượt quay </label>
                                                </div>
                                            </div>
                                            @foreach($giftSend as $value)
                                                <div class="form-group">
                                                    <label for="inputEmail3" class="col-sm-2 control-label"> {{$value->giftCoin}} </label>
                                                    <div class="col-sm-4">
                                                        <select name="{{$value->giftCoin}}" id="" class="form-control" style="margin-right: 5px">
                                                            <option value="" >-- Chọn vật phẩm--</option>
                                                            @foreach($product as $key => $item)
                                                                <option value="{{$item->id}}" {{$item->id == $value->product ? 'selected' :''}}>{{$item->prd_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input placeholder="Nhập lượt quay" type="number" name="lucky{{$value->giftCoin}}" value="{{$value->luckyNumber}}" class="form-control" style="width: 100%">
                                                    </div>
                                                </div>

                                            @endforeach
                                        </div>
                                    </div>
                                    <hr>
                                    <!-- /.box-body -->
                                    <div class="box-footer text-center">
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
