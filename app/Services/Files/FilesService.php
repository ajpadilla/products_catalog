<?php


namespace App\Services\Files;
use Illuminate\Http\UploadedFile;

class FilesService
{
    /**
     * @param UploadedFile $file
     * @param string $sku
     * @param string $path
     * @return string
     */
    public function makeProductImage(UploadedFile $file, string $sku, string $path)
    {
        $file_name  = $sku.".".$file->getClientOriginalName();
        $file->move(public_path($path), $file_name);
        return $path.$file_name;
    }
}
