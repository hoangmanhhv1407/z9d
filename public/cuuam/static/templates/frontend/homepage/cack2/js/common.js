$(document).ready(function() {
    $(window).on('scroll', function() {
        if ($(window).scrollTop() > 55) {
            $(".wrap-bg").addClass('hide-nine-dmenu');
        } else {
            $(".wrap-bg").removeClass('hide-nine-dmenu');
        }
    });
    $(".btn-slide-close").click(function() {
        $(".down-float-box").animate({ "margin-right": "10px" }, 300, !1, function() {
            $(".yy-float-wrap").animate({ right: "-164px" }, "2000");
            $(this).hide()
        });
        $(this).hide();
        $(".btn-slide-open").show()
    });
    $(".btn-slide-open").click(function() {
        $(".down-float-box").animate({ "margin-right": "0" }, 300, !1, function() {
            $(".yy-float-wrap").animate({ right: "50px" }, "2000");
            $(this).show()
        });
        $(this).hide();
        $(".btn-slide-close").show()
    });
    selectClassHandler();
    const swiper = new Swiper('.news-slide', {
        loop: true,
        speed: 400,
        spaceBetween: 100,
		autoplay: {
			delay: 3000,
		},
        pagination: {
            el: '.swiper-pagination',
            type: 'bullets',
        },
    });
});

function selectClassHandler() {
    const buttonCharecrers = $('.icon-character');
    const characterTitiles = $('.sect-title');
    const characterDescriptions = $('.char-desc');
    const skillVideo = $('.skill-video');

    if (buttonCharecrers) {
        buttonCharecrers.on('click', function(e) {
            const index = buttonCharecrers.index(e.target);

            characterDescriptions.removeClass('show');
            characterDescriptions[index].classList.add('show');
            characterTitiles.removeClass('show');
            characterTitiles[index].classList.add('show');
            skillVideo.removeClass('show');
            skillVideo[index].classList.add('show');
            skillVideo[index].play();
        })
    }
}