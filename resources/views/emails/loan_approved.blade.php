<!DOCTYPE html>
<html>

<head>
    <title>Loan Approval Notification</title>
</head>

<body>
    <h1>Hello, {{ $name }}</h1>
    <p>We are pleased to inform you that your loan application has been approved.</p>
    <p>Loan Amount: {{ $loanAmount }}</p>

    @if ($reasonOfRejection)
        <p>Reason for Rejection: {{ $reasonOfRejection }}</p>
    @endif

    <p>If you have any questions, feel free to contact us.</p>
</body>

</html>
