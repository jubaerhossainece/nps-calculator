<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class ImageService
{
    public function compress($image, $compressed_width = 400)
    {
        $image = Image::make($image);

        // compress if image width is greater than 400px
        if($image->width() > $compressed_width){
            $image = $image->resize($compressed_width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        return $image->stream();
    }


    public function upload($file_to_upload, $extension, $path_to_upload = 'public', $previous_file_name = null){
        $filename_with_ext = time() . '.' . $extension;

        // delete previous image if exists
        if ($previous_file_name) {
            if (Storage::exists($path_to_upload . $previous_file_name)) {
                Storage::delete($path_to_upload . $previous_file_name);
            }
        }

        // upload file using storage facade
        Storage::put($path_to_upload . $filename_with_ext, $file_to_upload);

        // check if file is being uploaded
        if(Storage::exists($path_to_upload . $filename_with_ext)){
            return $filename_with_ext;
        }

        return null;
    }
}
