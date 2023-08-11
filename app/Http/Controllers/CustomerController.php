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

    public function store(Request $request)
    {
        $customer = new Customer;
        $customer->nama = $request->modalNama;
        $customer->alamat = $request->modalAlamat;
        $customer->instansi = $request->modalInstansi;
        $customer->telepon = $request->modalTelepon;
        $customer->infoPelanggan = $request->modalInfoPelanggan;
        $customer->save();

        return response()->json(['success' => 'true', 'message' => 'Data berhasil ditambahkan']);
    }

}
