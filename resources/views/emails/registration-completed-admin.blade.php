<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
<h2>New user registered in your region!</h2>

<p>User: {{ $user->first_name }} {{ $user->last_name }}, email: {{ $user->email }} just registered!</p>

<p>
    Please review the account
</p>

<a href="{{ route('login') }}"> Visit </a>

<p><strong>ADN Team</strong></p>
</body>
</html>
