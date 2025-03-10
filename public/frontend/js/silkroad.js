var appRoot = "https://localhost:44385/";
if (document.URL.indexOf('srotest') >= 0) {
    appRoot = "https://srotest.vtcgame.vn/";
}
if (document.URL.indexOf('sro.vtcgame.vn') >= 0) {
    appRoot = "https://sro.vtcgame.vn/";
}
if (document.URL.indexOf('localhost') >= 0) {
    appRoot = "https://localhost:44385/";
}
if (document.URL.indexOf('sro-beta.vtcgame.vn') >= 0) {
    appRoot = "https://sro-beta.vtcgame.vn/";
}
function closePopUp() {
    $("#truy_kick_modal").html("");
}
function closePopUpQC(a) {
    var status = 0;
    if ($("#cbCheck").is(":checked")) {
        status = 1;
    }

    var param = {
        bannerID: a,
        status: status
    }
    $.ajax({
        type: 'POST',
        url: appRoot + "Home/BlockPopup",
        data: param,
        success: function (data) {
            $("#popupQC").html("");
        }
    });
    
}
function onSearchAndForwardPage() {
    if (event.key === 'Enter') {
        event.preventDefault();
        var keyword = $("#keyword").val(); 
        
        location.href = appRoot + "tim-kiem-tin-tuc?keyword=" + keyword + "&pageNum=" + 1 + "&pageSize=" + 10;                
    }
}
function onPagingSearch(pageNum, pageSize) {
    var param = {
        keyword: $("#keyword-search").val(),
        pageNum: Number(pageNum) == 0 ? 1 : Number(pageNum),
        pageSize: pageSize
    }

    $.ajax({
        type: 'POST',
        url: appRoot + "Home/GetArticlesListSearchByKeyWord",
        data: param,
        success: function (data) {
            $("#new_list").html(data);
        }
    });
}
function onSearchDetail(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        onPagingSearch(1, 10)
    }

}
function onSearch(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        onPaging(1, 1, 10)
    }

}
function onPaging(CateID, PageNum, PageSize) {
    var param = {
        Keyword: $("#keyword").val(),
        PageNum: Number(PageNum) == 0 ? 1 : Number(PageNum),
        PageSize: PageSize,
        CateID: CateID
    }

    $.ajax({
        type: 'POST',
        url: appRoot + "Home/GetArticlesList",
        data: param,
        success: function (data) {
            $("#new_list").html(data);
            $(".app1-right__tabs-body").removeClass("active");

            if (CateID == 1) {
                $("#menu1").addClass("active");
            }
            if (CateID == 2) {
                $("#menu2").addClass("active");
            }
            if (CateID == 3) {
                $("#menu3").addClass("active");
            }
            if (CateID == 4) {
                $("#menu4").addClass("active");
            }
        }
    });
}

function onSearchIntruct(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        onPagingIntruct(6, 1, 10)
    }

}
function onPagingIntruct(CateID, PageNum, PageSize) {
    var param = {
        Keyword: $("#keyword").val(),
        PageNum: Number(PageNum) == 0 ? 1 : Number(PageNum),
        PageSize: PageSize,
        CateID: CateID
    }

    $.ajax({
        type: 'POST',
        url: appRoot + "Home/GetArticlesIntructList",
        data: param,
        success: function (data) {
            $("#new_list").html(data);
            $(".app1-right__tabs-body").removeClass("active");

            if (CateID == 6) {
                $("#menu1").addClass("active");
            }
            if (CateID == 7) {
                $("#menu2").addClass("active");
            }
        }
    });
}
function onPagingQuestion(cateID, page, pageSize) {
    var param = {
        search: $("#txtSearchQuestion").val(),
        page: Number(page) == 0 ? 1 : Number(page),
        pageSize: pageSize,
        cateID: cateID
    }

    $.ajax({
        type: 'POST',
        url: appRoot + "Home/GetQuestionPaging",
        data: param,
        success: function (data) {
            $("#binding-data-question-" + cateID).html(data);
        }
    });
}
function onSearchQuestion(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        var cateID = $(".dvkh-ul2__a.active").attr('data-cateId');
        onPagingQuestion(cateID, 1, 10)
    }

}

function checkIsMobileDevice() {
    var isiPhone = navigator.userAgent.toLowerCase().indexOf("iphone");
    var isiPad = navigator.userAgent.toLowerCase().indexOf("ipad");
    var isiPod = navigator.userAgent.toLowerCase().indexOf("ipod");

    var isAndroid = /android/i.test(navigator.userAgent.toLowerCase());

    var isBlackberry = navigator.userAgent.toLowerCase().indexOf("BlackBerry");

    var isOpera = /Opera/i.test(navigator.userAgent.toLowerCase());

    var isIE = navigator.userAgent.toLowerCase().indexOf("IEMobile");

    var isWinPhone = navigator.userAgent.toLowerCase().indexOf("windows phone");

    if (isiPhone > -1) return true;
    if (isiPad > -1) return true;
    if (isiPod > -1) return true;
    if (isAndroid) return true;
    if (isBlackberry > -1) return true;
    if (isOpera) return true;
    if (isIE > -1) return true;
    if (isWinPhone > -1) return true;
    return false;
    //$('#watchme').text("You are not using any of the common mobile platforms like Apple, Android, Blackberry, Opera, IE Mobile");
}

