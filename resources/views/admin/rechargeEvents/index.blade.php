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
                            <a href="#">Cấu hình sự kiện nạp thẻ</a>
                        </li>
                    </ul>
                </div>
                <div class="inbox">
                    <div class="box">
                        <div class="box-body">
<!-- Form Nạp Lần Đầu -->
<div class="box box-primary clearfix">
    <form class="form-horizontal" action="{{ route('admin.rechargeEvents.updateFirstRecharge') }}" method="post">
        @csrf
        <div class="box-body clearfix">
            <div class="form-group">
                <h3 class="col-sm-12">Nạp lần đầu</h3>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Trạng thái</label>
                <div class="col-sm-10">
                    <select name="first_recharge_status" class="form-control">
                        <option value="1" {{ $config->first_recharge_status ? 'selected' : '' }}>Bật</option>
                        <option value="0" {{ !$config->first_recharge_status ? 'selected' : '' }}>Tắt</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
    <label class="col-sm-2 control-label">Thời gian bắt đầu</label>
    <div class="col-sm-10">
        <input type="datetime-local" name="first_recharge_start_time" 
               class="form-control" 
               value="{{ isset($config->first_recharge_start_time) ? date('Y-m-d\TH:i', strtotime($config->first_recharge_start_time)) : '' }}">
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Thời gian kết thúc</label>
    <div class="col-sm-10">
        <input type="datetime-local" name="first_recharge_end_time" 
               class="form-control" 
               value="{{ isset($config->first_recharge_end_time) ? date('Y-m-d\TH:i', strtotime($config->first_recharge_end_time)) : '' }}">
    </div>
