<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Sales;
use App\Models\Antrian;
use App\Models\Design;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Documentation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;


class AntrianController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(auth()->user()->role == 'sales'){
            $sales = Sales::where('user_id', auth()->user()->id)->first();
            $salesId = $sales->id;

            $antrians = Antrian::with(['order' => function ($query) {
                $query->orderByDesc('is_priority');
            }, 'sales', 'customer', 'job', 'design', 'operator', 'finishing'])
            ->orderByDesc('created_at')
            ->where('status', '1')
            ->where('sales_id', $salesId)->get();
        }

        $antrians = Antrian::with(['order' => function ($query) {
                        $query->orderByDesc('is_priority');
                    }, 'sales', 'customer', 'job', 'design', 'operator', 'finishing'])
                    ->orderByDesc('created_at')
                    ->where('status', '1')->get();
        // Ambil data antrian dari database yang memiliki relasi dengan sales, customer, job, design, operator, dan finishing dan statusnya 1 (aktif)

        $antrianSelesai = Antrian::with('sales', 'customer', 'job', 'design', 'operator', 'finishing', 'order')
                            ->orderByDesc('created_at')
                            ->where('status', '2')->get();
        return view('page.antrian-workshop.index', compact('antrians', 'antrianSelesai'));
    }

    public function downloadPrintFile($id){
        $antrian = Antrian::where('id', $id)->first();
        $file = $antrian->order->file_cetak;
        $path = storage_path('app/public/file-cetak/' . $file);
        return response()->download($path);

    }

     public function store(Request $request)
     {

        $idCustomer = Customer::where('id', $request->input('nama'))->first();
        $countRepeat = $idCustomer->repeat_order;
        if ($countRepeat == null) {
            $countRepeat = 0;
        } else {
            $countRepeat = $countRepeat + 1;
        }

        $order = Order::where('id', $request->input('idOrder'))->first();
        $designerID = $order->user_id;
        $ticketOrder = $order->ticket_order;

        if($request->file('buktiPembayaran')){
        $buktiPembayaran = $request->file('buktiPembayaran');
        $namaBuktiPembayaran = $buktiPembayaran->getClientOriginalName();
        $namaBuktiPembayaran = Carbon::now()->format('Ymd') . '_' . $namaBuktiPembayaran;
        $path = 'bukti-pembayaran/' . $namaBuktiPembayaran;
        Storage::disk('public')->put($path, $buktiPembayaran->get());
        }else{
            $namaBuktiPembayaran = null;
        }

        $payment = new Payment();
        $payment->ticket_order = $ticketOrder;
        $payment->total_payment = $request->input('totalPembayaran');
        $payment->payment_amount = $request->input('jumlahPembayaran');
        $payment->payment_method = $request->input('jenisPembayaran');
        $payment->payment_status = $request->input('statusPembayaran');
        $payment->payment_proof = $namaBuktiPembayaran;
        $payment->save();

        $accDesain = $request->file('accDesain');
        $namaAccDesain = $accDesain->getClientOriginalName();
        $namaAccDesain = Carbon::now()->format('Ymd') . '_' . $namaAccDesain;
        $path = 'acc-desain/' . $namaAccDesain;
        Storage::disk('public')->put($path, $accDesain->get());


        $order->acc_desain = $namaAccDesain;
        $order->toWorkshop = 1;
        $order->save();

        $antrian = Antrian::create([
            'ticket_order' => $ticketOrder,
            'sales_id' => $request->input('sales'),
            'customer_id' => $request->input('nama'),
            'job_id' => $request->input('namaPekerjaan'),
            'note' => $request->input('keterangan'),
            'design_id' => $designerID,
            'omset' => $request->input('totalPembayaran'),
            'order_id' => $request->input('idOrder')
        ]);

        $url = route('antrian.index');
        return view('loader.index', compact('url'));

     }

    public function edit($id)
    {

        $antrian = Antrian::where('id', $id)->first();

        $jenis = $antrian->job->job_type;

        $employees = Employee::where('division', $jenis)->get();

        $qualitys = Employee::where('can_qc', 1)->get();

        return view('page.antrian-workshop.edit', compact('antrian', 'employees', 'qualitys'));
    }

    public function update(Request $request, $id)
    {

        $antrian = Antrian::find($id);

        $antrian->operator_id = $request->input('operator');
        $antrian->finisher_id = $request->input('finisher');
        $antrian->working_at = $request->input('tempat');
        $antrian->end_job = $request->input('deadline');
        $antrian->qc_id = $request->input('quality');

        $antrian->save();

        return redirect()->route('antrian.index')->with('success-update', 'Data antrian berhasil diupdate!');
    }

    public function updateDeadline(Request $request, $id)
    {
        // dd(request()->all());
        // melakukan update ajax untuk deadline_status
        $antrian = Antrian::find($id);
        $status = $request->input('deadline_status');

        $antrian->deadline_status = $status;
        $antrian->save();

        if($status == 1){
            return response()->json(['success' => true]);
        }elseif($status == 2){
            return response()->json(['success' => false]);
        }


    }
    public function destroy($id)
    {
        // Melakukan pengecekan otorisasi sebelum menghapus antrian
        $this->authorize('delete', Antrian::class);

        $antrian = Antrian::find($id);
        if ($antrian) {
            $antrian->delete();
            return redirect()->route('antrian.index')->with('success-delete', 'Data antrian berhasil dihapus!');
        } else {
            return redirect()->route('antrian.index')->with('error-delete', 'Data antrian gagal dihapus!');
        }
    }
    //--------------------------------------------------------------------------

    public function design(){
        //Melarang akses langsung ke halaman ini sebelum login
        if (!auth()->check()) {
            return redirect()->route('auth.login')->with('belum-login', 'Silahkan login terlebih dahulu');
        }

        $list_desain = AntrianDesain::get();
        return view('antriandesain.index', compact('list_desain'));
    }

    public function tambahDesain(){
        $list_antrian = Antrian::get();
        return view('antriandesain.create', compact('list_antrian'));
    }

