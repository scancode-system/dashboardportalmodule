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
            self::products();
            self::logo();
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

    public static function products()
    {
        $files = Storage::files('token/images/produtos');
        foreach ($files as $file) {
            $file_name = str_replace('token/images/produtos/', '', $file);
            if(Storage::exists('public/produtos/'.$file_name)){
                Storage::delete('public/produtos/'.$file_name);
            }
            Storage::copy($file, 'public/produtos/'.$file_name);
        }
    }

    public static function logo()
    {
        Storage::disk('public')->put('companies/logo/logo.png', file_get_contents(storage_path('app/token/images/logo/logo.png')), 'public');
        CompanyRepository::updateLogo('storage/companies/logo/logo.png');
    }

}
