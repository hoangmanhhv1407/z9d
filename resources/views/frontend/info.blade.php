@extends('frontend.layout.main')

@section('css')
<link href="{{ asset('/frontend/css/userinfo.css') }}" rel="stylesheet">

@endsection

@section('content')
<div class="container-fluid">
    <div id="userId" data-user-id="{{ Auth::user()->id }}" style="display:none;"></div>
        <!-- Thêm phần tử thông báo -->
        <div id="notification-container" style="position: fixed; top: 20px; right: 20px; z-index: 1050;"></div>
    <div id="popup"></div>

    <div class="row">
    <div class="col-md-3 col-lg-2 px-0 sidebar">
    <div class="list-group list-group-flush">
    <div class="p-3">
            <a class="btn btn-danger btn-block" href="{{ url('logout') }}">
                <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
            </a>
        </div>
        <a class="list-group-item list-group-item-action active py-3 fs-5" id="generalInfoLi" data-bs-toggle="list" href="#home1">
            <i class="fas fa-user-circle fa-fw me-2"></i>
            Thông Tin Tài Khoản
        </a>
        <a class="list-group-item list-group-item-action py-3 fs-5" id="SecurityLi" data-bs-toggle="list" href="#home2">
            <i class="fas fa-lock fa-fw me-2"></i>
            Thay Đổi Mật Khẩu
        </a>
        <a class="list-group-item list-group-item-action py-3 fs-5" id="TransHisLi" data-bs-toggle="list" href="#home3">
            <i class="fas fa-history fa-fw me-2"></i>
            Lịch sử giao dịch
        </a>
        <a class="list-group-item list-group-item-action py-3 fs-5" id="Donates" data-bs-toggle="list" href="#home4">
            <i class="fas fa-coins fa-fw me-2"></i>
            Nạp Xu
        </a>
        <a class="list-group-item list-group-item-action py-3 fs-5" id="ItemShop" data-bs-toggle="list" href="#home5">
            <i class="fas fa-store fa-fw me-2"></i>
            Kỳ Trân Các
        </a>
        <a class="list-group-item list-group-item-action py-3 fs-5" id="DailyGift" data-bs-toggle="list" href="#home6">
            <i class="fas fa-gift fa-fw me-2"></i>
            Nhận Quà Hàng Ngày
        </a>        
    </div>
