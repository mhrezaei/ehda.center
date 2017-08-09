<?php

namespace App\Http\Controllers;

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
            // If current user can do any of "create", "edit" or "publish" we will keep posttype,
            // else we will forget it.
            if (!$postType->can('create') and !$postType->can('edit') and !$postType->can('publish')) {
                $postTypes->forget($key);
            }
        });

        return $postTypes;
    }

}
