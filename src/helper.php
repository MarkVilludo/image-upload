<?php
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;

if (!function_exists('imageUpload')) {
    /**
     * Get the Hanap instance
     *
     * @return \MarkVilludo\Hanap\Hanap
     */
    function imageUpload()
    {
        return app(\MarkVilludo\ImageUpload\ImageUpload::class);
    }
}

if (!function_exists('resizeAndSave')) {
    /**
     * Help resize images regardless of image dimension then save
     *
     * @param file $file
     * @param int $size
     * @param string $filepath
     * @param string $filename
     * @return response
     */
    function resizeAndSave($file, $size, $filepath, $filename)
    {

        $image = Image::make($file);
        $height = $image->height();
        $width = $image->width();

        if (($height > $size && $width > $size) || $height > $size || $width > $size) {
            if ($height < $width) {
              //resize to width and constraint then crop to meet size
                return $image->resize($size, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(public_path($filepath .'/'. $filename));
            } elseif ($height > $width) {
              //resize to height and contrain, crop, then save
                return $image->resize(null, $size, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(public_path($filepath .'/'. $filename));
            } else {
                return $image->resize($size, $size)->save(public_path($filepath .'/'. $filename));
            }
        } else {
            return $image->save(public_path($filepath .'/'. $filename));
        }
    }
}

if (!function_exists('resizeCropSquareAndSave')) {
    /**
     * Help crop and resize images to 1x1 dimension(square) then save
     *
     * @param file $file
     * @param int $size
     * @param string $filepath
     * @param string $filename
     * @return response
     */
    function resizeCropSquareAndSave($file, $size, $filepath, $filename)
    {

        $image = Image::make($file);
        $height = $image->height();
        $width = $image->width();

        if (($height > $size && $width > $size) || $height > $size || $width > $size) {
            if ($height < $width) {
              //resize to width and constraint then crop to meet size
                return $image->resize($size, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->crop($size, $size)->save(public_path($filepath .'/'. $filename));
            } elseif ($height > $width) {
              //resize to height and contrain, crop, then save
                return $image->resize(null, $size, function ($constraint) {
                    $constraint->aspectRatio();
                })->crop($size, $size)->save(public_path($filepath .'/'. $filename));
            } else {
                return $image->resize($size, $size)->save(public_path($filepath .'/'. $filename));
            }
        } else {
            return $image->save(public_path($filepath .'/'. $filename));
        }
    }
}

if (!function_exists('saveOriginal')) {
    /**
     * Save images to folder
     *
     * @param file $file
     * @param string $filepath
     * @param string $filename
     * @return response
     */
    function saveOriginal($file, $filepath, $filename)
    {

        $image = Image::make($file);
        return $image->save(public_path($filepath .'/'. $filename));
    }
}
if (!function_exists('storeImages')) {
    //Dynamic store image with original path and file
    function storeImages($file, $origFilePath)
    {
        $filename = md5($file->getClientOriginalName());
        $filetype = $file->getClientOriginalExtension();
        $origFileName = $filename.'.'.$filetype;
        $original = $origFilePath .'/original'; // maxres
        $standard = $origFilePath .'/standard';
        $high = $origFilePath .'/high';
        $medium = $origFilePath .'/medium';
        $small = $origFilePath .'/small';
        $xsmall = $origFilePath .'/xsmall'; // to support old versions
        $default = $origFilePath .'/default'; // same with xsmall

        if (!file_exists(public_path().'/'.$original)) {
          mkdir(public_path().$original, 0777, true);
        }
        if (!file_exists(public_path().'/'.$standard)) {
            mkdir(public_path().$standard, 0777, true);
        }
        if (!file_exists(public_path().'/'.$high)) {
            mkdir(public_path().$high, 0777, true);
        }
        if (!file_exists(public_path().'/'.$medium)) {
            mkdir(public_path().$medium, 0777, true);
        }
        if (!file_exists(public_path().'/'.$small)) {
            mkdir(public_path().$small, 0777, true);
        }
        if (!file_exists(public_path().'/'.$xsmall)) {
            mkdir(public_path().$xsmall, 0777, true);
        }
        if (!file_exists(public_path().'/'.$default)) {
            mkdir(public_path().$default, 0777, true);
        }

        // $path = URL($filePath150 . $fileName150);

        if (!file_exists(public_path().'/'.$original.'/'.$origFileName)) {
            // $size = 1080;
            saveOriginal($file, $original, $origFileName);
        }

        if (!file_exists(public_path().'/'.$standard.'/'.$origFileName)) {
            $size = 640;
            resizeAndSave($file, $size, $standard, $origFileName);
        }

        if (!file_exists(public_path().'/'.$high.'/'.$origFileName)) {
            $size = 480;
            resizeAndSave($file, $size, $high, $origFileName);
        }

        if (!file_exists(public_path().'/'.$medium.'/'.$origFileName)) {
            $size = 320;
            resizeAndSave($file, $size, $medium, $origFileName);
        }

        if (!file_exists(public_path().'/'.$small.'/'.$origFileName)) {
            $size = 240;
            resizeAndSave($file, $size, $small, $origFileName);
        }

        if (!file_exists(public_path().'/'.$xsmall.'/'.$origFileName)) {
            $size = 120;
            resizeAndSave($file, $size, $xsmall, $origFileName);
        }

        if (!file_exists(public_path().'/'.$default.'/'.$origFileName)) {
            $size = 120;
            resizeAndSave($file, $size, $default, $origFileName);
        }

        $data = [
            'filename' => $origFileName,
            'original_path' => $original.'/'.$origFileName,
            'standard_path' => $standard.'/'.$origFileName,
            'high_path' => $high.'/'.$origFileName,
            'medium_path' => $medium.'/'.$origFileName,
            'small_path' => $small.'/'.$origFileName,
            'xsmall_path' => $xsmall.'/'.$origFileName,
            'default_path' => $default.'/'.$origFileName,
        ];
        return $data;
    }

    
}

if (!function_exists('storeImagesOnSize')) {
    //Dynamic store image with original size, path and file
    function storeImagesOnSize($file, $origFilePath, $size)
    {
        $filename = md5($file->getClientOriginalName());
        $filetype = $file->getClientOriginalExtension();
        $origFileName = $filename.'.'.$filetype;

        if (!file_exists(public_path().'/'.$origFilePath)) {
          mkdir(public_path().$origFilePath, 0777, true);
        }

        if (!file_exists(public_path().'/'.$origFilePath.'/'.$origFileName)) {
            resizeAndSave($file, $size, $origFilePath, $origFileName);
        }

        $data = [
            'filename' => $origFileName,
            'filepath' => $origFilePath.'/'.$origFileName,
        ];
        return $data;
    }
}

if (!function_exists('storeSingleImage')) {
    //Dynamic store image with original path and file
    function storeSingleImage($file, $origFilePath)
    {
        $filename = md5($file->getClientOriginalName());
        $filetype = $file->getClientOriginalExtension();
        $origFileName = $filename.'.'.$filetype;
        $original = $origFilePath;

        if (!file_exists(public_path().'/'.$original)) {
          mkdir(public_path().$original, 0777, true);
        }
     
        // $path = URL($filePath150 . $fileName150);
        if (!file_exists(public_path().'/'.$original.'/'.$origFileName)) {
            // $size = 1080;
            saveOriginal($file, $original, $origFileName);
        }

      
        $data = [
            'filename' => $origFileName,
            'original_path' => $original.'/'.$origFileName
        ];
        return $data;
    }
}





    
