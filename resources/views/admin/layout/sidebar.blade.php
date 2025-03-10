<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <li class="start {{ Request::segment(2) == null ? 'open' : '' }}">
                <a href="{{ route('admin.transactionHistory.index') }}">
                    <i class="icon-settings"></i>
                    <span class="title">Lịch sử giao dịch</span>
                </a>
            </li>
            <li class="start {{ Request::segment(2) == 'time-setting' ? 'open' : '' }}">
                <a href="{{ route('admin.timeSetting.index') }}">
                    <i class="icon-settings"></i>
                    <span class="title">Khuyến mãi nạp thẻ</span>
                </a>
            </li>
            <li class="start {{ Request::segment(2) == 'gift' ? 'open' : '' }}">
                <a href="{{ route('admin.giftCode.index') }}">
                    <i class="icon-layers"></i>
                    <span class="title">Gift Code</span>
                </a>
                <ul class="sub-menu" style="{{ Request::segment(2) == 'gift' ? 'display: block' : '' }}">
                    <li class="nav-item start {{ Request::segment(3) == 'gift-code' ? 'active open' : '' }}">
                        <a href="{{ route('admin.giftCode.index') }}" class="nav-link ">
                            <span class="title">Quản lý Gift Code</span>
                        </a>
                    </li>
                    <li class="nav-item start {{ Request::segment(3) == 'gift-code-history' ? 'active open' : '' }}">
                        <a href="{{ route('admin.giftCodeHistory.index') }}" class="nav-link ">
                            <span class="title">Lịch sử Gift Code</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="start {{ Request::segment(2) == 'recharge-events' ? 'open' : '' }}">
                <a href="{{ route('admin.rechargeEvents.index') }}">
                    <i class="icon-trophy"></i>
                    <span class="title">Cấu hình sự kiện nạp</span>
                </a>
            </li>
            <li class="start {{ Request::segment(2) == 'ranking-event' ? 'open' : '' }}">
                <a href="{{ route('admin.rankingEvent.index') }}">
                    <i class="icon-trophy"></i>
                    <span class="title">Event Ranking</span>
                </a>
            </li>
            <li class="start {{ Request::segment(2) == 'gift-send' ? 'open' : '' }}">
                <a href="{{ route('admin.giftSend.index') }}">
                    <i class="icon-layers"></i>
                    <span class="title">Tặng quà</span>
                </a>
                <ul class="sub-menu" style="{{ Request::segment(2) == 'gift-send' ? 'display: block' : '' }}">
                    <li class="nav-item start {{ Request::segment(3) == 'gift-send-setting' ? 'active open' : '' }}">
                        <a href="{{ route('admin.giftSend.index') }}" class="nav-link ">
                            <span class="title">Cấu hình quà tặng</span>
                        </a>
                    </li>
                    <li class="nav-item start {{ Request::segment(3) == 'gift-send-history' ? 'active open' : '' }}">
                        <a href="{{ route('admin.giftSendHistory.index') }}" class="nav-link ">
                            <span class="title">Lịch sử quà tặng</span>
                        </a>
                    </li>
                    <li class="nav-item start {{ Request::segment(3) == 'lucky-history' ? 'active open' : '' }}">
                        <a href="{{ route('admin.giftSendHistory.luckyHistory') }}" class="nav-link ">
                            <span class="title">Vòng quay may mắn</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="start {{ Request::segment(2) == 'product-list' ? 'open' : '' }}">
                <a href="{{ route('admin.cateProduct.index') }}">
                    <i class="icon-layers"></i>
                    <span class="title">Sản phẩm</span>
                </a>
                <ul class="sub-menu"
                    style="{{ Request::segment(2) == 'product-list' ? 'display: block' : '' }}">
                    <li class="nav-item start {{ Request::segment(3) == 'category-product' ? 'active open' : '' }}">
                        <a href="{{ route('admin.cateProduct.index') }}" class="nav-link ">
                            <span class="title">Danh mục sản phẩm</span>
                        </a>
                    </li>
                    <li class="nav-item start {{ Request::segment(3) == 'product' ? 'active open' : '' }}">
                        <a href="{{ route('admin.product.index') }}" class="nav-link ">
                            <span class="title">Sản phẩm</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="start {{ Request::segment(2) == 'blog-list' ? 'open' : '' }}">
                <a href="{{ route('admin.cateBlog.index') }}">
                    <i class="icon-book-open"></i>
                    <span class="title">Tin tức</span>
                </a>
                <ul class="sub-menu" style="{{ Request::segment(2) == 'blog-list' ? 'display: block' : '' }}">
                    <li class="nav-item start {{ Request::segment(3) == 'category-blog' ? 'active open' : '' }}">
                        <a href="{{ route('admin.cateBlog.index') }}" class="nav-link ">
                            <span class="title">Danh mục tin tức</span>
                        </a>
                    </li>
                    <li class="nav-item start {{ Request::segment(3) == 'blog' ? 'active open' : '' }}">
                        <a href="{{ route('admin.blogs.index') }}" class="nav-link ">
                            <span class="title">Tin tức</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="start {{ Request::segment(2) == 'setting' ? 'open' : '' }}">
                <a href="{{ route('admin.relogMomo.index') }}">
                    <i class="icon-settings"></i>
                    <span class="title">Check Momo</span>
                </a>
            </li>
            <li class="start {{ Request::segment(2) == 'setting' ? 'open' : '' }}">
                <a href="{{ route('admin.settings.index') }}">
                    <i class="icon-settings"></i>
                    <span class="title">Cài đặt</span>
                </a>
            </li>
            {{-- <li class="start {{Request::segment(2) == 'help-list' ? 'open' : ''}}"> --}}
            {{-- <a href=""> --}}
            {{-- <i class="icon-info"></i> --}}
            {{-- <span class="title">Hỗ trợ</span> --}}
            {{-- </a> --}}
            {{-- <ul class="sub-menu" style="{{Request::segment(2) == 'help-list' ? 'display: block' : ''}}"> --}}
            {{-- <li class="nav-item start {{Request::segment(3) == 'category-help' ? 'active open' : ''}}"> --}}
            {{-- <a href="{{route('admin.cateHelp.index')}}" class="nav-link "> --}}
            {{-- <span class="title">Danh mục hỗ trợ</span> --}}
            {{-- </a> --}}
            {{-- </li> --}}
            {{-- <li class="nav-item start {{Request::segment(3) == 'help' ? 'active open' : ''}}"> --}}
            {{-- <a href="{{route('admin.help.index')}}" class="nav-link "> --}}
            {{-- <span class="title">Hỗ trợ</span> --}}
            {{-- </a> --}}
            {{-- </li> --}}
            {{-- </ul> --}}
            {{-- </li> --}}
            {{-- <li class="start {{Request::segment(2) == 'client-says' ? 'open' : ''}}"> --}}
            {{-- <a href="{{route('admin.clientSays.index')}}"> --}}
            {{-- <i class="icon-bulb"></i> --}}
            {{-- <span class="title">Ý kiến khách hàng</span> --}}
            {{-- </a> --}}
            {{-- </li> --}}
            {{-- <li class="start {{Request::segment(2) == 'contact' ? 'open' : ''}}"> --}}
            {{-- <a href="{{route('admin.contact.index')}}"> --}}
            {{-- <i class="icon-paper-plane"></i> --}}
            {{-- <span class="title">Tin nhắn liên hệ</span> --}}
            {{-- </a> --}}
            {{-- </li> --}}
        </ul>
    </div>
</div>
