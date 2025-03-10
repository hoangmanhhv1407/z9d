//cấu hình
//var HEADER_URL = 'http://localhost:2146/';
//var PORTAL_URL = 'http://localhost:53277/';
//var LinkTracking = 'http://sandbox.vtcgame.vn/accountapi/api/tracking/InsertNRU/';

/*const { ajax } = require("jquery");*/
var HEADER_URL = 'https://header.vtcgame.vn/';
var PORTAL_URL = 'https://vtcgame.vn/';
var LinkTracking = 'https://header.vtcgame.vn/accountapi/api/tracking/InsertNRU';

//if (document.URL.indexOf('localhost') >= 0) {
//    HEADER_URL = 'http://localhost:2146/';
//    PORTAL_URL = 'http://localhost:53277/';
//    LinkTracking = 'http://sandbox.vtcgame.vn/accountapi/api/tracking/InsertNRU';
//}
//if (document.URL.indexOf('sandbox.vtcgame.vn') >= 0) {
//    HEADER_URL = 'http://sandbox.vtcgame.vn/headercommon/';
//    PORTAL_URL = 'http://sandbox.vtcgame.vn/vtcgame/';
//    LinkTracking = 'http://sandbox.vtcgame.vn/accountapi/api/tracking/InsertNRU';
//}
//if (document.URL.indexOf('sandboxssl.vtcgame.vn') >= 0) {
//    HEADER_URL = 'https://sandboxssl.vtcgame.vn/headercommon/';
//    PORTAL_URL = 'https://sandboxssl.vtcgame.vn/vtcgame/';
//    LinkTracking = 'https://sandboxssl.vtcgame.vn/accountapi/api/tracking/InsertNRU';
//}
//if (document.URL.indexOf('vtcgame.vn') >= 0) {
//    HEADER_URL = 'https://header.vtcgame.vn/';
//    PORTAL_URL = 'https://vtcgame.vn/';
//    LinkTracking = 'https://vtcgame.vn/account.sso.api/api/tracking/InsertNRU';
//}
//if (document.URL.indexOf('beta.vtcgame.vn') >= 0) {
//    HEADER_URL = 'https://headerbeta.vtcgame.vn/';
//    PORTAL_URL = 'https://beta.vtcgame.vn/';
//    LinkTracking = 'https://beta.vtcgame.vn/accountapi/api/tracking/InsertNRU';
//}
//if (document.URL.indexOf('vtc.vn') >= 0) {
//    HEADER_URL = 'https://au.vtc.vn/header/';
//    PORTAL_URL = 'https://vtcgame.vn/';
//    LinkTracking = 'https://vtcgame.vn/account.sso.api/api/tracking/InsertNRU';
//}
//if (document.URL.indexOf('esports.vn') >= 0) {
//    HEADER_URL = 'https://header.esports.vn/';
//    PORTAL_URL = 'https://vtcgame.vn/';
//    LinkTracking = 'https://vtcgame.vn/account.sso.api/api/tracking/InsertNRU';
//}

var ConfigHeader = {
    HEADER_URL: HEADER_URL,
    PORTAL_URL: PORTAL_URL,
    HEADER_HANDLE: HEADER_URL + 'Handler/Authen.ashx',

    AUTHEN_OPENID_URL: PORTAL_URL + 'accountapi/api/openid/login?',
    REGISTER_API_URL: PORTAL_URL + 'accountapi/',
    UrlMediaVTCEbank: 'http://sandbox1.vtcebank.vn/cmsadmin/resources/upload/',
    Loading_Page: PORTAL_URL + 'Scripts/loading.js',
    FacebookAppId: '1723059994609435',
    urlRootOAuth: PORTAL_URL + 'account/oauthen/'
};

var validatekey = "bamboo";
Static_AddReference = function (url, type) {
    var fileref = "";
    if (type == "js") {
        fileref = document.createElement("script");
        fileref.setAttribute("type", "text/javascript");
        fileref.setAttribute("src", url);
    } else if (type == "css") {
        fileref = document.createElement("link");
        fileref.setAttribute("rel", "stylesheet");
        fileref.setAttribute("type", "text/css");
        fileref.setAttribute("href", url);
    }
    if (typeof fileref != "undefined")
        document.getElementsByTagName("head")[0].appendChild(fileref);
};

//Static_AddReference(ConfigHeader.HEADER_URL + 'content/headerCSS.css', 'css');
Static_AddReference(ConfigHeader.HEADER_URL + 'Assets/css/header_style.css', 'css');
Static_AddReference(ConfigHeader.HEADER_URL + 'scripts/client-custom.min.js', 'js');
Static_AddReference(ConfigHeader.HEADER_URL + 'headerJS/commonHead.js', 'js');
Static_AddReference(ConfigHeader.HEADER_URL + 'headerJS/Utility.js', 'js');
Static_AddReference(ConfigHeader.HEADER_URL + 'headerJS/AccountRegister.js', 'js');
Static_AddReference(ConfigHeader.HEADER_URL + 'Assets/js/qrcode.js', 'js');
//Static_AddReference(ConfigHeader.HASH_URL, 'js');
//Static_AddReference(ConfigHeader.HEADER_URL + 'content/bootstrap.min.css', 'css');
//Static_AddReference(ConfigHeader.HEADER_URL + 'css/component.css', 'css');
//Static_AddReference(ConfigHeader.HEADER_URL + 'css/default.css', 'css');
//Static_AddReference(ConfigHeader.HEADER_URL + 'CSS.css', 'css');

//I get this code from here http://stackoverflow.com/questions/901115/how-can-i-get-query-string-values-in-javascript
function getUrlParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
//Static_AddReference(ConfigHeader.HEADER_URL + 'Scripts/jquery-1.10.2.js', 'js');

function calPopLogin(returnUrl) {
    var urlPopup = ConfigHeader.HEADER_URL + "home/PopupRegisterByMobile";
    if (returnUrl != null && returnUrl.length > 0) {
        urlPopup = urlPopup + '?returnUrl=' + returnUrl;
    }
    $.ajax({
        type: "POST",
        url: urlPopup,
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        success: function (data) {
            if (data) {
                $("#LogAndReg").html(data);
            }
        }
    });
}

function calPopReg(returnUrl) {
    var urlPopup = ConfigHeader.HEADER_URL + "home/PopupRegister";
    if (returnUrl != null && returnUrl.length > 0) {
        urlPopup = urlPopup + '?returnUrl=' + returnUrl;
    }
    $.ajax({
        type: "POST",
        url: urlPopup,
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        success: function (data) {
            if (data) {
                $("#LogAndReg").html(data);
            }
        }
    });
}

function calPopFastReg(returnUrl) {
    var urlPopup = ConfigHeader.HEADER_URL + "home/PopupRegister";
    if (returnUrl != null && returnUrl.length > 0) {
        urlPopup = urlPopup + '?returnUrl=' + returnUrl;
    }
    $.ajax({
        type: "POST",
        url: urlPopup,
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        success: function (data) {
            if (data) {
                $("#LogAndReg").html(data);
            }
        }
    });
}

function calPopRegisterByVTCID(returnUrl) {
    var urlPopup = ConfigHeader.HEADER_URL + "home/PopupRegisterByVTCID";
    if (returnUrl != null && returnUrl.length > 0) {
        urlPopup = urlPopup + '?returnUrl=' + returnUrl;
    }
    $.ajax({
        type: "POST",
        url: urlPopup,
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        success: function (data) {
            if (data) {
                $("#LogAndReg").html(data);
            }
        }
    });
}
function calPopRegisterByNumber(returnUrl) {
    var urlPopup = ConfigHeader.HEADER_URL + "home/PopupRegisterByNumber";
    if (returnUrl != null && returnUrl.length > 0) {
        urlPopup = urlPopup + '?returnUrl=' + returnUrl;
    }
    $.ajax({
        type: "POST",
        url: urlPopup,
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        success: function (data) {
            if (data) {
                $("#LogAndReg").html(data);
            }
        }
    });
}

function calPopFastRegNew(returnUrl, accName) {
    var urlPopup = ConfigHeader.HEADER_URL + "home/PopupRegister";
    if (returnUrl != null && returnUrl.length > 0) {
        urlPopup = urlPopup + '?returnUrl=' + returnUrl;
    }
    $.ajax({
        type: "POST",
        url: urlPopup,
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        success: function (data) {
            if (data) {
                $("#LogAndReg").html(data);
                $('#txtUserNameDK').val(accName);
            }
        }
    });
}

