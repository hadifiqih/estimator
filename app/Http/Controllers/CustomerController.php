<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function search(Request $request)
    {
        $data = Customer::where('nama', 'LIKE', "%".request('q')."%")->get();
        return response()->json($data);
    }

    public function searchById(Request $request)
    {
        $data = Customer::where('id', 'LIKE', "%".request('id')."%")->get();
        return response()->json($data);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $customer = new Customer;
        $customer->nama = $request->modalNama;
        $customer->alamat = $request->modalAlamat;
        $customer->telepon = $request->modalTelepon;
        $customer->infoPelanggan = $request->modalInfoPelanggan;
        $customer->save();

        return response()->json(['success' => 'true', 'message' => 'Data berhasil ditambahkan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }


}
