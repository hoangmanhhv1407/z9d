@extends('frontend.layout.main')
@section('content')
<div class="container-fluid mt-5 px-4">
    <h1 class="text-center mb-5 text-primary fw-bold">Cửa Hàng Vật Phẩm Game - Mua Nhanh</h1>

    @foreach ($categories as $category)
        @if (count($category->products) > 0)
            <div class="card mb-5 shadow">
                <div class="card-header bg-gradient text-white" style="background-color: #ff6b6b;">
                    <h2 class="h4 mb-0"><i class="fas fa-gamepad me-2"></i>{{ $category->cpr_name }}</h2>
                </div>
                <div class="card-body">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                        @foreach ($category->products as $product)
                            <div class="col">
                                <div class="card h-100 shadow-sm">
                                    <img src="{{ asset('/uploads/imgProduct/' . $product->prd_thunbar) }}" class="card-img-top" alt="{{ $product->prd_name }}">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $product->prd_name }}</h5>
                                        @if ($product->turn)
                                            <p class="card-text text-muted"><i class="fas fa-redo me-1"></i>Lượt mua còn lại: <span id="turns-{{ $product->id }}">{{ $product->remaining_turns }}</span></p>
                                        @endif
                                        <p class="card-text"><strong>Giá: {{ $product->coin }} <i class="fas fa-coins text-warning"></i></strong></p>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0">
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-primary btn-sm buy-now" data-product-id="{{ $product->id }}"><i class="fas fa-shopping-cart me-1"></i>Mua ngay</button>
                                            <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#productDetail{{ $product->id }}"><i class="fas fa-info-circle me-1"></i>Chi tiết</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="productDetail{{ $product->id }}" tabindex="-1" aria-labelledby="productDetailLabel{{ $product->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-gradient text-white" style="background-color: #4e54c8;">
                                            <h5 class="modal-title" id="productDetailLabel{{ $product->id }}">{{ $product->prd_name }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <img src="{{ asset('/uploads/imgProduct/' . $product->prd_thunbar) }}" class="img-fluid rounded" alt="{{ $product->prd_name }}">
                                                </div>
                                                <div class="col-md-6">
                                                    {!! $product->prd_content !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                            <button class="btn btn-primary buy-now" data-product-id="{{ $product->id }}"><i class="fas fa-shopping-cart me-1"></i>Mua vật phẩm</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>
@endsection

@section('script')
<script src="{{ asset('js/quick-buy.js') }}"></script>
@endsection