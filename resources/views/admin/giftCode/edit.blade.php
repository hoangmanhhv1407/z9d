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
				                        <form class="form-horizontal" action="" method="post">
				                        	 <input type="hidden" name="_token" value="{{ csrf_token() }}">
					                            <div class="box-body clearfix">
					                                <div class="col-sm-2">
					                                </div>
					                                <div class="col-sm-8">
														<div class="form-group">
															<label for="inputEmail3" class="col-sm-2 control-label">Tên giftcode </label>
															<div class="col-sm-10">
																<textarea name="nameGiftcode"  cols="10" rows="10" class="form-control " disabled>{{ $arr }}</textarea>
															</div>
														</div>
					                                    <div class="form-group">
					                                        <label for="inputEmail3" class="col-sm-2 control-label"> Mã giftcode </label>
					                                        <div class="col-sm-10">
					                                            <input type="text" class="form-control" name="giftcode" value="{{ $blog->gift_code }}"   autocomplete="off">
					                                        </div>
					                                    </div>
														<div class="form-group">
															<label for="inputEmail3" class="col-sm-2 control-label"> Số lượng </label>
															<div class="col-sm-10">
																<input type="number" class="form-control" name="qty" value="{{ $blog->qty }}" autocomplete="off">
															</div>
														</div>
														<div class="form-group" >
															<label for="inputEmail3" class="col-sm-2 control-label" > Nội dung </label>
															<div class="col-sm-10" >
																<textarea name="content"  cols="10" rows="10" class="form-control summernote" >{{ $blog->content }}</textarea>
															</div>
															<div class="clearfix"></div>
														</div>
														<div class="form-group">
															<label for="" class="col-sm-2 control-label" style='padding-top: 2px;'> Trạng thái </label>
															<div class="col-sm-10">
																<div style="display: inline-block;margin-right: 15px;">
																	<input type="radio" name="status" value="1"  {{$blog->status == 1?'checked':''}} style='opacity: 1;top: 5px;left:17px'>
																	Hoạt động
																</div>
																<div style="display: inline-block;margin-right: 15px;">
																	<input type="radio" name="status" value="2"   {{$blog->status == 2?'checked':''}} style='opacity: 1;top: 5px;left:17px'>
																	Hết hạn
																</div>
															</div>
														</div>
														<div class="form-group">
															<label for="" class="col-sm-2 control-label" style='padding-top: 2px;'> Loại giftCode </label>
															<div class="col-sm-10">
																<div style="display: inline-block;margin-right: 15px;">
																	<input type="radio" name="type" value="1" disabled {{$blog->type == 1?'checked':''}} style='opacity: 1;top: 5px;left:17px'>
																	1 người dùng - 1 giftCode
																</div>
																<div style="display: inline-block;margin-right: 15px;">
																	<input type="radio" name="type" value="2"  disabled {{$blog->type == 2?'checked':''}}  style='opacity: 1;top: 5px;left:17px'>
																	Nhiều người dùng - 1 giftCode
																</div>
															</div>
														</div>
					                                </div>
					                            </div>
					                            <hr>
					                            <!-- /.box-body -->
					                           <div class="box-footer text-center">
							                        <a href="{{ route('admin.giftCode.index') }}" class="btn btn-xs btn-danger">Quay lại</a>
							                        <button type="submit" class="btn btn-xs btn-info">Sửa</button>
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
