@extends('frontend.layout.main')
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            if (isEmpty({{ Session::has('checkPopupRegister') }}) === false) {
                $('#myModal').modal('show')
            }
            if (isEmpty({{ Session::has('checkPopupLogin') }}) === false) {
                $('#myModalLogin').modal('show')
            }

            function isEmpty(str) {
                return (!str || 0 === str.length);
            }
        });
    </script>
@endsection
@section('content')
 @if (count($categoryBlogList))
    <!-- Bắt đầu kiểm tra xem danh sách danh mục bài viết có phần tử không -->
    <div class="news-list">
        <!-- Phần bao bọc toàn bộ danh sách bài viết -->
        <div class="top-bg">
            <!-- Phần nền trên cùng của danh sách -->
            <div class="bg">
                <img src="frontend/images/home/BG-top.jpg" alt="bg banner top">
                <!-- Hình ảnh nền -->
            </div>
        </div>
        <div class="container">
            <!-- Phần container để căn chỉnh layout -->
            <div class="news-content border-browp-2">
                <!-- Phần nội dung chính của danh sách bài viết -->
                <div class='nav nav-tabs mb-4'>
                    <!-- Danh sách các tab -->
                    @foreach ($categoryBlogList as $key => $item)
                        <!-- Bắt đầu vòng lặp foreach để lặp qua từng danh mục -->
                        <button class="nav-link {{$key==0? 'active': ''}}" data-bs-toggle="tab" data-bs-target="#tab-news{{ $key }}"
                            type="button" role="tab" aria-selected="false">{{ $item->cpo_name }}</button>
                        <!-- Mỗi button tương ứng với một danh mục, được đặt id và aria label dựa trên $key -->
                    @endforeach
                </div>
                <!-- Kết thúc danh sách tab -->

                <div class="tab-content">
                    <!-- Phần nội dung của từng tab -->
                    @foreach ($categoryBlogList as $key => $item)
                        <!-- Bắt đầu vòng lặp foreach để hiển thị nội dung cho từng danh mục -->
                        <div class="tab-pane fade {{$key==0? 'active show':''}}" id="tab-news{{ $key }}" role="tabpanel"
                            aria-labelledby="tab-news{{ $key }}">
                            <!-- Phần nội dung của mỗi tab -->
                            <div class="posts__list py-2" style="position: relative;">
                                <!-- Danh sách các bài viết -->
                                @foreach ($item->blog as $key2 => $value)
                                    <!-- Bắt đầu vòng lặp foreach để hiển thị từng bài viết trong danh mục -->
                                    <a class="news-item d-flex mb-3" href="{{ route('frontend.blogDetail', $value->b_slug) }}"
                                        title="{{ $value->b_name }}" target="_blank" nofollow="">
                                        <!-- Đường link của từng bài viết -->
                                        <div class="new-thump col-md-4 col-12 overflow-hidden">
                                            <img src="{{ asset('/uploads/imgBlog/' . $value->b_thunbar) }}" class="w-100 h-100" alt="">
                                            <!-- Hình ảnh đại diện của bài viết -->
                                        </div>
                                        <div class="col-md-8 col-12 p-3">
                                            <!-- Phần nội dung của bài viết -->
                                            <div class="new-type d-flex">
                                                <!-- Loại bài viết -->
                                                <div class="item-cate cate-event-{{ $key }} me-2">
                                                    {{ $item->cpo_name }}
                                                    <!-- Tên của danh mục -->
                                                </div>
                                                <div class="item-day">{{ $value->created_at }}</div>
                                                <!-- Thời gian đăng bài viết -->
                                            </div>
                                            <h3 class="item-title">
                                                <!-- Tiêu đề của bài viết -->
                                                {{ substr($value->b_name, 0, 50) }}
                                                <!-- Hiển thị chỉ 50 ký tự đầu tiên của tiêu đề -->
                                            </h3>
                                            <div class="item-des">
                                                <!-- Mô tả của bài viết -->
                                                {{ strip_tags($value->b_description) }}
                                                <!-- Loại bỏ các tag HTML để hiển thị mô tả -->
                                            </div>
                                        </div>
                                    </a>
                                    <!-- Kết thúc vòng lặp foreach hiển thị bài viết -->
                                @endforeach
                            </div>
                            <!-- Kết thúc danh sách bài viết -->

                            @if ($item->blog->total() > 10)
                                <!-- Kiểm tra nếu có nhiều hơn 10 bài viết -->
                                <div style="position: relative;margin-top: 25px;height: 60px;">
                                    <!-- Phần điều hướng trang -->
                                    <div style=" position: absolute;top: 50%; right: 50%;transform: translate(50%,-50%);">
                                        <!-- Căn giữa nút điều hướng trang -->
                                        {{ $item->blog->links() }}
                                        <!-- Hiển thị các nút điều hướng trang -->
                                    </div>
                                </div>
                            @endif
                            <!-- Kết thúc kiểm tra số lượng bài viết -->
                        </div>
                        <!-- Kết thúc vòng lặp foreach hiển thị nội dung từng tab -->
                    @endforeach
                </div>
                <!-- Kết thúc phần nội dung tab -->
            </div>
            <!-- Kết thúc phần nội dung chính -->
        </div>
        <!-- Kết thúc phần container -->
    </div>
    <!-- Kết thúc phần danh sách bài viết -->
@endif
<!-- Kết thúc kiểm tra có danh sách bài viết hay không -->



@endsection
