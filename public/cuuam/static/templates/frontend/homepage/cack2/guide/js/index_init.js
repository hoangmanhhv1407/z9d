$(document).ready(function(){
	// $('#wraper #container #header .menu-top .menu-ul .menu-li').removeClass('active');
	// $('#wraper #container #header .menu-top .menu-ul .menu-li.huongdantanthu').addClass('active');
	list_left();
});

function list_left(){
    var nav = $("#tanthu");
    var hover = nav.find('.block.tanthu:hover');
    if (hover.lenght > 0) {
        debugger;
    };
}

$('#owl-guide').slick({
  dots: false,
  infinite: false,
  speed: 300,
  slidesToShow: 13,
  slidesToScroll: 10,
});

