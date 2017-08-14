<?php

namespace App\Http\Controllers;

use App\Http\Requests\Front\CommentRequest;
use App\Http\Requests\Front\DropzoneUploadRequest;
use App\Models\Category;
use App\Models\Folder;
use App\Models\Posttype;
use App\Providers\UploadServiceProvider;
use App\Traits\ManageControllerTrait;
use App\Traits\TahaControllerTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Models\File as UploadedFileModel;

class DropzoneController extends Controller
{
    use ManageControllerTrait;

    public function upload_file(DropzoneUploadRequest $request)
    {
        $externalFields = json_decode($request->externalFields, true) ?: [];

        /******************** Validating Values of 'category', 'folder' and 'posttype' ***************** START */
        if (isset($externalFields['category']) and $externalFields['category']) {
            $category = Category::findByHashid($externalFields['category']);
            if ($category->exists) {
                $folder = $category->folder;
                $posttype = $folder->posttype;

                $externalFields['category'] = $category->id;
                $externalFields['folder'] = $folder->id;
                $externalFields['posttype'] = $posttype->id;
            } else {
                $externalFields['category'] = null;
                $externalFields['folder'] = null;
                $externalFields['posttype'] = null;
            }
        } else if (isset($externalFields['folder']) and $externalFields['folder']) {
            $folder = Folder::findByHashid($externalFields['folder']);
            if ($folder->exists) {
                $posttype = $folder->posttype;

                $externalFields['category'] = null;
                $externalFields['folder'] = $folder->id;
                $externalFields['posttype'] = $posttype->id;
            } else {
                $externalFields['category'] = null;
                $externalFields['folder'] = null;
                $externalFields['posttype'] = null;
            }
        } else if (isset($externalFields['posttype']) and $externalFields['posttype']) {
            $posttype = Posttype::findByHashid($externalFields['posttype']);
            if ($posttype->exists) {
                $externalFields['category'] = null;
                $externalFields['folder'] = null;
                $externalFields['posttype'] = $posttype->id;
            } else {
                $externalFields['category'] = null;
                $externalFields['folder'] = null;
                $externalFields['posttype'] = null;
            }
        }
        /******************** Validating Values of 'category', 'folder' and 'posttype' ***************** END */

        if (isset($externalFields['posttype']) and $externalFields['posttype']) {
            $postTypeConfigPointer = UploadServiceProvider::getPostTypeConfigPrefix() . $posttype->slug;
            $modifiedIdentifiers = str_replace(
                '__posttype__',
                $postTypeConfigPointer,
                $request->_uploadIdentifier
            );
            $request->merge(['_uploadIdentifier' => encrypt($modifiedIdentifiers)]);
        }

        $uploadIdentifiers = explodeNotEmpty(',', $request->_uploadIdentifier);

        if (count($uploadIdentifiers) == 0) {
            return $this->abort('403', true);
        }

        $file = $request->file;
        $fileExtension = $file->guessExtension();

        if (count($uploadIdentifiers) > 1) {
            if (($validationResponse = UploadServiceProvider::validateFile($request)) !== true) {
                return response()->json($validationResponse->toArray(), 422);
            }
            foreach ($uploadIdentifiers as $uploadIdentifier) {
                $acceptedExtensions = UploadServiceProvider::getTypeRule($uploadIdentifier, 'acceptedExtensions');
                if (in_array($fileExtension, $acceptedExtensions) !== false) {
                    $typeString = $uploadIdentifier;
                    break;
                }
            }

            $request->merge(['_uploadIdentifier', $typeString]);
        } else {
            $typeString = $request->_uploadIdentifier;
        }

        $sessionName = $request->_groupName;

        $typeStringParts = explode('.', $typeString);
        $sectionName = implode('.', array_slice($typeStringParts, 0, count($typeStringParts) - 1));
        $folderName = array_last($typeStringParts);


        if (($validationResponse = UploadServiceProvider::validateFile($request)) === true) {
            $itemIndex = str_random(4);
            if (session()->has($sessionName)) {
                $currentUploaded = session()->get($sessionName);

                // check if this item exists in the session and change it if needed
                while (array_key_exists($itemIndex, $currentUploaded) != false) {
                    $itemIndex = str_random(4);
                }

                $currentUploaded[$itemIndex] = [
                    'name'   => $file->getClientOriginalName(),
                    'number' => (count($currentUploaded) + 1),
                    'done'   => false,
                ];
                session()->put($sessionName, $currentUploaded);
            } else {
                session()->put($sessionName, [
                    $itemIndex => [
                        'name'   => $file->getClientOriginalName(),
                        'number' => 1,
                        'done'   => false,
                    ]
                ]);
            }
            session()->save();

            $uploadDir = implode(DIRECTORY_SEPARATOR, [
                UploadServiceProvider::getSectionRule($sectionName, 'uploadDir'),
                $folderName,
            ]);
            $uploadResult = UploadServiceProvider::uploadFile($file, $uploadDir, $externalFields);
            $dbRow = UploadedFileModel::findBySlug($uploadResult, 'id');

            if ($dbRow->exists()) {
                /**
                 * This condition is for synchronous uploading.
                 * If files number has reached the limit while uploading this file, this should be deleted.
                 */
                if (UploadServiceProvider::validateFileNumbers($sectionName, $typeString)) {

                    $currentUploaded[$itemIndex]['done'] = true;
                    $currentUploaded[$dbRow->hash_id] = $currentUploaded[$itemIndex];
                    unset($currentUploaded[$itemIndex]);

                    session()->put($sessionName, $currentUploaded);
                    session()->save();

                    return response()->json([
                        'success' => true,
                        'file'    => $dbRow->hash_id,
                    ]);
                } else {
                    UploadServiceProvider::removeFile($dbRow);
                }
            }
        } else {
            return response()->json($validationResponse->toArray(), 422);
        }

        return response()->make('', 400);
    }

    public function remove_file(Request $request)
    {
        $typeString = $request->_uploadIdentifier;
        $sessionName = $request->_groupName;
        $file = $request->file;

        if ($file) {

            if (session()->has($sessionName)) {
                $currentUploaded = session()->get($sessionName);
                if (array_key_exists($file, $currentUploaded)) {
                    unset($currentUploaded[$file]);
                    session()->put($sessionName, $currentUploaded);
                }
            }

            UploadServiceProvider::removeFile($file);
        }
    }
}
