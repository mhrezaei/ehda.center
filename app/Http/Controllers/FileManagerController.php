<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\File;
use App\Models\Folder;
use App\Models\Post;
use App\Models\Posttype;
use App\Providers\UploadServiceProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FileManagerController extends Controller
{
    public function index()
    {
        $postTypes = $this->getAccessiblePosttypes();

        return view('file-manager.main', compact('postTypes'));
    }

    /**
     * Returns all postypes that current user can do "create", "edit" or "submit" on theme
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getAccessiblePosttypes()
    {
        $postTypes = Posttype::orderBy('order')->orderBy('title')->get();


        $postTypes->each(function ($postType, $key) use ($postTypes) {
            $postType->spreadMeta();
            // If current user can do any of "create", "edit" or "publish"
            // and postType has "upload_configs" meta
            // we will keep posttype,
            // else we will forget it.
            if ((!$postType->can('create') and !$postType->can('edit') and !$postType->can('publish')) or
                (!$postType->canUploadFile())
            ) {
                $postTypes->forget($key);
            }
        });

        return $postTypes;
    }

    public function getList(Request $request)
    {
        $files = File::orderBy('created_at', 'DESC');
        switch ($request->instance) {
            case 'posttype':
                $postType = Posttype::findByHashid($request->key);
                if (!$postType->exists) {
                    return null;
                }
                $files->where(['posttype' => $postType->id])
                    ->whereNull('folder')
                    ->whereNull('category');
                break;
            case 'folder':
                $folder = Folder::findByHashid($request->key);
                if (!$folder->exists) {
                    return null;
                }
                $files->where(['folder' => $folder->id])
                    ->whereNotNull('posttype')
                    ->whereNull('category');
                break;
            case 'category':
                $category = Category::findByHashid($request->key);
                if (!$category->exists) {
                    return null;
                }
                $files->where(['category' => $category->id])
                    ->whereNotNull('posttype')
                    ->whereNotNull('folder');
                break;
        }

        $files = $files->get();

        return view('file-manager.media-frame-content-gallery-images-list', compact('files'));
    }

    public function getPreview(Request $request)
    {
        return UploadServiceProvider::getFileView($request->file, 'thumbnail');
    }
}
