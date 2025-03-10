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
                            <a href="{{ route('admin.product.index') }}">Sản phẩm</a>
                            <i class="fa fa-angle-right"></i>
                            <span>Chỉnh sửa</span>
                        </li>
                    </ul>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-body">
                                <form action="" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <!-- Cột trái - Hình ảnh và trạng thái -->
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="control-label">Hình ảnh</label>
                                                <input type="file" class="form-control" name="prd_thunbar" id="imgInp">
                                                @if($errors->first('prd_thunbar'))
                                                    <span class="text-danger">{{ $errors->first('prd_thunbar') }}</span>
                                                @endif
                                                <div class="preview-image mt-2">
                                                    <img src="{{ Storage::url('products/'.$product->prd_thunbar) }}" 
                                                         alt="Preview" 
                                                         id="blah"
                                                         class="img-fluid"
                                                         style="max-width: 100%; height: auto;">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Trạng thái hiển thị</label>
                                                <div>
                                                    <label class="radio-inline mr-3">
                                                        <input type="radio" name="prd_status" value="1" 
                                                               {{ $product->prd_status == 1 ? 'checked' : '' }}>
                                                        Hiển thị
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="prd_status" value="0" 
                                                               {{ $product->prd_status == 0 ? 'checked' : '' }}>
                                                        Ẩn
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Lượt mua/tuần</label>
                                                <input type="number" class="form-control" name="turn" 
                                                       value="{{ old('turn', $product->turn) }}" min="0">
                                            </div>
                                        </div>

                                        <!-- Cột phải - Thông tin sản phẩm -->
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label class="control-label">Danh mục</label>
                                                <select class="form-control" name="prd_category_product_id">
                                                    <option value="">-- Chọn danh mục --</option>
                                                    @foreach($category as $item)
                                                        <option value="{{ $item->id }}" 
                                                            {{ old('prd_category_product_id', $product->prd_category_product_id) == $item->id ? 'selected' : '' }}>
                                                            {{ $item->cpr_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if($errors->first('prd_category_product_id'))
                                                    <span class="text-danger">{{ $errors->first('prd_category_product_id') }}</span>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Tên sản phẩm</label>
                                                <input type="text" class="form-control" name="prd_name" 
                                                       value="{{ old('prd_name', $product->prd_name) }}">
                                                @if($errors->first('prd_name'))
                                                    <span class="text-danger">{{ $errors->first('prd_name') }}</span>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Mã sản phẩm</label>
                                                <input type="text" class="form-control" name="prd_code" 
                                                       value="{{ old('prd_code', $product->prd_code) }}">
                                                @if($errors->first('prd_code'))
                                                    <span class="text-danger">{{ $errors->first('prd_code') }}</span>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Giá (xu)</label>
                                                <input type="number" class="form-control" name="coin" 
                                                       value="{{ old('coin', $product->coin) }}" min="0">
                                                @if($errors->first('coin'))
                                                    <span class="text-danger">{{ $errors->first('coin') }}</span>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Tích lũy</label>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="radio-inline mr-3">
                                                            <input type="radio" name="accumulate_status" value="1" 
                                                                   {{ old('accumulate_status', $product->accumulate_status) == 1 ? 'checked' : '' }}>
                                                            Bật
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="accumulate_status" value="0" 
                                                                   {{ old('accumulate_status', $product->accumulate_status) == 0 ? 'checked' : '' }}>
                                                            Tắt
                                                        </label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="number" class="form-control" name="accu" 
                                                               value="{{ old('accu', $product->accu) }}" 
                                                               placeholder="Điểm tích lũy">
                                                        @if($errors->first('accu'))
                                                            <span class="text-danger">{{ $errors->first('accu') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Tỉ lệ vòng quay (%)</label>
                                                <input type="number" class="form-control" name="ratioLucky" 
                                                       value="{{ old('ratioLucky', $product->ratioLucky) }}" 
                                                       step="0.01" min="0" max="32">
                                                @if($errors->first('ratioLucky'))
                                                    <span class="text-danger">{{ $errors->first('ratioLucky') }}</span>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Mô tả ngắn</label>
                                                <textarea name="prd_description" class="form-control summernote" 
                                                          rows="5">{{ old('prd_description', $product->prd_description) }}</textarea>
                                                @if($errors->first('prd_description'))
                                                    <span class="text-danger">{{ $errors->first('prd_description') }}</span>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Nội dung chi tiết</label>
                                                <textarea name="prd_content" class="form-control summernote" 
                                                          rows="10">{{ old('prd_content', $product->prd_content) }}</textarea>
                                                @if($errors->first('prd_content'))
                                                    <span class="text-danger">{{ $errors->first('prd_content') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions text-center mt-3">
                                        <a href="{{ route('admin.product.index') }}" class="btn btn-default">
                                            <i class="fa fa-arrow-left"></i> Quay lại
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save"></i> Lưu thay đổi
                                        </button>
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
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                $('#blah').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#imgInp").change(function() {
        readURL(this);
    });

    $(document).ready(function() {
        $('.summernote').summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    });
</script>
@endsection