<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class Tag extends Model
{
    //允许批量赋值
    protected $filable = [
        'tag', 'title', 'subtitle', 'page_image', 'meta_description','reverse_direction'
    ];


    //定义文章与标签的多对多关系
    public function posts()
    {
        //'post_tag_piovt' 是中间表的名称，用于存储标签与文章之间的多对多关系。在这个中间表中，存储了标签与文章的关联关系。
        return $this->belongsToMany(Post::class, 'post_tag_piovt');
    }

    public static function addNeededTags(array $tags)
    {
        if(count($tags) === 0){
            return;
        }

        /*get() 执行查询并返回结果集。
        pluck('tag') 用于从结果集中提取所有记录中的 'tag' 列。
        all() 将集合转换为数组。
        static:: 用于引用当前类，即与调用这个方法的类相同的类。*/
        $found = static::whereIn('tag', $tags)->get()->pluck('tag')->all();
        foreach (array_diff($tags, $found) as $tag) {
            static::create([
                'tag' => $tag,
                'title' => $tag,
                'subtitle' => 'Subtitle for '.$tag,
                'page_image' => '',
                'meta_description' => '',
                'reverse_direction' => false,
            ]);
        }
    }
}
