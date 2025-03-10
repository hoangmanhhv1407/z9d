@extends('admin.layout.main')
@section('content')
    @include('admin/layout/header')
    <div class="clearfix"></div>
    <div class="page-container">
        @include('admin/layout/sidebar')
        <div class="page-content-wrapper">
            <div class="page-content">
                @include('admin/layout/message')
                
                <!-- Tiêu đề trang -->
                <div class="page-bar">
                    <ul class="page-breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="{{ route('admin.product.index') }}">Sản phẩm</a>
                        </li>
                    </ul>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <!-- Card chứa form tìm kiếm -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <form action="" method="GET">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 mb-2">
                                            <div class="form-group">
                                                <label class="control-label mb-2">Tên sản phẩm</label>
                                                <input type="text" name="name" value="{{ Request::get('name') }}" 
                                                    class="form-control" placeholder="Nhập tên sản phẩm">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <div class="form-group">
                                                <label class="control-label mb-2">Danh mục</label>
                                                <select name="category_product" class="form-control">
                                                    <option value="">-- Tất cả danh mục --</option>
                                                    @foreach($category as $value)
                                                        <option value="{{ $value->id }}" 
                                                            {{ Request::get('category_product') == $value->id ? 'selected' : '' }}>
                                                            {{ $value->cpr_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label class="control-label mb-2">&nbsp;</label>
                                            <div>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-search"></i> Tìm kiếm
                                                </button>
                                                <a href="{{ route('admin.product.add') }}" class="btn btn-success">
                                                    <i class="fa fa-plus"></i> Thêm mới
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Card chứa bảng dữ liệu -->
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="text-center" width="50">STT</th>
                                                <th>Tên sản phẩm</th>
                                                <th>Danh mục</th>
                                                <th>Mã sản phẩm</th>
                                                <th class="text-center">Hình ảnh</th>
                                                <th class="text-right">Giá (xu)</th>
                                                <th class="text-center">Trạng thái</th>
                                                <th class="text-center">Tích lũy</th>
                                                <th class="text-center" width="150">Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($showproduct as $key => $value)
                                            <tr>
                                                <td class="text-center">{{ $key + 1 }}</td>
                                                <td>{{ $value->prd_name }}</td>
                                                <td>{{ $value->categoryProduct->cpr_name ?? 'N/A' }}</td>
                                                <td>{{ $value->prd_code }}</td>
                                                <td class="text-center">
                                                    <img src="{{ Storage::url('products/'.$value->prd_thunbar) }}" 
                                                         alt="{{ $value->prd_name }}"
                                                         style="width: 60px; height: 60px; object-fit: cover"
                                                         class="img-thumbnail">
                                                </td>
                                                <td class="text-right">{{ number_format($value->coin) }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('admin.product.status', [$value->id, $value->prd_status]) }}"
                                                       class="btn btn-sm {{ $value->prd_status == 1 ? 'btn-success' : 'btn-secondary' }}">
                                                        {{ $value->prd_status == 1 ? 'Hiển thị' : 'Ẩn' }}
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('admin.product.accumulateStatus', [$value->id, $value->accumulate_status]) }}"
                                                       class="btn btn-sm {{ $value->accumulate_status == 1 ? 'btn-info' : 'btn-secondary' }}">
                                                        {{ $value->accumulate_status == 1 ? 'Bật' : 'Tắt' }}
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="{{ route('admin.product.edit', $value->id) }}" 
                                                           class="btn btn-sm btn-warning"
                                                           title="Sửa">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <a href="{{ route('admin.product.delete', $value->id) }}" 
                                                           class="btn btn-sm btn-danger"
                                                           onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')"
                                                           title="Xóa">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Thông báo số lượng sản phẩm hiển thị -->
                                <div class="mt-3">
                                    <p>Hiển thị {{ $showproduct->count() }} sản phẩm</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
<style>
    .table th {
        background-color: #f8f9fa;
        vertical-align: middle !important;
    }
    .table td {
        vertical-align: middle !important;
    }
    .btn-group {
        display: flex;
        gap: 5px;
    }
    .page-breadcrumb {
        padding: 15px 0;
        margin-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    .card {
        box-shadow: 0 0 10px rgba(0,0,0,.1);
        border: none;
        margin-bottom: 30px;
    }
    .card-body {
        padding: 1.5rem;
    }
</style>
@endsection