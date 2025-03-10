@extends('admin.layout.main') @section('content')
    @include('admin/layout/header')<div class="clearfix"></div>
    <div class="page-container">@include('admin/layout/sidebar')<div class="page-content-wrapper">
            <div class="page-content">@include('admin/layout/message')<div class="page-bar">
                    <ul class="page-breadcrumb">
                        <li><i class="fa fa-home"></i> <a href="index.html">Cấu hình khuyến mãi nạp</a></li>
                    </ul>
                </div>
                <div class="inbox">
                    <div class="box box-info">
                        <div class="table-responsive">
                            <table class="no-margin table">
                                <thead>
                                    <tr>
                                        <th>Tên</th>
                                        <th>Khuyến mãi gấp (lần)</th>
                                        <th>Level tối thiểu</th>
                                        <th>Tối đa mỗi tuần</th>
                                        <th>Số ngày tính rank</th>
                                        <th>Ngày bắt đầu</th>
                                        <th>Ngày kết thúc</th>
                                        <th>Trạng thái</th>
                                        <th>Sửa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $name_time = ['coin thưởng' , 'tích luỹ', 'Thời xếp rank']?>
                                    @foreach ($setting as $key => $value)
                                        <tr>
                                            <td>{{ $name_time[$key]  }}</td>
                                            <td>{{ isset($value->number)?$value->number:'' }}</td>
                                            <td>{{ $key === 1 ? $value->min_level : '' }}</td>
                                            <td>{{ $key === 1 ? $value->per_week : '' }}</td>
                                            <td>{{ $key === 2?$value->day_loop:'' }}</td>
                                            <td>{{ $key === 2?$value->day_start:'' }}</td>
                                            <td>{{ $key === 2?$value->day_end:'' }}</td>
                                            <td>{{ $value->status== 1?'kích hoạt':'tắt'}}</td>
                                            <td><a href="{{ route('admin.timeSetting.edit', $value->id) }}"
                                                    class="btn btn-warning btn-xs" style="margin-top:10px">Sửa</a></td>
                                        </tr>
                                        @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-footer">
        <div class="page-footer-inner"></div>
        <div class="scroll-to-top"><i class="icon-arrow-up"></i></div>
    </div>
@endsection
