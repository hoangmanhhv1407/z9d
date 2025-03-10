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
 <style>
        .subListNews .sub-list-news .subListNews__article .midbar__title {
            font-size: 30px;
        }

        @media screen and (min-width: 1024px) {
            .subNews .news_item__title, .subSubweb .news_item__title {
                width: 590px;
            }
        }

        @media (orientation: landscape) {
            .floating .floattop .floatnav ul li .active__dropdown ul li a {
                font-size: 17px;
            }
        }
    </style>
@section('content')
    <div class="news">
        <div class="top-bg">
            <div class="bg">
                <img src="/frontend/images/cms14714178094227184023.png" alt="image bai viet">
            </div>
            <div class="news-info">
                <ul class="breadcrumb">
                    <li><a href="/">Trang chủ</a></li>
                    <li> &gt; </li>
                    <li><a href="{{ route('frontend.blogList') }}">Tin tức</a></li>
                </ul>
                <div class="date-cate">
                    <div class="date">{{ $postDetail->created_at }}</div>
                    <div class="news-cate item-cate  cate-event-{{ $postType->id - 1}}">{{ $postType->cpo_name}}</div>
                </div>
                <h1 class="news-title">{{ $postDetail->b_name }}</h1>
            </div>
        </div>
        <div class="news-container">
            <div class="news-content">
                {!! $postDetail->b_content !!}
            </div>
        </div>
    </div>
@endsection
