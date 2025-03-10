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
@endsection
@section('content')
<div class="wrapper__content scrollContent">
<body class="locate-vn">
 <div id="wrapper" class="wrapper--subpage scaleDesktop scaleMobile">
    <div class="wrapper__content scrollContent">

        <section id="subHeader" class="section section--subHeader subHeader subHeaderNews scrollFrame">
        <div class="section__background">
            <img data-src="/frontend/images/bg-sub-head.png" class="desktop lazyload" alt="">
            <img data-src="/frontend/images/mb-bg-sub-head.png" class="mobile lazyload" alt="">
            <span id="subHeader-scrollwatch-pin" class="scrollwatch-pin"></span>
        </div>
        <div class="section__content">
            <div class="inner inner--subHeader"></div>
        </div>
    </section>
        
         

<div id="subNewsPage" class="floatingDesktopTopCenter floatingMobileTopLeft">
    <div class="main__sub">
        <img data-src="/frontend/images/icon-sub-page.png" class="mobile icon-sub-head lazyload" alt="">
       <div id="blockSubNewsSwiper" class="swiper subnews_list">
    @foreach ($categoryBlogList as $key => $category)
        <ul class="swiper-wrapper {{ $key == 0 ? 'active show' : '' }}" id="tab-news{{ $key }}" role="tabpanel" aria-labelledby="tab-news{{ $key }}">
            @foreach ($category->blog as $key2 => $blog)
                <li class="swiper-slide subNews__slide">
                    <img src="{{ asset('/uploads/imgBlog/' . $blog->b_thunbar) }}" alt="{{ $blog->b_name }}" class="imageSubNews lazyload" >
                    <img data-src="/frontend/images/bg-list-news.png" class="imgBGSub lazyload" alt="{{ $blog->b_name }}">
                    <a href="{{ route('frontend.blogDetail', $blog->b_slug) }}" rel="nofollow" class="title__subNews" title="{{ $blog->b_name }}" target="_blank">{{ $blog->b_name }}</a>
                    <div class="info flex">
                        <p class="content__left">{{ $blog->created_at->format('d.m.Y') }}</p>
                        <p class="item-cate cate-event-{{ $key }} me-2">Sự kiện</p>
                    </div>
                </li>
            @endforeach
        </ul>
    @endforeach
</div>

        <div class="swiper-button-prev swiper-button-prev--blockNewsPageSwiper"></div>
        <div class="swiper-button-next swiper-button-next--blockNewsPageSwiper"></div>
    </div>
</div>

	<div id="subNews" class="section section--subNews subNews">
		<div class="section__background">
			<img data-src="/frontend/images/bg-main-content.png" class="desktop lazyload" alt="">
		</div>

		<div class="section__content">
			<img src="/frontend/images/bg-content.png" class="section__content--bg desktop lazyload" alt="">
			<img src="/frontend/images/mb-bg-content.png" class="section__content--bg mobile lazyload" alt="">
			<div class="inner inner--subNews">
				<div class="panel">
					<div class="panel__loop">
						<div class="panel__content">


					<div id="blockSubNews" class="news news--blockSubNews">
						<div class="news_tab">
							<ul class="tab">
								@foreach ($categoryBlogList as $key => $item) 
								@endforeach
									<li>
										<a href="#" class="tab__item tab__item--news" 
										data-params="" data-block-name="tin-tuc" 
										data-shorturl="//tlbb2.vnggames.com/tin-tuc" 
										data-viewall="//tlbb2.vnggames.com/tin-tuc/danh-sach.1.html"> Tin tức</a>
									</li>

										<li>
											<a href="#" class="tab__item tab__item--event" 
											data-block-name="danh-sach" 
											data-shorturl="//tlbb2.vnggames.com/su-kien" 
											data-viewall="//tlbb2.vnggames.com/su-kien/danh-sach.1.html">Sự kiện</a>
										</li>
										<li>
											<a href="#" class="tab__item tab__item--feature" 
											data-params="" data-block-name="tinh-nang" 
											data-shorturl="//tlbb2.vnggames.com/tin-tuc" 
											data-viewall="//tlbb2.vnggames.com/tin-tuc/tinh-nang.1.html">Tính năng</a>
										</li>

										<li>
											<a href="#" class="tab__item tab__item--tutori" 
											data-params="" data-block-name="huong-dan" 
											data-shorturl="//tlbb2.vnggames.com/tin-tuc" 
											data-viewall="//tlbb2.vnggames.com/tin-tuc/huong-dan.1.html">Hướng dẫn</a>
										</li>
								
							</ul>
							<div class="tab__search">
								<img class="desktop lazyload" data-src="/frontend/images/search-sub.png" alt="">
								<img class="mobile lazyload" data-src="/frontend/images/mb-search-sub.png" alt="">
								<form action="//tlbb2.vnggames.com/tim-kiem.html">
									<input class="place__input" name="q" required="" placeholder="Tìm kiếm">
									<button type="submit" class="btn__search" value="Tìm"></button>
								</form>
							</div>
						</div>
						@foreach ($categoryBlogList as $key => $item)
						<div class="news_list">
							
								<ul class="flex flex-column {{ $key == 0 ? 'active' : '' }}" id="tab-news{{ $key }}" role="tabpanel" aria-labelledby="tab-news{{ $key }}">
									
										<div class="news_item isHot">
											@foreach ($item->blog as $key2 => $value)
												<li class="flex">
													<a href="{{ route('frontend.blogDetail', $value->b_slug) }}" title="{{ $value->b_name }}" target="_blank" rel="nofollow" class="news_item__thumbnail">
														<img class="lazyload" src="{{ asset('/uploads/imgBlog/' . $value->b_thunbar) }}" alt="">
													</a>
													<div class="content__right flex flex-column flex-between cate-event-{{ $key }} me-2">
														
														<a href="{{ route('frontend.blogDetail', $value->b_slug) }}" class="news_item__title flex flex-middle">
															<img src="/frontend/images/btn-hot.png" alt="">
															{{ substr($value->b_name, 0, 50) }}
														</a>
														<p class="shortContent"></p>
														<div class="content__bottom">
															<p>{{ $value->created_at->format('y/d/m') }}</p>
															{{ substr($item->b_name, 0, 30) }}
															<a href="{{ route('frontend.blogDetail', $value->b_slug) }}" class="news_item__viewdetail">
																<img src="/frontend/images/btn-readall.png">
															</a>
														</div>
													</div>
												</li>
											@endforeach
										</div>
									
								</ul>
							
						</div>
						@endforeach
					</div>
<input type="hidden" id="itemTotal" value="144">
<input type="hidden" id="itemPerPage" value="5">
<input type="hidden" id="currentSection" value="tin-tuc">
<input type="hidden" id="shortUri" value="//tlbb2.vnggames.com/tin-tuc">


								<div class="news_pagination">
									<ul class="pagination" data-block-name="tin-tuc" data-shorturl="//tlbb2.vnggames.com/tin-tuc"></ul>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    </div>

</div>


@endsection
