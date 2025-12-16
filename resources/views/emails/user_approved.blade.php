<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
<h2>Hello {{ $user->first_name ?? 'there' }},</h2>

<p>Your profile has been <strong>approved</strong>.</p>

<p>
    You can now log in and use our app!
</p>

<p>Thank you for registering!</p>

<p><strong>ADN Team</strong></p>
</body>
</html>
