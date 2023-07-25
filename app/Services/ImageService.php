<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class ImageService
{

    private $driver;

    public function __construct($driver = "public")
    {
        $this->driver = $driver;
    }


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


    public function upload($file_to_upload, $filename, $path_to_upload = '/', $previous_file_name = null){
        // delete previous image if exists
        if ($previous_file_name) {
            if (Storage::disk($this->driver)->exists($path_to_upload .'/'. $previous_file_name)) {
                Storage::disk($this->driver)->delete($path_to_upload .'/'. $previous_file_name);
            }
        }

        // upload file using storage facade
        Storage::disk($this->driver)->put($path_to_upload .'/'. $filename, $file_to_upload);

        // check if file is being uploaded
        if(Storage::disk($this->driver)->exists($path_to_upload .'/'. $filename)){
            return $filename;
        }

        return null;
    }
}
