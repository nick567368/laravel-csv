<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'time_ref',
        'account',
        'code',
        'country_code',
        'product_type',
        'value',
        'status',
    ];

    public function importToDb()
    {
        $path = resource_path("pending-files/*.csv");
         $files = glob($path);
        foreach ($files as $file) {
            $data = array_map('str_getcsv', file($file));
            
            foreach ($data as $key => $row) {
                Customer::Create([
                    'time_ref' => $row[0],
                    'account' => $row[1],
                    'code' => $row[2],
                    'country_code' => $row,
                    'product_type' => $row[4],
                    'value' => $row[5],
                    'status' => $row[6],
                ]);

            }

        }
    }
}