///// start đăng ký/đăng nhập tài khoản theo luồng mới 2019
//hàm đăng nhập
function LoginHeadByMobile(signex, Uname, idPass, token, idImageCaptcha, idHidverify, idRemember, idOtp, idOtpType, idFrameCaptcha, idThongBao, returnUrl) {
    var flag = true;
    var username = Uname;
    var pass = $("#" + idPass).val();
    var hidverify = $("#" + idHidverify).val();
    var isRemember = false;
    if ($("#" + idRemember).is(':checked')) {
        isRemember = true;
    }
    var otpType = $("#" + idOtpType).find('option:selected').val();
    var otp = '';
    if ($('.tt-blue:visible').length != 0) {
        otp = $("#" + idOtp).val();
        if (otp == '') {
            thongbao(idThongBao, "Vui lòng nhập OTP");
            return;
        }
    }
    var key = $("#key").val();
    if (pass == '') {
        thongbao(idThongBao, "Nhập đầy đủ các trường thông tin");
        return;
    }
    if (!CommonValid.ValidateUserName(username)) {
        thongbao(idThongBao, "Tên không hợp lệ");
        flag = false;
        flag = false;
        return;
    }
    ShowProgress();
    $.ajax({
        beforeSend: function () {
            return flag;
        },
        type: "POST",
        url: ConfigHeader.HEADER_URL + "Handler/Process.ashx?act=LoginByMobile",
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        data: {
            SignExtend: signex,
            conten: btoa(username), //conten: btoa(username.toLowerCase()),
            value: btoa(pass),
            capt: token,
            hidverify: "",
            isRemember: isRemember,
            key: key,
            otp: otp,
            otpType: 1,
            returnURL: returnUrl
        },
        success: function (data) {
           
            if (data) {
                HideProgress();
                if (data.ResponseStatus > 0) {
                    if (returnUrl.includes(ConfigHeader.PORTAL_URL + "verifyinsertinfo")
                        || returnUrl.includes(ConfigHeader.PORTAL_URL + "verifyemailchange")
                        || returnUrl.includes(ConfigHeader.PORTAL_URL + "verifyoldemail")
                        || returnUrl.includes(ConfigHeader.PORTAL_URL + "verifynewemail"))
                    {
                        var redirectUrl = returnUrl.replace('&type=login', '');
                        window.top.location.href = redirectUrl;
                    }
                    else {
                        window.top.location.reload();
                    }
                    
                }
                else {
                    if (data.ResponseStatus == -1000 || data.ResponseStatus == -1001)
                    {
                        var accID = data.accountID;
                        thongbao(idThongBao, '');
                        var urlCheckOTP = ConfigHeader.HEADER_URL + "Home/PopupLoginCheckOTP";
                        $.ajax({
                            type: "POST",
                            url: urlCheckOTP,
                            crossDomain: true,
                            xhrFields: {
                                withCredentials: true
                            },
                            data: {
                                accountName: username,
                                signExtend: signex,
                                accUseMobile: username,
                                returnUrl: returnUrl,
                                valueResult: btoa(pass),
                                ResCode: data.errorCode,
                                accountId: accID
                            },
                            success: function (data) {
                                if (data) {
                                    HideProgress();
                                    $("#LogAndReg").html(data);
                                }
                            }
                        });
                    }
                    else if (data.errorCode == -53) {
                        thongbao(idThongBao, "Sai thông tin đăng nhập!");
                    }
                    else if (data.ResponseStatus == -1005) {
                        $("#" + idFrameCaptcha).css("display", "block");
                        RefreshCaptcha('#ImgcaptchaDN', '#hidVerifyCapchaDN');
                        //$("#" + idImageCaptcha).attr("src", "data:image/jpeg;base64," + data.imageData);
                        //$("#" + idHidverify).val(data.Verify);
                        thongbao(idThongBao, "Sai thông tin đăng nhập!");
                    }
                    else if (data.ResponseStatus == -1007) {
                        window.top.location.reload();
                    }
                    else {
                        //$("#" + idImageCaptcha).attr("src", "data:image/jpeg;base64," + data.imageData);
                        //$("#" + idHidverify).val(data.Verify);
                        RefreshCaptcha('#ImgcaptchaDN', '#hidVerifyCapchaDN');
                        grecaptcha.reset();
                        thongbao(idThongBao, data.errorMessage);
                    }
                }
            }
        }
    });
}

function HomeCallPopLogReg(returnUrl) {
    var urlPopup = ConfigHeader.HEADER_URL + "home/PopupRegisterByMobile";
    if (returnUrl != null && returnUrl.length > 0) {
        urlPopup = urlPopup + '?returnUrl=' + returnUrl;
    }
    $.ajax({
        type: "POST",
        url: urlPopup,
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        success: function (data) {
            if (data) {
                $("#LogAndReg").html(data);
                var username = $("#cg_txtUserName").val();
                $('#phone_number').val(username);
            }
        }
    });
}

//mở popup đăng nhập/ đăng ký
function calPopRegByMobile(returnUrl) {
    var urlPopup = ConfigHeader.HEADER_URL + "home/PopupRegisterByMobile";
    if (returnUrl != null && returnUrl.length > 0) {
        urlPopup = urlPopup + '?returnUrl=' + returnUrl;
    }
    $.ajax({
        type: "POST",
        url: urlPopup,
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        success: function (data) {
            if (data) {
                $("#LogAndReg").html(data);
            }
        }
    });
}

//function calPopLogin(returnUrl) {
//    var urlPopup = ConfigHeader.HEADER_URL + "home/PopupRegisterByMobile";
//    if (returnUrl != null && returnUrl.length > 0) {
//        urlPopup = urlPopup + '?returnUrl=' + returnUrl;
//    }
//    $.ajax({
//        type: "POST",
//        url: urlPopup,
//        crossDomain: true,
//        xhrFields: {
//            withCredentials: true
//        },
//        success: function (data) {
//            if (data) {
//                $("#LogAndReg").html(data);
//            }
//        }
//    });
//}

