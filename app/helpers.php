<?php

use Illuminate\Support\Str;
//返回可读性更好的文件尺寸

function human_filesize($bytes, $decimals = 2)
{
    $size = ['B','KB','MB','GB','TB','PB'];
    $factor = floor((strlen($bytes)-1)/3);

    //pow(a,b):计算a的b次方
    /*
    *sprintf(dormat,arg1,arg2)
    *format:是一个半酣格式说明符的字符串，用于指导数据的格式。
    arg，arg2：是要插入到字符串中的数据
    注意：该函数和c语言的printf功能几乎一样
    */
    return sprintf("%.{$decimals}f",$bytes/pow(1024, $factor)).@$size[$factor];
}

function is_image($mimeType)
{
    //该方法用于检查字符串是否以指定的前缀开始。如果 MIME 类型以 'image/' 开头
    return Str::startsWith($mimeType, 'image/');
}




/**
 * Return "checked" if true
 */
function checked($value)
{
    return $value ? 'checked' : '';
}

/**
 * Return img url for headers
 */
function page_image($value = null)
{
    if (empty($value)) {
        $value = config('blog.page_image');
    }
    if (! Str::startsWith($value, 'http') && $value[0] !== '/') {
        $value = config('blog.uploads.webpath') . '/' . $value;
    }

    return $value;
}