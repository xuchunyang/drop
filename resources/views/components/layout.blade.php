<!DOCTYPE html>
@props([
    'title' => null,
])
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Drop - 静态网站托管，支持 HTTPS、自定义域名' }}</title>
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

        code {
            display: inline-block;
            padding: 2px 10px;
            background-color: #d2d2d2;
            color: #000;
            border-radius: 2px;
        }
    </style>
</head>
<body>

<p>
    <a href="/">首页</a>
</p>

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
