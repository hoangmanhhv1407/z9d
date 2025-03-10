$(document).ready(function() {
    let countdownTimer;
    let remainingTime = 600; // 10 phút = 600 giây
    const userId = $('#userId').data('user-id');

    // Hiệu ứng đếm số
    function animateValue(obj, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            obj.textContent = Math.floor(progress * (end - start) + start).toLocaleString() + ' Xu';
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }

    function updateCurrentCoins(newCoins) {
       // console.log('Updating current coins to:', newCoins);
        const currentCoinsElement = $('#currentCoins')[0];
        const currentCoins = parseInt(currentCoinsElement.textContent.replace(/[^\d]/g, ''));
        animateValue(currentCoinsElement, currentCoins, newCoins, 1000);
    }



    function showNotification(message, amount) {
       
        const notificationHtml = `
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>${message}</strong> Bạn đã nạp thành công ${amount.toLocaleString()} xu.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        const $notification = $(notificationHtml);
        $('#notification-container').append($notification);

        setTimeout(() => {
            $notification.fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
    }

    function updateCountdown() {
        const minutes = Math.floor(remainingTime / 60);
        const seconds = remainingTime % 60;
        $('#countdown').text(`${minutes}:${seconds < 10 ? '0' : ''}${seconds}`);
        
        if (remainingTime <= 0) {
            clearInterval(countdownTimer);
            resetUI();
            alert('Hết thời gian xử lý giao dịch! Vui lòng kiểm tra lại sau vài phút.');
        } else {
            remainingTime--;
        }
    }

    function startCountdown() {
        remainingTime = 600;
        updateCountdown();
        countdownTimer = setInterval(updateCountdown, 1000);
    }

    function stopCountdown() {
        if (countdownTimer) {
            clearInterval(countdownTimer);
            countdownTimer = null;
        }
    }

    function loadQRCode(amount) {
        $.ajax({
            url: '/generate-qr-code',
            method: 'POST',
            data: { amount: amount },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#qr_images').attr('src', response.qrCode);
                    $('#processing-section').show();
                    $('#qr-code-section').show();
                    $('#closePopup').show();
                    startCountdown();
                } else {
                    alert('Không thể tạo mã QR. Vui lòng thử lại sau.');
                    resetUI();
                }
            },
            error: function() {
                alert('Có lỗi xảy ra. Vui lòng thử lại sau.');
                resetUI();
            }
        });
    }

    function resetUI() {
        $('#amount').val('').prop('readonly', false);
        $('#processing-section').hide();
        $('#qr-code-section').hide();
        $('#closePopup').hide();
        $('#confirm-amount').show();
        stopCountdown();
    }

    function fetchTransactionHistory(page) {
        $.ajax({
            url: '/user-info?page=' + page,
            type: 'GET',
            success: function(data) {
                $('#home3').html($(data).find('#home3').html());
                window.history.pushState("", "", '/user-info?page=' + page);
            }
        });
    }

    function initializeAnimation() {
        $('.card-body .list-group-item').each(function() {
            const valueSpan = $(this).find('span:last-child');
            const value = valueSpan.text().trim();
            
            if (/^\d+(\,\d{3})*(\.\d+)?\s*Xu$/.test(value)) {
                const numericValue = parseInt(value.replace(/,/g, '').replace(' Xu', ''));
                if (!isNaN(numericValue)) {
                    animateValue(valueSpan[0], 0, numericValue, 1000);
                }
            }
        });
    }

    function updateVipInfo() {
        $.ajax({
            url: '/get-vip-info',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    $('.current-vip').text(response.currentVip);
                    $('.vip-progress').css('width', response.progress + '%').attr('aria-valuenow', response.progress);
                    $('.vip-progress').text(response.progress.toFixed(2) + '%');
                    $('.xu-needed').text(response.xuNeeded.toLocaleString());
                    $('.total-deposits').text(response.totalDeposits.toLocaleString());
                    $('.xu-for-next-vip').text(response.xuForNextVip.toLocaleString());
                    $('.total-coins').text(response.totalCoins.toLocaleString() + ' Xu');
                    $('.next-vip').text(response.nextVip);
                }
            },
            error: function(xhr, status, error) {
                console.error('Lỗi khi cập nhật thông tin VIP:', error);
            }
        });
    }

    $('#confirm-amount').on('click', function() {
        const amount = $('#amount').val();
        if (amount && amount >= 1000) {
            $('#amount').prop('readonly', true);
            $('#confirm-amount').hide();
            loadQRCode(amount);
        } else {
            alert('Vui lòng nhập số tiền cần nạp (tối thiểu 1,000 VND)');
        }
    });

    $('#closePopup').on('click', resetUI);

    $(document).on('click', '#pagination-container a', function(e) {
        e.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        fetchTransactionHistory(page);
    });

    $('#generalInfoLi').on('shown.bs.tab', initializeAnimation);

    if ($('#generalInfoLi').hasClass('active')) {
        initializeAnimation();
    }

    // Pusher setup

    $.ajax({
        url: '/pusher-config',
        method: 'GET',
        success: function(config) {
            const pusher = new Pusher(config.key, {
                cluster: config.cluster
            });

            const userId = $('#userId').data('user-id');
            const channelName = 'user-channel-' + userId;

            const channel = pusher.subscribe(channelName);

            channel.bind('deposit-event', function(data) {
                showNotification(data.message, data.amount);
                updateCurrentCoins(data.currentCoins);
             
            });
        },
        error: function(xhr, status, error) {
            console.error('Error fetching Pusher config:', error);
        }
    });
});

// Gift handling
document.addEventListener('DOMContentLoaded', function() {
    const selectCharacter = document.getElementById('selectCharacter');
    const giftContainer = document.getElementById('giftContainer');
    const notification = document.getElementById('notification');

    if (selectCharacter) {
        selectCharacter.addEventListener('change', function() {
            giftContainer.style.display = this.value ? 'block' : 'none';
        });
    }

    window.submitGiftForm = function(giftCode) {
        const characterId = selectCharacter.value;
        
        if (!characterId) {
            alert('Vui lòng chọn một nhân vật trước khi nhận quà!');
            return;
        }

        const form = document.getElementById('giftForm');
        const formData = new FormData(form);
        formData.append('gift_code', giftCode);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            notification.style.display = 'block';
            notification.className = 'alert';
            if (data.success) {
                notification.classList.add('alert-success');
                notification.innerHTML = data.message;

                const claimButton = document.querySelector(`button[onclick="submitGiftForm('${giftCode}')"]`);
                if (claimButton) {
                    claimButton.classList.remove('btn-primary');
                    claimButton.classList.add('btn-secondary');
                    claimButton.disabled = true;
                    claimButton.innerHTML = '<i class="fas fa-check-circle me-1"></i>Đã Nhận';
                }
            } else {
                notification.classList.add('alert-danger');
                notification.innerHTML = data.message || 'Có lỗi xảy ra khi nhận quà.';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            notification.style.display = 'block';
            notification.className = 'alert alert-danger';
            notification.innerHTML = 'Đã xảy ra lỗi khi xử lý yêu cầu.';
        });
    };

});