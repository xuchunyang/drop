<!DOCTYPE html>
@props([
    'title' => null,
])
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? "$title | Drop" : 'Drop' }}</title>
    <style>
        body {
            line-height: 1.6;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>

<nav>
    <a href="/">Home</a>
</nav>

@if(session('success'))
    <p class="success">{{ session('success') }}</p>
@endif

@if($errors->any())
    <div class="error">
        <p>出差了！</p>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{ $slot }}

</body>
</html>
