@php
    use Illuminate\Support\Str;
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config('blog.title')}}</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>{{config('blog.title')}}</h1>
        <h5>Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}</h5>
        <hr>
        <ul>
                
            @foreach ($posts as $post)   
            <li>
                <a href="{{ route('blog.detail', ['slug' => $post->slug]) }}">{{ $post->title }}</a>
                <em>{{$post->published_at}}</em>
                <p>
                    {{ Str::limit($post->content) }}
                </p>
            </li>
            @endforeach
        </ul>
        <hr>
        {!! $posts->render() !!}
    </div>
</body>
</html>