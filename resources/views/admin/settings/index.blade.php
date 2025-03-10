@extends('admin.layout.main')
@section('content')
    @include('admin/layout/header')
    <div class="clearfix"></div>
    <div class="page-container">
        @include('admin/layout/sidebar')
        <div class="page-content-wrapper">
            <div class="page-content">
                <div class="page-bar">
                    <ul class="page-breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="index.html">Cài đặt</a>
                        </li>
                    </ul>
                </div>
                @include('admin/layout/message')

                <div class="inbox">
                    <div class="box">
                        <div class="box-body ">
                            <!-- Horizontal Form -->
                            <div class="box box-primary clearfix">
                                <form class="form-horizontal" action="{{route('admin.settings.index.update')}}" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="box-body clearfix row">
{{--                                        <div class="col-md-6 ">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label for="inputEmail3" class="col-sm-2 control-label"> Logo </label>--}}
{{--                                                <div class="col-sm-10">--}}
{{--                                                    <input type="file" class="form-control" name="logo" id="imgInp">--}}
{{--                                                </div>--}}
{{--                                                <div class="col-sm-10" style="margin-top: 10px;margin-left: 17%">--}}
{{--                                                    <img src="{{asset('/uploads/logo/'.$settingOptions['logo'])}}" alt="" class="img img-responsive" id="blah" title="Logo" style="max-width:400px;height: 150px;border: 1px solid #dedede">--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label for="inputEmail3" class="col-sm-2 control-label"> Tiêu đề Website </label>--}}
{{--                                                <div class="col-sm-10">--}}
{{--                                                    <input type="text" class="form-control" name="title" value="{{$settingOptions['title']}}"  placeholder="" autocomplete="off">--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label for="inputEmail3" class="col-sm-2 control-label"> Khẩu hiệu </label>--}}
{{--                                                <div class="col-sm-10">--}}
{{--                                                    <input type="text" class="form-control" name="slogan" value="{{$settingOptions['slogan']}}"  placeholder="" autocomplete="off">--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label for="inputEmail3" class="col-sm-2 control-label"> Số điện thoại </label>--}}
{{--                                                <div class="col-sm-10">--}}
{{--                                                    <input type="text" class="form-control" name="telephone" value="{{$settingOptions['telephone']}}"  placeholder="" autocomplete="off">--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label for="inputEmail3" class="col-sm-2 control-label"> Email </label>--}}
{{--                                                <div class="col-sm-10">--}}
{{--                                                    <input type="text" class="form-control" name="email" value="{{$settingOptions['email']}}"  placeholder="" autocomplete="off">--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label for="inputEmail3" class="col-sm-2 control-label"> Địa chỉ </label>--}}
{{--                                                <div class="col-sm-10">--}}
{{--                                                    <input type="text" class="form-control" name="address" value="{{$settingOptions['address']}}"  placeholder="" autocomplete="off">--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label for="inputEmail3" class="col-sm-2 control-label"> Link zalo </label>--}}
{{--                                                <div class="col-sm-10">--}}
{{--                                                    <input type="text" class="form-control" name="zalo" value="{{$settingOptions['zalo']}}"  placeholder="" autocomplete="off">--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label for="inputEmail3" class="col-sm-2 control-label"> Link youtube </label>--}}
{{--                                                <div class="col-sm-10">--}}
{{--                                                    <input type="text" class="form-control" name="youtube" value="{{$settingOptions['youtube']}}"  placeholder="" autocomplete="off">--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label for="inputEmail3" class="col-sm-2 control-label"> Link Facebook </label>--}}
{{--                                                <div class="col-sm-10">--}}
{{--                                                    <input type="text" class="form-control" name="link_facebook" value="{{$settingOptions['link_facebook']}}"  placeholder="" autocomplete="off">--}}
{{--                                                </div>--}}
{{--                                            </div>--}}

{{--                                        </div>--}}
                                        <div class="col-md-3 "></div>
                                        <div class="col-md-6 ">
                                            <div class="form-group" style="margin-bottom: 0">
                                                <label  class="col-sm-12 control-label" style="text-align: left"> Download </label>
                                                <div class="col-sm-12" >
                                                    <textarea name="download" cols="10" rows="10" class="form-control summernote" placeholder="">{{$settingOptions['download']}}</textarea>

                                                </div>
                                            </div>
                                            <div class="form-group" style="margin-bottom: 0">
                                                <label  class="col-sm-12 control-label" style="text-align: left"> Giới thiệu </label>
                                                <div class="col-sm-12" >
                                                    <textarea name="introduce" cols="10" rows="10" class="form-control summernote" placeholder="">{{$settingOptions['introduce']}}</textarea>

                                                </div>
                                            </div>
{{--                                            <div class="form-group">--}}
{{--                                                <label class="col-sm-12 control-label" style="text-align: left"> Giới thiệu về công ty (link video youtube) </label>--}}
{{--                                                <div class="col-sm-12">--}}
{{--                                                    <input type="text" class="form-control" name="company" value="{{$settingOptions['company']}}"  placeholder="" autocomplete="off">--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
                                        </div>
                                    </div>

                                    <hr>
                                    <!-- /.box-body -->
                                    <div class="box-footer text-center">
                                        <button type="submit" class="btn btn-success">Xác nhận</button>
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
