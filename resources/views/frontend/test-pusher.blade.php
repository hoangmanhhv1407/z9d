<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusher Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Pusher Test</h1>
    <button id="triggerEvent">Trigger Pusher Event</button>
    <div id="notifications"></div>

    <script>
        const pusherAppKey = '{{ env('PUSHER_APP_KEY') }}';
        const pusherAppCluster = '{{ env('PUSHER_APP_CLUSTER') }}';
        const userId = {{ Auth::id() }};

        const pusher = new Pusher(pusherAppKey, {
            cluster: pusherAppCluster
        });

        pusher.connection.bind('connected', function() {
            console.log('Connected to Pusher');
        });

        const channel = pusher.subscribe('user-channel-' + userId);

        channel.bind('pusher:subscription_succeeded', function() {
            console.log('Subscribed to channel user-channel-' + userId);
        });

        channel.bind('deposit-event', function(data) {
            console.log('Received deposit event:', data);
            $('#notifications').append('<p>' + data.message + ' Amount: ' + data.amount + '</p>');
        });

        $('#triggerEvent').click(function() {
            $.ajax({
                url: '{{ route('trigger.pusher.event') }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Event triggered:', response);
                },
                error: function(xhr, status, error) {
                    console.error('Error triggering event:', error);
                }
            });
        });
    </script>
</body>
</html>