//function calPopReg(returnUrl) {
//    var urlPopup = ConfigHeader.HEADER_URL + "home/PopupRegisterByMobile";
//    if (returnUrl != null && returnUrl.length > 0) {
//        urlPopup = urlPopup + '?returnUrl=' + returnUrl;
//    }
//    $.ajax({
//        type: "POST",
//        url: urlPopup,
//        crossDomain: true,
//        xhrFields: {
//            withCredentials: true
//        },
//        success: function (data) {
//            if (data) {
//                $("#LogAndReg").html(data);
//            }
//        }
//    });
//}
function CheckAccountByMobile2(idUname, idThongBao, accountName, token, returnUrl, idCountry) {
    ShowProgress();
    $.ajax({
        url: ConfigHeader.HEADER_URL + 'Home/CheckAcountByMobile',
        type: 'POST',
        data: {
            acountName: accountName,
            reCaptchaToken: token
        },
        success: function (data) {
            grecaptcha.reset();
            if (data) {
                if (data.ResponseStatus > 0) {
                    //tài khoản đã tồn tại, chuyển sang nhập mật khẩu để đăng nhập
                    var urlPopupLogin = ConfigHeader.HEADER_URL + "Home/PopupLoginByMobile";
                    var IsRegister = $('#IsRegisterByPhone').val()
                    if (IsRegister == 1) {
                        HideProgress();
                        thongbao(idThongBao, data.ErorrMess);
                        return;
                    }
                    if (returnUrl != null && returnUrl.length > 0) {
                        urlPopupLogin = urlPopupLogin + '?returnUrl=' + returnUrl;
                    }
                    $.ajax({
                        url: ConfigHeader.HEADER_URL + 'Home/CheckSignexItem',
                        type: 'GET',
                        data: {
                            acountName: accountName,
                        },
                        success: function (data) {
                            LoginHeadByMobile(data, accountName, 'txtPass', token , 'ImgcaptchaDN', 'hidVerifyCapchaDN', 'checkbox_dangky', 'otp', 'optType', 'frameCapcha', idThongBao, returnUrl);
                        }
                    })

                    return false;
                }else {
                    HideProgress();
                    thongbao(idThongBao, "Sai thông tin đăng nhập !");
                }
            }
        },
        error: function () {
            HideProgress();
            /* $(idThongBao).html("Hệ thống bận. Vui lòng thử lại sau.");*/
            thongbao(idThongBao, "Hệ thống bận. Vui lòng thử lại sau.")
            //$('#hdvalidate').val("0");
        }
    });
};
//Step 1 - AccountRegisterByMobile
//kiểm tra tài khoản
function CheckAccountByMobile(idUname, idThongBao, accountName, token, returnUrl, idCountry) {
    ShowProgress();

    //fb account kit
    //var country_code = $("#" + idCountry).val();
    //if (country_code.indexOf('84') >= 0) { }

    $.ajax({
        url: ConfigHeader.HEADER_URL + 'Home/CheckAcountByMobile',
        type: 'POST',
        data: {
            acountName: accountName,
            reCaptchaToken: token
        },
        success: function (data) {
            grecaptcha.reset();
            if (data) {
                if (data.ResponseStatus > 0) {
                    //tài khoản đã tồn tại, chuyển sang nhập mật khẩu để đăng nhập
                    var urlPopupLogin = ConfigHeader.HEADER_URL + "Home/PopupLoginByMobile";
                    var IsRegister = $('#IsRegisterByPhone').val()
                    if (IsRegister == 1) {
                        HideProgress();
                        thongbao(idThongBao, data.ErorrMess);
                        return;
                    }
                    if (returnUrl != null && returnUrl.length > 0) {
                        urlPopupLogin = urlPopupLogin + '?returnUrl=' + returnUrl;
                    }
                    $.ajax({
                        url: ConfigHeader.HEADER_URL + 'Home/CheckSignexItem',
                        type: 'GET',
                        data: {
                            acountName: accountName,
                        },
                        success: function (data) {
                            LoginHeadByMobile(data, accountName, 'txtPass', token, 'ImgcaptchaDN', 'hidVerifyCapchaDN', 'checkbox_dangky', 'otp', 'optType', 'frameCapcha', idThongBao, returnUrl);
                        }
                    })
                    
                    return false;
                }
                else if (data.ResponseStatus == -50) {
                    ////Đổi luồng Đăng ký nhanh - tienpx - 05032021
                    ////$('#box_pwd').show();
                    //calPopFastRegNew(location.href, accountName);
                    //HideProgress();
                    //return;
                    ////Đổi luồng Đăng ký nhanh - tienpx - 05032021 - end

                    //Step 1 - AccountRegisterByMobile
                    //tài khoản chưa tồn tại, chuyển sang nhập OTP để đăng ký, step = 1
                    var client = new ClientJS();
                    var detectDevice = LocalStorageHelper(1, 'vtc_device_secure');
                    if (detectDevice === null || detectDevice === undefined || detectDevice === '') {
                        try {
                            detectDevice = '';
                            detectDevice += 'fingerprint:' + client.getFingerprint();
                            var deviceBrowser = client.getBrowser();
                            deviceBrowser = (deviceBrowser === 'Chrome' && client.getUserAgentLowerCase().indexOf("coc_coc") >= 0) ? 'Coccoc' : deviceBrowser;
                            detectDevice += ';devicebrowser:' + deviceBrowser;
                            detectDevice += ';OS:' + (client.getOS() + '-' + client.getOSVersion());
                            var device = client.getDevice();
                            device = device === undefined ? 'PC' : device;
                            detectDevice += ';device:' + device;
                            var deviceType = client.getDeviceType();
                            deviceType = deviceType === undefined ? 'PC' : deviceType;
                            detectDevice += ';devicetype:' + deviceType;
                            detectDevice += ';resolution:' + client.getCurrentResolution();
                        }
                        catch (err) {
                            console.log(err.message);
                            utils.unLoading();
                            return;
                        }
                    }
                    $.ajax({
                        type: "POST",
                        url: ConfigHeader.HEADER_URL + "Handler/Process.ashx?act=RegisterByMobile",
                        crossDomain: true,
                        xhrFields: {
                            withCredentials: true
                        },
                        data: {
                            step: 1,
                            conten: btoa(accountName.toLowerCase()),
                            DeviceDetail: detectDevice,
                            SignExtend: data.SignExtend

                        },
                        success: function (data) {
                            if (data) {
                                HideProgress();
                                if (data.ResponseCode > 0) {
                                    //kiểm tra SĐT chưa đăng ký, chuyển sang popup nhập OTP
                                    var urlPopupLogin = ConfigHeader.HEADER_URL + "Home/PopupRegisterCheckOTPByMobile";
                                    if (returnUrl != null && returnUrl.length > 0) {
                                        urlPopupLogin = urlPopupLogin + '?returnUrl=' + returnUrl;
                                    }
                                    $.ajax({
                                        type: "POST",
                                        url: urlPopupLogin,
                                        crossDomain: true,
                                        xhrFields: {
                                            withCredentials: true
                                        },
                                        data: {
                                            accountName: accountName,
                                            signExtend: data.Extend,
                                            accUseMobile: data.AccountUsingMobile,
                                            ResCode: data.ResponseCode
                                        },
                                        success: function (data) {
                                            if (data) {
                                                $("#LogAndReg").html(data);
                                            }
                                        }
                                    });
                                }
                                else {
                                    if (data.ResponseCode == -5) {
                                        thongbao(idThongBao, "Vui lòng đăng ký tài khoản VTC Game bằng SĐT hợp lệ");
                                    }
                                    else {
                                        thongbao(idThongBao, data.Description);
                                    }
                                }
                            }
                        }
                    });

                    //thongbao(idThongBao, "");
                    //$('#hdvalidate').val("1");
                    return true;
                }
                else {
                    HideProgress();
                    thongbao(idThongBao, data.ErorrMess);
                }
            }
        },
        error: function () {
            HideProgress();
           /* $(idThongBao).html("Hệ thống bận. Vui lòng thử lại sau.");*/
            thongbao(idThongBao, "Hệ thống bận. Vui lòng thử lại sau.")
            //$('#hdvalidate').val("0");
        }
    });

};

