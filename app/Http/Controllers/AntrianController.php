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
        // Ambil data antrian dari database yang memiliki relasi dengan sales, customer, job, design, operator, dan finishing dan statusnya 1 (aktif)
        $antrians = Antrian::with('sales', 'customer', 'job', 'design', 'operator', 'finishing')->where('status', '1')->get();
        $antrianSelesai = Antrian::with('sales', 'customer', 'job', 'design', 'operator', 'finishing')->where('status', '2')->get();
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

        $buktiPembayaran = $request->file('buktiPembayaran');
        $namaBuktiPembayaran = $buktiPembayaran->getClientOriginalName();
        $namaBuktiPembayaran = Carbon::now()->format('Ymd') . '_' . $namaBuktiPembayaran;
        $buktiPembayaran->move(storage_path('app/public/bukti-pembayaran/'), $namaBuktiPembayaran);

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
        $accDesain->move(storage_path('app/public/acc-desain/'), $namaAccDesain);

        $order->acc_desain = $namaAccDesain;
        $order->save();

        $antrian = new Antrian();
        $antrian->ticket_order = $ticketOrder;
        $antrian->sales_id = $request->input('sales');
        $antrian->customer_id = $request->input('nama');
        $antrian->job_id = $request->input('namaPekerjaan');
        $antrian->note = $request->input('keterangan');
        $antrian->design_id = $designerID;
        $antrian->omset = $request->input('totalPembayaran');
        $antrian->order_id = $request->input('idOrder');
        $antrian->save();

        return redirect()->route('antrian.index')->with('successToAntrian', 'Data antrian berhasil ditambahkan!');
     }

    public function edit($id)
    {

        $antrian = Antrian::where('id', $id)->first();

        $jenis = $antrian->job->job_type;

        $employees = Employee::where('division', $jenis)->get();

        $qualitys = Employee::where('can_qc', $jenis)->get();

        $rekanan = Employee::where('id', '9999')->first();

        return view('page.antrian-workshop.edit', compact('antrian', 'employees', 'qualitys', 'rekanan'));
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
        // melakukan update ajax untuk deadline_status
        $antrian = Antrian::find($id);
        $antrian->deadline_status = $request->input('deadline_status');
        $antrian->save();

        return response()->json(['message' => 'Melewati Deadline !']);
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
    public function updateDeadlineStatus(Request $request, $id)
    {
    $antrian = Antrian::find($id);
    $antrian->deadline_status = $request->input('deadline_status');
    $antrian->save();

    return response()->json(['message' => 'Melewati Deadline !']);
    }
    //--------------------------------------------------------------------------
    // fungsi untuk check documentasi sudah terisi id atau belum
    public function checkDocumentation($id)
{
    $antrian = Antrian::find($id);

    // Periksa apakah ada dokumentasi (documentation_id terisi)
    if ($antrian->documentation_id) {
        // Hentikan timer
        $antrian->timer_stop = now();
        // Ganti deadline_status menjadi 2 (sesuai deadline)
        $antrian->deadline_status = 2;
        $antrian->save();

        return response()->json([
            'documentation_id' => $antrian->documentation_id
        ]);
    }

    return response()->json([
        'documentation_id' => null
    ]);
}

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
    $uploadedFiles = [];

        if ($request->hasFile('file')) {
            $files = $request->file('file');


            foreach ($files as $file) {
                //Rename nama file
                $nama_file = $file->getClientOriginalName();
                $nama_file = time()."_".$nama_file;
                $path = $file->move('storage/dokumentasi', $nama_file);// Simpan file di direktori 'storage/app/dokumentasi'
                $uploadedFiles[] = $path;

                //simpan nama file ke database (table documentations)
                $documentation = new Documentation();
                $documentation->antrian_id = $request->input('antrian_id');
                $documentation->filename = $nama_file;
                $documentation->save();
            }
        }

        return response()->json(['uploaded_files' => $uploadedFiles], 200);
}

}
