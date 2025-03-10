
$(document).ready(function() {

    // Xử lý button GoTop
    var widBody = $('body').width();
    var heiBody = $('body').height();
    var offset = 200,
    scroll_opacity = 400,
    scroll_duration = 600,
    goTop = $('.btn_gotop');
    goTop.css('left',widBody/2+400);
    goTop.css('top',heiBody/2-500);
    $(window).scroll(function() {
        ($(this).scrollTop() < offset) ? goTop.removeClass('active') : goTop.addClass('active');
        if($(this).scrollTop()>scroll_opacity){
            goTop.addClass('active');
        }
    })
    goTop.click(function() {
        $('html,body').animate({scrollTop: 0}, scroll_duration);
    });
    
    //Xử lí menubar
    // heightfooter    = $('.footer').height();
    // menusidebar = $('.menu_sidebar');
    // heimenusidebar  = menusidebar.height();
    // menusidebar.css('position','relative');
    // var $topOffset1 = 200;
    // console.log($(document).height() );

    // function top () {
    //     if($(document).height() - heightfooter < $(window).scrollTop() + heimenusidebar) {
    //         var $top = $(document).height() - heimenusidebar - heightfooter  - 25;
    //         $('.menu_sidebar').attr('style', 'position:absolute; top:'+$top+'px;');
    //     }else if($(window).scrollTop() > $topOffset1) {
    //         $('.menu_sidebar').attr('style', 'position:fixed; top: 0px');
    //     }else{
    //         $('.menu_sidebar').attr('style', 'position:relative;');
    //         }
    //     }
    // $(window).scroll(function() {
    //     top();
    // })
});