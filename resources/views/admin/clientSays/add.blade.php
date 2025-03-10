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
                                <a href="index.html">Client Says / Add</a>
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
					                                <div class="col-sm-4">
					                                    <div class="form-group">
					                                        <label for="inputEmail3" class="col-sm-2 control-label"> Hình ảnh   </label>
					                                        <div class="col-sm-10">
					                                            <input type="file" class="form-control" name="cs_thunbar" id="imgInp">
					                                            @if($errors->first('cs_thunbar'))
					                                                <span class="text-danger">{{ $errors->first('cs_thunbar') }}</span>
					                                            @endif
					                                        </div>
					                                        <div class="col-sm-10" style="margin-top: 10px;margin-left: 17%">
					                                            <img src="" alt="" class="img img-responsive" id="blah" title=" Logo " style="width: 100%;height: 258px;border: 1px solid #dedede">
					                                        </div>
					                                    </div>
					                                    <div class="form-group">
					                                        <label for="" class="col-sm-2 control-label" style='padding-top: 2px;'> Trạng thái </label>
					                                        <div class="col-sm-10">
					                                        	<div style="display: inline-block;margin-right: 15px;">
					                                        		<input type="radio" name="cs_status" value="1"  checked="" style='opacity: 1;top: 5px;left:17px'>
			                                                    	Hiển thị
					                                        	</div>
			                                                    <div style="display: inline-block;margin-right: 15px;">
			                                                    	<input type="radio" name="cs_status" value="0"   style='opacity: 1;top: 5px;left:17px'>
			                                                    	Ẩn
			                                                    </div>
					                                        </div>
					                                    </div>
					                                </div>
					                                <div class="col-sm-8">

														<div class="form-group">
															<label for="inputEmail3" class="col-sm-2 control-label"> Tên </label>
															<div class="col-sm-10">
																<input type="text" class="form-control" name="cs_name" value="{{ old('cs_name') }}"  placeholder="" autocomplete="off">
																@if($errors->first('cs_name'))
																	<span class="text-danger">{{ $errors->first('cs_name') }}</span>
																@endif
															</div>
														</div>
														<div class="form-group">
															<label for="inputEmail3" class="col-sm-2 control-label"> Chức vụ </label>
															<div class="col-sm-10">
																<input type="text" class="form-control" name="cs_service" value="{{ old('cs_service') }}"  placeholder="" autocomplete="off">
																@if($errors->first('cs_service'))
																	<span class="text-danger">{{ $errors->first('cs_service') }}</span>
																@endif
															</div>
														</div>
					                                    <div class="form-group" >
							                                <label for="inputEmail3" class="col-sm-2 control-label" > Nội dung </label>
							                                <div class="col-sm-10" >
							                                    <textarea name="cs_content"  cols="10" rows="10" class="form-control summernote" placeholder=" Please enter your message">{{ old('cs_content') }}</textarea>
							                                    @if($errors->first('cs_content'))
							                                        <span class="text-danger">{{ $errors->first('cs_content') }}</span>
							                                    @endif
							                                </div>
							                                <div class="clearfix"></div>
							                            </div>
					                                    
					                                </div>
					                            </div>
					                            <hr>
					                            <!-- /.box-body -->
					                           <div class="box-footer text-center">
							                        <a href="{{ route('admin.clientSays.index') }}" class="btn btn-danger">Quay lại</a>
							                        <button type="submit" class="btn btn-info">Thêm mới</button>
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
