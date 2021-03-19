<?php

namespace App\Jobs;

use App\Models\Insurance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class ProcessCsvUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $file;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Redis::throttle('upload-csv')->allow(1)->every(20)->then(function () {
            dump('procesando el archivo:---',$this->file);
            $data = array_map('str_getcsv',file($this->file));

            foreach($data as $row){
                Insurance::updateOrCreate([
                    'policy_id' => $row[0]
                ],[
                    'county' => $row[1],
                    'lat' => $row[2],
                    'lng' => $row[3],
                ]);
            }
            dump('Se ha procesado el archivo:---',$this->file);
            unlink($this->file);
        }, function () {

            return $this->release(10);
        });


    }
}
