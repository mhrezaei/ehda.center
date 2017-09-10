<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileManager\DeleteFileDetails;
use App\Http\Requests\FileManager\GetFileDetailsRequest;
use App\Http\Requests\FileManager\GetFilesListRequest;
use App\Http\Requests\FileManager\RestoreFileDetails;
use App\Http\Requests\FileManager\SetFileDetails;
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

        return view('file-manager.media-frame-content-gallery-file-details', compact('file'));
    }

    public function setFileDetails(SetFileDetails $request)
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

    public function deleteFile(DeleteFileDetails $request)
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

    public function restoreFile(RestoreFileDetails $request)
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
