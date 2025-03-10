@extends('frontend.layout.main')

@section('content')
<div class="container">
    <h2>Lịch sử giao dịch</h2>
    <div id="transaction-history"></div>
</div>

<script>
$(document).ready(function() {
    $('#TransactionHistoryLi').on('click', function() {
        let fromDate = moment().subtract(30, 'days').format('YYYY-MM-DD');
        $.ajax({
            url: '{{ route("frontend.transactionHistory") }}',
            method: 'GET',
            data: {
                fromDate: fromDate,
                page: 1,
                pageSize: 20,
                sort: 'DESC'
            },
            success: function(response) {
                let html = '<ul class="list-group">';
                response.data.forEach(function(transaction) {
                    html += '<li class="list-group-item">' +
                        'Số tiền: ' + transaction.amount + ' VND<br>' +
                        'Mô tả: ' + transaction.description + '<br>' +
                        'Thời gian: ' + moment(transaction.when).format('DD/MM/YYYY HH:mm:ss') +
                        '</li>';
                });
                html += '</ul>';
                $('#transaction-history').html(html);
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    });
});
</script>
@endsection