//fungsi untuk menggunggah & menyimpan file gambar dokumentasi
    public function showDokumentasi($id){
        $antrian = Antrian::find($id);
        return view ('page.antrian-workshop.dokumentasi' , compact('antrian'));
    }

    public function storeDokumentasi(Request $request){
        $files = $request->file('files');
        $id = $request->input('idAntrian');

        foreach($files as $file){
            $filename = time()."_".$file->getClientOriginalName();
            $path = 'dokumentasi/'.$filename;
            Storage::disk('public')->put($path, $file->get());

            $dokumentasi = new Documentation();
            $dokumentasi->antrian_id = $id;
            $dokumentasi->filename = $filename;
            $dokumentasi->type_file = $file->getClientOriginalExtension();
            $dokumentasi->path_file = $path;
            $dokumentasi->job_id = $request->input('jobType');
            $dokumentasi->save();
        }

        return response()->json(['success'=>'You have successfully upload file.']);
    }

    public function submitDokumentasi($id)
    {
        //cek apakah waktu sekarang sudah melebihi waktu deadline


        $antrian = Antrian::find($id);
        $antrian->timer_stop = Carbon::now();

        if($antrian->deadline_status = 1){
            $antrian->deadline_status = 1;
        }
        elseif($antrian->deadline_status = 0){
            $antrian->deadline_status = 2;
        }
        $antrian->status = 2;
        $antrian->save();

        return redirect()->route('antrian.index')->with('success-dokumentasi', 'Dokumentasi berhasil diunggah!');
    }

    }

// $files = $request->file('files');
        // $id = $request->input('idAntrian');

        // foreach ($files as $file) {
        //     //Rename nama file
        //     $uploadedFile = [];
        //     $nama_file = $file->getClientOriginalName();
        //     $nama_file = time()."_".$nama_file;
        //     $path = $file->storeAs('storage/dokumentasi', $nama_file);// Simpan gambar yang diupload ke folder public/dokumentasi
        //     $uploadedFile[] = $path;

        //     $dokumentasi = new Dokumentasi();
        //     $dokumentasi->antrian_id = $id;
        //     $dokumentasi->filename = $nama_file;
        //     $dokumentasi->path_file = $path;
        //     $dokumentasi->type_file = $file->getClientOriginalExtension();
        //     $dokumentasi->job_id = $request->input('type_job');
        //     $dokumentasi->save();

        // }

        // return response()->json(['success' => $uploadedFile]);
