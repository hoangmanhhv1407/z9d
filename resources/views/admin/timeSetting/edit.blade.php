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
                            <a href="index.html">Cấu hình khuyến mại / Edit</a>
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
                                        <div class="col-12">
                                            @if (Request::segment(4) != 3)
                                                <div class="form-group">
                                                    <label for="inputEmail3" class="col-sm-4 control-label"> Khuyến mại gấp:
                                                    </label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" name="number"
                                                            value="{{ $promotion->number }}" autocomplete="off">
                                                    </div>
                                                </div>
                                            @endif
                                            @if (Request::segment(4) == 2)
                                                <div class="form-group">
                                                    <label for="inputEmail3" class="col-sm-4 control-label"> Level tối
                                                        thiểu: </label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" name="min_level"
                                                            value="{{ $promotion->min_level }}" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail3" class="col-sm-4 control-label"> Tối đa mỗi
                                                        tuần: </label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" name="per_week"
                                                            value="{{ $promotion->per_week }}" autocomplete="off">
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label" style='padding-top: 2px;'>
                                                    Trạng thái </label>
                                                <div class="col-sm-8">
                                                    <div style="display: inline-block;margin-right: 15px;">
                                                        <input type="radio" name="status" value="1"
                                                            {{ $promotion->status == 1 ? 'checked' : '' }}
                                                            style='opacity: 1;top: 5px;left:17px'>
                                                        Hoạt động
                                                    </div>
                                                    <div style="display: inline-block;margin-right: 15px;">
                                                        <input type="radio" name="status" value="2"
                                                            {{ $promotion->status == 2 ? 'checked' : '' }}
                                                            style='opacity: 1;top: 5px;left:17px'>
                                                        Hết hạn
                                                    </div>
                                                </div>
                                            </div>
                                            @if (Request::segment(4) == 3)
                                                <div class="form-group show-choice set-interval">
                                                    <label for="inputEmail3" class="col-sm-4 control-label">Số ngày tính
                                                        rank (từ lúc tạo): </label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" name="day_loop"
                                                            value="{{ $promotion->min_level }}" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class=" show-choice set-date">
                                                    <div class="form-group">
                                                        <label for="inputEmail3" class="col-sm-4 control-label"> Ngày bắt
                                                            đầu:</label>
                                                        <div class="col-sm-8">
                                                            <input type="date" class="form-control" name="day_start"
                                                                value="{{ $promotion->per_week }}" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputEmail3" class="col-sm-4 control-label"> Ngày kết
                                                            thúc: </label>
                                                        <div class="col-sm-8">
                                                            <input type="date" class="form-control" name="day_end"
                                                                value="{{ $promotion->per_week }}" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group text-center">
                                                    <div class="btn btn-primary select-prom" cur="set-interval">Chọn ngày
                                                        bắt đầu/kết
                                                        thúc</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                    <!-- /.box-body -->
                                    <div class="box-footer text-center">
                                        <a href="{{ route('admin.timeSetting.index') }}"
                                            class="btn btn-xs btn-danger">Quay lại</a>
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
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.set-date').hide();
            const inputDayloop = $('input[name="day_loop"]');
            const inputDayStart = $('input[name="day_start"]');
            const inputDayEnd = $('input[name="day_end"]');

            inputDayloop.on('change', function() {
                inputDayStart.val('');
                inputDayEnd.val('');
            });
            inputDayStart.on('change', function() {
                inputDayloop.val('');
            });
            inputDayEnd.on('change', function() {
                inputDayloop.val('');
            });
            $('.select-prom').click(function(e) {
                const currentButton = $(e.target);
                const currentForm = $(this).attr('cur');
                inputDayloop.val('');
                inputDayStart.val('');
                inputDayEnd.val('');
                if (currentForm == 'set-interval') {
                    currentButton.text('Chọn số ngày tính rank (từ lúc tạo)');
                    currentButton.attr('cur', 'set-date');
                    $('.set-interval').hide();
                    $('.set-date').show();
                } else {
                    currentButton.text('Chọn ngày bắt đầu/kết thúc');
                    currentButton.attr('cur', 'set-interval');
                    $('.set-date').hide();
                    $('.set-interval').show();
                }
            })
        });
    </script>
@endsection
