<?php

namespace Modules\DashboardPortal\Services;

use Illuminate\Support\Facades\Storage;
use Modules\Dashboard\Repositories\CompanyRepository;
use  ZipArchive;

class FileService {

    public static function storage($data){
        if($data !== false)
        {
            self::clear();
            Storage::disk('local')->put('token/files.zip', $data, 'public');
            self::extract(storage_path('app/token/files.zip'));
            unlink(storage_path('app/token/files.zip'));
        }
    }

    public static function extract($path)
    {
        $zip = new ZipArchive;
        if ($zip->open($path) === TRUE) {
            $zip->extractTo(storage_path('app/token'));
            $zip->close();
        } 
    }    

    public static function clear()
    {
        $files = glob(storage_path('app/token/*'));
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file);
        } 
    }

}