function getItemWebMall(shop_no, sub_no) {
    var param = {
        shop_no: shop_no,
        sub_no: sub_no,
        pageNum: 1
    }
    $("#shop-no").val(shop_no);
    $("#sub-no").val(sub_no);
    $.ajax({
        type: 'POST',
        url: appRoot + "WebMall/GetItemWebMall",
        data: param,
        success: function (data) {
            $("#item-webmall-lcl").html(data);
        }
    });
}
function onPagingGetItemWebMall(page) {
    var param = {
        shop_no: $("#shop-no").val(),
        sub_no: $("#sub-no").val(),
        pageNum: page
    }
    $.ajax({
        type: 'GET',
        url: appRoot + "WebMall/GetItemWebMall",
        data: param,
        success: function (data) {
            $("#item-webmall-lcl").html(data);
        }
    });
}
function forwardBuyItem(package_id, item_name, silk_price, package_code, package_icon, package_name, explain, use_method, use_restriction) {
    var param = {
        package_id: package_id,
        item_name: item_name,
        silk_price: silk_price,
        package_code: package_code,
        package_icon: package_icon,
        package_name: package_name,
        explain: explain,
        use_method: use_method,
        use_restriction: use_restriction
    };
    localStorage.setItem('bi', JSON.stringify(param));
    window.location.href = "/xac-nhan-giao-dich";
}
function buyItem() {
    var amount = $("#buy-item-silk").text().replace(" Silk", "").replaceAll(",","");
    var haveSilk = $("#account-buy-item-silk").text().replace(" Silk", "").replaceAll(",", "");
    if (Number(amount) > Number(haveSilk)) {
        popupNoti(1, "Bạn không đủ Silk để mua.");
        return;
    }
    var data = JSON.parse(localStorage.getItem('bi'));;
    if (!data) {
        return;
    }
    var antiforgeytoken = $('input[name=__RequestVerificationToken]').val();
    var param = {
        serverID: $("#wm-server").find(":selected").val(),
        serverName: $("#wm-server").find(":selected").text(),
        packageID: data.package_id,
        packageName: data.package_name,
        quantity: $("#wm-quantity").find(":selected").text(),
        silkPrice: $("#p-i").val(),
        __RequestVerificationToken: antiforgeytoken
    }

    $.ajax({
        type: 'POST',
        url: appRoot + "WebMall/BuyItem",
        data: param,
        success: function (res) {
            if (res && res.Status > 0) {
                localStorage.setItem('q', $("#wm-quantity").find(":selected").text());
                localStorage.setItem('bl', res.Balance);
                localStorage.setItem('sn', $("#wm-server").find(":selected").text());
                forwardLink("giao-dich-thanh-cong");
            } else {
                popupNoti(1, res.Message);
            }
        }
    });
}

function backWebMall() {
    window.location.href = "/webmall";
}
function popupNoti(type, des, obj) {
    if (!des) {
        des = "";
    }
    $.ajax({
        type: "GET",
        url: appRoot + "Home/PopupNoti",
        data: {
            type: type,
            description: des
        },
        success: function (data) {
            $(".noti_popup").html(data);
            $('.noti_popup').css('display', 'flex');
        }
    });
}
function closePopup() {
    $(".noti_popup").html('');
    $('.noti_popup').css('display', 'none');
}
function refLink(link) {
    window.open(link, '_blank');
}
function forwardLink(link) {
    window.location.href = link;
}

function onPagingHistory(PageNum, PageSize) {
    var param = {
        month: $('#month-lcl').find(":selected").val(),
        year: $('#year-lcl').find(":selected").val(),
        pageNum: Number(PageNum),
        pageSize: PageSize
    }

    $.ajax({
        type: 'POST',
        url: appRoot + "WebMall/GetHistoryBuy",
        data: param,
        success: function (data) {
            $("#table-history").html('');
            $("#table-history").html(data);
        }
    });
}
function popupHistoryGiftCode() {
    $.ajax({
        type: "GET",
        url: appRoot + "Service/PopupHistoryGiftCode",
        success: function (data) {
            $(".noti_popup").html(data);
            $('.noti_popup').css('display', 'flex');
        }
    });
}
function onPagingHistoryGiftCode(PageNum, PageSize) {
    var param = {
        page: Number(PageNum),
        pageSize: PageSize
    }

    $.ajax({
        type: 'POST',
        url: appRoot + "Service/GetHistoryGiftCode",
        data: param,
        success: function (data) {
            $("#history-giftcode-lcl").html(data);
        }
    });
}
function forwardLinkCheckLogin(accId, link) {
    if (!(accId > 0)) {
        calPopLogin();
        return;
    }
    window.location.href = link;
}

