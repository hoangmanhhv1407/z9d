document.addEventListener('DOMContentLoaded', function() {
    const buyButtons = document.querySelectorAll('.buy-now');
    buyButtons.forEach(button => {
        button.addEventListener('click', buyNow);
    });
});

function buyNow(event) {
    const productId = event.target.dataset.productId;
    fetch('/buy-now', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.success);
            updateUserInfo(data.remaining_coin);
            updateRemainingTurns(productId, data.remaining_turns);
        } else if (data.error) {
            alert(data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi mua hàng');
    });
}

function updateUserInfo(remainingCoin) {
    const coinElement = document.getElementById('user-coin');
    if (coinElement) {
        coinElement.textContent = remainingCoin;
    }
}

function updateRemainingTurns(productId, remainingTurns) {
    const turnsElement = document.getElementById(`turns-${productId}`);
    if (turnsElement) {
        turnsElement.textContent = remainingTurns;
    }
}