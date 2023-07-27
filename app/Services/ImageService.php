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
        $file = Image::make($image);

        // compress if image width is greater than 400px
        if($file->width() > $compressed_width){
            $file = $file->resize($compressed_width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        return $file->stream();
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
