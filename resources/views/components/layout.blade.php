<!DOCTYPE html>
@props([
    'title' => null,
])
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? "$title | Drop" : 'Drop' }}</title>
</head>
<body>

<nav>
    <a href="/">Home</a>
</nav>

{{ $slot }}

</body>
</html>