</div>
        <div class="col-md-9 col-lg-10 content-area">
            <div class="tab-content">
            <div class="tab-pane fade show active" id="home1">
    <h4 class="mb-4">
        <img src="/frontend/images/icon_thongtinchung.png" alt="icon_thongtinchung" style="width: 32px; height: 32px; margin-right: 10px;">
        Thông tin tài khoản
    </h4>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-user mr-2"></i>Thông tin cá nhân</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Tên tài khoản:</span>
                            <span>{{ $user->userid }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Email:</span>
                            <span>{{ $user->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Ngày đăng ký:</span>
                            <span>18/07/2023</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-coins mr-2"></i>Thông tin xu</h5>
                </div>
                <div class="card-body">
                <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Tổng số xu đã nạp:</span>
                    <span>{{ number_format($totalDepositedCoins) }} Xu</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Tổng số xu đã tiêu:</span>
                    <span>{{ number_format($totalSpentCoins) }} Xu</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Số xu khả dụng:</span>
                    <span>{{ $user && isset($user->coin) ? number_format($user->coin) : '0' }} Xu</span>
                </li>
            </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-gamepad mr-2"></i>Thông tin trò chơi</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded bg-light">
                                <h2 class="font-weight-bold text-primary">{{ $chars ? count($chars) : '0' }}</h2>
                                <p class="mb-0">Số lượng nhân vật</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded bg-light">
                                <h2 class="font-weight-bold text-success">{{ $user && isset($user->coin) ? number_format($user->coin) : '0' }}</h2>
                                <p class="mb-0">Xu khả dụng</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="tab-pane fade" id="home2">
    <h4 class="mb-4">
        <img src="/frontend/images/icon_baovetaikhoan.png" alt="icon_baovetaikhoan" style="width: 32px; height: 32px; margin-right: 10px;">
        Thay đổi mật khẩu
    </h4>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-lock mr-2"></i>Đổi mật khẩu</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('resetPass2') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="u_name" class="form-label">Tài khoản</label>
                            <input id="u_name" class="form-control" name="u_name" placeholder="Nhập tài khoản của bạn" type="text" value="{{ old('u_name', $user->userid) }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu mới</label>
                            <input id="password" class="form-control" name="password" placeholder="Nhập mật khẩu mới" type="password">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                            <input id="password_confirmation" class="form-control" name="password_confirmation" placeholder="Nhập lại mật khẩu mới" type="password">
                        </div>
                        <div class="text-center">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-save mr-2"></i>Đổi mật khẩu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="tab-pane fade" id="home3">
    <h4 class="mb-4">
        <img src="/frontend/images/icon_lichsugiaodich.png" alt="icon_lichsugiaodich" style="width: 32px; height: 32px; margin-right: 10px;">
        Lịch sử giao dịch
    </h4>

    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h5 class="card-title mb-0"><i class="fas fa-history mr-2"></i>Lịch sử nạp và sử dụng coin</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>STT</th>
                            <th>Tên vật phẩm</th>
                            <th>Số lượng</th>
                            <th>Coin</th>
                            <th>Mã giao dịch</th>
                            <th>Số điện thoại</th>
                            <th>Hình thức</th>
                            <th>Ngày giao dịch</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($showHistory as $key => $value)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $value->type == 2 && $value->product !== null ? $value->product->prd_name : '' }}</td>
                                <td>{{ $value->type == 2 ? $value->qty : '' }}</td>
                                <td class="{{ $value->type == 1 ? 'text-success' : ($value->type == 2 ? 'text-danger' : '') }} font-weight-bold">
                                    {{ $value->type == 1 ? '+' : '-' }} {{ number_format($value->coin) }} coin
                                </td>
                                <td>{{ $value->type == 1 ? $value->code : '' }}</td>
                                <td>{{ $value->type == 1 ? $value->phone : '' }}</td>
                                <td>
                                    <span class="badge {{ $value->type == 1 ? 'bg-success' : ($value->type == 2 ? 'bg-danger' : 'bg-primary') }}">
                                        {{ $value->type == 1 ? 'Banking' : ($value->type == 2 ? 'Mua vật phẩm' : 'Admin nạp') }}
                                    </span>
                                </td>
                                <td>{{ $value->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($showHistory->total() > 10)
            <div class="mt-3 d-flex justify-content-center" id="pagination-container">
                    {{ $showHistory->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
                <div class="tab-pane fade" id="home4">
    <h4><img src="/frontend/images/icon_lichsugiaodich.png" alt="icon_Donates" style="width: 32px; height: 32px; margin-right: 10px;">Nạp Xu</h4>
    <div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title"><i class="fas fa-coins mr-2"></i>Số Xu hiện có</h5>
        <p class="card-text h3" id="currentCoins">{{ number_format($user->coin) }} Xu</p>
    </div>
</div>
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-qrcode mr-2"></i> Cách 1: Chuyển khoản bằng mã QR</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle mr-2"></i> Hướng dẫn nạp tiền qua mã QR:
                        <ol class="mt-2">
                            <li>Nhập số tiền bạn muốn nạp vào ô bên dưới.</li>
                            <li>Bấm nút "Xác nhận" để tạo mã QR.</li>
                            <li>Sử dụng ứng dụng ngân hàng của bạn để quét mã QR.</li>
                            <li>Hoàn tất giao dịch trong ứng dụng ngân hàng.</li>
                            <li>Đợi hệ thống xác nhận giao dịch (khoảng 1-2 phút).</li>
                        </ol>
                    </div>
                    <div class="form-group mb-3">
                        <label for="amount" class="mb-2"><i class="fas fa-money-bill-wave mr-2"></i>Số tiền cần nạp:</label>
                        <div class="input-group">
                            <input type="number" id="amount" class="form-control" placeholder="Nhập số tiền">
                            <div class="input-group-append">
                                <span class="input-group-text">VNĐ</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mb-3">
                        <button id="confirm-amount" class="btn btn-primary btn-lg"><i class="fas fa-check mr-2"></i>Xác nhận</button>
                    </div>

                    <div id="processing-section" class="mb-3" style="display: none;">
                        <div class="alert alert-warning mb-3" role="alert">
                            <i class="fas fa-spinner fa-spin mr-2"></i> Đang xử lý giao dịch, vui lòng đợi...
                            <span class="float-right">Thời gian còn lại: <span id="countdown">10:00</span></span>
                            </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                        </div>
                    </div>
                    <div id="qr-code-section" class="mb-3 text-center" style="display: none;">
                        <img id="qr_images" class="img-fluid mx-auto d-block border p-2" src="" alt="QR Code">
                        <p class="mt-2 text-muted"><small>Quét mã QR này bằng ứng dụng ngân hàng của bạn</small></p>
                    </div>

                    <div class="text-center">
                        <button id="closePopup" class="btn btn-secondary" style="display: none;"><i class="fas fa-times mr-2"></i>Đóng</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-university mr-2"></i> Cách 2: Chuyển khoản thủ công</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle mr-2"></i> Hướng dẫn chuyển khoản thủ công:
                        <ol class="mt-2">
                            <li>Mở ứng dụng ngân hàng của bạn.</li>
                            <li>Chọn chức năng "Chuyển tiền".</li>
                            <li>Nhập thông tin tài khoản như bên dưới.</li>
                            <li>Điền số tiền bạn muốn nạp.</li>
                            <li>Trong phần nội dung chuyển tiền, nhập chính xác: <strong class="text-danger">CLTB9D{{Auth::user()->id}}</strong></li>
                            <li>Xác nhận và hoàn tất giao dịch.</li>
                        </ol>
                    </div>
                    <div class="text-center mb-3">
                        <img class="img-fluid" src="/frontend/images/bank_logo.png" style="max-height: 6rem; max-width: 12rem;">
                    </div>
                    <div class="bank-info">
                        <p class="mb-2"><i class="fas fa-user mr-2"></i><strong>Chủ tài khoản:</strong> LE VIET ANH</p>
                        <p class="mb-2"><i class="fas fa-credit-card mr-2"></i><strong>Số Tài khoản:</strong> <span class="text-primary">90511239999</span></p>
                        <p class="mb-2"><i class="fas fa-comment-dots mr-2"></i><strong>Nội dung chuyển tiền:</strong> <span class="text-danger">CLTB9D{{Auth::user()->id}}</span></p>
                        <p class="mb-0"><i class="fas fa-university mr-2"></i><strong>Ngân hàng:</strong> Quân đội (MB)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="tab-pane fade" id="home6">
    <h4 class="mb-4">
        <i class="fas fa-gift fa-fw me-2"></i>Nhận Quà Hàng Ngày
    </h4>
    
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm border-primary">
                <div class="card-header bg-transparent text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-user-tag me-2"></i>Thông Tin Tài Khoản VIP</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-user me-2"></i>Tài Khoản:</strong> <span class="text-primary">{{ $user->userid }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-crown me-2"></i>Cấp VIP Hiện Tại:</strong> <span class="badge bg-warning text-dark current-vip">{{ $vipLevel }}</span></p>
                        </div>
                        <div class="col-md-4">
                    <p><strong><i class="fas fa-coins me-2"></i>Tổng Xu:</strong> <span class="badge bg-info text-dark total-coins">{{ number_format($vipInfo['totalCoins']) }} Xu</span></p>
                </div>
                    </div>
                    <h6 class="mb-2">Tiến Trình Lên Cấp VIP Tiếp Theo</h6>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar bg-success vip-progress" role="progressbar" style="width: {{ $vipInfo['progress'] }}%;" aria-valuenow="{{ $vipInfo['progress'] }}" aria-valuemin="0" aria-valuemax="100">{{ number_format($vipInfo['progress'], 2) }}%</div>
                    </div>
                    <p class="mt-2 mb-0">
                    <small>
        Cần thêm <strong class="xu-needed">{{ number_format($vipInfo['xuNeeded']) }}</strong> xu để lên VIP <span class="next-vip">{{ $vipInfo['nextVip'] }}</span>
        (<span class="total-deposits">{{ number_format($vipInfo['totalDeposits']) }}</span>/<span class="xu-for-next-vip">{{ number_format($vipInfo['xuForNextVip']) }}</span> xu)
    </small>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div id="notification" class="alert" style="display: none;"></div>

    <div class="card shadow-sm">
        <div class="card-header text-white">
            <h5 class="card-title mb-0"><i class="fas fa-box-open me-2"></i>Nhận Quà</h5>
        </div>
        <div class="card-body">
            <form id="giftForm" action="{{ route('frontend.claimGift') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="selectCharacter" class="form-label"><i class="fas fa-user-astronaut me-2"></i>Chọn Nhân Vật (Yêu Cầu Cấp 108 Trở Lên)</label>
                    <select id="selectCharacter" name="character_id" class="form-select" required>
                        <option value="" disabled selected>-- Chọn nhân vật --</option>
                        @if(isset($chars))
                            @foreach($chars as $char)
                                <option value="{{ $char->unique_id }}">{{ $char->chr_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="mb-3" id="giftContainer" style="display: none;">
                    <label class="form-label"><i class="fas fa-list-ul me-2"></i>Quà Có Thể Nhận</label>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Tên Quà</th>
                                    <th>Chi tiết</th>
                                    <th>Trạng Thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($products))
                                    @foreach($products as $product)
                                        <tr>
                                            <td>{{ $product->prd_name }}</td>
                                            <td>{{ $product->description ?? 'Không có mô tả' }}</td>
                                            <td>
                                                @if($product->already_claimed)
                                                    <button type="button" class="btn btn-secondary btn-sm" disabled><i class="fas fa-check-circle me-1"></i>Đã Nhận</button>
                                                @else
                                                    <button type="button" class="btn btn-primary btn-sm" onclick="submitGiftForm('{{ $product->prd_code }}')"><i class="fas fa-gift me-1"></i>Nhận Quà</button>
                                                @endif
                                            </td>    
                                        </tr>
                                    @endforeach
                                @endif
                                @if($vipLevel >= 1 && $vipProduct)
                                
                                    <tr>
                                        <td>{{ $vipProduct->prd_name }} <span class="badge bg-warning text-dark">VIP</span></td>
                                        <td>{{ $vipProduct->description ?? 'Không có mô tả' }}</td>
                                        <td>
                                            @if($vipProduct->already_claimed)
                                                <button type="button" class="btn btn-secondary btn-sm" disabled><i class="fas fa-check-circle me-1"></i>Đã Nhận</button>
                                            @else
                                                <button type="button" class="btn btn-primary btn-sm" onclick="submitGiftForm('{{ $vipProduct->prd_code }}')"><i class="fas fa-gift me-1"></i>Nhận Quà</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
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
<script src="/frontend/js/pusher.min.js"></script>

<script src="/frontend/js/userinfo.js"></script>
@endsection