//Step 2 - AccountRegisterByMobile
//check OTP
function CheckOTPByMobile(username, id_otp, sign_code, returnUrl, idThongBao) {
    var otp_val = $("#" + id_otp).val();
    if (otp_val) {
        if (otp_val.length < 6) {
            thongbao(idThongBao, "Mã kích hoạt không hợp lệ");
            return false;
        }
    }
    ShowProgress();
    var client = new ClientJS();
    var detectDevice = LocalStorageHelper(1, 'vtc_device_secure');
    if (detectDevice === null || detectDevice === undefined || detectDevice === '') {
        try {
            detectDevice = '';
            detectDevice += 'fingerprint:' + client.getFingerprint();
            var deviceBrowser = client.getBrowser();
            deviceBrowser = (deviceBrowser === 'Chrome' && client.getUserAgentLowerCase().indexOf("coc_coc") >= 0) ? 'Coccoc' : deviceBrowser;
            detectDevice += ';devicebrowser:' + deviceBrowser;
            detectDevice += ';OS:' + (client.getOS() + '-' + client.getOSVersion());
            var device = client.getDevice();
            device = device === undefined ? 'PC' : device;
            detectDevice += ';device:' + device;
            var deviceType = client.getDeviceType();
            deviceType = deviceType === undefined ? 'PC' : deviceType;
            detectDevice += ';devicetype:' + deviceType;
            detectDevice += ';resolution:' + client.getCurrentResolution();
        }
        catch (err) {
            console.log(err.message);
            utils.unLoading();
            return;
        }
    }
    $.ajax({
        type: "POST",
        url: ConfigHeader.HEADER_URL + "Handler/Process.ashx?act=RegisterByMobile",
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        data: {
            step: 2,
            conten: btoa(username), //conten: btoa(username.toLowerCase()),
            otpCode: otp_val,
            signExtend: sign_code,
            DeviceDetail: detectDevice
        },
        success: function (data) {
            if (data) {
                HideProgress();
                if (data.ResponseCode > 0) {
                    //check OTP hợp lệ, chuyển sang popup nhập mật khẩu
                    var urlPopupLogin = ConfigHeader.HEADER_URL + "Home/PopupRegisterPasswordByMobile";
                    if (returnUrl != null && returnUrl.length > 0) {
                        urlPopupLogin = urlPopupLogin + '?returnUrl=' + returnUrl;
                    }
                    $.ajax({
                        type: "POST",
                        url: urlPopupLogin,
                        crossDomain: true,
                        xhrFields: {
                            withCredentials: true
                        },
                        data: {
                            accountName: username,
                            signExtend: data.Extend
                        },
                        success: function (data) {
                            if (data) {
                                $("#LogAndReg").html(data);
                            }
                        }
                    });
                }
                else {
                    thongbao(idThongBao, data.Description);
                }
            }
        }
    });
}
function CheckOTPLogin(username, id_otp, sign_code, returnUrl, idThongBao, valueResult) {
    var otp_val = $("#" + id_otp).val();
    if (otp_val) {
        if (otp_val.length < 6) {
            thongbao(idThongBao, "Mã kích hoạt không hợp lệ");
            return false;
        }
    }
    ShowProgress();
    var client = new ClientJS();
    var detectDevice = LocalStorageHelper(1, 'vtc_device_secure');
    if (detectDevice === null || detectDevice === undefined || detectDevice === '') {
        try {
            detectDevice = '';
            detectDevice += 'fingerprint:' + client.getFingerprint();
            var deviceBrowser = client.getBrowser();
            deviceBrowser = (deviceBrowser === 'Chrome' && client.getUserAgentLowerCase().indexOf("coc_coc") >= 0) ? 'Coccoc' : deviceBrowser;
            detectDevice += ';devicebrowser:' + deviceBrowser;
            detectDevice += ';OS:' + (client.getOS() + '-' + client.getOSVersion());
            var device = client.getDevice();
            device = device === undefined ? 'PC' : device;
            detectDevice += ';device:' + device;
            var deviceType = client.getDeviceType();
            deviceType = deviceType === undefined ? 'PC' : deviceType;
            detectDevice += ';devicetype:' + deviceType;
            detectDevice += ';resolution:' + client.getCurrentResolution();
        }
        catch (err) {
            console.log(err.message);
            utils.unLoading();
            return;
        }
    }

    //new data
    var key = $("#key").val();
    $.ajax({
        type: "POST",
        url: ConfigHeader.HEADER_URL + "Handler/Process.ashx?act=LoginByMobile",
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        data: {
            //step: 2,
            //conten: btoa(username), //conten: btoa(username.toLowerCase()),
            //otpCode: otp_val,
            //signExtend: sign_code,
            //DeviceDetail: detectDevice
            SignExtend: sign_code,
            conten: btoa(username), //conten: btoa(username.toLowerCase()),
            value: valueResult,
            capt: "",
            hidverify: "",
            isRemember: false,
            key: key,
            otp: otp_val,
            otpType: 1,
            returnURL: returnUrl
        },
        success: function (data) {
            if (data) {
                HideProgress();
                if (data.ResponseStatus > 0) {
                    window.top.location.reload();
                }
                else {
                    thongbao(idThongBao, data.errorMessage);
                }
            }
        }
    });
}
//Step 3 - AccountRegisterByMobile
//nhập mật khẩu - đăng ký tài khoản
function RegisterHeadByMobile(username, idPass, idRePass, sign_code, checkbox_dongy, idThongBao, returnUrl) {
    var flag = true;
    var pass = $("#" + idPass).val();
    var rePass = $("#" + idRePass).val();
    var dongy = true;
    //if ($("#" + checkbox_dongy).is(':checked')) {
    //    dongy = true;
    //}
    //else {
    //    thongbao(idThongBao, "Bạn chưa đồng ý với thỏa thuận sử dụng.");
    //    flag = false;
    //    return;
    //}
    if (pass == '') {
        thongbao(idThongBao, "Hãy nhập mật khẩu");
        flag = false;
        return;
    }
    if (hasWhiteSpace(pass) == true) {
        thongbao(idThongBao, "Mật khẩu không được chứa khoảng trắng");
        flag = false;
        return;
    }
    if (!CommonValid.ValidateLetterPassword(pass)) {
        thongbao(idThongBao, "Mật khẩu không hợp lệ");
        flag = false;
        return;
    }
    if (!ValidatePassRegister(idPass, idThongBao)) //mật khẩu phải có độ dài từ 4-18 ký tự
    {
        flag = false;
        return;
    }
    if (rePass == '') {
        thongbao(idThongBao, "Hãy nhập lại mật khẩu");
        flag = false;
        return;
    }

    if (pass != rePass) {
        thongbao(idThongBao, "Nhập lại mật khẩu không trùng khớp");
        flag = false;
        return;
    }

    
    var key = $("#key").val();
    var serveridck = "";
    var linkgenck = "";
    try {
        if (typeof HeaderChackingObj != undefined) {
            serveridck = HeaderChackingObj.ServiceId;
            linkgenck = HeaderChackingObj.LinkGen;
        }
    }
    catch (e) {
    }
    ShowProgress();
    var client = new ClientJS();
    var detectDevice = LocalStorageHelper(1, 'vtc_device_secure');
    if (detectDevice === null || detectDevice === undefined || detectDevice === '') {
        try {
            detectDevice = '';
            detectDevice += 'fingerprint:' + client.getFingerprint();
            var deviceBrowser = client.getBrowser();
            deviceBrowser = (deviceBrowser === 'Chrome' && client.getUserAgentLowerCase().indexOf("coc_coc") >= 0) ? 'Coccoc' : deviceBrowser;
            detectDevice += ';devicebrowser:' + deviceBrowser;
            detectDevice += ';OS:' + (client.getOS() + '-' + client.getOSVersion());
            var device = client.getDevice();
            device = device === undefined ? 'PC' : device;
            detectDevice += ';device:' + device;
            var deviceType = client.getDeviceType();
            deviceType = deviceType === undefined ? 'PC' : deviceType;
            detectDevice += ';devicetype:' + deviceType;
            detectDevice += ';resolution:' + client.getCurrentResolution();
        }
        catch (err) {
            console.log(err.message);
            utils.unLoading();
            return;
        }
    }
    $.ajax({
        beforeSend: function () {
            return flag;
        },
        type: "POST",
        url: ConfigHeader.HEADER_URL + "/Handler/Process.ashx?act=RegisterByMobile",
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        data: {
            step: 3,
            conten: btoa(username.toLowerCase()),
            value: btoa(pass),
            dongy: dongy,
            key: key,
            ServiceId: serveridck,
            LinkGen: linkgenck,
            signExtend: sign_code,
            DeviceDetail: detectDevice
        },
        success: function (data) {
            if (data) {
                if (data.ResponseStatus > 0) {
                    var redirecUrl = returnUrl;
                    if (redirecUrl == null || redirecUrl == '') {
                        redirecUrl = getUrlParameterByName('returnUrl');
                    }
                    try {
                        if (typeof HeaderRedirecUrl != undefined && (redirecUrl == null || redirecUrl == '')) {
                            redirecUrl = HeaderRedirecUrl.RedirecUrl;
                        }
                    }
                    catch (e) {
                        console.log("reload this page");
                    }
                    //tracking
                    if (data.linkgenck == '' || data.linkgenck == undefined) {
                        HideProgress();
                        if (redirecUrl != null && redirecUrl.length > 0) {
                            window.top.location = redirecUrl;
                        } else {
                            window.top.location.reload();
                        }
                    }
                    else {
                        $.ajax({
                            type: "POST",
                            url: LinkTracking,
                            timeout: 1000,
                            data: {
                                ServiceId: data.ServiceID,
                                OSId: 1,
                                //CPId: 1,
                                LinkGen: data.linkgenck,
                                AccountId: data.AccountID,
                                AccountName: data.AccountName,
                                AccountTypeId: 101
                            },
                            crossDomain: true,
                            xhrFields: {
                                withCredentials: true
                            },
                            error: function () {
                                HideProgress();
                                if (redirecUrl != null && redirecUrl.length > 0) {
                                    window.top.location = redirecUrl;
                                } else {
                                    window.top.location.reload();
                                }
                            },
                            success: function (data) {
                                HideProgress();
                                if (redirecUrl != null && redirecUrl.length > 0) {
                                    window.top.location = redirecUrl;
                                } else {
                                    window.top.location.reload();
                                }
                            }
                        });
                    }
                }
                else if (data.ResponseStatus == -108) {
                    window.top.location.reload();
                }
                else {
                    HideProgress();
                    thongbao(idThongBao, data.Description);
                }
            }
        }
    });
}

function LocalStorageHelper(type, key, data) {
    try {
        if (typeof (Storage) === "undefined") return '';
        //set
        if (type === 0) {
            if (data === null || data === undefined || data === '') return;
            if (key === 'vtc_device_secure') {
                localStorage.setItem(key, CryptoJS.AES.encrypt(data, "4cab17a1134e44d298506398cafd5235"));
            }
            else {
                localStorage.setItem(key, data);
            }
        }
            //get
        else {
            if (key === 'vtc_device_secure') {
                var decrypted = CryptoJS.AES.decrypt(localStorage.getItem(key), "4cab17a1134e44d298506398cafd5235");
                return decrypted.toString(CryptoJS.enc.Utf8);
            }
            return localStorage.getItem(key);
        }
    } catch (e) {
        console.log(e.message);
        return '';
    }
}
///// end đăng ký/đăng nhập tài khoản theo luồng mới 2019

