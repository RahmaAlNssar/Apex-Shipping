<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait uploadImage
{

    public function uploads($file, $path)
    {
        if ($file) {


            $file_name  = $file->hashName();
            $file_type  = $file->getClientOriginalExtension();

            Storage::disk('public')->put($path . $file_name, File::get($file));


            return $file = [
                'fileName' => $file_name,
                'fileType' => $file_type,
                'filePath' => $path,
                'fileSize' => $this->fileSize($file),
                // 'pdf'      =>$pdf
            ];
        }
    }

    public function fileSize($file, $precision = 2)
    {
        $size = $file->getSize();

        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        }

        return $size;
    }



    public function removeFile($file, $folder)
    {
        if (File::exists('storage/uploads/' . $folder . '/' . $file)) {
            unlink('storage/uploads/' . $folder . '/' . $file);
        }
    }
}