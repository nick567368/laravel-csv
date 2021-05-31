<?php

namespace App\Jobs;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;
use File;
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
        //
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        Redis::throttle('upload-csv')->allow(1)->every(1000)->then(function () {
            dump('processing this file',$this->file);
            $data = array_map('str_getcsv', file($this->file));
            foreach ($data as $row) {
                Customer::updateOrCreate([
                    'time_ref' => $row[0],
                    'account' => $row[1],
                    'code' => $row[2],
                    'country_code' => $row[3],
                    'product_type' => $row[4],
                    'value' => $row[5],
                    'status' => $row[6],
                ]);
            }
            dump('done this file', $this->file);

            unlink('pending-files/'.$this->file);
            // Handle job...
        }, function () {
            // Could not obtain lock...

            return $this->release(5);
        });

    }
}
