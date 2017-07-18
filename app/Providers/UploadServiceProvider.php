<?php

namespace App\Providers;

use App\Http\Controllers\DropzoneController;
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
        return self::followUpConfig(self::generateConfigPath($section, true) . ".$configName");
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
            return view('uploader.box', compact(
                    'fileType',
                    'preloaderView',
                    'uploadIdentifier'
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
    public static function validateFile($request)
    {
        $file = $request->file;
        $typeString = $request->_uploadIdentifier;
        $sessionName = $request->_groupName;

        $acceptedExtensions = self::getTypeRule($typeString, 'acceptedExtensions');
        if (!$acceptedExtensions or !is_array($acceptedExtensions) or !count($acceptedExtensions)) {
            $acceptedExtensions = [];
        }

        $validator = Validator::make($request->all(), [
            'file' => 'mimes:' . implode(',', $acceptedExtensions) .
                '|max:' . (self::getTypeRule($typeString, 'maxFileSize') * 1024)
        ]);

        if (!$validator->fails() and
            self::validateFileNumbers($sessionName, $typeString)
        ) {
            return true;
        }

        return false;
    }

    /**
     * Checks if files number for an uploader reached the limit
     *
     * @param string $sessionName
     * @param string $typeString
     */
    public static function validateFileNumbers($sessionName, $typeString)
    {
        if (!session()->has($sessionName) or
            count(array_filter(session()->get($sessionName), function ($item) {
                return $item['done'];
            })) < self::getTypeRule($typeString, 'maxFiles')
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
    public static function uploadFile($file, $uploadDir)
    {
        $newName = self::generateFileName() . '.' . $file->getClientOriginalExtension();
        $finalUploadDir = implode(DIRECTORY_SEPARATOR, [
            self::$rootUploadDir,
            self::$temporaryFolderName,
            $uploadDir
        ]);

        // Save uploaded file to db
        $id = UploadedFileModel::saveFile($file, [
            'physical_name' => $newName,
            'directory'     => $finalUploadDir,
        ]);

        // Move uploaded file to target directory
        $file->move($finalUploadDir, $newName);

        return $id;
    }

    /**
     * Remove File Physically
     *
     * @param string|UploadedFileModel $file
     *
     * @return bool
     */
    public static function removeFile($file)
    {
        if (!$file instanceof UploadedFileModel) {
            $file = UploadedFileModel::findByHashid($file);
        }

        if ($file->exists and
            FilesFacades::exists($file->pathname) and
            $file->hasStatus('temp')
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

            $file->delete();

            return FilesFacades::delete($pathname);
        }
    }

    /**
     * Searches for uploaded files and move theme from temporary folder
     *
     * @param \App\Http\Requests\Front\CommentRequest $request
     */
    public static function moveUploadedFiles(&$request)
    {
        foreach ($request->all() as $index => $value) {
            if (ends_with($index, '_files')) {
                $filesHashids = json_decode($value, true);
                if ($filesHashids and is_array($filesHashids)) {
                    foreach ($filesHashids as $key => $fileHashid) {
                        $fileRow = UploadedFileModel::findByHashid($fileHashid);
                        if ($fileRow->exists() and FilesFacades::exists($fileRow->pathname)) {
                            $newDir = str_replace(self::$temporaryFolderName . DIRECTORY_SEPARATOR, '', $fileRow->directory);

                            $fileRow->setStatus('used');

                            $file = new File($fileRow->pathname);
                            $file->move($newDir);
                            $fileRow->directory = $newDir;

                            $movedFile = new File($newDir . DIRECTORY_SEPARATOR . $file->getFilename());

                            $fileRow = $fileRow->toArray();
                            $fileRow['related_files'] = self::generateRelatedFiles($movedFile, $movedFile->getFilename(), $newDir);

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
                }
                $request->merge([$index => json_encode($filesHashids)]);
            }
        }
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
    }

    public static function getThumb($fileUrl, $thumbFolder = 'thumbs')
    {
        return str_replace_last('/', "/$thumbFolder/", $fileUrl);
    }

    /**
     * Convert $fileTypeString to a meaning full array
     *
     * @param string $fileTypeString
     *
     * @return array
     */
    private static function translateFileTypeString($fileTypeString)
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
    private static function translateSectionString($sectionString)
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

        return 'upload.' . implode('.', $parts);
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
        } else if ($width = $height) {
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
    private static function generateRelatedFiles($file, $fileName, $directory)
    {
        $mimeType = $file->getMimeType();
        $fileType = substr($mimeType, 0, strpos($mimeType, '/'));

        switch ($fileType) {
            case "image" : {
                // creating thumbnail
                $thumbWidth = self::getTypeRule('image', 'thumbnail.height');
                $thumbHeight = self::getTypeRule('image', 'thumbnail.height');
                $thumbName = self::changeFileNameVersion($fileName, 'thumb');
                $thumbPath = $directory . DIRECTORY_SEPARATOR . $thumbName;

                $thumbnail = Image::make($file->getPathname());
                $thumbnail = self::safeResizeImage($thumbnail, $thumbWidth, $thumbHeight);
                $thumbnail->save($thumbPath);

                return [
                    'thumbnail' => $thumbName,
                ];
            }
        }
    }
}
