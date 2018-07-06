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
            if ($height > $width) {
              //resize to width and constraint then crop to meet size
                return $image->resize($size, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(public_path($filepath .'/'. $filename));
            } elseif ($height < $width) {
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
            if ($height > $width) {
              //resize to width and constraint then crop to meet size
                return $image->resize($size, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->crop($size, $size)->save(public_path($filepath .'/'. $filename));
            } elseif ($height < $width) {
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
    if (request()->file('file')) {
        //call resize and crop images function
            $file = request()->file('file');
            $origFilePath = request()->savePath;
            $filename = md5($file->getClientOriginalName());
            $filetype = $file->getClientOriginalExtension();
            storeImages($file, $origFilePath);
        //end
            
        $data['path'] = $origFilePath;
        $data['file_name'] = $filename.'.'.$filetype;

        $statusCode = 200;
    }
}

    
