<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    protected $disk;
    protected $mimeDetect;

    public function __construct()
    {
        //这一行代码初始化了 $disk 属性，该属性是一个文件系统实例。
        //从配置文件中获取了文件系统的驱动
        $this->disk = Storage::disk(config('blog.uploads.storage'));
    }

    /**
     * Return files and directories within a folder
     *
     * @param string $folder
     * @return array of [
     *     'folder' => 'path to current folder',
     *     'folderName' => 'name of just current folder',
     *     'breadCrumbs' => breadcrumb array of [ $path => $foldername ]
     *     'folders' => array of [ $path => $foldername] of each subfolder
     *     'files' => array of file details on each file in folder
     * ]
     */
    public function folderInfo($folder)
    {
        $folder = $this->cleanFolder($folder);

        $breadcrumbs = $this->breadcrumbs($folder);
        $slice = array_slice($breadcrumbs, -1);
        $folderName = current($slice);
        $breadcrumbs = array_slice($breadcrumbs, 0, -1);

        $subfolders = [];
        foreach (array_unique($this->disk->directories($folder)) as $subfolder) {
            $subfolders["/$subfolder"] = basename($subfolder);
        }

        $files = [];
        foreach ($this->disk->files($folder) as $path) {
            $files[] = $this->fileDetails($path);
        }

        return compact(
            'folder',
            'folderName',
            'breadcrumbs',
            'subfolders',
            'files'
        );
    }

    /**
     * Sanitize the folder name
     */
    protected function cleanFolder($folder)
    {
        /*str_replace($search, $replace, $subject);
        $search 是要搜索的字符串或字符串数组。
        $replace 是用于替换匹配到的字符串的字符串或字符串数组。
        $subject 是要在其中进行替换操作的源字符串或字符串数组。
        */
        return '/' . trim(str_replace('..', '', $folder), '/');
    }

    /**
     * 返回当前目录路径
     */
    protected function breadcrumbs($folder)
    {
        $folder = trim($folder, '/');
        $crumbs = ['/' => 'root'];

        if (empty($folder)) {
            return $crumbs;
        }

        $folders = explode('/', $folder);
        $build = '';
        foreach ($folders as $folder) {
            $build .= '/' . $folder;
            $crumbs[$build] = $folder;
        }

        return $crumbs;
    }

    /**
     * 返回文件详细信息数组
     */
    protected function fileDetails($path)
    {
        $path = '/' . ltrim($path, '/');

        return [
            'name' => basename($path),
            'fullPath' => $path,
            'webPath' => $this->fileWebpath($path),
            'mimeType' => $this->fileMimeType($path),
            'size' => $this->fileSize($path),
            'modified' => $this->fileModified($path),
        ];
    }

    /**
     * 返回文件完整的web路径
     */
    public function fileWebpath($path)
    {
        $path = rtrim(config('blog.uploads.webpath'), '/') . '/' . ltrim($path, '/');
        return url($path);
    }

    /**
     * 返回文件MIME类型
     */
    public function fileMimeType($path)
    {
        return $this->disk->mimeType($path);
    }

    /**
     * 返回文件大小
     */
    public function fileSize($path)
    {
        return $this->disk->size($path);
    }

    /**
     * 返回最后修改时间
     */
    public function fileModified($path)
    {
        return Carbon::createFromTimestamp(
            $this->disk->lastModified($path)
        );
    }
    /**
     * 创建新目录
     */
    public function createDirectory($folder)
    {
        $folder = $this->cleanFolder($folder);

        if ($this->disk->exists($folder)) {
            return "Folder '$folder' already exists.";
        }

        return $this->disk->makeDirectory($folder);
    }

    /**
     * 删除目录
     */
    public function deleteDirectory($folder)
    {
        $folder = $this->cleanFolder($folder);

        $filesFolders = array_merge(
            $this->disk->directories($folder),
            $this->disk->files($folder)
        );
        if (! empty($filesFolders)) {
            return "Directory must be empty to delete it.";
        }

        return $this->disk->deleteDirectory($folder);
    }

    /**
     * 删除文件
     */
    public function deleteFile($path)
    {
        $path = $this->cleanFolder($path);

        if (! $this->disk->exists($path)) {
            return "File does not exist.";
        }

        return $this->disk->delete($path);
    }

    /**
     * 保存文件
     */
    public function saveFile($path, $content)
    {
        $path = $this->cleanFolder($path);

        if ($this->disk->exists($path)) {
            return "File already exists.";
        }

        return $this->disk->put($path, $content);
    }
}