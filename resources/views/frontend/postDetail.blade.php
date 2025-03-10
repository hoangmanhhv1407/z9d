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
<div class="wrapper__content scrollContent">
<div id="wrapper" class="wrapper wrapper--subpage scaleDesktop scaleMobile">
    <div class="wrapper__content scrollContent">

                	<section id="subHeader" class="section section--subHeader subHeader scrollFrame">
		<div class="section__background">
			<img data-src="/frontend/images/bg-sub-child.png" class="desktop lazyload" alt="">
			<img data-src="/frontend/images/mb-bg-sub-child.png" class="mobile lazyload" alt="">
			<span id="subHeader-scrollwatch-pin" class="scrollwatch-pin"></span>
		</div>
		<div class="section__content">
			<div class="inner inner--subHeader">
				<a href="{{route('frontend.index')}}" class="logo">Home</a>
			</div>
		</div>
	</section>
        
         <div id="subListNews" class="section section--subListNews subListNews">
	<div class="section__background">
		<img src="/frontend/images/bg-main-content.png" class="desktop " alt="">
		<img src="/frontend/images/mb-bg-content.png" class="mobile " alt="">
	</div>
	<div class="section__content">
		<img src="/frontend/images/bg-content-news-child.png" class="section__content--bg desktop lazyload" alt="">
		<img src="/frontend/images/title-sub-news.png" class="mobile lazyload" alt="">
		<div class="inner inner--subListNews">
			<div class="sub-list-news">
				<div class="subListNews__article">
					<div class="midbar">
						<div class="midbar__line--head">
							<a href="{{route('frontend.index')}}">
								<img src="/frontend/images/breadcrumb-home.png" class="lazyload" alt="">
							</a>
							<img src="/frontend/images/breadcrumb-next.png" class="lazyload" alt="">
							<a href="" class="child-1">{{ $postType->cpo_name}}</a>
							<img src="images/breadcrumb-next.png" class="lazyload" alt="">
							<div class="child-2">{{ $postDetail->b_name }}</div>
						</div>
						<div class="midbar__title">
							{{ $postDetail->b_name }}
						</div>
					</div>
					<div class="subweb__content">
						<article class="article">
							<div class="article__meta">
								<a href="#" class="article__cate cate-event-{{ $postType->id - 1}}">{{ $postType->cpo_name}}</a>
								<span class="article__time"><img src="/frontend/images/dot.png">{{ $postDetail->created_at->format('d.m.Y') }}</span>
							</div>

							<div class="article__content">
								<div class="StaticMain">{!! $postDetail->b_content !!}</div>
							</div>
						</article>
					</div>
				</div>
					<div class="subListNews__aside">
		<div class="subListNews__menutitle">
			<img src="/frontend/images/list-news-news.png" class="section__content--bg desktop lazyload" alt="">
		</div>
		<div class="child__menu">
																								<a href="//tlbb2.vnggames.com/su-kien/uu-dai-nap-bat-ngo-30-5/tham-gia-ngay-487.html" rel="nofollow" title="Ưu Đãi Nạp Bất Ngờ 30/5">
					<div class="thumnail">
						<img src="/frontend/images/pbm-daily-pc.jpg" class="thumnail__img desktop lazyload" alt="Ưu Đãi Nạp Bất Ngờ 30/5">
												<div class="content">
							<p class="title">Ưu Đãi Nạp Bất Ngờ 30/5</p>
							<p class="date">29.05.24</p>
						</div>
					</div>
				</a>
																								<a href="//tlbb2.vnggames.com/su-kien/su-kien-tuan-28-05-04-06/dua-top-tieu-phi-nhan-hao-hiep-hiem-vuong-ngu-yen.html" rel="nofollow" title="Sự kiện tuần 28.05 - 04.06">
					<div class="thumnail">
						<img src="/frontend/images/pbm-daily-pc.jpg" class="thumnail__img desktop lazyload" alt="Sự kiện tuần 28.05 - 04.06">
												<div class="content">
							<p class="title">Sự kiện tuần 28.05 - 04.06</p>
							<p class="date">28.05.24</p>
						</div>
					</div>
				</a>
																								<a href="//tlbb2.vnggames.com/su-kien/su-kien-tuan-21-05-27-05/bxh-hoa-tuoi-nhan-ngoai-trang-toc-va-ao-moi.html" rel="nofollow" title="Sự kiện tuần 21.05 - 27.05">
					<div class="thumnail">
						<img src="/frontend/images/pbm-daily-pc.jpg" class="thumnail__img desktop lazyload" alt="Sự kiện tuần 21.05 - 27.05">
												<div class="content">
							<p class="title">Sự kiện tuần 21.05 - 27.05</p>
							<p class="date">21.05.24</p>
						</div>
					</div>
				</a>
					</div>
	</div>

			</div>
		</div>
	</div>
</div>

        

    </div>

</div>





<script>
    // set font size 32px if $(".midbar__title") length > 60
    if ($(".midbar__title").text().length > 60) {
        $(".midbar__title").css("font-size", "32px");
    }
</script>
@endsection