function receiveGiftCode() {
    var giftcode = $("#g-giftcode").val();
    if (!giftcode) {
        popupNoti(1, "GiftCode chưa nhập");
        return;
    }
    var antiforgeytoken = $('input[name=__RequestVerificationToken]').val();
    var param = {
        serverID: $("#wm-server").find(":selected").val(),
        giftCode: giftcode,
        __RequestVerificationToken: antiforgeytoken
    }

    $.ajax({
        type: 'POST',
        url: appRoot + "Service/ReceiveGiftCode",
        data: param,
        success: function (res) {
            if (res) {
                popupNoti(1, res.Message);
            } else {
                popupNoti(1, "Hệ thống đang bận! Vui lòng thử lại sau");
            }
        }
    });
}

function receiveGift(rechargeID, type, id) {
    var serverID;
    if (type == 1) { //Quà nạp lần đầu
        serverID = $("#wm-server-first").find(":selected").val();
    }
    else if (type == 2) { //Tích nạp
        serverID = $("#wm-server-tn").find(":selected").val();
    }
    var antiforgeytoken = $('input[name=__RequestVerificationToken]').val();
    var param = {
        rechargeID: rechargeID,
        serverID: serverID,
        type: type,
        __RequestVerificationToken: antiforgeytoken
    }

    $.ajax({
        type: 'POST',
        url: appRoot + "Service/ReceiveGift",
        data: param,
        success: function (res) {
            if (res) {
                if (res.Status > 0) {
                    if (type == 1) {
                        $(".gift-first-btn").addClass('defaul-button');
                        $(".gift-first-btn").text('Đã nhận');
                    }
                    if (type == 2) {
                        $(".tn-btn-" + id).removeClass('active');
                        $(".tn-btn-" + id).text('Đã nhận');
                    }
                }
                popupNoti(1, res.Message);
            } else {
                popupNoti(1, "Hệ thống đang bận! Vui lòng thử lại sau");
            }
        }
    });
}

function resetAccount() {
    var antiforgeytoken = $('input[name=__RequestVerificationToken]').val();
    var param = {
        serverID: $("#wm-server-rs").find(":selected").val(),
        __RequestVerificationToken: antiforgeytoken
    }
    $.ajax({
        type: 'POST',
        url: appRoot + "Service/ResetAccount",
        data: param,
        success: function (res) {
            if (res) {
                popupNoti(1, res.Message);
                if (res.Status > 0) {
                    document.querySelectorAll('#info-silk').forEach((value, index, array) => {
                        value.textContent = res.Data.Balance
                    });
                    document.querySelectorAll('#info-1silk').forEach((value, index, array) => {
                        value.textContent = res.Data.SilkMonth
                    });
                    document.querySelectorAll('#info-3silk').forEach((value, index, array) => {
                        value.textContent = res.Data.Silk3Month
                    });
                }
            } else {
                popupNoti(1, "Hệ thống đang bận! Vui lòng thử lại sau");
            }
        }
    });
}
function numberWithCommas(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function trackDownload() {
    fbq('track', 'Subscribe');
    popupNoti(3, "");
}
function detectIEEdge() {
    var ua = window.navigator.userAgent;

    var msie = ua.indexOf('MSIE ');
    if (msie > 0) {
        // IE 10 or older => return version number
        return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
    }

    var trident = ua.indexOf('Trident/');
    if (trident > 0) {
        // IE 11 => return version number
        var rv = ua.indexOf('rv:');
        return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
    }

    var edge = ua.indexOf('Edge/');
    if (edge > 0) {
        // Edge => return version number
        return parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
    }

    // other browser
    return 0;
}

function onTabRank(type) {
    var server = $("#server-type").find(":selected").val();
    if (type == 3) {
        type = $("#job-sl").find(":selected").val();
    }
    onPagingRank(server, 1, 10, type)
}
function onSearchServerRank() {
    var server = $("#server-type").find(":selected").val();
    var type = $("#bxh-type").val();
    onPagingRank(server, 1, 10, type)
}
function onPagingRank(server, pageNum, pageSize, type) {
    var param = {        
        page: Number(pageNum) == 0 ? 1 : Number(pageNum),
        pageSize: pageSize,
        server: server,
        type: type
    }
    $("#bxh-type").val(type);
    $.ajax({
        type: 'POST',
        url: appRoot + "Home/RankLevel",
        data: param,
        success: function (data) {
            if (type == 1) { //Level
                $("#rank-level").html(data);
            } else if(type == 2){ //Guild
                $("#rank-guild").html(data);
            } else if (type == 5) { //Gỉa kim thuật
                $("#rank-gkt").html(data);
            } else{ //3,4: Bảo tiêu, đạo tặc
                $("#job-guild").html(data);
            }
        }
    });
}