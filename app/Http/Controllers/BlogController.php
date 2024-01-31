<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {
        //让published_at小于当前时间并倒叙排列
        $posts = Post::where('published_at', '<=', Carbon::now())
        ->orderBy('published_at','desc')
        //paginate(): 这是 Laravel Eloquent 查询构建器的一个方法，用于将查询结果分页。
        ->paginate(config('blog.posts_per_page'));

        return view('blog.index', ['posts' => $posts]);
    }
    public function showPost($slug)
    {
        $post = Post::where('slug',$slug)->firstOrFail();
        return view('blog.post',['post' => $post]);
    }
}
