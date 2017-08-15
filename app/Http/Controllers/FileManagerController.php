<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Posttype;
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
        $files = File::orderBy('id');
        switch ($request->instance) {
            case 'posttype':
                $files->where(['posttype' => $request->key])
                    ->whereNull('folder')
                    ->whereNull('category');
                break;
            case 'folder':
                $files->where(['folder' => $request->key])
                    ->whereNotNull('posttype')
                    ->whereNull('category');
                break;
            case 'category':
                $files->where(['category' => $request->key])
                    ->whereNotNull('posttype')
                    ->whereNotNull('folder');
                break;
        }

        $files = $files->get();

        return view('file-manager.media-frame-content-gallery-images-list', compact('files'));
    }

}
