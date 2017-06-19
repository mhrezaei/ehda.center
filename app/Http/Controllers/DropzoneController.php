<?php

namespace App\Http\Controllers;

use App\Http\Requests\Front\CommentRequest;
use App\Providers\UploadServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Models\UploadedFile as UploadedFileModel;

class DropzoneController extends Controller
{
    public function upload_file(Request $request)
    {
        $file = $request->file;
        $typeString = $request->uploadIdentifier;
        $sessionName = $request->groupName;

        $typeStringParts = explode('.', $typeString);
        $sectionName = implode('.', array_slice($typeStringParts, 0, count($typeStringParts) - 1));
        $folderName = array_last($typeStringParts);

        if (UploadServiceProvider::validateFile($request)) {
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
            $uploadResult = UploadServiceProvider::uploadFile($file, $uploadDir);
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
        }

        return response()->make('', 400);
    }

    public function remove_file(Request $request)
    {
        $typeString = $request->uploadIdentifier;
        $sessionName = $request->groupName;
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
