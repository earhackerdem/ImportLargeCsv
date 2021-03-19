<?php

namespace App\Models;

use App\Jobs\ProcessCsvUpload;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Insurance extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function importToDb()
    {
        $path = resource_path('pending-files/*.csv');

        $files = glob($path);

        foreach($files as $file){

            ProcessCsvUpload::dispatch($file);
        }

    }
}
