<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use App\Models\Sales;
use App\Models\Job;
use App\Models\Order;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Design;
use App\Models\User;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    public function antrianDesain(){

        $listDesain = Order::with('employee', 'sales', 'job', 'user')->orderByDesc('is_priority')->where('status', 0)->get();
        $listDikerjakan = Order::with('employee', 'sales', 'job', 'user')->orderByDesc('is_priority')->where('status', 1)->get();
        $listSelesai = Order::with('employee', 'sales', 'job', 'user')->where('status', 2)->get();

        return view('page.antrian-desain.index', compact('listDesain', 'listDikerjakan', 'listSelesai'));
    }

    //Ambil Desain
    public function ambilDesain(string $id){
        $antrian = Order::find($id);
        $antrian->status = 1;
        $antrian->user_id = auth()->user()->id;
        $antrian->time_taken = now();
        $antrian->save();

        return redirect('/design')->with('success-take', 'Design berhasil diambil');
    }

    public function create()
    {
        if (!auth()->check()) {
            return redirect()->route('auth.login')->with('belum-login', 'Silahkan login terlebih dahulu');
        }

        $sales = Sales::all();
        $jobs = Job::all();

        return view('page.order.add', compact('sales', 'jobs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi form add.blade.php
        $rules = [
            'title' => 'required',
            'sales' => 'required',
            'job' => 'required',
            'description' => 'required',
            'refdesain' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ];

        $validator = Validator::make($request->all(), $rules);

        // Jika validasi gagal, kembali ke halaman add.blade.php dengan membawa pesan error
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        //ubah nama file
        $file = $request->file('refdesain');
        if($file){
            $fileName = time() . '.' . $file->getClientOriginalName();
            $file->move(storage_path('app/public/ref-desain'), $fileName);
        }else{
            return redirect()->back()->with('error', 'File tidak ditemukan !');
        }

        $lastId = Order::latest()->first();
        $lastId = $lastId->id + 1;
        $ticketOrder = date('Ymd') . $lastId;

        // Jika validasi berhasil, simpan data ke database
        $order = new Order;
        $order->ticket_order = $ticketOrder;
        $order->title = $request->title;
        $order->sales_id = $request->sales;
        $order->job_id = $request->job;
        $order->description = $request->description;
        $order->desain = $fileName;
        $order->status = '0';
        $order->is_priority = $request->priority ? '1' : '0';
        $order->save();

        return redirect()->route('design.index')->with('success-design', 'Order berhasil ditambahkan');
    }

    public function uploadPrintFile(Request $request)
    {
        //Menyimpan file cetak dari form dropzone
        $file = $request->file('file');
        $fileName = time() . '.' . $file->getClientOriginalName();
        $path = $file->storeAs('public/file-cetak', $fileName);

        //Menyimpan nama file cetak ke database
        $order = Order::find($request->id);
        $order->file_cetak = $fileName;
        $order->save();

        return response()->json(['success' => $fileName]);
    }

    public function submitFileCetak(Request $request, $id){

        $order = Order::find($id);

        if(!$order->file_cetak){
            return redirect()->back()->with('error-filecetak', 'File cetak belum diupload, silahkan ulangi proses upload file cetak');
        }

        $order->status = 2;
        $order->time_end = now();
        $order->save();

        return redirect()->route('design.index')->with('success-submit', 'File berhasil diupload');
    }

    public function toAntrian(string $id){
        $order = Order::find($id);

        return view ('page.antrian-workshop.create', compact('order'));
    }
}
