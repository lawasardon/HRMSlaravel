<!DOCTYPE html>
<html>

<head>
    <title>Leave Rejection Notification</title>
</head>

<body>
    <h1>Hello, {{ $name }}</h1>
    <p>We regret to inform you that your leave request has been rejected.</p>
    @if ($reasonOfRejection)
        <p>Reason for rejection: {{ $reasonOfRejection }}</p>
    @endif
    <p>If you have any questions, please feel free to contact us.</p>
</body>

</html>