function RemovePopLogReg() {
    $("#LogAndReg").html("");
}
function showLogin() {
    $("#popLogin").css("display", "block");
}
function showRegister() {
    $(".tab-container").css("display", "block");
}
function processHead(tInNa, tInPa, tInCa) {
    var pattern = /^[,<.>/?;:'"[{]}\|=`~]$/;
    if (pattern.test("afaiudbf\"jjausduf'fa?adf")) {
        console.log("ky tu dau vao khong hop le");
    }
    else {
        console.log("hop le");
    }

    var http = location.protocol;
    var slashes = http.concat("//");
    var host = slashes.concat(window.location.hostname);
    host = CryptoJS.MD5(host);
    var flag = true;

    var na = $("#" + tInNa).val();
    var pa = $("#" + tInPa).val();
    var ca = $("#" + tInCa).val();
    if (na == "" || pa == "") {
        alert("hay nhap day du cac truong thong tin");
        flag = false;
    }



    $.ajax({
        beforeSend: function () {
            return flag;
        },
        type: "POST",
        url: "http://localhost:1158/home/LoginHead",
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        data: {
            conten: btoa(username.toLowerCase()),
            value: btoa(pass),
            returnURL: "http://localhost:3955/",
        },
        success: function (data) {
            if (data) {
                if (data = "<span>havebody</span>") {
                    window.top.location.reload();
                }
            }
        }
    });
}
function LoginHead(idUname, idPass, idCaptcha, idImageCaptcha, idHidverify, idRemember, idOtp, idOtpType, idFrameCaptcha, idThongBao, returnUrl) {
    var flag = true;
    var username = $("#" + idUname).val();
    var pass = $("#" + idPass).val();
    var captcha = $("#" + idCaptcha).val();
    var hidverify = $("#" + idHidverify).val();
    var isRemember = false;

    if ($("#" + idRemember).is(':checked')) {
        isRemember = true;
    }
    var otp = $("#" + idOtp).val();
    var otpType = $("#" + idOtpType).find('option:selected').val();

    var key = $("#key").val();
    if (username == '' || pass == '') {
        thongbao(idThongBao, "Nhập đầy đủ các trường thông tin");
        return;
    }
    if (!CommonValid.ValidateUserName(username)) {
        thongbao(idThongBao, "Tên không hợp lệ");
        flag = false;
        flag = false;
        return;
    }
    //if (!CommonValid.ValidateLetterPassword(pass)) {
    //    thongbao(idThongBao, "Mật khẩu không hợp lệ");
    //    flag = false;
    //    return;
    //}
    //var action = 'Login';
    //if ($("#" + idOtp).is(":visible")) {
    //    action = 'oauthOTP';
    //}
    ShowProgress();
    $.ajax({
        beforeSend: function () {
            return flag;
        },
        type: "POST",
        url: ConfigHeader.HEADER_URL + "Handler/Process.ashx?act=Login",
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        data: {
            conten: btoa(username.toLowerCase()),
            value: btoa(pass),
            capt: captcha,
            hidverify: hidverify,
            isRemember: isRemember,
            key: key,
            otp: otp,
            otpType: otpType,
            returnURL: "http://localhost:3955/",
        },
        success: function (data) {
            if (data) {
                HideProgress();
                if (data.ResponseStatus > 0) {
                    var redirecUrl = returnUrl;
                    if (redirecUrl == null || redirecUrl == '') {
                        redirecUrl = getUrlParameterByName('returnUrl');
                    }
                    try {
                        if (typeof HeaderRedirecUrl != undefined && (redirecUrl == null || redirecUrl == '')) {
                            redirecUrl = HeaderRedirecUrl.RedirecUrl;
                        }
                    }
                    catch (e) {
                        console.log("reload this page");
                    }
                    if (redirecUrl != null && redirecUrl.length > 0) {
                        window.top.location = redirecUrl;
                    } else {
                        window.top.location.reload();
                    }
                }
                else {
                    if (data.errorCode == -1000) {
                        $("#" + idOtpType).css("display", "block");
                        $("#" + idOtp).css("display", "block");
                        $("#topalert").css("display", "block");
                        $(".cg_social_popup_dangky").css("display", "none");
                        $(".cg_text_mau_dangky").css("display", "none");
                        $(".ghinho-thongin").css("display", "none");
                        $(".cg_tabs_menu_login").css("display", "none");
                        $("#txtUserName").css("display", "none");
                        $("#txtPass").css("display", "none");
                        $(".showpass-inclickdn").css("display", "none");
                        $(".tt-blue").css("display", "block");
                        $(".cg_all_maxacnhan").css("display", "none");
                        //$("#" + idImageCaptcha).attr("src", "data:image/jpeg;base64," + data.imageData);
                        //$("#" + idHidverify).val(data.Verify);
                        thongbao(idThongBao, '');
                    }
                    else if (data.ResponseStatus == -1005) {
                        $("#" + idFrameCaptcha).css("display", "block");
                        RefreshCaptcha('#ImgcaptchaDN', '#hidVerifyCapchaDN');
                        //$("#" + idImageCaptcha).attr("src", "data:image/jpeg;base64," + data.imageData);
                        //$("#" + idHidverify).val(data.Verify);
                        thongbao(idThongBao, data.errorMessage);
                    }
                    else if (data.ResponseStatus == -1007) {
                        window.top.location.reload();
                    }
                    else {
                        //$("#" + idImageCaptcha).attr("src", "data:image/jpeg;base64," + data.imageData);
                        //$("#" + idHidverify).val(data.Verify);
                        RefreshCaptcha('#ImgcaptchaDN', '#hidVerifyCapchaDN')
                        thongbao(idThongBao, data.errorMessage);
                    }
                }
            }
        }
    });
}

function LoginSiteExtend(idUname, idPass, idRemember, idOtp, idOtpType, idThongBao, returnUrl) {
    var flag = true;
    var username = $("#" + idUname).val();
    var pass = $("#" + idPass).val();


    var isRemember = false;

    if ($("#" + idRemember).is(':checked')) {
        isRemember = true;
    }
    var otp = $("#" + idOtp).val();
    var otpType = $("#" + idOtpType).find('option:selected').val();

    var key = $("#key").val();

    if (username == '' || pass == '') {
        thongbao(idThongBao, "Nhập đầy đủ các trường thông tin");
        return;
    }

    if (!CommonValid.ValidateUserName(username)) {
        thongbao(idThongBao, "Tên không hợp lệ");
        return;
    }
    //if (!CommonValid.ValidateLetterPassword(pass)) {
    //    thongbao(idThongBao, "Mật khẩu không hợp lệ");
    //    return;
    //}
    $.ajax({
        beforeSend: function () {
            return flag;
        },
        type: "POST",
        url: ConfigHeader.HEADER_URL + "Handler/Process.ashx?act=Login",
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        data: {
            conten: btoa(username.toLowerCase()),
            value: btoa(pass),
            capt: "",
            hidverify: "",
            isRemember: isRemember,
            key: key,
            otp: otp,
            otpType: otpType,
            returnURL: "http://localhost:3955/",
        },
        success: function (data) {
            if (data) {
                if (data.ResponseStatus > 0) {
                    var redirecUrl = returnUrl;
                    if (redirecUrl == null || redirecUrl == '') {
                        redirecUrl = getUrlParameterByName('returnUrl');
                    }
                    try {
                        if (typeof HeaderRedirecUrl != undefined && (redirecUrl == null || redirecUrl == '')) {
                            redirecUrl = HeaderRedirecUrl.RedirecUrl;
                        }
                    }
                    catch (e) {
                        console.log("reload this page");
                    }
                    if (redirecUrl != null && redirecUrl.length > 0) {
                        window.top.location = redirecUrl;
                    } else {
                        window.top.location.reload();
                    }
                }
                else {
                    //alert(data.errorCode);
                    if (data.errorCode == -1000) {
                        // alert(idOtpType);
                        $("#" + idOtpType).css("display", "block");
                        $("#" + idOtp).css("display", "block");
                        $("#cg_div_Login").css("height", "326px");

                    }
                    else if (data.ResponseStatus == -1005) {
                        popupHeader("Bạn đã đăng nhập sai quá 5 lần, hãy đăng nhập bằng popup.");
                    }
                    else if (data.ResponseStatus == -1007) {
                        window.top.location.reload();
                    }
                    else {
                        thongbao(idThongBao, data.errorMessage);
                    }
                }

            }
        }
    });
}
function LoginO_Auth(provider, url) {
    var redirecUrl = url;
    try {
        if (typeof HeaderRedirecUrl != undefined && (redirecUrl == null || redirecUrl == '')) {
            redirecUrl = HeaderRedirecUrl.RedirecUrl;
        }
    }
    catch (e) {
        console.log("reload this page");
    }

    if (redirecUrl == null || redirecUrl == '') {
        redirecUrl = window.top.location;
    }


    var serveridck = "";
    var linkgenck = "";
    try {
        if (typeof HeaderChackingObj != undefined) {
            serveridck = HeaderChackingObj.ServiceId;
            linkgenck = HeaderChackingObj.LinkGen;
        }
    }
    catch (e) {

    }

    //returnUrl = encodeURI(returnUrl)
    switch (provider) {
        case "facebook":

            window.location = ConfigHeader.HEADER_URL + 'Handler/Process.ashx?act=facebook&ReturnUrlFromExtend=' + encodeURIComponent(redirecUrl) + '&serveridck=' + serveridck + '&linkgenck=' + linkgenck;
            break;
        case "google":
            //console.log(ConfigHeader.HEADER_URL + 'Handler/Process.ashx?act=google&ReturnUrlFromExtend=' + encodeURIComponent(redirecUrl) + '&serveridck=' + serveridck + '&linkgenck=' + linkgenck);
            window.location = ConfigHeader.HEADER_URL + 'Handler/Process.ashx?act=google&ReturnUrlFromExtend=' + encodeURIComponent(redirecUrl) + '&serveridck=' + serveridck + '&linkgenck=' + linkgenck;
            break;
        case "yahoo":
            window.location = 'https://vtcgame.vn/account.sso.api/api/openid/login?openid_identifier=http://yahoo.com/&ReturnUrl=' + encodeURIComponent(utm.returnUrl + "&provider=yahoo") + '&sid=' + utm.sid + '&utm_source=' + utm.utm_source + '&utm_medium=' + utm.utm_medium + '&utm_campaign=' + utm.utm_campaign + '&serveridck=' + serveridck + '&linkgenck=' + linkgenck;
            break;
		 case "appleid":
		window.location = ConfigHeader.HEADER_URL + 'Handler/Process.ashx?act=appleid&ReturnUrlFromExtend=' + encodeURIComponent(redirecUrl) + '&serveridck=' + serveridck + '&linkgenck=' + linkgenck;
    }

}
function Logout() {
    $.ajax({
        type: "POST",
        url: ConfigHeader.HEADER_URL + "Handler/Process.ashx?act=Logout",
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        data: {

        },
        success: function (data) {
            if (data) {
                if (data.ResponseStatus > 0) {
                    window.top.location.reload();
                }
            }
        }
    });
}
function HeaderLogout() {
    $.ajax({
        type: "POST",
        url: ConfigHeader.HEADER_URL + "Handler/Process.ashx?act=Logout",
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        data: {

        },
        success: function (data) {
            if (data) {
                if (data.ResponseStatus > 0) {
                    window.top.location.reload();
                }
            }
        }
    });
}
function popupHeader(content) {
    alert(content);
    calPopLogin();
}
function thongbao(idThongBao, content) {
    $("#" + idThongBao).html(content);
}

function hasWhiteSpace(s) {
    return s.indexOf(' ') >= 0;
}

function RegisterHead(idUname, idPass, idPass2, token, idHidverify, idImgCaptcha, checkbox_dongy, idThongBao, returnUrl) {
    var flag = true;
    var username = $("#" + idUname).val();
    var pass = $("#" + idPass).val();
    var pass2 = $("#" + idPass2).val();
    var captcha = token; //$("#" + idCaptchaInput).val();
    var HidverifyVal = $("#" + idHidverify).val();
    var dongy = true;
    //if ($("#" + checkbox_dongy).is(':checked')) {
    //    dongy = true;
    //}
    //else {
    //    thongbao(idThongBao, "Bạn chưa đồng ý với thỏa thuận sử dụng.");
    //    flag = false;
    //    return;
    //}
    //if (username == '' || pass == '' || captcha == '') {
    //    thongbao(idThongBao, "Hãy nhập đầy đủ các trường thông tin");
    //    flag = false;
    //    return;
    //}
    if (!CommonValid.ValidateUserName(username)) {
        thongbao(idThongBao, "Tên không hợp lệ");
        flag = false;
    }
    if (hasWhiteSpace(pass) == true) {
        thongbao(idThongBao, "Mật khẩu không được chứa khoảng trắng");
        flag = false;
        return;
    }
    if (!CommonValid.ValidateLetterPassword(pass)) {
        thongbao(idThongBao, "Mật khẩu không hợp lệ");
        flag = false;
        return;
    }
    if (pass2 == '') {
        thongbao(idThongBao, "Hãy nhập lại mật khẩu");
        flag = false;
        return;
    }

    if (pass != pass2) {
        thongbao(idThongBao, "Nhập lại mật khẩu không trùng khớp");
        flag = false;
        return;
    }
    if (!ValidatePassRegister(idPass, idThongBao)) //mật khẩu phải có độ dài từ 4-18 ký tự
    {
        flag = false;
        return;
    }

    var key = $("#key").val();
    var serveridck = "";
    var linkgenck = "";
    try {
        if (typeof HeaderChackingObj != undefined) {
            serveridck = HeaderChackingObj.ServiceId;
            linkgenck = HeaderChackingObj.LinkGen;
        }
    }
    catch (e) {

    }
    ShowProgress();
    $.ajax({
        beforeSend: function () {
            return flag;
        },
        type: "POST",
        url: ConfigHeader.HEADER_URL + "/Handler/Process.ashx?act=Register",
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        data: {
            conten: btoa(username.toLowerCase()),
            value: btoa(pass),
            value2: btoa(pass2),
            capt: captcha,
            hidverify: HidverifyVal,
            dongy: dongy,
            key: key,
            ServiceId: serveridck,
            LinkGen: linkgenck,
        },
        success: function (data) {
            if (data) {

                if (data.ResponseStatus > 0) {
                    var redirecUrl = returnUrl;
                    if (redirecUrl == null || redirecUrl == '') {
                        redirecUrl = getUrlParameterByName('returnUrl');
                    }
                    try {
                        if (typeof HeaderRedirecUrl != undefined && (redirecUrl == null || redirecUrl == '')) {
                            redirecUrl = HeaderRedirecUrl.RedirecUrl;
                        }
                    }
                    catch (e) {
                        console.log("reload this page");
                    }
                    //tracking
                    if (data.linkgenck == '' || data.linkgenck == undefined) {
                        HideProgress();
                        if (redirecUrl != null && redirecUrl.length > 0) {
                            window.top.location = redirecUrl;
                        } else {
                            window.top.location.reload();
                        }
                    }
                    else {
                        $.ajax({
                            type: "POST",
                            url: LinkTracking,
                            timeout: 1000,
                            data: {
                                ServiceId: data.serviceIdck,
                                OSId: 1,
                                //CPId: 1,
                                LinkGen: data.linkgenck,
                                AccountId: data.AcountId,
                                AccountName: data.AccountName,
                                AccountTypeId: 101
                            },
                            crossDomain: true,
                            xhrFields: {
                                withCredentials: true
                            },
                            error: function () {
                                HideProgress();
                                if (redirecUrl != null && redirecUrl.length > 0) {
                                    window.top.location = redirecUrl;
                                } else {
                                    window.top.location.reload();
                                }
                            },
                            success: function (data) {
                                $.ajax({
                                    type: "POST",
                                    url: ConfigHeader.HEADER_URL + "Home/PopupRegisterSuss",
                                    success: function (data) {
                                        $("#LogAndReg").html(data);
                                    }
                                });
                                HideProgress();
                                if (redirecUrl != null && redirecUrl.length > 0) {
                                    window.top.location = redirecUrl;
                                } else {
                                    window.top.location.reload();
                                }
                            }
                        });
                    }
                }
                else if (data.ResponseStatus == -108) {
                    window.top.location.reload();
                }
                else {
                    HideProgress();
                    //$("#" + idHidverify).val(data.Verify);
                    //$("#" + idImgCaptcha).attr("src", "data:image/jpeg;base64," + data.imageData);
                    RefreshCaptcha('#ImgcaptchaDK', '#hidVerifyCapchaDK');

                    thongbao(idThongBao, data.errorMessage);
                }
            }
        }
    });
}
function ValidatePassRegister(idPass, idThongBao) {
    var pass = $('#' + idPass).val();
    if (pass.length < 4 || pass.length > 18) { $('#' + idThongBao).html("Mật khẩu có độ dài 4-18 ký tự."); return false; }
    //var fliter = /^(?=.*\d)(?=.*[a-zA-Z]).{4,18}$/;
    //if (!fliter.test(pass)) { $('#' + idThongBao).html("Mật khẩu có độ dài 4-18 ký tự."); return false; }
    //for (var index = 0; index < pass.length; index++) {
    //    if (!CommonValid.ValidateLetterPassword(pass.charAt(index))) {
    //        $('#' + idThongBao).html("Mật khẩu có độ dài 4-18 ký tự."); return false;
    //    }
    //}
    //if (!CommonValid.ValidateLetterPassword(pass)) { $('#' + idThongBao).html("Mật khẩu có độ dài 4-18 ký tự."); return false; }
    //else {
    //    $('#' + idThongBao).html("");
    //    return true;
    //}
    $('#' + idThongBao).html("");
    return true;
}
function CheckAccount(idUname, idThongBao, accountName) {
    //if (accountName.length < 4 || accountName.length > 16 || accountName == 'nguoi_dep_bikini2') {
    //    $('#' + idUname).focus();
    //    thongbao(idThongBao, "Tên tài khoản từ 4 - 16 ký tự và bắt đầu bằng chữ cái");
    //    $('#hdvalidate').val("0");
    //    return false;
    //}
    //if (accountName.endsWith('.') || accountName.endsWith('_')) {
    //    $('#' + idUname).focus();
    //    thongbao(idThongBao, "Tên đăng nhập không được kết thúc bằng dấu '.' hoặc '_'");
    //    $('#hdvalidate').val("0");
    //    return false;
    //}
    for (var index = 0; index < accountName.length; index++) {
        if (!CommonValid.ValidateUserName(accountName.charAt(index))) {
            $('#' + idUname).focus();
            thongbao(idThongBao, "Tên tài khoản không được chứa ký tự đặc biệt");
            $('#hdvalidate').val("0");
            return false;
        }
    }
    $.ajax({
        url: ConfigHeader.HEADER_URL + 'Home/CheckAcount',
        type: 'POST',
        data: {
            acountName: accountName,
        },
        success: function (data) {
            if (data) {
                if (data.ResponseStatus > 0) {
                    thongbao(idThongBao, "Tài khoản đã tồn tại.");
                    $('#hdvalidate').val("0");
                    return false;
                }
                else if (data.ResponseStatus == -50) {
                    thongbao(idThongBao, "");
                    $('#hdvalidate').val("1");
                    return true;
                }

            }
        },
        error: function () {
            $(idThongBao).html("Hệ thống đang bận, xin thử lại sau.");
            $('#hdvalidate').val("0");
        }
    });

};
function encrypt(txt) {
    var key = CryptoJS.enc.Utf8.parse('8080808080808080');
    var iv = CryptoJS.enc.Utf8.parse('8080808080808080');

    var encryptedlogin = CryptoJS.AES.encrypt(CryptoJS.enc.Utf8.parse(txt), key,
    {
        keySize: 128 / 8,
        iv: iv,
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7
    });
    return encryptedlogin;
}
function LoginOTP(name, passw) {

    $("#lbThongBao").html("------------------");
    var flag = true;
    if (name == null || name == undefined || passw == null || passw == undefined) {
        var username = $("#txtUserName").val();
        var pass = $("#txtPass").val();
        var otp = $("#otp").val();
        if ($("#txtUserName").val() == '' || $("#txtPass").val() == '' || otp == '') {
            //$("#lbThongBao").html("(*) Nhập đầy đủ các trường thông tin");
            console.log("hay nhap day du cac truong thong tin");
            flag = false;
            return;
        }
    }
    else {
        var username = name;
        var pass = passw;
        flag = true;
    }

    $.ajax({
        beforeSend: function () {
            return flag;
        },
        type: 'POST',
        dataType: 'json',
        crossDomain: true,
        url: "http://localhost:1158/Handler/Authen.ashx",
        data: {
            act: "LoginOTP",
            username: username,
            pass: pass,
            OTP: otp,

        },
        success: function (data) {
            if (data) {//data.AccId > 0) {
                if (data.status == 11) {

                    alert("login success, please redirect to header index setForm authen");
                }
                else {
                    window.top.location.href = "http://localhost:1158/home/index"
                }


            } else {
                console.log("login fail")

            }
        }


    });
}

function showPopupOtp() {
    $(".tab-container2").css("display", "block");
}

function pThongBao(content) {
    alert(content);
    window.top.location.reload();
}
function RefreshCaptcha(idImages, idVerify) {

    var capt = ConfigHeader.HEADER_URL + "/CaptchaImage.ashx?ss=" + Math.random() + "&w=60&h=40";
    $(idImages).attr("src", capt);
    //$.ajax({
    //    type: "GET",
    //    url: ConfigHeader.HEADER_URL + 'Handler/Process.ashx?act=getCaptcha',
    //    data: {
    //    },
    //    crossDomain: true,
    //    xhrFields: {
    //        withCredentials: true
    //    },
    //    success: function (data) {

    //        if (data) {
    //            $(idImages).attr("src", "data:image/jpeg;base64," + data.imageData);
    //            $(idVerify).val(data.verify);
    //        }
    //    }
    //});
}
function DKVip() {

    $.ajax({
        type: "GET",
        url: ConfigHeader.HEADER_URL + 'Handler/Process.ashx?act=VipRegister',
        data: {
        },
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        success: function (data) {

            if (data) {
                if (data.ResponseStatus > 0) {
                    alert("Kích Hoạt Vip Thành Công!");
                }
                else {
                    alert("kích hoạt vip thất bại.");
                }
            }
        }
    });
}
function ShowProgress() {
    setTimeout(function () {
        $(".fullscreenFF").css({ height: $(document).height() });
        $(".TB_overlayBG").show();
        $('.cg_tab_danhnhap_dangky').css({ opacity: 0.4 });
    }, 100);
}
function HideProgress() {
    setTimeout(function () {
        $(".fullscreenFF").css({ height: 0 });
        $(".TB_overlayBG").hide();
        $('.cg_tab_danhnhap_dangky').css({ opacity: 1 });
    }, 100)

}
function TabListGame(event, objThis) {
    event.preventDefault();
    $(objThis).parent().addClass("cg_current");
    $(objThis).parent().siblings().removeClass("cg_current");
    var tab = $(objThis).attr("href");
    $(".header_game_tab_content").not(tab).css("display", "none");
    $(tab).fadeIn();
}

function comeEnterUsername() {
    $.ajax({
        type: "GET",
        url: ConfigHeader.HEADER_URL + 'Home/RePasswordUserName',
        data: {
        },
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        success: function (data) {
            if (data) {
                $("#LogAndReg").html(data);
            }
        }
    });
}
function comeChooseFunction(accountName) {
    $.ajax({
        type: "GET",
        url: ConfigHeader.HEADER_URL + 'Home/RePasswordFunction',
        data: {
            accountName: accountName
        },
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        success: function (data) {
            if (data) {
                $("#LogAndReg").html(data);
            }
        }
    });
}

function comeRePassEmail(accountName) {
    $.ajax({
        type: "GET",
        url: ConfigHeader.HEADER_URL + 'Home/RePasswordEmail',
        data: {
            accountName: accountName,
        },
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        success: function (data) {
            if (data) {
                $("#LogAndReg").html(data);
            }
        },
        error: function () {
            thongbao("thongbaoDK","Hệ thống đang bận. Vui lòng thử lại sau.")
            setTimeout(function () { window.location.reload(); }, 3000);
        }
    });
}


function comeNumberSmsSuccess() {
    $.ajax({
        type: "GET",
        url: ConfigHeader.HEADER_URL + 'Home/RePasswordSuccess',
        data: {
        },
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        success: function (data) {
            if (data) {
                $("#LogAndReg").html(data);
            }
        }
    });
}

function comeEmailSuccess(email) {
    $.ajax({
        type: "GET",
        url: ConfigHeader.HEADER_URL + 'Home/RePasswordEmailSuccess',
        data: {
            email:email
        },
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        success: function (data) {
            if (data) {
                $("#LogAndReg").html(data);
            }
        }
    });
}

function ForgetPassSMSPLus(accountName, acccountId) {
    $.ajax({
        type: 'POST',
        url: ConfigHeader.HEADER_URL + 'Home/ForgetPassSMSPLus',
        data: {
            accountName: accountName
        },
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        success: function (data) {
            if (data.ResponseStatus == -5 || data.ResponseStatus >= 0) {
                var sign = data.SignExtend;
                var resCode = data.ResponseStatus;
                comeSmsOtp(accountName, acccountId,sign, resCode);
            } else {
                $('#lberror').html(data.ErorrMess);
            }
        }
    });
}

function comeSmsOtp(accountName, accountId ,sign, resCode) {
    $.ajax({
        type: "GET",
        url: ConfigHeader.HEADER_URL + 'Home/RePasswordSmsOtp',
        data: {
            accountName: accountName,
            accountId: accountId,
            sign: sign,
            resCode : resCode
        },
        success: function (data) {
            if (data) {
                $("#LogAndReg").html(data);
            }
        }
    });
}


function rePasscheckIvalidAccount(idThongBao, idAccount, token, returnUrl){
    ShowProgress();
    var accountName = $('#' + idAccount).val();
    $.ajax({
        url: ConfigHeader.HEADER_URL + 'Home/CheckAcountByMobile',
        type: 'POST',
        data: {
            acountName: accountName,
            reCaptchaToken: token
        },
        success: function (data) {
            if (data) {
                if (data.ResponseStatus > 0) {
                    //tài khoản đã tồn tại, chuyển sang nhập mật khẩu để đăng nhập
                    var urlPopupLogin = ConfigHeader.HEADER_URL + "Home/";

                    if (returnUrl != null && returnUrl.length > 0) {
                        urlPopupLogin = urlPopupLogin + '?returnUrl=' + returnUrl;
                    }
                    $.ajax({
                        url: ConfigHeader.HEADER_URL + 'Home/RePasswordFunction',
                        type: 'GET',
                        crossDomain: true,
                        xhrFields: {
                            withCredentials: true
                        },
                        data: {
                            accountName: accountName,
                        },
                        success: function (data) {
                            if (data) {
                                $("#LogAndReg").html(data);
                            }
                        }
                    })
                    return false;
                }
                else if (data.ResponseStatus == -50) {
                    thongbao(idThongBao, "Tài khoản không tồn tại");
                    return true;
                }
                else {
                    HideProgress();
                    thongbao(idThongBao, data.ErorrMess);
                }
            }
        },
        error: function () {
            HideProgress();
            $(idThongBao).html("Hệ thống bận. Vui lòng thử lại sau.");
            //$('#hdvalidate').val("0");
        }
    });
}

function rePassCheckOtp(username, id_otp, returnUrl, idThongBao) {
    var otp_val = $("#" + id_otp).val();
    var sign = $('#sign').val();
    if (otp_val) {
        if (otp_val.length < 6) {
            thongbao(idThongBao, "Mã kích hoạt không hợp lệ");
            return false;
        }
    }
    ShowProgress();
   
    $.ajax({
        type: "POST",
        url: ConfigHeader.HEADER_URL + "Home/RePasswordSendOtp",
        data: {
            accountName: username,
            otp: otp_val,
            sign : sign
        },
        success: function (data) {
            if (data) {
                HideProgress();
                var sign = data.SignExtend;

                if (data.ResponseStatus >= 0) {
                    //check OTP hợp lệ, chuyển sang popup nhập mật khẩu
                    var urlPopupLogin = ConfigHeader.HEADER_URL + "Home/RePasswordCreatePass";
                    if (returnUrl != null && returnUrl.length > 0) {
                        urlPopupLogin = urlPopupLogin + '?returnUrl=' + returnUrl;
                    }
                    $.ajax({
                        type: "POST",
                        url: urlPopupLogin,
                        crossDomain: true,
                        xhrFields: {
                            withCredentials: true
                        },
                        data: {
                            accountName: username,
                            sign: sign
                        },
                        success: function (data) {
                            if (data) {
                                $("#LogAndReg").html(data);
                            }
                        }
                    });
                }
                else {
                    thongbao(idThongBao, data.ErorrMess);
                }
            }
        }
    });
}

function handleRePassEmail(accountName, idEmail) {
    var inputEmail = $("#" + idEmail).val();

    $.ajax({
        url: ConfigHeader.HEADER_URL + 'Home/RePasswordSendEmail',
        type: 'POST',
        data: {
            accountName: accountName,
            email: inputEmail
        },
        success: function (result) {
            console.log(result);
            if (result) {
                if (result.ResponseStatus > 0) {
                    $.ajax({
                        type: "GET",
                        url: ConfigHeader.HEADER_URL + 'Home/RePasswordEmailSuccess',
                        data: {
                            email: inputEmail
                        },
                        crossDomain: true,
                        xhrFields: {
                            withCredentials: true
                        },
                        success: function (data) {
                            if (data) {
                                $("#LogAndReg").html(data);
                            }
                        }
                    });
                }
                else {
                    if (result.ResponseStatus == -77) {
                        thongbao("thongbaoDK", "Hệ thống đang bận. Vui lòng thử lại sau.");
                        return;
                    }
                    thongbao("thongbaoDK", result.ErorrMess );
                }
            }
        },
        error: function () {
            HideProgress();
            thongbao("thongbaoDK", "Hệ thống đang bận. Vui lòng thử lại sau.");
        }
    });

}

function rePassCreate(username,idPass, idRePass, idThongBao, returnUrl) {
    var flag = true;
    var pass = $("#" + idPass).val();
    var rePass = $("#" + idRePass).val();
    var sign = $('#sign').val();
    if (pass == '') {
        thongbao(idThongBao, "Hãy nhập mật khẩu");
        flag = false;
        return;
    }
    if (hasWhiteSpace(pass) == true) {
        thongbao(idThongBao, "Mật khẩu không được chứa khoảng trắng");
        flag = false;
        return;
    }
    if (!CommonValid.ValidateLetterPassword(pass)) {
        thongbao(idThongBao, "Mật khẩu không hợp lệ");
        flag = false;
        return;
    }
    if (!ValidatePassRegister(idPass, idThongBao)) //mật khẩu phải có độ dài từ 4-18 ký tự
    {
        flag = false;
        return;
    }
    if (rePass == '') {
        thongbao(idThongBao, "Hãy nhập lại mật khẩu");
        flag = false;
        return;
    }

    if (pass != rePass) {
        thongbao(idThongBao, "Nhập lại mật khẩu không trùng khớp");
        flag = false;
        return;
    }

    ShowProgress();
    var client = new ClientJS();
    var detectDevice = LocalStorageHelper(1, 'vtc_device_secure');
    if (detectDevice === null || detectDevice === undefined || detectDevice === '') {
        try {
            detectDevice = '';
            detectDevice += 'fingerprint:' + client.getFingerprint();
            var deviceBrowser = client.getBrowser();
            deviceBrowser = (deviceBrowser === 'Chrome' && client.getUserAgentLowerCase().indexOf("coc_coc") >= 0) ? 'Coccoc' : deviceBrowser;
            detectDevice += ';devicebrowser:' + deviceBrowser;
            detectDevice += ';OS:' + (client.getOS() + '-' + client.getOSVersion());
            var device = client.getDevice();
            device = device === undefined ? 'PC' : device;
            detectDevice += ';device:' + device;
            var deviceType = client.getDeviceType();
            deviceType = deviceType === undefined ? 'PC' : deviceType;
            detectDevice += ';devicetype:' + deviceType;
            detectDevice += ';resolution:' + client.getCurrentResolution();
        }
        catch (err) {
            utils.unLoading();
            return;
        }
    }
    $.ajax({
        beforeSend: function () {
            return flag;
        },
        type: "POST",
        url: ConfigHeader.HEADER_URL + "Home/RePasswordCreate",
        data: {
            accountName: username,
            passNew: pass,
            sign: sign
        },
        success: function (data) {
            if (data) {
                if (data.ResponseStatus >= 0) {
                    $.ajax({
                        type: "GET",
                        url: ConfigHeader.HEADER_URL + 'Home/RePasswordSuccess',
                        data: {
                        },
                        crossDomain: true,
                        xhrFields: {
                            withCredentials: true
                        },
                        success: function (data) {
                            if (data) {
                                $("#LogAndReg").html(data);
                            }
                        }
                    });
                }
                else {
                    HideProgress();
                    thongbao(idThongBao, data.ErorrMess);
                }
            }
        }
    });
}