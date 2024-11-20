<!DOCTYPE html>
<html>

<head>
    <title>Leave Approval Notification</title>
</head>

<body>
    <h1>Hello, {{ $name }}</h1>
    <p>We are pleased to inform you that your leave request has been approved.</p>
    <p>Total Leave: {{ $leaveAmount }}</p>

    @if ($reasonOfRejection)
        <p>Reason for Rejection (if applicable): {{ $reasonOfRejection }}</p>
    @endif

    <p>If you have any questions, feel free to contact us.</p>
</body>

</html>
