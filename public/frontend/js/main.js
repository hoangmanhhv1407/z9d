function generateQRCode() {
    // Assuming there's an element with id 'userId' holding the user ID in a data attribute
    const userIdElement = document.getElementById('userId');
    const userId = userIdElement ? userIdElement.dataset.userId : 'defaultUserId';

    const bankId = 'MB';
    const accountNo = '90511239999';
    const template = 'compact';
    // Removed duplicate declaration of 'description'
    const description = `NAP9D${userId}`;
    // Tạo URL cho mã QR
    const qrUrl = `https://img.vietqr.io/image/${bankId}-${accountNo}-${template}.png?addInfo=NAP9D${userId}`;

    // Cập nhật thuộc tính src của thẻ img
    document.getElementById('qr_image').src = qrUrl;
}

document.addEventListener('DOMContentLoaded', (event) => {
    const tabs = document.querySelectorAll('.nav-link');
    const content = document.querySelectorAll('.app-right__ul12');

    tabs.forEach(tab => {
        tab.addEventListener('click', (e) => {
            e.preventDefault();

            // Remove active class from all tabs
            tabs.forEach(t => t.parentElement.classList.remove('active'));

            // Hide all content
            content.forEach(c => c.classList.remove('active'));

            // Add active class to the clicked tab
            tab.parentElement.classList.add('active');

            // Show corresponding content
            const target = document.querySelector(tab.getAttribute('href'));
            if (target) {
                target.classList.add('active');
            }
        });
    });
});

function startCountdown(duration, display) {
    let timer = duration, minutes, seconds;
    const interval = setInterval(function () {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = minutes + ":" + seconds;

        if (--timer < 0) {
            clearInterval(interval);
            document.getElementById('app_payment_qr').style.display = 'none';
        }
    }, 1000);

    return interval;
}

document.getElementById('Donates').onclick = function () {
    document.getElementById('app_payment_qr').style.display = 'block';
    const countdown = document.getElementById('countdown');
    // Start the countdown for 10 minutes
    const timerInterval = startCountdown(600, countdown);

    // Attach the interval ID to the close button, so it can be cleared when the popup is closed
    document.getElementById('closePopup').onclick = function () {
        document.getElementById('app_payment_qr').style.display = 'none';
        clearInterval(timerInterval);
    };
};

