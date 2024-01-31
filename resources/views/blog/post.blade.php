<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <title>详细信息</title>
</head>
<body>
    <body>
        <div class="container">
            <h1>{{ $post->title }}</h1>
            <h5>{{ $post->published_at }}</h5>
            <hr>
            {{-- nl2br 函数是 PHP 中的一个函数，用于将换行符 \n 转换成 HTML 的 <br> 标签。它会在每个换行符前插入 <br>。 --}}
            {!! nl2br(e($post->content)) !!}
            <hr>
            <button class="btn btn-primary" onclick="history.go(-1)">
                « Back
            </button>
        </div>
        </body>
</body>
</html>