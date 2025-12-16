<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
<h2>Hello {{ $user->first_name ?? 'there' }},</h2>

<p>Your registration has been successfully completed.</p>

<p>
    Our administrators will review your account shortly.
    You will receive another email once your account is approved.
</p>

<p>Thank you for registering!</p>

<p><strong>ADN Team</strong></p>
</body>
</html>
