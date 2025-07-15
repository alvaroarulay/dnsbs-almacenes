<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use ZipArchive;
use Illuminate\Support\Facades\Storage;

class ZipArchiveController extends Controller
{
    public function downloadZip()
    {        
        try {
            $zip = new ZipArchive;
            $date = Carbon::now();
            $database = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host = env('DB_HOST');
            $backupPath = storage_path('app/backups');
            $fileName = $date->format('Ymd') . '.sql';
            $command = "mysqldump --user={$username} {$database} > {$backupPath}/{$fileName}";
    
            exec($command); 

            return response()->download(storage_path('app/backups/'.$fileName));
            
            } catch (Exception $e) {

               return print_r("hubo un error");
            }
            
    }
}
