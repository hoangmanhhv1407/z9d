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
                            <div class="box-body">
                                <!-- Horizontal Form -->
                                <div class="box box-primary clearfix">
                                    <form class="form-horizontal" action="" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="box-body clearfix">
                                            <div class="col-sm-8 col-md-offset-2">
                                                <div class="form-group">
                                                    <label for="inputEmail3" class="col-sm-2 control-label">Tiêu đề</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="cpo_name" value="{{ $blog->cpo_name }}" placeholder="" autocomplete="off">
                                                        @if($errors->first('cpo_name'))
                                                            <span class="text-danger">{{ $errors->first('cpo_name') }}</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Thêm phần upload ảnh -->
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Hình ảnh</label>
                                                    <div class="col-sm-10">
                                                        @if($blog->b_thunbar)
                                                            <div class="mb-2">
                                                                <img src="{{ asset('storage/'.$blog->b_thunbar) }}" 
                                                                     alt="Current image" 
                                                                     style="max-width: 200px; height: auto; margin-bottom: 10px;">
                                                            </div>
                                                        @endif
                                                        
                                                        <input type="file" 
                                                               name="b_thunbar" 
                                                               accept="image/jpeg,image/png,image/gif"
                                                               class="form-control">
                                                        @if($errors->first('b_thunbar'))
                                                            <span class="text-danger">{{ $errors->first('b_thunbar') }}</span>
                                                        @endif
                                                        <small class="text-muted">Chấp nhận file: JPG, PNG, GIF. Kích thước tối đa: 2MB</small>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-2 control-label" style='padding-top: 2px;'>Trạng thái</label>
                                                    <div class="col-sm-10">
                                                        <div style="display: inline-block;margin-right: 15px;">
                                                            <input type="radio" name="cpo_active" value="1" {{$blog->cpo_active == 1?'checked':''}} style='opacity: 1;top: 5px;left:17px'>
                                                            Hiển thị
                                                        </div>
                                                        <div style="display: inline-block;margin-right: 15px;">
                                                            <input type="radio" name="cpo_active" value="0" {{$blog->cpo_active == 0?'checked':''}} style='opacity: 1;top: 5px;left:17px'>
                                                            Ẩn
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="box-footer text-center">
                                            <a href="{{ route('admin.cateBlog.index') }}" class="btn btn-danger">Quay lại</a>
                                            <button type="submit" class="btn btn-info">Sửa</button>
                                        </div>
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