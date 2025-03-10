@extends('frontend.layout.main')
@section('content')
<div class="container-fluid mt-5 px-4">
    <h1 class="text-center mb-5 text-primary fw-bold">Cửa Hàng Vật Phẩm Game</h1>

    @if (count($accumulateList) > 0)
        <div class="card mb-5 shadow">
            <div class="card-header bg-gradient text-white" style="background-color: #4e54c8;">
                <h2 class="h4 mb-0"><i class="fas fa-star me-2"></i>Shop Tích Lũy</h2>
            </div>
            <div class="card-body">
                <div class="row align-items-center mb-4">
                    <div class="col-md-6">
                        <p class="mb-0"><i class="fas fa-info-circle me-2"></i>Điểm tích lũy: Khi tiêu 10 xu KTC sẽ nhận được 1 điểm tích lũy</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-0">Tài khoản của bạn hiện có <span class="badge bg-success fs-5 accumulate-point">{{ $user->accumulate }}</span> điểm tích luỹ</p>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th></th>
                                <th>Hình ảnh</th>
                                <th>Thông tin</th>
                                <th>Giá</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($accumulateList as $value)
                            <tr>
                                <td><img src="frontend/images/new1.gif" width="25" height="25" alt="New" class="me-2"><span class="badge bg-warning text-dark">Mới</span></td>
                                <td><img src="{{ asset('/uploads/imgProduct/' . $value->prd_thunbar) }}" width="70" height="70" alt="{{ $value->prd_name }}" class="img-thumbnail"></td>
                                <td>
                                    <h5 class="mb-1">{{ $value->prd_name }}</h5>
                                    <small class="text-muted">{!! $value->prd_description !!}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info fs-6">{{ $value->accu }} <i class="fas fa-coins ms-1"></i></span>
                                </td>
                                <td>
                                    <button type="button" value-id="{{ $value->id }}" class="btn btn-sm btn-success accu-buy accu-buy-{{ $value->id }}" onclick="confirmAccumulate(event)"><i class="fas fa-shopping-cart me-1"></i>Nhận ngay</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if (count($cart) > 0)
        <a href="{{ route('frontend.cart.order') }}" class="btn btn-primary btn-lg mb-4">
            <i class="fas fa-shopping-cart me-2"></i> Xem giỏ hàng <span class="badge bg-light text-dark ms-1">{{ count($cart) }}</span>
        </a>
    @endif

    @foreach ($postList as $key2 => $item)
        @if (count($item->product) > 0)
            <div class="card mb-5 shadow">
                <div class="card-header bg-gradient text-white" style="background-color: #ff6b6b;">
                    <h2 class="h4 mb-0"><i class="fas fa-gamepad me-2"></i>{{ $item->cpr_name }}</h2>
                </div>
                <div class="card-body">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                        @foreach ($item->product as $key => $value)
                            <?php
                            $date = \Carbon\Carbon::now();
                            $start = $date->copy()->startOfWeek();
                            $end = $date->copy()->endOfWeek();
                            $shoppingDay = 0;
                            if (isset($user)) {
                                $shoppingDay = \App\Models\TransactionHistory::where('user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id)
                                    ->where('product_id', $value->id)
                                    ->where('created_at', '>', $start)
                                    ->where('created_at', '<', $end)
                                    ->sum('qty');
                            }
                            $qtyCart = 0;
                            foreach ($cart as $cartItem) {
                                if ((int) $cartItem->id === (int) $value->id) {
                                    $qtyCart = $cartItem->qty;
                                }
                            }
                            ?>
                            <div class="col">
                                <div class="card h-100 shadow-sm">
                                    <img src="{{ asset('/uploads/imgProduct/' . $value->prd_thunbar) }}" class="card-img-top" alt="{{ $value->prd_name }}">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $value->prd_name }}</h5>
                                        @if ($value->turn)
                                            @if ($value->turn - (int) $shoppingDay - (int) $qtyCart > 0)
                                                <p class="card-text text-muted"><i class="fas fa-redo me-1"></i>Lượt mua/tuần: {{ $value->turn - (int) $shoppingDay - (int) $qtyCart }}</p>
                                            @else
                                                <p class="card-text text-danger"><i class="fas fa-times-circle me-1"></i>Hết lượt mua</p>
                                            @endif
                                        @endif
                                        <p class="card-text"><strong>Giá: {{ $value->coin }} <i class="fas fa-coins text-warning"></i></strong></p>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0">
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('frontend.cart.add', $value->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-shopping-cart me-1"></i>Mua ngay</a>
                                            <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#myProductDetail{{ $key2 }}{{ $key }}"><i class="fas fa-info-circle me-1"></i>Chi tiết</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="myProductDetail{{ $key2 }}{{ $key }}" tabindex="-1" aria-labelledby="productDetailLabel{{ $key2 }}{{ $key }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-gradient text-white" style="background-color: #4e54c8;">
                                            <h5 class="modal-title" id="productDetailLabel{{ $key2 }}{{ $key }}">{{ $value->prd_name }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <img src="{{ asset('/uploads/imgProduct/' . $value->prd_thunbar) }}" class="img-fluid rounded" alt="{{ $value->prd_name }}">
                                                </div>
                                                <div class="col-md-6">
                                                    {!! $value->prd_content !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                            <a href="{{ route('frontend.cart.add', $value->id) }}" class="btn btn-primary"><i class="fas fa-shopping-cart me-1"></i>Mua vật phẩm</a>
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
    <script type="text/javascript">
$(document).ready(function() {
    // Hiển thị danh sách sản phẩm khi click vào nút postlist
    $('.postlist-button').click(function() {
        const postlistId = $(this).data('postlist-id');
        $('.product-list[data-postlist-id=' + postlistId + ']').toggle();
    });
});
   $(document).ready(function() {
        // Xử lý sự kiện click vào thẻ "Thêm vào giỏ hàng"
        $('.add-to-cart').click(function() {
            const id = $(this).data('product-id');
            // Gửi yêu cầu thêm hàng vào giỏ hàng bằng AJAX
            $.ajax({
                type: 'POST',
                url: '/addCart/' + id, // Đường dẫn xử lý yêu cầu thêm hàng vào giỏ hàng
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    // Xử lý thành công: thông báo cho người dùng và cập nhật số lượng sản phẩm trong giỏ hàng nếu cần
                    alert('Thêm vào giỏ hàng thành công');
                },
                error: function(data) {
                    // Xử lý thất bại: thông báo cho người dùng nếu cần
                    alert('Thêm vào giỏ hàng thất bại');
                }
            });
        });
    });
	
       $(document).ready(function() {
            $('.accumulate-item').hide();
        });

        function showInputAccumulate(e) {
            const id = $(e.target).attr('value-id');
            $('.accumulate-item').hide();
            $('.accu-buy-' + id).hide();
            $('.accumulate-item-' + id).show();
        }

function confirmAccumulate(e) {
    const id = $(e.target).attr('value-id');
    const qty = 1; // Số lượng cố định là 1

    // Hỏi người dùng xác nhận mua hàng
    const confirmBuy = confirm("Bạn có muốn nhận vật phẩm này?");
    if (!confirmBuy) {
        return; // Không làm gì nếu người dùng từ chối
    }

    console.log({ 
        product_id: id,
        qty: qty,
        _token: '{{ csrf_token() }}',
    });

    $.post('{{ route('frontend.buyAccumulator') }}',{ 
        product_id: id,
        qty: qty,
        _token: '{{ csrf_token() }}',
    })
    .done(function(data) {
        alert('Nhận vật phẩm tích lũy thành công');
        const accP = $('.accumulate-point');
        $('.accumulate-item').hide();
        $('.accu-buy-' + id).show();
        accP.html(accP.html() - data.price);
    })
    .fail(function(data) {
        console.log(data);
        alert('Nhận vật phẩm tích lũy thất bại, số điểm tích luỹ không đủ');
    });
}
    </script>
@endsection
