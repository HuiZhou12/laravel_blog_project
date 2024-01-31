<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //使用 $faker 生成一个包含 3 到 10 个单词的句子，作为文章的标题。
            'title' => $this->faker->sentence(mt_rand(3, 10)),
            //使用 $faker 生成包含 3 到 6 段落的文章内容，并用两个换行符分隔每一段。
            'content' => join("\n\n", $this->faker->paragraphs(mt_rand(3, 6))),
            //生成一个在过去一个月到未来三天之间的随机日期时间，作为文章的发布时
            'published_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
