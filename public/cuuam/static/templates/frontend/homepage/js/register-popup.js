$(document).ready(function() {
    var chooseTurn = $('#chooseTurn').val();
    var ActiveTab = $('#ActiveTab').val();
    ActiveTab = 1;
    if (ActiveTab != "") {
        if (chooseTurn == "1") {
            $('#content-popup ul li').removeClass('activexx1');
            $('#chooseTurn').val('2');
            if (ActiveTab != "") {
                if (ActiveTab == 1) {
                    $('#contenttabxx1').show();
                    $('#contenttabxx2').hide();
                    $('#content-popup ul li:first').addClass('activexx1');
                }
                else {
                    $('#contenttabxx1').hide();
                    $('#contenttabxx2').show();
                    $('#content-popup ul li:last').addClass('activexx1');
                }
            }
        }
        else {
            $('#content-popup ul li').removeClass('activexx1');
            var chooseTab = $('#choosetab').val();
            if (chooseTab == '#contenttabxx1') {
                $('#contenttabxx1').show();
                $('#contenttabxx2').hide();
                $('#content-popup ul li:first').addClass('activexx1');
            }
            else {
                $('#contenttabxx1').hide();
                $('#contenttabxx2').show();
                $('#content-popup ul li:last').addClass('activexx1');
            }
        }
    }
    else {
        $('#content-popup ul li').removeClass('activexx1');
        var chooseTab = $('#choosetab').val();
        if (chooseTab == '#contenttabxx1') {
            $('#contenttabxx1').show();
            $('#contenttabxx2').hide();
            $('#content-popup ul li:first').addClass('activexx1');
        }
        else {
            $('#contenttabxx1').hide();
            $('#contenttabxx2').show();
            $('#content-popup ul li:last').addClass('activexx1');
        }
    }

    $('#content-popup ul li a').click(function() {
        $('#content-popup ul li').removeClass('activexx1');
        $(this).parent().addClass('activexx1');
        var currentTab = $(this).attr('href');
        $('#tab').val($(this).attr('href'));
        $('.subTabxx').hide();
        $(currentTab).show();
        return false;
    });
    $('.loginQuick a').click(function() {
        $('#contenttabxx1').hide();
        $('#contenttabxx2').show();
        $('#content-popup ul li').removeClass('activexx1');
        $('#content-popup ul li:last').addClass('activexx1');

    });
});

function myFocus(element) {
    if (element.value == element.defaultValue) {
        element.value = '';
    }
}
function myBlur(element) {
    if (element.value == '') {
        element.value = element.defaultValue;
    }
}
$(document).ready(function() {
    $('#regFast a').click(function() {
        $('#contenttabxx1').show();
        $('#contenttabxx2').hide();
        $('#content-popup ul li').removeClass('activexx1');
        $('#content-popup ul li:first').addClass('activexx1');

    });
});