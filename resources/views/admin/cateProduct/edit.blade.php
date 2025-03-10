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
                                <a href="index.html">CataProduct / Edit</a>
                            </li>
                        </ul>
                    </div>
                    <div class="inbox">
                        <div class="box">
				            <div class="box-body ">
				                    <!-- Horizontal Form -->
				                    <div class="box box-primary clearfix">
				                        <form class="form-horizontal" action="" method="POST">
				                        	 <input type="hidden" name="_token" value="{{ csrf_token() }}">
					                            <div class="box-body clearfix">
					                                <div class="col-sm-8 col-md-offset-2">
					                                    <div class="form-group">
					                                        <label for="inputEmail3" class="col-sm-2 control-label"> Tiêu đề </label>
					                                        <div class="col-sm-10">
					                                            <input type="text" class="form-control" name="cpr_name" value="{{ $editCateProduct->cpr_name }}"  placeholder="The title can not exceed 200 words" autocomplete="off">
					                                        </div>
					                                    </div>
														<div class="form-group">
															<label for="" class="col-sm-2 control-label" style='padding-top: 2px;'> Trạng thái </label>
															<div class="col-sm-10">
																<div style="display: inline-block;margin-right: 15px;">
																	<input type="radio" name="cpr_active" value="1"  {{$editCateProduct->cpr_active == 1?'checked':''}} style='opacity: 1;top: 5px;left:17px'>
																	Hiển thị
																</div>
																<div style="display: inline-block;margin-right: 15px;">
																	<input type="radio" name="cpr_active" value="0"   {{$editCateProduct->cpr_active == 0?'checked':''}} style='opacity: 1;top: 5px;left:17px'>
																	Ẩn
																</div>
															</div>
														</div>
					                                    
					                                </div>
					                            </div>
					                            <hr>
					                            <!-- /.box-body -->
					                           <div class="box-footer text-center">
							                        <a href="{{ route('admin.cateProduct.index') }}" class="btn btn-danger">Quay lại</a>
							                        <button type="submit" class="btn btn-info">Sửa</button>
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