</div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Quà tặng</label>
                <div class="col-sm-10">
                    <div id="first_recharge_gifts">
                        @if(isset($firstRechargeGifts) && $firstRechargeGifts->count() > 0)
                            @foreach($firstRechargeGifts as $gift)
                                <div class="gift-item mb-2">
                                    <div class="input-group">
                                        <select name="first_recharge_gifts[]" class="form-control select2">
                                            <option value="">-- Chọn quà tặng --</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" {{ $gift->product_id == $product->id ? 'selected' : '' }}>
                                                    {{ $product->prd_name }} {{ $product->prd_status == 0 ? '(Đang bị ẩn)' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-danger remove-gift"><i class="fa fa-times"></i></button>
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="gift-item mb-2">
                                <div class="input-group">
                                    <select name="first_recharge_gifts[]" class="form-control select2">
                                        <option value="">-- Chọn quà tặng --</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">
                                                {{ $product->prd_name }} {{ $product->prd_status == 0 ? '(Đang bị ẩn)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-danger remove-gift"><i class="fa fa-times"></i></button>
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn btn-info btn-sm mt-2" onclick="addGift('first_recharge_gifts')">
                        <i class="fa fa-plus"></i> Thêm quà
                    </button>
                </div>
            </div>
        </div>
        <div class="box-footer text-center">
            <button type="submit" class="btn btn-primary">Lưu cấu hình nạp lần đầu</button>
        </div>
    </form>
</div>

                            <!-- Form Nạp Theo Mốc -->
<div class="box box-primary clearfix" style="margin-top: 30px">
    <form class="form-horizontal" action="{{ route('admin.rechargeEvents.updateMilestone') }}" method="post">
        @csrf
        <div class="box-body clearfix">
            <div class="form-group">
                <h3 class="col-sm-12">Nạp theo mốc</h3>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Trạng thái</label>
                <div class="col-sm-10">
                    <select name="milestone_status" class="form-control">
                        <option value="1" {{ $config->milestone_status ? 'selected' : '' }}>Bật</option>
                        <option value="0" {{ !$config->milestone_status ? 'selected' : '' }}>Tắt</option>
                    </select>
                </div>
            </div>
            <!-- Thêm vào form nạp theo mốc -->
<div class="form-group">
    <label class="col-sm-2 control-label">Thời gian bắt đầu</label>
    <div class="col-sm-10">
        <input type="datetime-local" name="milestone_start_time" 
               class="form-control" 
               value="{{ isset($config->milestone_start_time) ? date('Y-m-d\TH:i', strtotime($config->milestone_start_time)) : '' }}">
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Thời gian kết thúc</label>
    <div class="col-sm-10">
        <input type="datetime-local" name="milestone_end_time" 
               class="form-control" 
               value="{{ isset($config->milestone_end_time) ? date('Y-m-d\TH:i', strtotime($config->milestone_end_time)) : '' }}">
    </div>
</div>
            @foreach($milestones as $milestone)
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{ number_format($milestone->amount) }}đ</label>
                    <div class="col-sm-10">
                        <div id="milestone_gifts_{{ $milestone->amount }}">
                            @if(isset($milestone->gifts) && count($milestone->gifts) > 0)
                                @foreach($milestone->gifts as $gift)
                                    <div class="gift-item mb-2">
                                        <div class="input-group">
                                            <select name="milestone_gifts[{{ $milestone->amount }}][]" class="form-control select2">
                                                <option value="">-- Chọn quà tặng --</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" {{ $gift->product_id == $product->id ? 'selected' : '' }}>
                                                        {{ $product->prd_name }} {{ $product->prd_status == 0 ? '(Đang bị ẩn)' : '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-danger remove-gift"><i class="fa fa-times"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="gift-item mb-2">
                                    <div class="input-group">
                                        <select name="milestone_gifts[{{ $milestone->amount }}][]" class="form-control select2">
                                            <option value="">-- Chọn quà tặng --</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">
                                                    {{ $product->prd_name }} {{ $product->prd_status == 0 ? '(Đang bị ẩn)' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-danger remove-gift"><i class="fa fa-times"></i></button>
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-info btn-sm mt-2" onclick="addGift('milestone_gifts_{{ $milestone->amount }}')">
                            <i class="fa fa-plus"></i> Thêm quà
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="box-footer text-center">
            <button type="submit" class="btn btn-primary">Lưu cấu hình nạp theo mốc</button>
        </div>
    </form>
</div>

                            <!-- Form Giờ Vàng -->
                            <div class="box box-primary clearfix" style="margin-top: 30px">
                            <form class="form-horizontal" action="{{ route('admin.rechargeEvents.updateGoldenHour') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="event_type" value="golden_hour">
                                    <div class="box-body clearfix">
                                        <div class="form-group">
                                            <h3 class="col-sm-12">Giờ vàng</h3>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Trạng thái</label>
                                            <div class="col-sm-10">
                                                <select name="golden_hour_status" class="form-control">
                                                    <option value="1" {{ $config->golden_hour_status ? 'selected' : '' }}>Bật</option>
                                                    <option value="0" {{ !$config->golden_hour_status ? 'selected' : '' }}>Tắt</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Thêm vào form giờ vàng -->
<div class="form-group">
    <label class="col-sm-2 control-label">Thời gian diễn ra sự kiện</label>
    <div class="col-sm-10">
        <div class="row">
            <div class="col-sm-6">
                <input type="datetime-local" name="golden_hour_event_start" 
                       class="form-control" 
                       value="{{ isset($config->golden_hour_event_start) ? date('Y-m-d\TH:i', strtotime($config->golden_hour_event_start)) : '' }}"
                       placeholder="Thời gian bắt đầu sự kiện">
            </div>
            <div class="col-sm-6">
                <input type="datetime-local" name="golden_hour_event_end" 
                       class="form-control" 
                       value="{{ isset($config->golden_hour_event_end) ? date('Y-m-d\TH:i', strtotime($config->golden_hour_event_end)) : '' }}"
                       placeholder="Thời gian kết thúc sự kiện">
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Thời gian áp dụng hàng ngày</label>
    <div class="col-sm-10">
        <p class="text-muted">Khoảng thời gian áp dụng x{{ $config->golden_hour_multiplier }} trong ngày</p>
    </div>
</div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Thời gian bắt đầu</label>
                                            <div class="col-sm-10">
                                                <input type="time" name="golden_hour_start_time" 
                                                       class="form-control" 
                                                       value="{{ $config->golden_hour_start_time }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Thời gian kết thúc</label>
                                            <div class="col-sm-10">
                                                <input type="time" name="golden_hour_end_time" 
                                                       class="form-control" 
                                                       value="{{ $config->golden_hour_end_time }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Hệ số nhân</label>
                                            <div class="col-sm-10">
                                                <input type="number" name="golden_hour_multiplier" 
                                                       class="form-control" 
                                                       value="{{ $config->golden_hour_multiplier }}"
                                                       step="0.1" min="1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-footer text-center">
                                        <button type="submit" class="btn btn-primary">Lưu cấu hình giờ vàng</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    $('.select2').select2();

    // Xử lý xóa quà
    $(document).on('click', '.remove-gift', function() {
        if ($(this).closest('.gift-item').siblings('.gift-item').length === 0) {
            // Nếu là item cuối cùng, reset giá trị select thay vì xóa
            $(this).closest('.gift-item').find('select').val('').trigger('change');
        } else {
            $(this).closest('.gift-item').remove();
        }
    });
});

function addGift(containerId) {
    const container = $(`#${containerId}`);
    const milestoneAmount = containerId.includes('milestone_gifts_') ? 
        containerId.split('milestone_gifts_')[1] : null;
    const fieldName = milestoneAmount ? 
        `milestone_gifts[${milestoneAmount}][]` : 'first_recharge_gifts[]';
        
    // Sửa cách khai báo template HTML
    const template = `
        <div class="gift-item mb-2">
            <div class="input-group">
                <select name="${fieldName}" class="form-control select2">
                    <option value="">-- Chọn quà tặng --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">
                            {{ $product->prd_name }} {{ $product->prd_status == 0 ? '(Đang bị ẩn)' : '' }}
                        </option>
                    @endforeach
                </select>
                <span class="input-group-btn">
                    <button type="button" class="btn btn-danger remove-gift"><i class="fa fa-times"></i></button>
                </span>
            </div>
        </div>
    `;
    
    container.append(template);
    container.find('.select2').last().select2();
}
</script>
@endsection