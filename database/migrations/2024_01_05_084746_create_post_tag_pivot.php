<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTagPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_tag_piovt', function (Blueprint $table) {
            $table->id();
            //创建一个整数类型的列，用于存储博客文章的 ID。unsigned() 表示该列只能存储正整数，index() 表示为该列创建索引，以提高查询效率。
            $table->integer('post_id')->unsigned()->index();
            //创建一个整数类型的列，用于存储标签的 ID。同样，unsigned() 表示该列只能存储正整数，index() 表示为该列创建索引。
            $table->integer('tag_id')->unsigned()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_tag_piovt');
    }
}
