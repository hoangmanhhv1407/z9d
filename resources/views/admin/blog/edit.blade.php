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
                                <a href="index.html">Blogs / Edit</a>
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
					                                            <input type="file" class="form-control" name="b_thunbar" id="imgInp" >
					                                        </div>
					                                        <div class="col-sm-10" style="margin-top: 10px;margin-left: 17%">
					                                            <img src="/storage/blogs/{{$blog->b_thunbar}}" alt="" class="img img-responsive" id="blah" title=" Logo " style="width: 100%;height: 258px;border: 1px solid #dedede">
					                                        </div>
					                                    </div>
					                                    <div class="form-group">
					                                        <label for="" class="col-sm-2 control-label" style='padding-top: 2px;'> Trạng thái </label>
					                                        <div class="col-sm-10">
					                                        	<div style="display: inline-block;margin-right: 15px;">
					                                        		<input type="radio" name="b_status" value="1"  {{$blog->b_status == 1?'checked':''}} style='opacity: 1;top: 5px;left:17px'>
                                                                    Hiển thị
					                                        	</div>
			                                                    <div style="display: inline-block;margin-right: 15px;">
			                                                    	<input type="radio" name="b_status" value="0"   {{$blog->b_status == 0?'checked':''}} style='opacity: 1;top: 5px;left:17px'>
                                                                    Ẩn
			                                                    </div>
					                                        </div>
					                                    </div>
														<div class="form-group">
															<label for="" class="col-sm-2 control-label" style='padding-top: 2px;'>  Nổi bật </label>
															<div class="col-sm-10">
																<div style="display: inline-block;margin-right: 15px;">
																	<input type="radio" name="hot" value="1"  {{$blog->hot == 1?'checked':''}} style='opacity: 1;top: 5px;left:17px'>
																	Bật
																</div>
																<div style="display: inline-block;margin-right: 15px;">
																	<input type="radio" name="hot" value="0"   {{$blog->hot == 0?'checked':''}} style='opacity: 1;top: 5px;left:17px'>
																	Tắt
																</div>
															</div>
														</div>

					                                </div>
					                                <div class="col-sm-8">
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-2 control-label"> Danh mục </label>
                                                            <div class="col-sm-10">
                                                                <select class="form-control" name="b_category_id" id="">
                                                                    <option value="">-- Chọn --</option>
                                                                    @foreach($category as $key => $value)
                                                                        <option value="{{$value->id}}" {{$blog->b_category_id == $value->id ? 'selected' : '' }}>{{$value->cpo_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
					                                    <div class="form-group">
					                                        <label for="inputEmail3" class="col-sm-2 control-label"> Tiêu đề </label>
					                                        <div class="col-sm-10">
					                                            <input type="text" class="form-control" name="b_name" value="{{ $blog->b_name }}"  placeholder="The title can not exceed 200 words" autocomplete="off">
					                                        </div>
					                                    </div>
					                                    <div class="form-group" >
							                                <label for="inputEmail3" class="col-sm-2 control-label" > Mô tả ngắn </label>
							                                <div class="col-sm-10" >
							                                    <textarea name="b_description"  cols="10" rows="10" class="form-control summernote" placeholder=" Please enter your message">{{ $blog->b_description }}</textarea>
							                                </div>
							                                <div class="clearfix"></div>
							                            </div>
														<div class="form-group" >
															<label for="inputEmail3" class="col-sm-2 control-label" > Nội dung </label>
															<div class="col-sm-10" >
																<textarea name="b_content"  cols="10" rows="10" class="form-control summernote" placeholder=" Please enter your message">{{ $blog->b_content }}</textarea>
															</div>
															<div class="clearfix"></div>
														</div>

					                                </div>
					                            </div>
					                            <hr>
					                            <!-- /.box-body -->
					                           <div class="box-footer text-center">
							                        <a href="{{ route('admin.blogs.index') }}" class="btn btn-xs btn-danger">Quay lại</a>
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
