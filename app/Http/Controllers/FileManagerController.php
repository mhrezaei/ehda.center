<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\File;
use App\Models\Folder;
use App\Models\Post;
use App\Models\Posttype;
use App\Providers\UploadServiceProvider;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FileManagerController extends Controller
{
    use ManageControllerTrait;

    public function index()
    {
        $postTypes = $this->getAccessiblePosttypes();

        // If current user hasn't permission to any posttype
        if(!$postTypes->count()) {
            return $this->abort('403');
        }

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
            if (
                user()->can('file-manager.*') or
                (!$postType->can('create') and !$postType->can('edit') and !$postType->can('publish')) or
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
                $postType = $folder->posttype;
                if (!$folder->exists) {
                    return null;
                }
                $files->where(['folder' => $folder->id])
                    ->whereNotNull('posttype')
                    ->whereNull('category');
                break;
            case 'category':
                $category = Category::findByHashid($request->key);
                $postType = $category->folder->posttype;
                if (!$category->exists) {
                    return null;
                }
                $files->where(['category' => $category->id])
                    ->whereNotNull('posttype')
                    ->whereNotNull('folder');
                break;
            default:
                return null;
        }

        $files = $files->get();

        return view(
            'file-manager.media-frame-content-gallery-images-list',
            compact('files', 'postType')
        );
    }

    public function getPreview(Request $request)
    {
        return UploadServiceProvider::getFileView($request->file, 'thumbnail', [
            'style' => [
                'max-width' => '100%',
            ]
        ]);
    }

    public function download($hadhid, $fileName = null)
    {
        $file = File::findByHashid($hadhid);
        if (!$file->exists or !UploadServiceProvider::getFileObject($file->pathname)) {
            return $this->abort('404');
        }

        if ($fileName) {
            if (!ends_with($fileName, '.' . $file->extension)) {
                $fileName = $fileName . '.' . $file->extension;
            }
        } else {
            $fileName = $file->original_name;
        }

        $headers = array(
            'Content-Type: ' . $file->mime_type,
        );

        return response()->download($file->pathname, $fileName, $headers);
    }
}
