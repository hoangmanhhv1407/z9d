@extends('admin.layout.main')
@section('content')
    @include('admin/layout/header')
    <div class="clearfix"></div>
    <div class="page-container">
        @include('admin/layout/sidebar')
        <div class="page-content-wrapper">
            <div class="page-content">
                <!-- Alert Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle mr-2"></i>
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('danger'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa fa-exclamation-circle mr-2"></i>
                        {{ session('danger') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Breadcrumb -->
                <div class="page-bar mb-3">
                    <ul class="page-breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="{{ route('admin.product.index') }}">Sản phẩm</a>
                            <i class="fa fa-angle-right"></i>
                            <span>Thêm mới</span>
                        </li>
                    </ul>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Thêm sản phẩm mới</h4>
                                <p class="card-subtitle text-muted">Điền đầy đủ thông tin bên dưới để thêm sản phẩm mới</p>
                            </div>
                            <div class="card-body">
                                <form action="" method="post" enctype="multipart/form-data" id="product-form">
                                    @csrf
                                    <div class="row">
                                        <!-- Left Column - Image and Status -->
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="control-label">Hình ảnh <span class="text-danger">*</span></label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="prd_thunbar" id="imgInp">
                                                    <label class="custom-file-label" for="imgInp">Chọn file...</label>
                                                </div>
                                                @error('prd_thunbar')
                                                    <div class="invalid-feedback d-block">
                                                        <i class="fa fa-exclamation-circle"></i> {{ $message }}
                                                    </div>
                                                @enderror
                                                <small class="form-text text-muted">
                                                    Định dạng cho phép: JPG, PNG, GIF (Max: 2MB)
                                                </small>
                                                <div class="preview-image mt-2">
                                                    <img id="blah" src="#" alt="Preview" class="img-thumbnail" style="max-width: 200px; display: none">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Trạng thái hiển thị</label>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="status1" name="prd_status" value="1" class="custom-control-input" {{ old('prd_status', 1) == 1 ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="status1">Hiển thị</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="status0" name="prd_status" value="0" class="custom-control-input" {{ old('prd_status') == 0 ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="status0">Ẩn</label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Lượt mua/tuần</label>
                                                <input type="number" class="form-control" name="turn" value="{{ old('turn', 0) }}" min="0">
                                                @error('turn')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Right Column - Product Details -->
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label class="control-label">Danh mục <span class="text-danger">*</span></label>
                                                <select class="form-control @error('prd_category_product_id') is-invalid @enderror" name="prd_category_product_id">
                                                    <option value="">-- Chọn danh mục --</option>
                                                    @foreach($category as $item)
                                                        <option value="{{ $item->id }}" {{ old('prd_category_product_id') == $item->id ? 'selected' : '' }}>
                                                            {{ $item->cpr_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('prd_category_product_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Tên sản phẩm <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('prd_name') is-invalid @enderror" 
                                                       name="prd_name" value="{{ old('prd_name') }}"
                                                       placeholder="Nhập tên sản phẩm">
                                                @error('prd_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Mã sản phẩm <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('prd_code') is-invalid @enderror" 
                                                       name="prd_code" value="{{ old('prd_code') }}"
                                                       placeholder="Nhập mã sản phẩm">
                                                @error('prd_code')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Giá (xu) <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control @error('coin') is-invalid @enderror" 
                                                       name="coin" value="{{ old('coin', 0) }}" min="0">
                                                @error('coin')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Tích lũy</label>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" id="accumulate1" name="accumulate_status" value="1" 
                                                                   class="custom-control-input" {{ old('accumulate_status') == 1 ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="accumulate1">Bật</label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" id="accumulate0" name="accumulate_status" value="0" 
                                                                   class="custom-control-input" {{ old('accumulate_status', 0) == 0 ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="accumulate0">Tắt</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="number" class="form-control @error('accu') is-invalid @enderror" 
                                                               name="accu" value="{{ old('accu') }}" 
                                                               placeholder="Điểm tích lũy">
                                                        @error('accu')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Tỉ lệ vòng quay (%)</label>
                                                <input type="number" class="form-control @error('ratioLucky') is-invalid @enderror" 
                                                       name="ratioLucky" value="{{ old('ratioLucky', 0) }}" 
                                                       step="0.01" min="0" max="32">
                                                @error('ratioLucky')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="form-text text-muted">Giá trị từ 0 đến 32%</small>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Mô tả ngắn</label>
                                                <textarea name="prd_description" class="form-control summernote" 
                                                          rows="5">{{ old('prd_description') }}</textarea>
                                                @error('prd_description')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Nội dung chi tiết <span class="text-danger">*</span></label>
                                                <textarea name="prd_content" class="form-control summernote @error('prd_content') is-invalid @enderror" 
                                                          rows="10">{{ old('prd_content') }}</textarea>
                                                @error('prd_content')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions text-center mt-4">
                                        <a href="{{ route('admin.product.index') }}" class="btn btn-secondary">
                                            <i class="fa fa-arrow-left mr-2"></i>Quay lại
                                        </a>
                                        <button type="submit" class="btn btn-primary ml-2">
                                            <i class="fa fa-save mr-2"></i>Thêm mới
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
    // Preview image before upload
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                $('#blah').attr('src', e.target.result).show();
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#imgInp").change(function() {
        readURL(this);
        // Update custom file label
        $(this).next('.custom-file-label').html(this.files[0].name);
    });

    // Initialize summernote
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
            ],
            callbacks: {
                onImageUpload: function(files) {
                    // Custom image upload handling if needed
                }
            }
        });

        // Form validation
        $('#product-form').submit(function(e) {
            var requiredFields = $(this).find('[required]');
            var hasError = false;
            
            requiredFields.each(function() {
                if (!$(this).val()) {
                    $(this).addClass('is-invalid');
                    hasError = true;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if (hasError) {
                e.preventDefault();
                toastr.error('Vui lòng điền đầy đủ thông tin bắt buộc');
            }
        });
    });
</script>
@endsection