<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
<h2>User {{ $user->first_name ?? 'there' }} {{ $user->last_name }}</h2>

<p>Created new work order.</p>

<a href="{{ route('work.inspect', $workOrder->id) }}">Open</a>

<p><strong>ADN Team</strong></p>
</body>
</html>
