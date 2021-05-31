<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data=Customer::all();
        return response($data,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('import');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'file' => 'required|mimes:csv,xlsx',
        ]);
        if ($request->file('file')) {
            $data = file($request->file);
            $header = $data[0];
            // unlink($data[0]);

            //chunking file
            $chunks = array_chunk($data, 100000);
            foreach ($chunks as $key => $chunk) {
                $name = "/tmp{$key}.csv";
                $path = resource_path('pending-files');
                file_put_contents($path . $name, $chunk);
            }
            $path = resource_path("pending-files/*.csv");
            $files = glob($path);
            foreach ($files as $index => $file) {
                $data = array_map('str_getcsv', file($file));
                if ($index == 0) {
                    $header = $data[0];
                }
                foreach ($data as $i => $customer) {
                    if ($i == 1) {
                        $cumData = array_combine($header, $customer);
                        Customer::Create($cumData);
                    }

                }

            }

        }

        session()->flash('success', 'queue for importing');
        return redirect('import');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }
}