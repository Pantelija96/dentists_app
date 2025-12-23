<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
<h2>User just created new work order!</h2>

<p>User: {{ $work_order->user->first_name }} {{ $work_order->user->last_name }}, email: {{ $work_order->user->email }} just created new work order!</p>

<a href="{{ route('work.inspect', $work_order->id) }}"> Check new work order! </a>

<p><strong>ADN Team</strong></p>
</body>
</html>
