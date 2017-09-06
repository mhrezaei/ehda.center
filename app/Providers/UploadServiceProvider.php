<?php

namespace App\Providers;

use App\Http\Controllers\DropzoneController;
use App\Models\Posttype;
use function GuzzleHttp\Promise\is_settled;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\File;
use \Illuminate\Support\Facades\File as FilesFacades;
use Symfony\Component\HttpFoundation\Request;
use App\Models\File as UploadedFileModel;

class UploadServiceProvider extends ServiceProvider
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
    private static $temporaryFolderName = 'temp'; // Folder name to save file before move
    private static $versionsPostfixes = [
        'original' => 'original',
    ]; // Postfixes that should be added at the end of names of any version of any file
    private static $postTypeConfigPrefix = 'posttype__';

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
     * Checks if uploading of a file type is activated or not
     *
     * @param        $fileType
     * @param string $userType
     *
     * @return mixed
     */
    public static function isActive($fileType, $userType = 'client')
    {
        return self::followUpConfig(self::generateConfigPath($fileType) . ".status");
    }

    /**
     * Get a Config Value For a File Type from Upload Configs
     *
     * @param string $fileType
     * @param string $configName
     *
     * @return mixed
     */
    public static function getTypeRule($fileType, $configName)
    {
        return self::followUpConfig(self::generateConfigPath($fileType) . ".$configName");
    }

    /**
     * Get a Config Value For a Section from Upload Configs
     *
     * @param string $section
     * @param string $configName
     *
     * @return mixed
     */
    public static function getSectionRule($section, $configName)
    {
        $result = self::followUpConfig(self::generateConfigPath($section, true) . ".$configName");

        /**
         * Modify result if needed
         */
        switch ($configName) {
            case 'uploadDir':
                $result = self::rectifyDirectory($result);
                break;
        }

        return $result;
    }

    /**
     * Return a View Containing DropZone Uploader Element and Related JavaScript Codes
     *
     * @param string $fileTypeString
     * @param array  $data
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function dropzoneUploader($fileTypeString, $data = [])
    {
        if (self::isActive($fileTypeString)) {
            // preloader view will be added to view only in generating first uploader
            if (!self::$preloaderShown) {
                $preloaderView = view('uploader.preloader');
                self::$preloaderShown = true;
            }

            $fileTypeStringParts = self::translateFileTypeString($fileTypeString);
            $fileType = last($fileTypeStringParts);
            $uploadIdentifier = implode('.', $fileTypeStringParts);
            $uploadIdentifiers = [$uploadIdentifier];
            return view('uploader.box', compact(
                    'fileType',
                    'preloaderView',
                    'uploadIdentifiers'
                ) + $data);
        }
    }

    /**
     * Set User Type to Read Upload Settings From Configs
     *
     * @param string $userType
     *
     * @return void
     */
    public static function setUserType($userType)
    {
        self::$currentLevels[0] = $userType;
    }

    /**
     * Set Section to Read Upload Settings From Configs
     *
     * @param string $section
     *
     * @return void
     */
    public static function setSection($section)
    {
        self::$currentLevels[1] = $section;
    }

    /**
     * Set Some Configs to Be Set to All Uploader Elements
     *
     * @param array|string $first  If this is string it will be assumed as config key
     * @param null|mixed   $second If $first is string this will be assumed as config value
     */
    public static function setDefaultJsConfigs($first, $second = null)
    {
        if (is_array($first)) {
            self::$defaultJsConfigs = array_merge(self::$defaultJsConfigs, $first);
        } else {
            self::$defaultJsConfigs = array_merge(self::$defaultJsConfigs, [$first => $second]);
        }
    }

    /**
     * Get Current Default JS Configs
     *
     * @param string $key Key Of Requested Config (If empty all JS Configs will be returned)
     *
     * @return array|mixed
     */
    public static function getDefaultJsConfigs($key = '')
    {
        if ($key) {
            return self::$defaultJsConfigs[$key];
        }

        return self::$defaultJsConfigs;
    }

    /**
     * Validates an UploadedFile with other info in $request argument
     *
     * @param Request $request
     *
     * @return bool
     */
    public static function validateFile($request, $returnErrors = true)
    {
        $file = $request->file;
        $typeString = $request->_uploadIdentifier;
        $sessionName = $request->_groupName;

        $validationRules = self::getCompleteRules($request->_uploadIdentifier);
        $acceptedExtensions = $validationRules['acceptedExtensions'];

        if (!$acceptedExtensions or !is_array($acceptedExtensions) or !count($acceptedExtensions)) {
            $acceptedExtensions = [];
        }

        $validator = Validator::make($request->all(), [
            'file' => [
                'mimes:' . implode(',', $acceptedExtensions),
//                'max:' . ($validationRules['maxFileSize'] * 1024)
            ],
        ]);

        if (!$validator->fails() and
            self::validateFileNumbers($sessionName, $typeString)
        ) {
            return true;
        }

        if ($returnErrors) {
            return $validator->errors();
        }

        return false;
    }

    /**
     * Checks if files number for an uploader reached the limit
     *
     * @param string|array $sessionName
     * @param string       $typeString
     */
    public static function validateFileNumbers($sessionName, $fileType)
    {
        $maxFiles = self::getCompleteRules($fileType)['maxFiles'];
        if (!session()->has($sessionName) or
            ($maxFiles === null) or
            (count(array_filter(session()->get($sessionName), function ($item) {
                    return $item['done'];
                })) < $maxFiles)
        ) {
            return true;
        }
        return false;
    }

    /**
     * Upload File to Specified Directory
     *
     * @param UploadedFile $file
     * @param string       $uploadDir Directory for Destination File
     *
     * @return File;
     */
    public static function uploadFile($file, $uploadDir, $externalFields = [])
    {
        $newName = self::generateFileName() . '.' . $file->getClientOriginalExtension();
        $finalUploadDirParts = [self::$rootUploadDir];
        if (self::$temporaryFolderName) {
            $finalUploadDirParts[] = self::$temporaryFolderName;
        }
        $finalUploadDirParts[] = $uploadDir;
        $finalUploadDir = implode(DIRECTORY_SEPARATOR, $finalUploadDirParts);

        // Save uploaded file to db
        $saveData = array_merge([
            'physical_name' => $newName,
            'directory'     => $finalUploadDir,
        ], $externalFields);
        FilesFacades::makeDirectory($finalUploadDir, 0775, true, true);
        $id = UploadedFileModel::saveFile($file, $saveData);

        // Move uploaded file to target directory
        $file->move($finalUploadDir, $newName);

        return $id;
    }

    /**
     * Remove File Physically
     *
     * @param string|UploadedFileModel $file
     * @param boolean                  $onlyTemp If true, file will be removed only in temp status
     *
     * @return bool
     */
    public static function removeFile($file, $onlyTemp = true)
    {
        if (!$file instanceof UploadedFileModel) {
            $file = UploadedFileModel::findByHashid($file, ['with_trashed' => true]);
        }

        if ($file->exists and (!$onlyTemp or $file->hasStatus('temp'))
        ) {
            $pathname = $file->pathname;

            $relatedFiles = $file->related_files_pathname;
            if ($relatedFiles and is_array($relatedFiles)) {
                foreach ($relatedFiles as $relatedFilePath) {
                    if (FilesFacades::exists($relatedFilePath)) {
                        FilesFacades::delete($relatedFilePath);
                    }
                }
            }

            $file->forceDelete();

            if (FilesFacades::exists($file->pathname)) {
                FilesFacades::delete($pathname);
            }

            return true;
        } else {
            return false;
        }

    }

    /**
     * Searches for uploaded files and move theme from temporary folder
     *
     * @param \App\Http\Requests\Front\CommentRequest $request
     */
    public static function moveUploadedFilesWithRequest(&$request)
    {
        foreach ($request->all() as $index => $value) {
            if (ends_with($index, '_files')) {
                $filesHashids = json_decode($value, true);
                $filesHashids = self::moveUploadedFiles($filesHashids);
                $request->merge([$index => json_encode($filesHashids)]);
            }
        }
    }

    /**
     * Move uploaded files from temporary folder
     *
     * @param array $filesHashids
     *
     * @return array|null
     */
    public static function moveUploadedFiles($filesHashids)
    {
        if ($filesHashids and is_array($filesHashids)) {
            foreach ($filesHashids as $key => $fileHashid) {
                $fileRow = UploadedFileModel::findByHashid($fileHashid);
                if ($fileRow->status == 'used') {
                    continue;
                }
                if ($fileRow->exists and FilesFacades::exists($fileRow->pathname)) {
                    $fileRow->spreadMeta();
                    $newDir = str_replace(self::$temporaryFolderName . DIRECTORY_SEPARATOR, '', $fileRow->directory);

                    $file = new File($fileRow->pathname);
                    if ($fileRow->related_files) {
                        foreach ($fileRow->related_files as $key => $relatedFileName) {
                            $relatedFilePathname = $fileRow->getRelatedFilePathname($key);
                            $relatedFile = new File($relatedFilePathname);
                            $relatedFile->move($newDir);
                        }
                    }

                    $fileRow->setStatus('used');
                    $file->move($newDir);
                    $fileRow->directory = $newDir;

//                    $movedFile = new File($newDir . DIRECTORY_SEPARATOR . $file->getFilename());

//                            $fileRow = $fileRow->toArray();
//                            $fileRow['related_files'] = self::generateRelatedFiles($movedFile, $movedFile->getFilename(), $newDir);

//                            $fileRow->spreadMeta();
//                            $relatedFiles = $fileRow->related_files;
//                            if ($relatedFiles and is_array($relatedFiles)) {
//                                foreach ($relatedFiles as $key => $relatedFilePath) {
//                                    if (FilesFacades::exists($relatedFilePath)) {
//                                        $relatedFile = new File($relatedFilePath);
//                                        $relatedFile->move($newDir);
//                                        $relatedFiles[$key] = $newDir . DIRECTORY_SEPARATOR . $relatedFile->getFilename();
//                                    } else {
//                                        unset($relatedFiles[$key]);
//                                    }
//                                }
//                            }


                    UploadedFileModel::store($fileRow);
                } else {
                    unset($filesHashids[$key]);
                }
            }
            return $filesHashids;
        }

        return null;
    }

    /**
     * Generates a random name
     *
     * @param string $version  The Postfix tha Will Be Added at the End of the File Name
     * @param string $baseName Basic Part of the File Name (If empty basic part will be generated randomly)
     *
     * @return string
     */
    public static function generateFileName($version = 'original', $baseName = '')
    {
        if (array_key_exists($version, self::$versionsPostfixes)) {
            $version = self::$versionsPostfixes[$version];
        }

        if ($baseName and is_string($baseName)) {
            $basementPart = [$baseName];
        } else {
            $basementPart = [time(), str_random(self::$randomNameLength)];
        }

        return implode(self::$fileNameSeparator, array_merge($basementPart, [$version]));
    }

    /**
     * Returns specified $fileName in requested version
     *
     * @param string $fileName Source File Name
     * @param string $newVersion
     *
     * @return null|string
     */
    public static function changeFileNameVersion($fileName, $newVersion)
    {
        // remove extension
        $ext = FilesFacades::extension($fileName);
        $fileName = substr($fileName, 0, -(strlen($ext) + 1));

        $fileNameParts = explode(self::$fileNameSeparator, $fileName);
        if (count($fileNameParts) == 3) {
            $fileNameParts[2] = $newVersion;
            return implode(self::$fileNameSeparator, $fileNameParts) . '.' . $ext;
        }

        return null;
    }

    /**
     * Returns specified $url in requested version
     *
     * @param string $url Source File UrL
     * @param string $version
     *
     * @return mixed
     */
    public static function changeFileUrlVersion($url, $version)
    {
        $fileName = pathinfo($url)['basename'];
        $newVersion = self::changeFileNameVersion($fileName, $version);

        if ($newVersion) {
            $newUrl = str_replace($fileName, $newVersion, $url);
            $newPath = str_replace(url('/') . '/', '', $newUrl);

            if (self::getFileObject($newPath)) {
                return $newUrl;
            }
        }

        return $url;
    }

    public static function getThumb($fileUrl, $thumbFolder = 'thumbs')
    {
//        if (strpos($fileUrl, '_original') !== false) {
//            return str_replace('_original', '_thumb', $fileUrl);
//        } else {
//            $file = explode('.', $fileUrl);
//            return $file[0] . '_thumb.' . $file[1];
//        }
        $thumbUrl = self::changeFileUrlVersion($fileUrl, 'thumb');
        if ($thumbUrl) {
            return $thumbUrl;
        }

        return str_replace_last('/', "/$thumbFolder/", $fileUrl);
    }

    /**
     * Convert $fileTypeString to a meaning full array
     *
     * @param string $fileTypeString
     *
     * @return array
     */
    public static function translateFileTypeString($fileTypeString)
    {
        $fileTypeParts = array_reverse(explodeNotEmpty('.', $fileTypeString));
        if (!isset($fileTypeParts[1])) {
            $fileTypeParts[1] = self::$currentLevels[1];
        }
        if (!isset($fileTypeParts[2])) {
            $fileTypeParts[2] = self::$currentLevels[0];
        }

        $fileTypeParts = array_reverse($fileTypeParts);

        return $fileTypeParts;
    }

    /**
     * Convert $sectionString to a meaning full array
     *
     * @param string $sectionString
     *
     * @return array
     */
    public static function translateSectionString($sectionString)
    {
        $sectionParts = array_reverse(explodeNotEmpty('.', $sectionString));
        if (!isset($sectionParts[0])) {
            $sectionParts[0] = self::$currentLevels[1];
        }
        if (!isset($sectionParts[1])) {
            $sectionParts[1] = self::$currentLevels[0];
        }

        $sectionParts = array_reverse($sectionParts);

        return $sectionParts;
    }

    /**
     * Returns upload rules for list of identifiers
     *
     * @param string|array $identifiers If string could be comma (,) separated
     *
     * @return array
     */
    public static function getCompleteRules($identifiers)
    {
        if (is_string($identifiers)) {
            $identifiers = explodeNotEmpty(',', $identifiers);
        }

        $rules = [];
        foreach ($identifiers as $identifier) {
            $thisRules = self::followUpConfig(self::generateConfigPath($identifier));

            // Merge "acceptedExtensions"
            if (isset($rules['acceptedExtensions'])) {
                $rules['acceptedExtensions'] = array_merge($rules['acceptedExtensions'],
                    $thisRules['acceptedExtensions']);
            } else {
                $rules['acceptedExtensions'] = $thisRules['acceptedExtensions'];
            }

            // Merge "acceptedFiles"
            if (isset($rules['acceptedFiles'])) {
                $rules['acceptedFiles'] = array_merge($rules['acceptedFiles'], $thisRules['acceptedFiles']);
            } else {
                $rules['acceptedFiles'] = $thisRules['acceptedFiles'];
            }

            // Merge "maxFileSize"
            if (isset($rules['maxFileSize'])) {
                $rules['maxFileSize'] = max($rules['maxFileSize'], $thisRules['maxFileSize']);
            } else {
                $rules['maxFileSize'] = $thisRules['maxFileSize'];
            }

            // Merge "maxFiles"
            if (isset($rules['maxFiles'])) {
                $rules['maxFiles'] = max($rules['maxFiles'], $thisRules['maxFiles']);
            } else {
                $rules['maxFiles'] = $thisRules['maxFiles'];
            }

            // Merge "icon"
            if (isset($rules['icon'])) {
                if ($rules['icon'] != $thisRules['icon']) {
                    $rules['icon'] = 'file';
                }
            } else {
                $rules['icon'] = $thisRules['icon'];
            }
        }

        return $rules;
    }

    /**
     * Generate Config Path String
     *
     * @param string $configPath
     * @param bool   $section <ul><li>TRUE: Path is For a Section</li><li>FALSE: Path is For a File Type</li></ul>
     *
     * @return string This function will return a dot separated string to use in accessing a config
     */
    private static function generateConfigPath($configPath, $section = false)
    {
        $parts = self::translateFileTypeString($configPath);
        if ($section) {
            $parts = self::translateSectionString($configPath);
        } else {
            $parts = self::translateFileTypeString($configPath);
            array_splice($parts, 2, 0, 'fileTypes');
        }

        self::fetchConfigFromDb($parts);

        return self::configPartsToPath($parts);
    }

    /**
     * Follow up a config and search specified or default available value
     *
     * @param string $configPath Config Path (Dot Separated)
     * @param int    $checked    Number of Checked Levels
     *
     * @return mixed Found Config
     * @throws null if config not found
     */
    private static function followUpConfig($configPath, $checked = 0)
    {
        $existedPath = self::findExistedPath($configPath);
        if ($existedPath) {
            return Config::get($existedPath);
        }
    }

    /**
     * Searches for closest existed path
     *
     * @param string $configPath
     * @param int    $step Searching Step (Starts from 0)
     *
     * @return string
     */
    private static function findExistedPath($configPath, $step = 0)
    {
        $levels = explodeNotEmpty('.', $configPath);
        $mostPossibleLevel = pow(2, self::$defaultAcceptableLevelsNumber) - 1;

        if ($step <= $mostPossibleLevel) {
            $keysToBeChanged = self::getReplacementKeys($step, self::$defaultAcceptableLevelsNumber);
            $changed = false;

            foreach ($keysToBeChanged as $key) {
                if ($levels[$key + 1] != self::$defaultLevels[$key]) {
                    $levels[$key + 1] = self::$defaultLevels[$key];
                    $changed = true;
                }
            }

            if (!$step or $changed) {
                $newConfigPath = implode('.', $levels);
                if (Config::has($newConfigPath)) {
                    return $newConfigPath;
                }
            }

            return self::findExistedPath($configPath, $step + 1);
        }
    }

    /**
     * Returns keys that should be replaced by default value in every step
     *
     * @param int $stepNumber
     * @param int $replacementRange Number of Fields That Can Be Replaced by the Default Value
     *
     * @return array
     */
    private static function getReplacementKeys($stepNumber, $replacementRange)
    {
        $binary = str_pad(decbin($stepNumber), $replacementRange, 0, STR_PAD_LEFT);
        $binaryArr = str_split($binary);
        $filteredBinaryArr = array_filter($binaryArr, function ($var) {
            return $var;
        });
        $replacementKeys = array_keys($filteredBinaryArr);

        return $replacementKeys;
    }

    /**
     * @param \Intervention\Image\Image $image
     * @param integer                   $width
     * @param integer                   $height
     *
     * @return \Intervention\Image\Image
     */
    private static function safeResizeImage($image, $width, $height)
    {
        if ($width == $height) {
            if ($image->getWidth() >= $image->getHeight()) {
                $widthBased = false;
            } else {
                $widthBased = true;
            }
        } else if ($width < $height) {
            $widthBased = false;
        } else {
            $widthBased = true;
        }

        if ($widthBased) {
            $cropWidth = $image->getWidth();
            $cropHeight = floor(($cropWidth * $height) / $width);
        } else {
            $cropHeight = $image->getHeight();
            $cropWidth = floor(($cropHeight * $width) / $height);
        }

        $image->crop($cropWidth, $cropHeight);
        $image->resize($width, $height);

        return $image;
    }

    /**
     * @param UploadedFile $file
     * @param string       $fileName
     * @param string       $filePath
     *
     * @return array
     */
    public static function generateRelatedFiles($file, $fileName, $directory)
    {
        $mimeType = $file->getMimeType();
        $fileType = substr($mimeType, 0, strpos($mimeType, '/'));

        switch ($fileType) {
            case "image" : {
                $result = [];

                // creating thumbnail
                $thumbWidth = self::getTypeRule('image', 'thumbnail.width');
                $thumbHeight = self::getTypeRule('image', 'thumbnail.height');
                if ($thumbWidth and $thumbHeight) {
                    $thumbName = self::changeFileNameVersion($fileName, 'thumb');
                    $thumbPath = $directory . DIRECTORY_SEPARATOR . $thumbName;
                    self::createRelatedImage($file, $thumbPath, $thumbWidth, $thumbHeight);
                    $result['thumbnail'] = $thumbName;
                }

                $extraFiles = self::getTypeRule('image', 'extraFiles');
                if ($extraFiles and is_array($extraFiles) and count($extraFiles)) {
                    foreach ($extraFiles as $version => $extraFile) {
                        $extraFileConfigPath = 'extraFiles.' . $version;
                        $extraFileWidth = self::getTypeRule('image', $extraFileConfigPath . '.width');
                        $extraFileHeight = self::getTypeRule('image', $extraFileConfigPath . '.height');
                        if ($extraFileWidth and $extraFileHeight) {
                            $extraFileName = self::changeFileNameVersion($fileName, $version);
                            $extraFilePath = $directory . DIRECTORY_SEPARATOR . $extraFileName;
                            self::createRelatedImage($file, $extraFilePath, $extraFileWidth, $extraFileHeight);
                            $result[$version] = $extraFileName;
                        }
                    }
                }


                return $result;
            }
        }
    }


    /**
     * Returns fields that should be stored only for this file type
     *
     * @param UploadedFile $file
     *
     * @return array
     */
    public static function getFileTypeRelatedFields($file)
    {
        $mimeType = $file->getMimeType();
        $fileType = substr($mimeType, 0, strpos($mimeType, '/'));
        $result = [];

        switch ($fileType) {
            case "image" : {
                list($result['image_width'], $result['image_height']) = getimagesize($file->getPathname());

                return $result;
            }
        }

        return $result;
    }

    /**
     * If a config doesn't exists and it is possible to be read from db, it will be fetched from db.
     *
     * @param array $parts
     */
    private static function fetchConfigFromDb($parts)
    {
        if (!Config::has(self::configPartsToPath($parts))) {
            if (starts_with($parts[1], self::$postTypeConfigPrefix)) {
                $postTypeSlug = str_replace(self::$postTypeConfigPrefix, '', $parts[1]);
                $postType = Posttype::findBySlug($postTypeSlug);
                $postType->spreadMeta();
                $configsJson = $postType->upload_configs;
                $configs = json_decode($configsJson, true);
                if ($configs and is_array($configs)) {
                    $configPath = self::configPartsToPath(array_slice($parts, 0, 2));
                    \config([$configPath => $configs]);
                }
            }
        }
    }

    /**
     * Generates config path from parts array
     *
     * @param array $parts
     *
     * @return string
     */
    private static function configPartsToPath($parts)
    {
        return 'upload.' . implode('.', $parts);
    }

    /**
     * Returns $postTypeConfigPrefix (static variable of this class)
     *
     * @return string
     */
    public static function getPostTypeConfigPrefix()
    {
        return self::$postTypeConfigPrefix;
    }

    /**
     * Returns an <img /> element containing proper image file
     *
     * @param string|UploadedFileModel $file     File Identifier
     * @param string                   $version  Version of file to be shown
     * @param array                    $switches Switches to be user in showing file
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function getFileView($file, $version = 'original', $switches = [])
    {
        $switches = array_normalize($switches, [
            'style'           => [],
            'width'           => null,
            'height'          => null,
            'class'           => [],
            'otherAttributes' => [],
            'dataAttributes'  => [],
            'extra'           => '',
        ]);

        $file = self::smartFindFile($file, true);
        if ($file->exists) {
            $file->spreadMeta();
            $fileObj = self::getFileObject($file->pathname);
            if ($fileObj) {
                if (self::isImage($fileObj)) {
                    $relatedFilesPathnames = $file->related_files_pathname ?: [];

                    if (
                        array_key_exists($version, $relatedFilesPathnames) and // version exists in db
                        self::getFileObject($relatedFilesPathnames[$version]) // version exists in storage
                    ) {
                        $pathname = $relatedFilesPathnames[$version];
                    } else {
                        $pathname = $file->pathname;
                    }

                    $imgUrl = url($pathname);

                } else {
                    $fileType = substr($file->mime_type, 0, strpos($file->mime_type, '/'));
                    switch ($fileType) {
                        case "video":
                            $imageName = 'file-video-o.svg';
                            break;
                        case "audio":
                            $imageName = 'file-audio-o.svg';
                            break;
                        case "text":
                        case "application":
                        case "docs":
                            $imageName = 'file-text-o.svg';
                            break;
                        default:
                            $imageName = 'file-o.svg';
                            break;
                    }

                    $imgUrl = url('assets/images/template/' . $imageName);
//                return view('front.frame.widgets.icon-image-element'
//                    , array_merge(compact('fileType'), $switches));
                }
            }
        }

        if (isset($imgUrl)) {
            $fileExisted = true;
        } else {
            $fileExisted = false;
            $imgUrl = url('assets/images/template/chain-broken.svg');
        }

        return view(
            'front.frame.widgets.img-element',
            array_merge(compact('imgUrl', 'fileExisted'), $switches));
    }

    /**
     * Returns an <a /> element containing proper file link
     * If file doesn't exist in db or in storage, it will return "null"
     *
     * @param string|UploadedFileModel $file     File Identifier
     * @param string                   $version  Version of file to be reached by <a /> element
     * @param array                    $switches Switches to be user in showing <a /> element
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
    public static function getFileAnchor($file, $version = 'original', $switches = [])
    {
        $switches = array_normalize($switches, [
            'style'           => [],
            'width'           => null,
            'height'          => null,
            'class'           => [],
            'otherAttributes' => [],
            'dataAttributes'  => [],
            'extra'           => '',
        ]);

        $file = self::smartFindFile($file, true);
        if ($file->exists) {
            $file->spreadMeta();
            $fileObj = self::getFileObject($file->pathname);
            if ($fileObj) {
                $relatedFilesPathnames = $file->related_files_pathname ?: [];
//                $nameWithoutExtension = str_replace(
//                    '.' . $file->extension,
//                    '',
//                    $file->file_name
//                );
                $nameWithoutExtension = $file->name;

                if (
                    array_key_exists($version, $relatedFilesPathnames) and // version exists in db
                    self::getFileObject($relatedFilesPathnames[$version]) // version exists in storage
                ) {
                    $pathname = $relatedFilesPathnames[$version];
                    $fileNameWithoutExtension = $nameWithoutExtension . '_' . $version;
                } else {
                    $pathname = $file->pathname;
                    $fileNameWithoutExtension = $nameWithoutExtension;
                }

                $fileUrl = url($pathname);
                $fileName = $nameWithoutExtension . '.' . $file->extension;

                return view(
                    'front.frame.widgets.a-element',
                    array_merge(compact('fileUrl', 'fileName'), $switches)
                );
            }
        }

        return null;
    }

    /**
     * Returns file url
     * If the file doesn't exist on db, it will be return "null"
     *
     * @param string|UploadedFileModel $file File Identifier
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|null|string
     */
    public static function getFileUrl($file)
    {
        $file = self::smartFindFile($file, true);
        if ($file->exists) {
            return url($file->pathname);
        }
        return null;
    }

    /**
     * Returns \Symfony\Component\HttpFoundation\File\File which exists on $pathname
     * If there isn't any file in specified pathname, it will be return "null"
     *
     * @param string $pathname Pathname of file to create file object
     *
     * @return null|\Symfony\Component\HttpFoundation\File\File
     */
    public static function getFileObject($pathname)
    {
        if (FilesFacades::exists($pathname)) {
            return new File($pathname);
        }

        return null;
    }

    /**
     * Checks if specified file is an object
     *
     * @param string|UploadedFileModel $file File Identifier
     *
     * @return bool
     */
    public static function isImage($file)
    {
        $validator = Validator::make([
            'file' => $file,
        ], [
            'file' => 'image',
        ]);

        return !$validator->fails();
    }

    /**
     * Find file with multiple types of identifiers
     *
     * @param         $identifier
     * @param boolean $checkDirectId If false, file will not be searched with id of db table
     *
     * @return UploadedFileModel
     */
    public static function smartFindFile($identifier, $checkDirectId = false)
    {
        if ($identifier instanceof UploadedFileModel) {
            $file = $identifier;
        } else if (is_numeric($identifier) and $checkDirectId) {
            $file = UploadedFileModel::find($identifier);
        } else if (count($dehashed = hashid_decrypt($identifier, 'ids')) and
            is_numeric($id = $dehashed[0])
        ) {
            $file = UploadedFileModel::find($id) ?: new UploadedFileModel();
        } else {
            $file = new UploadedFileModel();

        }

        return $file;
    }

    /**
     * Sets $temporaryFolderName
     *
     * @param string $folder
     */
    public static function setTemporaryFolderName($folder)
    {
        self::$temporaryFolderName = $folder;
    }

    /**
     * Rectifies directory name string relating on operating system
     *
     * @param string $directory
     *
     * @return mixed
     */
    public static function rectifyDirectory($directory)
    {
        return preg_replace("/\/|\\\\/", DIRECTORY_SEPARATOR, $directory);
    }

    /**
     * Returns file eloquent that matched with $pathname
     *
     * @param string $pathname Pathname (starting after public folder)
     *
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public static function findFileByPathname($pathname)
    {
        $conditions['directory'] = pathinfo($pathname, PATHINFO_DIRNAME);
        $conditions['physical_name'] = pathinfo($pathname, PATHINFO_BASENAME);
        $file = UploadedFileModel::where($conditions)->first();

        if ($file and $file->exists) {
            return $file;
        }

        return null;
    }

    /**
     * Generates a new image from $sourceFile, in size of $width*$height, at $pathname
     *
     * @param \Symfony\Component\HttpFoundation\File\File $sourceFile File Object to Make Clone From It
     * @param string                                      $pathname   Pathname to save new image at it
     * @param integer                                     $width      Width of Result Image
     * @param integer                                     $height     Height of Result Image
     */
    private static function createRelatedImage($sourceFile, $pathname, $width, $height)
    {
        $newFile = Image::make($sourceFile->getPathname());
        $newFile = self::safeResizeImage($newFile, $width, $height);
        $newFile->save($pathname);
    }
}
