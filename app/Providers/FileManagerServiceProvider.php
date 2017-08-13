<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Posttype;
use App\Models\Folder;
use App\Models\Category;

class FileManagerServiceProvider extends ServiceProvider
{
    private static $defaultLevels = [
        'default', // default userType
        'default', // default section
    ];
    private static $currentLevels = [
        'default',
        'default',
    ];
    private static $preloaderShown = false;
    private static $defaultJsConfigs = [];
    private static $defaultAcceptableLevelsNumber = 2; // number of level that can be changed to default value
    private static $rootUploadDir = 'uploads'; // Root Directory
    private static $randomNameLength = 30; // Length of Random Name to Be Generated for Uploading Files
    private static $fileNameSeparator = '_';
    private static $temporaryFolderName = 'temp';
    private static $versionsPostfixes = [
        'original' => 'original',
    ]; // Postfixes that should be added at the end of names of any version of any file

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * @param Posttype|Folder|Category $input
     *
     * @return boolean
     */
    public static function getPointData($point)
    {
        $output = [];
        if ($point instanceof Posttype) {
            $output = self::getPointSelfInfo($point, 'posttype');

            $folders = $point->folders;
            if ($folders->count()) {

                $notEmptyFolders = $folders->filter(function ($item, $key) use ($folders) {
                    return ($item->slug != 'no');
                });

                $children = [];
                foreach ($folders as $folder) {
                    $children[] = self::getPointData($folder);
                }

                if (!$notEmptyFolders->count()) {
                    $endChildren = [];
                    foreach ($children as $key => $child) {
                        if (array_key_exists('children', $child)) {
                            $endChildren = array_merge($endChildren, $child['children']);
                        }
                    }
                    $children = $endChildren;
                }

                $output['children'] = $children;
            }
        } else if ($point instanceof Folder) {
            $output = self::getPointSelfInfo($point, 'folder');

            $categories = $point->categories;

            if ($categories->count()) {
                $children = [];
                foreach ($categories as $category) {
                    $children[] = self::getPointData($category);
                }

                $output['children'] = $children;
            }
        } else if ($point instanceof Category) {
            $output = self::getPointSelfInfo($point, 'category');
        }

        return $output;
    }

    private static function getPointSelfInfo($point, $instance)
    {
        $output = [];
        $output['instance'] = $instance;
        $output['key'] = $point->hashid;

        switch ($instance) {
            case 'posttype':
                $output['title'] = $point->titleIn(getLocale());
                break;
            case 'folder':
                $output['title'] = $point->title ?: trans('front.unnamed', [], $point->locale);
                break;
            case 'category':
                $output['title'] = $point->title;
                break;
        }
        return $output;
    }

    /**
     * Return a View Containing DropZone Uploader Element and Related JavaScript Codes
     *
     * @param string|array $fileTypeString
     * @param array        $data
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function dropzoneUploader($fileTypes, $data = [])
    {
        if (is_string($fileTypes)) {
            $fileTypes = [$fileTypes];
        }

        $uploadConfig = UploadServiceProvider::getCompleteRules($fileTypes);

        // preloader view will be added to view only in generating first uploader
        if (!self::$preloaderShown) {
            $preloaderView = view('file-manager.frame.uploader.preloader');
            self::$preloaderShown = true;
        }

        foreach ($fileTypes as $fileTypeString) {
            if (UploadServiceProvider::isActive($fileTypeString)) {

                $fileTypeStringParts = UploadServiceProvider::translateFileTypeString($fileTypeString);

                if (isset($fileType)) {
                    if ($fileType != last($fileTypeStringParts)) {
                        $fileType = 'file';
                    }
                } else {
                    $fileType = last($fileTypeStringParts);
                }
                $uploadIdentifiers[] = implode('.', $fileTypeStringParts);
            }
        }

        return view('file-manager.frame.uploader.box', compact(
                'fileType',
                'preloaderView',
                'uploadIdentifiers',
                'uploadConfig'
            ) + $data);
    }
}
