<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Http\Requests\TagRequest;
use App\Http\Requests\TagUpdateRequest;
use Illuminate\Support\Arr;

class TagController extends Controller
{
    protected $fields = [
        'tag' => '',
        'title' => '',
        'subtitle' => '',
        'meta_description' => '',
        'page_image' => '',
        'layout' => 'blog.layouts.index',
        'reverse_direction' => 0,
    ];
    

    //触发时机：当访问对应路由（如 /admin/tag）时触发。
    public function index()
    {
        $tags = Tag::all();
        return view('admin.tag.index',['tags' => $tags]);
    }


    //触发时机：当访问创建标签的路由（如 /admin/tag/create）时触发。
    public function create()
    {
        $data = [];
        foreach ($this->fields as $field => $default) {
            // old 函数的工作原理是检查先前请求中的输入数据（Input），如果发现输入中包含有关指定字段的旧值，就返回该值；否则，返回提供的默认值。
            $data[$field] = old($field, $default);
        }
        

        return view('admin.tag.create', $data);
    }



    //触发时机：当提交创建标签的表单时触发。
    public function store(TagRequest $request)
    {
        $tag = new Tag();
        foreach (array_keys($this->fields) as $field) {

            $tag->$field = $request->input($field);
        }
        $tag->save();
        // $tag = Tag::create($request->all());
    
        return redirect('/admin/tag')
                        ->with('success', '标签「' . $tag->tag . '」创建成功.');        
    }



    //触发时机：当访问编辑标签的路由（如 /admin/tag/{id}/edit）时触发。
    public function edit($id)
    {
        $tag = Tag::findOrFail($id);
        $data = ['id' => $id];
        foreach (array_keys($this->fields) as $field) {
            $data[$field] = old($field, $tag->$field);
        }
    
        return view('admin.tag.edit', $data);    
    }




    //触发时机：当提交编辑标签的表单时触发。
    public function update(TagUpdateRequest $request, $id)
    {
        $tag = Tag::findOrFail($id);
        
        foreach (array_keys(Arr::except($this->fields, 'tag')) as $field) {

            $tag->$field = $request->input($field);
        }
        $tag->save();
    
        return redirect("/admin/tag/$id/edit")
            ->with('success', '修改已保存.');
    }



    //触发时机：当删除标签的路由被访问时触发。
    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();
    
        return redirect('/admin/tag')
            ->with('success', '标签「' . $tag->tag . '」已经被删除.');
    }
}
