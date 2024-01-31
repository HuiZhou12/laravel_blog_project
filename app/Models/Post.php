<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Tag;
use App\Services\Markdowner;

class Post extends Model
{
    use HasFactory;
    //在控制器中
    //set前缀的方法是设置器
    //get前缀的方法是访问器
    //由于 $dates 属性的设置，published_at 字段将被自动转换为 Carbon 实例
    protected $dates = ['published_at'];
    protected $fillable = [
        'title', 'subtitle', 'content_raw', 'page_image', 'meta_description','layout', 'is_draft', 'published_at',
    ];
    

    /**
     * 返回 published_at 字段的日期部分
     */
    public function getPublishDateAttribute($value)
    {
        return $this->published_at->format('Y-m-d');
    }

    /**
     * 返回 published_at 字段的时间部分
     */
    public function getPublishTimeAttribute($value)
    {
        return $this->published_at->format('g:i A');
    }

    /**
     * content_raw 字段别名
     */
    public function getContentAttribute($value)
    {
        return $this->content_raw;
    }



    //创建多对多关系以post_tag_pivot作为中间表
    public function tags()
    {
        return $this->BelongsToMany(Tag::class, 'post_tag_piovt');
    }
    

    //填充slug字段
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        if(!$this->exists){
            //在设置模型的 slug 属性时，将其转换为 URL 友好的格式
            $this->attributes['slug'] = Str::slug($value);
        }
    }
    
    //获取slug唯一的标识
    protected function setUniqueSlug($title,$extra)
    {
        $slug = str_slug($title . '-' . $extra);

        if (static::where('slug', $slug)->exists()) {
            $this->setUniqueSlug($title, $extra + 1);
            return;
        }

        $this->attributes['slug'] = $slug;
    }

    //这段代码是一个在模型类中定义的属性设置器（Mutator），它用于在设置模型的 content_raw 属性时，自动对应更新 content_html 属性。
    public function setContentRawAttribute($value)
    {
        //创建了一个新的 Markdowner 对象，这可能是一个自定义的 Markdown 转换类。
        $markdown = new Markdowner();

        //将原始的 $value 赋给 content_raw 属性。
        $this->attributes['content_raw'] = $value;
        //将经过 Markdown 转换后的 HTML 内容赋给 content_html 属性。
        $this->attributes['content_html'] = $markdown->toHTML($value);
    }

    //
    public function syncTags(array $tags)
    {
        Tag::addNeededTags($tags);

        if (count($tags)) {
            $this->tags()->sync(
                Tag::whereIn('tag', $tags)->get()->pluck('id')->all()
            );
            return;
        }

        $this->tags()->detach();
    }
}
