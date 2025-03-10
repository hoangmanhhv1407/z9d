$(document).ready(function () {
    if (window.location.href.indexOf("vtcgame.vn") != -1 || window.location.href.indexOf("localhost") != -1 || window.location.href.indexOf("vtc.vn") != -1 || window.location.href.indexOf("vtcpay.vn") != -1) // || window.location.href.indexOf("esports.vn") != -1
    {
        $.ajax({
            type: "Get",
            url: ConfigHeader.HEADER_URL + "home/commonv2",
            crossDomain: true,
            xhrFields: {
                withCredentials: true
            },
            success: function (data) {
                if (data) {
                    $("#header").html(data);
                }
            }
        });
    }
});

var vis = (function () {
    var stateKey,
        eventKey,
        keys = {
            hidden: "visibilitychange",
            webkitHidden: "webkitvisibilitychange",
            mozHidden: "mozvisibilitychange",
            msHidden: "msvisibilitychange"
        };
    for (stateKey in keys) {
        if (stateKey in document) {
            eventKey = keys[stateKey];
            break;
        }
    }
    return function (c) {
        if (c) document.addEventListener(eventKey, c);
        return !document[stateKey];
    }
})();

vis(function () {
    if (vis()) {
        $.ajax({
            type: "Get",
            url: ConfigHeader.HEADER_URL + "home/commonv2",
            crossDomain: true,
            xhrFields: {
                withCredentials: true
            },
            success: function (data) {
                if (data) {
                    $("#header").html(data);
                }
            }
        });

    } else {

    }
});