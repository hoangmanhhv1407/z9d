$(document).ready(function(){
    $('#wraper #container #header .menu-top .menu-ul .menu-li').removeClass('active');
    $('#wraper #container #header .menu-top .menu-ul .menu-li.huongdancaothu').addClass('active');
    list_left();
});

$(document).ready(function(){
    $(window).scroll(function(){
        if ($(this).scrollTop() > 100) {
            $('.scrollToTop').fadeIn();
        } else {
            $('.scrollToTop').fadeOut();
        }
    });
    $('.scrollToTop').click(function(){
        $('html, body').animate({scrollTop : 0},800);
        return false;
    });
});

function list_left(){
    // var id = $('.category-news.active').attr('id');
    // $('#'+id).next().show();
    // $('.category-news').click(function(){
    //  $('.category-news').removeClass('active');
    //  $(this).addClass('active');
    //  $('.list-news .news .menu-ul').slideUp();
    //  $(this).next().slideDown();
    // });

    //cache nav
    var nav = $(".list-news");

    var hover = nav.find('ul li.menu-li.active');

    if (hover.length > 0) {
        hover.parent('ul').show();
    };

    //add indicator and hovers to submenu parents
    nav.find(".news").each(function() {
        if ($(this).find("ul.menu-ul").length > 0) {
            var $subMenu = $(this).find("ul.menu-ul");

            $(this).hover(
                function() {
                    $(this).find(".category-news").addClass('active');
                    $subMenu.slideDown(65);
                },
                function() {
                    if ($(this).find(".menu-li.active").length > 0) {
                        $(this).find(".category-news").addClass('active');
                        $subMenu.slideDown(65);
                    }else{
                        $subMenu.slideUp(65);
                        $(this).find(".category-news").removeClass('active');
                    }
                }
            );
        }
    });
}

