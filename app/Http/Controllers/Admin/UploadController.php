<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\FileUploadService;
use App\Http\Requests\UploadFileRequest;
use App\Http\Requests\UploadNewFileRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    protected $manager;
    public function __construct(FileUploadService $manager)
    {
        $this->manager = $manager;
    }



    public function index(Request $request)
    {
        $folder = $request->get('folder');
        $data = $this->manager->folderInfo($folder);

        return view('admin.upload.index', $data);
    }

    
    public function createFolder(UploadNewFileRequest $request)
    {
        $new_folder = $request->get('new_folder');
        $folder = $request->get('folder') . '/' . $new_folder;
    
        $result = $this->manager->createDirectory($folder);
    
        if ($result === true) {
            return redirect()
                ->back()
                ->with('success', '目录「' . $new_folder . '」创建成功.');
        }
    
        $error = $result ?: "创建目录出错.";
        return redirect()
            ->back()
            ->withErrors([$error]);
    }
    
    /**
     * 删除文件
     */
    public function deleteFile(Request $request)
    {
        $del_file = $request->get('del_file');
        $path = $request->get('folder') . '/' . $del_file;
    
        $result = $this->manager->deleteFile($path);
    
        if ($result === true) {
            return redirect()
                ->back()
                ->with('success', '文件「' . $del_file . '」已删除.');
        }
    
        $error = $result ?: "文件删除出错.";
        return redirect()
            ->back()
            ->withErrors([$error]);
    }
    
    /**
     * 删除目录
     */
    public function deleteFolder(Request $request)
    {
        $del_folder = $request->get('del_folder');
        $folder = $request->get('folder') . '/' . $del_folder;
    
        $result = $this->manager->deleteDirectory($folder);
    
        if ($result === true) {
            return redirect()
                ->back()
                ->with('success', '目录「' . $del_folder . '」已删除');
        }
    
        $error = $result ?: "An error occurred deleting directory.";
        return redirect()
            ->back()
            ->withErrors([$error]);
    }
    
    /**
     * 上传文件
     */
    public function uploadFile(UploadFileRequest $request)
    {   //使用php自带的功能来进行文件上传
        // $file = $_FILES['file'];
        // $fileName = $request->get('file_name');
        // $fileName = $fileName ?: $file['name'];
        // $path = Str::finish($request->get('folder'), '/') . $fileName;
        // $content = File::get($file['tmp_name']);

        //使用文件系统抽象来完成文件上传工作
        $uploadedFile = $request->file('file');
        $fileName = $request->input('file_name') ?: $uploadedFile->getClientOriginalName();
        $folder = $request->input('folder');
        $path = Str::finish($folder, '/') . $fileName;
        $content = file_get_contents($uploadedFile->getRealPath());
    
        $result = $this->manager->saveFile($path, $content);
    
        if ($result === true) {
            return redirect()
                ->back()
                ->with("success", '文件「' . $fileName . '」上传成功.');
        }
    
        $error = $result ?: "文件上传出错.";
        return redirect()
            ->back()
            ->withErrors([$error]);
    }
}
