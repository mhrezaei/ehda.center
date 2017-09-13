<?php

namespace App\Models;

use App\Providers\UploadServiceProvider;
use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile as UploadedFileIlluminate;

class File extends Model
{
    use TahaModelTrait, SoftDeletes;

    public static $meta_fields = [
        'image_width',
        'image_height',
        'resolution', // for images and videos
        'title',
        'alternative',
        'description',
        'related_files',
    ];
    protected $guarded = ['id'];
    protected $casts = [
        'published_at'   => 'datetime',
        'meta'           => "array",
        'relative_files' => "relative_files",
    ];
    private static $statusesNames = [
        1 => 'temp',
        2 => 'used',
    ];

    /*
    |--------------------------------------------------------------------------
    | Storing
    |--------------------------------------------------------------------------
    */

    /**
     *
     * Saves an uploaded file in DB
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param array                         $data
     */
    public static function saveFile($file, $data = [])
    {
        if ($file instanceof UploadedFileIlluminate) {
            $data = array_normalize_keep_originals($data, [
//                'name'          => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'name'          => implode('.',array_splice(preg_split('/\.(?=[^\.]*$)/', $file->getClientOriginalName()), 0,1)),
                'physical_name' => UploadServiceProvider::generateFileName() .
                    '.'
                    . $file->getClientOriginalExtension(),
                'directory'     => 'uploads',
                'mime_type'     => $file->getClientMimeType(),
                'extension'     => $file->getClientOriginalExtension(),
                'size'          => $file->getSize(),
                'status'        => self::getStatusValue('temp'),
                'posttype'      => null,
                'category'      => null,
                'folder'        => null,
            ]);

            $fileTypeRelatedFields = UploadServiceProvider::getFileTypeRelatedFields($file);
            $data = array_merge($data, $fileTypeRelatedFields);

            $relatedFiles = UploadServiceProvider::generateRelatedFiles(
                $file,
                $data['physical_name'],
                $data['directory']
            );
            if ($relatedFiles) {
                $data['related_files'] = $relatedFiles;
            }

            return self::store($data);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    |
    */

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category');
    }

    public function folder()
    {
        return $this->belongsTo('App\Models\Folder', 'folder');
    }

    public function posttype()
    {
        return $this->belongsTo('App\Models\Posttype', 'posttype');
    }


    /*
    |--------------------------------------------------------------------------
    | Modification
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | Assessors
    |--------------------------------------------------------------------------
    */

    /**
     * Returns Pathname (Concatenation of "directory" and "physical_name")
     *
     * @return string
     */
    public function getPathnameAttribute()
    {
        return implode(DIRECTORY_SEPARATOR, [
            $this->directory,
            $this->physical_name,
        ]);
    }

    /**
     * Returns names of related files
     *
     * @return array|null
     */
    public function getRelatedFilesAttribute()
    {
        return $this->meta('related_files');
    }

    /**
     * Returns pathname of related files
     *
     * @return array|mixed
     */
    public function getRelatedFilesPathnameAttribute()
    {
        $relatedFiles = $this->related_files;
        if ($relatedFiles and is_array($relatedFiles)) {
            foreach ($relatedFiles as $key => $relatedFile) {
                $relatedFiles[$key] = $this->directory . DIRECTORY_SEPARATOR . $relatedFile;
            }
        }

        return $relatedFiles;
    }


    public function getCategoryEloquentAttribute()
    {
        return $this->category()->first();
    }

    public function getFolderEloquentAttribute()
    {
        $category = $this->category_eloquent;
        if ($category and $category->exists) {
            return $category->folder;
        } else {
            return $this->folder()->first();
        }
    }

    public function getPosttypeEloquentAttribute()
    {
        $folder = $this->folder_eloquent;
        if ($folder and $folder->exists) {
            return $folder->posttype;
        } else {
            return $this->posttype()->first();
        }
    }

    public function getFileNameAttribute()
    {
        return $this->name . '.' . $this->extension;
    }


    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Sets the status of file with status name
     *
     * @param string $statusName
     *
     * @return self
     */
    public function setStatus($statusName)
    {
        $this->status = self::getStatusValue($statusName);
        return $this;
    }

    /**
     * Compares the status of file with value of given status name
     *
     * @param string $statusName
     *
     * @return bool
     */
    public function hasStatus($statusName)
    {
        return ($this->status == self::getStatusValue($statusName)) ? true : false;
    }

    /**
     * Returns name of a related file with specified key
     *
     * @param string $relatedFileKey Key of related file to be returned
     *
     * @return string|null
     */
    public function getRelatedFile($relatedFileKey)
    {
        $relatedFiles = $this->related_files;
        if (array_key_exists($relatedFileKey, $relatedFiles)) {
            return $relatedFiles[$relatedFileKey];
        }

        return null;
    }

    /**
     * Returns pathname of a related file with specified key
     *
     * @param string $relatedFileIdentifier Key/Name of related file to be returned
     *
     * @return null|string
     */
    public function getRelatedFilePathname($relatedFileIdentifier)
    {
        $relatedFiles = $this->related_files;
        if (array_key_exists($relatedFileIdentifier, $relatedFiles)) {
            $relatedFileName = $relatedFiles[$relatedFileIdentifier];
        } else if (($relatedFileKey = in_array($relatedFileIdentifier, $relatedFiles)) !== false) {
            $relatedFileName = $relatedFileIdentifier;
        } else {
            return null;
        }

        return implode(DIRECTORY_SEPARATOR, [
            $this->directory,
            $relatedFileName,
        ]);
    }

    /**
     * @param string                $task
     * @param null|\App\Models\User $user
     *
     * @return boolean
     */
    public function can($task)
    {
        if (auth()->guest()) {
            return false;
        }

        $postType = $this->posttype_eloquent;

        $isCreator = $this->creator->id == user()->id;
        $admin = user()->as('admin');

        switch ($task) {
            case 'preview':
                if (
                    // Current user owned this file
                    $isCreator or
                    // Current user has permission to edit files in file-manager
                    $admin->can('file-manager.edit') or
                    // Current user has permission to delete files in file-manager
                    $admin->can('file-manager.delete') or
                    (
                        // This file is uploaded for a posttype, folder or category
                        $postType and
                        $postType->exists and
                        (
                            // Current user has permission to edit in this file's posttype
                            $postType->can('edit') or
                            // Current user has permission to publish in this file's posttype
                            $postType->can('publish')
                        )
                    )
                ) {
                    return true;
                } else {
                    return false;
                }
                break;

            case 'edit':
                if (
                    // Current user owned this file
                    $isCreator or
                    // Current user has permission to edit files in file-manager
                    $admin->can('file-manager.edit') or
                    (
                        // This file is uploaded for a posttype, folder or category
                        $postType and
                        $postType->exists and
                        (
                            // Current user has permission to edit in this file's posttype
                            $postType->can('edit') or
                            // Current user has permission to publish in this file's posttype
                            $postType->can('publish')
                        )
                    )
                ) {
                    return true;
                } else {
                    return false;
                }
                break;

            case 'delete':
                if (
                    // Current user owned this file
                    $isCreator or
                    // Current user has permission to edit files in file-manager
                    $admin->can('file-manager.delete')
                ) {
                    return true;
                } else {
                    return false;
                }
                break;
        }

        if ($postType->exists) {
            return false;
        } else {
            return true;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Static Data
    |--------------------------------------------------------------------------
    */

    /**
     * Returns numeric value of specified $statusName
     *
     * @param string $statusName
     *
     * @return integer|string
     */
    public static function getStatusValue($statusName)
    {
        return array_search($statusName, self::$statusesNames);
    }

}

