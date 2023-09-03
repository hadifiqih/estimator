<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

use App\Models\Sales;
use App\Models\Job;
use App\Models\Order;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Design;
use App\Models\User;

use App\Events\SendGlobalNotification;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function notifTest(){
        $users = User::all();
        $notification = new AntrianNew();

        Notification::send($users, $notification);
    }

    public function cobaPush(){



    }

    public function antrianDesain(){
        if(auth()->user()->role == 'sales'){
            $sales = Sales::where('user_id', auth()->user()->id)->first();
            $salesId = $sales->id;
            $listDesain = Order::with('employee', 'sales', 'job', 'user')->orderByDesc('is_priority')->where('status', 0)->where('sales_id', $salesId)->get();
            $listDikerjakan = Order::with('employee', 'sales', 'job', 'user')->orderByDesc('is_priority')->where('status', 1)->where('sales_id', $salesId)->get();
            $listSelesai = Order::with('employee', 'sales', 'job', 'user')->where('status', 2)->where('sales_id', $salesId)->get();
        }else{
            $listDesain = Order::with('employee', 'sales', 'job', 'user')->orderByDesc('is_priority')->where('status', 0)->get();
            $listDikerjakan = Order::with('employee', 'sales', 'job', 'user')->orderByDesc('is_priority')->where('status', 1)->get();
            $listSelesai = Order::with('employee', 'sales', 'job', 'user')->where('status', 2)->get();
        }

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

        $sales = Sales::where('user_id', auth()->user()->id)->first();
        $jobs = Job::all();

        return view('page.order.add', compact('sales', 'jobs'));
    }

    public function edit($id)
    {
        $order = Order::find($id);
        $sales = Sales::where('user_id', auth()->user()->id)->first();
        $job = Job::where('id', $order->job_id)->first();
        $jobs = Job::all();

        return view('page.order.edit', compact('order', 'sales', 'job', 'jobs'));
    }

    public function update(Request $request, $id){
        // Validasi form add.blade.php
        $rules = [
            'title' => 'required',
            'sales' => 'required',
            'job' => 'required',
            'description' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        // Jika validasi gagal, kembali ke halaman add.blade.php dengan membawa pesan error
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        //ubah nama file
        if($request->file('refdesain')){
            //Hapus file lama / sebelumnya diupload
            $orderLama = Order::find($id);
            $oldFile = $orderLama->desain;
            if($oldFile != '-'){
                Storage::disk('public')->delete('ref-desain/' . $oldFile);
            }

            $file = $request->file('refdesain');
            $fileName = time() . '.' . $file->getClientOriginalName();
            $path = 'ref-desain/' . $fileName;
            Storage::disk('public')->put($path, $file->get());
        }

        // Jika validasi berhasil, simpan data ke database
        $order = Order::find($id);
        $order->title = $request->title;
        $order->sales_id = $request->sales;
        $order->job_id = $request->job;
        $order->description = $request->description;
        $order->type_work = $request->jenisPekerjaan;
        if($request->file('refdesain')){
            $order->desain = $fileName;
        }
        $order->is_priority = $request->priority ? '1' : '0';
        $order->save();

        $url = route('design.index');
        return view('loader.index', compact('url'));
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
            'jenisPekerjaan' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        // Jika validasi gagal, kembali ke halaman add.blade.php dengan membawa pesan error
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        //ubah nama file
        if($request->file('refdesain')){
            $file = $request->file('refdesain');
            $fileName = time() . '.' . $file->getClientOriginalName();
            $path = 'ref-desain/' . $fileName;
            Storage::disk('public')->put($path, $file->get());
        }else{
            $fileName = '-';
        }

        $lastId = Order::latest()->first();
        if($lastId == null){
            $lastId = 1;
        }else{
            $lastId = $lastId->id + 1;
        }
        $ticketOrder = date('Ymd') . $lastId;

        // Jika validasi berhasil, simpan data ke database
        $order = new Order;
        $order->ticket_order = $ticketOrder;
        $order->title = $request->title;
        $order->sales_id = $request->sales;
        $order->job_id = $request->job;
        $order->user_id = auth()->user()->id;
        $order->description = $request->description;
        $order->type_work = $request->jenisPekerjaan;
        $order->desain = $fileName;
        $order->status = '0';
        $order->is_priority = $request->priority ? '1' : '0';
        $order->save();

        // $sales = Sales::where('id', $request->input('sales'))->first();

        // $notification = [
        //     'title' => 'Antrian Desain',
        //     'body' => 'Desain baru dari ' . $sales->sales_name,
        // ];

        // //Mengirimkan notifikasi ke semua user ketika ada penambahan antrian
        // event(new SendGlobalNotification($notification));

        $url = route('design.index');
        return view('loader.index', compact('url'));
    }

    public function uploadPrintFile(Request $request)
    {
        //Menyimpan file cetak dari form dropzone
        $file = $request->file('fileCetak');
        $fileName = time() . '.' . $file->getClientOriginalName();
        $path = 'file-cetak/' . $fileName;
        Storage::disk('public')->put($path, $file->get());

        //Menyimpan nama file cetak ke database
        $order = Order::where('id', $request->id)->first();
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

    public function tambahProdukByModal(Request $request){

        $job = new Job;
        $job->job_name = $request->namaProduk;
        $job->job_type = $request->jenisProduk;
        $job->save();

        return response()->json([
            'status' => 200,
            'message' => 'Produk berhasil ditambahkan'
        ]);
    }

    public function getJobsByCategory($category_id){
        $jobs = Job::where('job_type', $category_id)->get();

        return response()->json($jobs);
    }
}

