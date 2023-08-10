<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Sales;

class AuthController extends Controller
{
    //Menampilkan halaman login
    public function index() {
        return view('auth.login');
    }

    public function create()
    {
        $sales = Sales::all();
        return view('auth.register', compact('sales'));
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //cek apakah email dan password benar
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            //menyimpan data pengguna ke dalam session
            $request->session()->put('user', $user);
            //jika email dan password benar
            return redirect()->route('antrian.index')->with('success', 'Login berhasil !');
        }

        //jika email dan password salah
        return redirect()->route('auth.login')->with('error', 'Email atau password salah !');

    }

    public function logout(){
        //logout user
        Auth::logout();

        //kembalikan ke halaman login
        return redirect()->route('auth.login')->with('logout', 'Logout berhasil !');
    }

    public function store(Request $request)
    {

        //Membuat rules validasi
        $rules = [
            'nama' => 'required|min:5|max:50',
            'email' => 'required|email|unique:users',
            'telepon' => 'required|min:10|max:13',
            'password' => 'required|min:8|max:35',
            'tahunMasuk' => 'required',
            'divisi' => 'required',
            'lokasi' => 'required',
            'terms' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return redirect()->route('auth.register')->withErrors($validator)->withInput();
        }

        //menentukan lokasi kerja
        $tempatKerja = $request->lokasi;
        if ($tempatKerja == "Surabaya"){
            $tempatKerja = "1";
        }else if ($tempatKerja == "Malang"){
            $tempatKerja = "2";
        }else if ($tempatKerja == "Kediri"){
            $tempatKerja = "3";
        }
        $indexTempatKerja = $request->lokasi;

        //menentukan divisi
        $divisi = $request->divisi;
        if ($divisi == "CEO"){
            $divisi = "1";
        }else if ($divisi == "Direktur"){
            $divisi = "2";
        }else if ($divisi == "HRD"){
            $divisi = "3";
        }else if ($divisi == "Keuangan"){
            $divisi = "4";
        }else if ($divisi == "Marketing Online"){
            $divisi = "5";
        }else if ($divisi == "Sales"){
            $divisi = "6";
        }else if ($divisi == "Produksi Stempel"){
            $divisi = "7";
        }else if ($divisi == "Produksi Advertising"){
            $divisi = "8";
        }else if ($divisi == "Gudang/Logistik"){
            $divisi = "9";
        }else if ($divisi == "IT"){
            $divisi = "10";
        }else if ($divisi == "Desain"){
            $divisi = "11";
        }else if ($divisi == "Dokumentasi"){
            $divisi = "12";
        }

        //$divisi ditambah 0 jika index divisi kurang dari 10
        if ($divisi < 10){
            $divisi = "0".$divisi;
        }

        //tahun masuk
        $tahunMasuk = $request->tahunMasuk;
        $tahunMasuk = substr($tahunMasuk, -2);

        //membuat user baru
        $user = new User;
        $user->name = ucwords(strtolower($request->nama));
        $user->email = $request->email;
        $user->phone = $request->telepon;
        $user->password = bcrypt($request->password);
        $user->role = strtolower($request->divisi);
        $user->divisi = $request->divisi;
        $user->save();

        //membuat nip baru dengan mengambil 2 digit terakhir index tempat kerja, 2 digit terakhir tahun masuk, 2 digit terakhir index divisi, dan 2 digit terakhir index user
        $nip = $tempatKerja.$divisi.$tahunMasuk.$user->id;

        //membut employee baru
        $employee = new Employee;
        $employee->nip = $nip;
        $employee->name = ucwords(strtolower($request->nama));
        $employee->email = $request->email;
        $employee->phone = $request->telepon;
        $employee->division = $request->divisi;
        $employee->office = $request->lokasi;
        $employee->user_id = $user->id;
        $employee->save();

        //mengubah sales yang dipilih menjadi sales yang baru dibuat
        if($request->salesApa){
        $sales = Sales::where('id', $request->salesApa)->get();
        $sales->user_id = $user->id;
        $sales->save();
        }

        //jika user berhasil dibuat
        return redirect()->route('auth.login')->with('success-register', 'Registrasi berhasil, silahkan login');
    }
}
