// Trang JavaScript mới (ví dụ: index.js)

// Xử lý URL và active tab
var link = window.location.href.split('/');
var l = link[link.length - 1];
if (l == "dich-vu") {
    $(".app-dichvu__right-content").removeClass("active");
    $(".nav-link").removeClass("active");
    $("#home1").addClass("active");
    $("#home1").addClass("show");
    $("#dich-vu-lcl").addClass("active");
}

// Định dạng số
$("#mb-info-silk").text(numberWithCommas($("#mb-info-silk").text()));
$("#mb-info-1silk").text(numberWithCommas($("#mb-info-1silk").text()));
$("#mb-info-3silk").text(numberWithCommas($("#mb-info-3silk").text()));

// Mở menu con trên mobile
function openSubMenuMobile(n) {
    $(".header-menunew__hover" + n + " ul").toggleClass("open-sub-menu-mobile");
}

// Định dạng số cho thông tin người dùng
$("#info-silk").text(numberWithCommas($("#info-silk").text()));
$("#info-1silk").text(numberWithCommas($("#info-1silk").text()));
$("#info-3silk").text(numberWithCommas($("#info-3silk").text()));

// Ẩn phần thông tin server nếu URL dài hơn 25 ký tự
var href = window.location.href; 
if (href.length > 25) { 
    $(".app2-left").hide() 
}

// Xóa lớp active từ cả hai tab "Ác Danh" và "Danh Tiếng"
function removeActiveClassFromAcDanhAndDanhTiengTabs() {
    document.getElementById("ac-danh").classList.remove("active");
    document.getElementById("danh-tieng").classList.remove("active");
}

// Xử lý sự kiện khi chọn tab
function onTabRank(tabIndex) {
    if (tabIndex === 1 || tabIndex === 2) {
        removeActiveClassFromAcDanhAndDanhTiengTabs();
    }
    // Thêm các xử lý khác tại đây nếu cần
}

// Hiển thị tab "Ác Danh" hoặc "Danh Tiếng" khi chọn từ dropdown
function showAcDanhRank() {
    var selectBox = document.getElementById("job-sl");
    var selectedValue = selectBox.options[selectBox.selectedIndex].value;
    if (selectedValue === "3") {
        document.getElementById("ac-danh").classList.add("active");
        document.getElementById("danh-tieng").classList.remove("active");
    } else if (selectedValue === "4") {
        document.getElementById("danh-tieng").classList.add("active");
        document.getElementById("ac-danh").classList.remove("active");
    } else {
        removeActiveClassFromAcDanhAndDanhTiengTabs();
    }
}





// Di chuyển lên đầu trang
$(".js-movtop").on("click", function () { 
    window.scrollTo({ top: 0, left: 0, behavior: 'smooth' }); 
});

// Hàm định dạng số (cần được định nghĩa)
function numberWithCommas(x) {
    // Thêm logic định dạng số ở đây
}

// Các hàm khác cần được định nghĩa
function onSearchServerRank() {
    // Logic tìm kiếm server
}

function onPagingRank(param1, param2, param3, param4) {
    // Logic phân trang
}

function refLink(url) {
    // Logic chuyển hướng link
}
