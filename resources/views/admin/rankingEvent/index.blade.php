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
                            <a href="#">Cấu Hình Event Ranking</a>
                        </li>
                    </ul>
                </div>

                <div class="inbox">
                    <div class="box">
                        <div class="box-body">
                            <!-- Cấu hình chung -->
                            <div class="box box-primary clearfix">
                                <form class="form-horizontal" action="{{ route('admin.rankingEvent.update') }}" method="post">
                                    @csrf
                                    <div class="box-body clearfix">
                                        <div class="form-group">
                                            <h3 class="col-sm-12">Cấu Hình Chung</h3>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Trạng thái chung</label>
                                            <div class="col-sm-10">
                                                <select name="event_status" class="form-control">
                                                    <option value="1" {{ $config->status ? 'selected' : '' }}>Bật</option>
                                                    <option value="0" {{ !$config->status ? 'selected' : '' }}>Tắt</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-footer text-center">
                                        <button type="submit" class="btn btn-primary">Lưu cấu hình chung</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Cấu hình phần thưởng cho từng loại ranking -->
                            @php
                                $rankingTypes = [
                                    'recharge' => 'Top Nạp',
                                    'spend' => 'Top Tiêu',
                                    'level' => 'Top Level',
                                    'honor' => 'Top Danh Vọng',
                                    'gong' => 'Top Ác Danh'
                                ];
                            @endphp

                            @foreach($rankingTypes as $type => $label)
                                <div class="box box-primary clearfix" style="margin-top: 20px;">
                                    <form class="form-horizontal" action="{{ route('admin.rankingEvent.updateRewards', ['type' => $type]) }}" method="post">
                                        @csrf
                                        <div class="box-body clearfix">
                                            <div class="form-group">
                                                <h3 class="col-sm-12">{{ $label }}</h3>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Trạng thái</label>
                                                <div class="col-sm-10">
                                                    <select name="status" class="form-control">
                                                        <option value="1" {{ isset($config->type_status[$type]) && $config->type_status[$type] ? 'selected' : '' }}>Bật</option>
                                                        <option value="0" {{ isset($config->type_status[$type]) && !$config->type_status[$type] ? 'selected' : '' }}>Tắt</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Thời gian bắt đầu</label>
                                                <div class="col-sm-10">
                                                    <input type="datetime-local" name="start_time" 
                                                           class="form-control" 
                                                           value="{{ isset($config->type_start_time[$type]) ? date('Y-m-d\TH:i', strtotime($config->type_start_time[$type])) : '' }}">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Thời gian kết thúc</label>
                                                <div class="col-sm-10">
                                                    <input type="datetime-local" name="end_time" 
                                                           class="form-control" 
                                                           value="{{ isset($config->type_end_time[$type]) ? date('Y-m-d\TH:i', strtotime($config->type_end_time[$type])) : '' }}">
                                                </div>
                                            </div>

                                            <hr>
                                            <div class="form-group">
                                                <h4 class="col-sm-12">Phần Thưởng</h4>
                                            </div>

                                            @for($i = 1; $i <= 10; $i++)
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Top {{ $i }}</label>
                                                    <div class="col-sm-10">
                                                        <div id="rank_gifts_{{ $type }}_{{ $i }}">
                                                            @if(isset($rewards[$type][$i]))
                                                                @php 
                                                                 // Chuyển đối tượng thành mảng nếu chỉ có một phần thưởng
																$rankRewards = (is_array($rewards[$type][$i]) || $rewards[$type][$i] instanceof \Illuminate\Support\Collection) 
																			 ? $rewards[$type][$i] 
																			 : [$rewards[$type][$i]];
                                                                @endphp
                                                                @foreach($rankRewards as $reward)
                                                                    <div class="gift-item mb-2">
                                                                        <div class="input-group">
                                                                            <select name="rewards[{{ $type }}][{{ $i }}][]" class="form-control select2">
                                                                                <option value="">-- Chọn phần thưởng --</option>
                                                                                @foreach($products as $product)
                                                                                    <option value="{{ $product->id }}" 
                                                                                        {{ $reward->product_id == $product->id ? 'selected' : '' }}>
                                                                                        {{ $product->prd_name }} {{ $product->prd_status == 0 ? '(Ẩn)' : '' }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            <span class="input-group-btn">
                                                                                <button type="button" class="btn btn-danger remove-gift">
                                                                                    <i class="fa fa-times"></i>
                                                                                </button>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <div class="gift-item mb-2">
                                                                    <div class="input-group">
                                                                        <select name="rewards[{{ $type }}][{{ $i }}][]" class="form-control select2">
                                                                            <option value="">-- Chọn phần thưởng --</option>
                                                                            @foreach($products as $product)
                                                                                <option value="{{ $product->id }}">
                                                                                    {{ $product->prd_name }} {{ $product->prd_status == 0 ? '(Ẩn)' : '' }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        <span class="input-group-btn">
                                                                            <button type="button" class="btn btn-danger remove-gift">
                                                                                <i class="fa fa-times"></i>
                                                                            </button>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <button type="button" class="btn btn-info btn-sm mt-2" 
                                                                onclick="addRankGift('{{ $type }}', {{ $i }})">
                                                            <i class="fa fa-plus"></i> Thêm quà
                                                        </button>
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>
                                        
                                        <div class="box-footer text-center">
                                            <button type="submit" class="btn btn-success">Lưu cấu hình {{ $label }}</button>
                                        </div>
                                    </form>
                                </div>
                            @endforeach
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

function addRankGift(type, rank) {
    const container = $(`#rank_gifts_${type}_${rank}`);
    const template = `
        <div class="gift-item mb-2">
            <div class="input-group">
                <select name="rewards[${type}][${rank}][]" class="form-control select2">
                    <option value="">-- Chọn phần thưởng --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">
                            {{ $product->prd_name }} {{ $product->prd_status == 0 ? '(Ẩn)' : '' }}
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