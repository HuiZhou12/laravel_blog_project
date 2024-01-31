<?php

return [
    'title' => env('BLOG_NAME', 'myBlog'),
    'posts_per_page' => env('BLOG_POSTS_PER_PAGE', 5),
    'uploads' => [
        'storage' => 'public',
        'webpath' => '/storage'
    ]
];

// // 获取博客标题
// $blogTitle = config('blog.title');

// // 获取每页显示文章数
// $postsPerPage = config('blog.posts_per_page');