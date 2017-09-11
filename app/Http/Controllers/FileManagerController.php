<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileManager\DeleteFileRequest;
use App\Http\Requests\FileManager\GetFileDetailsRequest;
use App\Http\Requests\FileManager\GetFilesListRequest;
use App\Http\Requests\FileManager\RestoreFileRequest;
use App\Http\Requests\FileManager\SetFileDetailsRequest;
use App\Models\Category;
use App\Models\File;
use App\Models\FileDownloads;
use App\Models\Folder;
use App\Models\Posttype;
use App\Providers\UploadServiceProvider;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;

class FileManagerController extends Controller
{
    use ManageControllerTrait;

    public function index()
    {
        $postTypes = $this->getAccessiblePosttypes();

        // If current user hasn't permission to any posttype
        if (!$postTypes->count()) {
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
                (
                    !user()->can('file-manager.*') and
                    !$postType->can('create') and
                    !$postType->can('edit') and
                    !$postType->can('publish')
                ) or
                (!$postType->canUploadFile())
            ) {
                $postTypes->forget($key);
            }
        });

        return $postTypes;
    }

    public function getList($instance = '', $key = '')
    {
        $files = File::orderBy('created_at', 'DESC');
        switch ($instance) {
            case 'posttype':
                $postType = Posttype::findByHashid($key);
                if (!$postType->exists) {
                    return null;
                }
                $files->where(['posttype' => $postType->id])
                    ->whereNull('folder')
                    ->whereNull('category');
                break;
            case 'folder':
                $folder = Folder::findByHashid($key);
                $postType = $folder->posttype;
                if (!$folder->exists) {
                    return null;
                }
                $files->where(['folder' => $folder->id])
                    ->whereNotNull('posttype')
                    ->whereNull('category');
                break;
            case 'category':
                $category = Category::findByHashid($key);
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
        $files = explodeNotEmpty('-', $request->file);
        $result = '';

        foreach ($files as $file) {
            $result .= UploadServiceProvider::getFileView($file, 'thumbnail', [
                'style' => [
                    'max-width' => '100%',
                ]
            ]);
        }
        return $result;
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
            $fileName = $file->file_name;
        }

        $headers = array(
            'Content-Type: ' . $file->mime_type,
        );

        return response()->download($file->pathname, $fileName, $headers);
    }

    public function disposableDownload($hashString, $hadhid, $fileName = null)
    {
        $fileDownloadRow = FileDownloads::findByHashid($hashString);
        $file = File::findByHashid($hadhid);
        if (
            !$fileDownloadRow->exists or
            !$file->exists or
            !UploadServiceProvider::getFileObject($file->pathname)
        ) {
            return $this->abort('404');
        }

        // Check if $fileDownloadRow doesn't belong to $file
        if ($fileDownloadRow->file_id != $file->id) {
            return $this->abort(403);
        }

        // Check if $fileDownloadRow can't be downloaded any more
        if ($fileDownloadRow->downloaded_count >= $fileDownloadRow->downloadable_count) {
            return $this->abort(404);
        }

        // Change $fileDownloadRow to count this download
        $fileDownloadRow->downloaded_count++;
        $fileDownloadRow->save();
        if ($fileDownloadRow->downloaded_count >= $fileDownloadRow->downloadable_count) {
            $fileDownloadRow->delete();
        }

        // Set downloading file name
        if ($fileName) {
            if (!ends_with($fileName, '.' . $file->extension)) {
                $fileName = $fileName . '.' . $file->extension;
            }
        } else {
            $fileName = $file->file_name;
        }

        // Set header
        $headers = array(
            'Content-Type: ' . $file->mime_type,
        );

        // Return response
        return response()->download($file->pathname, $fileName, $headers);
    }

    public function getFileDetails($fileKey = '')
    {
        $file = File::findByHashid($fileKey);
        if (!$file->exists) {
            return $this->abort(404);
        }
        if (!$file->can('preview')) {
            return $this->abort(403);
        }

        $fileObj = UploadServiceProvider::getFileObject($file->pathname);

        if ($fileObj) {
            $fileExistsOnStorage = true;
        } else {
            $fileExistsOnStorage = false;
        }

        $breadCrumb = [];
        if ($file->category) {
            $category = Category::findBySlug($file->category, 'id');
            if ($category->exists) {
                $folder = $category->folder;
                $posttype = $folder->posttype;

                $breadCrumb[] = [
                    'label'    => $posttype->title,
                    'instance' => 'posttype',
                    'key'      => $posttype->hashid,
                ];

                if ($folder->title) {
                    $breadCrumb[] = [
                        'label'    => $folder->title,
                        'instance' => 'folder',
                        'key'      => $folder->hashid,
                    ];
                }

                $breadCrumb[] = [
                    'label'    => $category->title,
                    'instance' => 'category',
                    'key'      => $category->hashid,
                ];
            }
        } else if ($file->folder) {
            $folder = Folder::findBySlug($file->folder, 'id');
            if ($folder->exists) {
                $posttype = $folder->posttype;

                $breadCrumb[] = [
                    'label'    => $posttype->title,
                    'instance' => 'posttype',
                    'key'      => $posttype->hashid,
                ];

                if ($folder->title) {
                    $breadCrumb[] = [
                        'label'    => $folder->title,
                        'instance' => 'folder',
                        'key'      => $folder->hashid,
                    ];
                }
            }

        } else if ($file->posttype) {
            $posttype = Posttype::findBySlug($file->posttype, 'id');
            if ($posttype->exists) {
                $breadCrumb[] = [
                    'label'    => $posttype->title,
                    'instance' => 'posttype',
                    'key'      => $posttype->hashid,
                ];
            }

        }

        return view(
            'file-manager.media-frame-content-gallery-file-details',
            compact('file', 'fileExistsOnStorage', 'breadCrumb')
        );
    }

    public function setFileDetails(SetFileDetailsRequest $request)
    {
        $file = File::findByHashid($request->fileKey);
        if (!$file->exists) {
            return $this->abort(404);
        }
        if (!$file->can('edit')) {
            return $this->abort(403);
        }

        $saveData = array_merge(['id' => $file->id], $request->all());

        File::store($saveData, ['fileKey']);
    }

    public function deleteFile(DeleteFileRequest $request)
    {
        $file = File::findByHashid($request->fileKey);
        if (!$file->exists) {
            return $this->abort(404);
        }
        if (!$file->can('delete')) {
            return $this->abort(403);
        }

        $file->delete();
    }

    public function restoreFile(RestoreFileRequest $request)
    {
        $file = File::findByHashid($request->fileKey, ['with_trashed' => true]);
        if (!$file->exists) {
            return $this->abort(404);
        }
        if (!$file->can('delete')) {
            return $this->abort(403);
        }

        $file->restore();
    }
}
