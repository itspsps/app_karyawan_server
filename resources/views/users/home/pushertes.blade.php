<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pusher Test</title>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('0669fcbbbe74c392eded', {
            cluster: 'ap1'
        });

        var channel = pusher.subscribe('HRD-APPS');
        channel.bind('IzinPost', function(data) {
            console.log(data);
            console.log('{{$cek_user_id}}');
            if (data.user_id == '{{$cek_user_id}}') {

                alert(`Hi ${data.comment}`) //here you can add you own logic
            }
        });
    </script>
</head>

<body>
    <h1>Pusher Test</h1>
    <p>
        Try publishing an event to channel <code>my-channel</code>
        with event name <code>my-event</code>.
    </p>
</body>

</html>