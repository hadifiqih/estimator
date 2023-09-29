<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Sales;
use App\Models\Antrian;
use App\Models\Design;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Documentation;
use App\Models\Machine;
use App\Models\Dokumproses;

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

            $antrians = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing')
            ->orderByDesc('created_at')
            ->where('status', '1')
            ->where('sales_id', $salesId)
            ->get();

            $antrianSelesai = Antrian::with('sales', 'customer', 'job', 'design', 'operator', 'finishing', 'order')
                            ->orderByDesc('created_at')
                            ->where('status', '2')
                            ->where('sales_id', $salesId)
                            ->get();

        }

        $antrians = Antrian::with('payment','sales', 'customer', 'job', 'design', 'operator', 'finishing', 'order')
            ->orderByDesc('created_at')
            ->where('status', '1')
            ->get();
        // Ambil data antrian dari database yang memiliki relasi dengan sales, customer, job, design, operator, dan finishing dan statusnya 1 (aktif)

        $antrianSelesai = Antrian::with('sales', 'customer', 'job', 'design', 'operator', 'finishing', 'order')
                            ->orderByDesc('created_at')
                            ->where('status', '2')
                            ->get();

        return view('page.antrian-workshop.index', compact('antrians', 'antrianSelesai'));
    }

    public function estimatorIndex(){
        $fileBaruMasuk = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing')
        ->where('status', '1')
        ->where('is_aman', '0')
        ->orderByDesc('created_at')
        ->get();

        $progressProduksi = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing', 'dokumproses')
        ->where('status', '1')
        ->where('is_aman', '1')
        ->orderByDesc('created_at')
        ->get();

        $selesaiProduksi = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing', 'dokumproses')
        ->where('status', '2')
        ->orderByDesc('created_at')
        ->get();

        return view('page.antrian-workshop.estimator-index', compact('fileBaruMasuk', 'progressProduksi', 'selesaiProduksi'));
    }

    public function downloadPrintFile($id){
        $antrian = Antrian::where('id', $id)->first();
        $file = $antrian->order->file_cetak;
        $path = storage_path('app/public/file-cetak/' . $file);
        return response()->download($path);
    }

    public function downloadProduksiFile($id){
        $antrian = Antrian::where('id', $id)->first();
        $file = $antrian->design->filename;
        $path = storage_path('app/public/file-jadi/' . $file);
        return response()->download($path);
    }

     public function store(Request $request)
     {

        $idCustomer = Customer::where('telepon', $request->input('noHp'))->first();
        if($idCustomer){
            $repeat = $idCustomer->frekuensi_order + 1;
            $idCustomer->frekuensi_order = $repeat;
            $idCustomer->save();
        }

        $order = Order::where('id', $request->input('idOrder'))->first();
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
        $totalPembayaran = str_replace('.', '', $request->input('totalPembayaran'));
        $payment->total_payment = $totalPembayaran;
        $pembayaran = str_replace('.', '', $request->input('jumlahPembayaran'));
        $payment->payment_amount = $pembayaran;
        // menyimpan inputan biaya jasa pengiriman
        if($request->input('biayaPengiriman') == null){
            $biayaPengiriman = 0;
        }else{
            $biayaPengiriman = str_replace('.', '', $request->input('biayaPengiriman'));
        }
        $payment->shipping_cost = $biayaPengiriman;
        // menyimpan inputan biaya jasa pemasangan
        if($request->input('biayaPemasangan') == null){
            $biayaPemasangan = 0;
        }else{
            $biayaPemasangan = str_replace('.', '', $request->input('biayaPemasangan'));
        }
        $payment->installation_cost = $biayaPemasangan;

        // Menyimpan file purcase order
        if($request->file('purchaseOrder')){
            $purchaseOrder = $request->file('purchaseOrder');
            $namaPurchaseOrder = $purchaseOrder->getClientOriginalName();
            $namaPurchaseOrder = Carbon::now()->format('Ymd') . '_' . $namaPurchaseOrder;
            $path = 'purchase-order/' . $namaPurchaseOrder;
            Storage::disk('public')->put($path, $purchaseOrder->get());
        }else{
            $namaPurchaseOrder = null;
        }
        $payment->purchase_order = $namaPurchaseOrder;
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

        $hargaProduk = str_replace('.', '', $request->input('hargaProduk'));
        $omset = str_replace('.', '', $request->input('totalPembayaran'));

        $antrian = new Antrian();
        $antrian->ticket_order = $ticketOrder;
        $antrian->sales_id = $request->input('sales');
        $antrian->customer_id = $idCustomer->id;
        $antrian->job_id = $request->input('namaPekerjaan');
        $antrian->note = $request->input('keterangan');
        $antrian->omset = $omset;
        $antrian->qty = $request->input('qty');
        $antrian->order_id = $request->input('idOrder');
        $antrian->alamat_pengiriman = $request->input('alamatPengiriman');
        $antrian->harga_produk = $hargaProduk;
        $antrian->save();

        // Menampilkan push notifikasi saat selesai
        $beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
            "instanceId" => "0958376f-0b36-4f59-adae-c1e55ff3b848",
            "secretKey" => "9F1455F4576C09A1DE06CBD4E9B3804F9184EF91978F3A9A92D7AD4B71656109",
        ));

        $publishResponse = $beamsClient->publishToInterests(
            array('operator', 'admin'),
            array("web" => array("notification" => array(
              "title" => "📣 Cek sekarang, ada antrian baru !",
              "body" => "Cek pekerjaan baru sekarang, cepat kerjakan biar cepet pulang !",
            )),
        ));

        return redirect()->route('antrian.index')->with('success', 'Data antrian berhasil ditambahkan!');

     }

    public function edit($id)
    {
        $antrian = Antrian::where('id', $id)->first();

        $jenis = strtolower($antrian->job->job_type);

        if($jenis == 'non stempel'){
            $operators = User::where('role', 'stempel')->orWhere('role', 'advertising')->with('employee')->get();
        }else{
            $operators = User::where('role', $jenis)->with('employee')->get();
        }

        //Melakukan explode pada operator_id, finisher_id, dan qc_id
        $operatorId = explode(',', $antrian->operator_id);
        $finisherId = explode(',', $antrian->finisher_id);
        $qualityId = explode(',', $antrian->qc_id);

        $machines = Machine::get();

        $qualitys = Employee::where('can_qc', 1)->get();

        $tempat = explode(',', $antrian->working_at);

        return view('page.antrian-workshop.edit', compact('antrian', 'operatorId', 'finisherId', 'qualityId', 'operators', 'qualitys', 'machines', 'tempat'));
    }

    public function update(Request $request, $id)
    {

        $antrian = Antrian::find($id);

        //Jika input operator adalah array, lakukan implode lalu simpan ke database
        $operator = implode(',', $request->input('operator'));
        $antrian->operator_id = $operator;

        //Jika input finisher adalah array, lakukan implode lalu simpan ke database
        $finisher = implode(',', $request->input('finisher'));
        $antrian->finisher_id = $finisher;

        //Jika input quality adalah array, lakukan implode lalu simpan ke database
        $quality = implode(',', $request->input('quality'));
        $antrian->qc_id = $quality;

        //Jika input tempat adalah array, lakukan implode lalu simpan ke database
        $tempat = implode(',', $request->input('tempat'));
        $antrian->working_at = $tempat;

        //start_job diisi dengan waktu sekarang
        $antrian->start_job = $request->input('start_job');
        $antrian->end_job = $request->input('deadline');

        //Jika input mesin adalah array, lakukan implode lalu simpan ke database
        if($request->input('jenisMesin')){
        $mesin = implode(',', $request->input('jenisMesin'));
        $antrian->machine_code = $mesin;
        }
        $antrian->admin_note = $request->input('catatan');
        $antrian->save();

        // Menampilkan push notifikasi saat selesai
        $beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
            "instanceId" => "0958376f-0b36-4f59-adae-c1e55ff3b848",
            "secretKey" => "9F1455F4576C09A1DE06CBD4E9B3804F9184EF91978F3A9A92D7AD4B71656109",
        ));

        $users = [];

        foreach($request->input('operator') as $operator){
            $user = 'user-' . $operator;
            $users[] = $user;
        }

        foreach($request->input('finisher') as $finisher){
            $user = 'user-' . $finisher;
            $users[] = $user;
        }

        foreach($request->input('quality') as $quality){
            $user = 'user-' . $quality;
            $users[] = $user;
        }


        foreach($users as $user){
            $publishResponse = $beamsClient->publishToUsers(
                array($user),
                array("web" => array("notification" => array(
                "title" => "📣 Cek sekarang, ada antrian baru !",
                "body" => "Cek pekerjaan baru sekarang, cepat kerjakan biar cepet pulang !",
                )),
            ));
        }

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

        $order = Order::where('id', $antrian->order_id)->first();
        $order->toWorkshop = 0;
        $order->save();

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

    public function getMachine(Request $request){
        //Menampilkan data mesin pada tabel Machines
        $machines = Machine::get();
        return response()->json($machines);
    }

    public function showProgress($id){
        $antrian = Antrian::where('id', $id)->with('job', 'sales', 'order')
        ->first();

        return view('page.antrian-workshop.progress', compact('antrian'));
    }

    public function storeProgressProduksi(Request $request){
        $antrian = Antrian::where('id', $request->input('idAntrian'))->first();

        if($request->file('fileGambar')){
        $gambar = $request->file('fileGambar');
        $namaGambar = time()."_".$gambar->getClientOriginalName();
        $pathGambar = 'dokum-proses/'.$namaGambar;
        Storage::disk('public')->put($pathGambar, $gambar->get());
        }else{
            $namaGambar = null;
        }

        if($request->file('fileVideo')){
        $video = $request->file('fileVideo');
        $namaVideo = time()."_".$video->getClientOriginalName();
        $pathVideo = 'dokum-proses/'.$namaVideo;
        Storage::disk('public')->put($pathVideo, $video->get());
        }else{
            $namaVideo = null;
        }

        $dokumProses = new Dokumproses();
        $dokumProses->note = $request->input('note');
        $dokumProses->file_gambar = $namaGambar;
        $dokumProses->file_video = $namaVideo;
        $dokumProses->antrian_id = $request->input('idAntrian');
        $dokumProses->save();

        return redirect()->route('antrian.index');
    }

    public function markAman($id)
    {
        $design = Antrian::find($id);
        $design->is_aman = 1;
        $design->save();

        return redirect()->back()->with('success', 'File berhasil di tandai aman');
    }

    public function markSelesai($id){
        //cek apakah waktu sekarang sudah melebihi waktu deadline
        $antrian = Antrian::where('id', $id)->with('job', 'sales', 'order')->first();
        $antrian->timer_stop = Carbon::now();

        if($antrian->deadline_status = 1){
            $antrian->deadline_status = 1;
        }
        elseif($antrian->deadline_status = 0){
            $antrian->deadline_status = 2;
        }
        $antrian->status = 2;
        $antrian->save();

         // Menampilkan push notifikasi saat selesai
         $beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
            "instanceId" => "0958376f-0b36-4f59-adae-c1e55ff3b848",
            "secretKey" => "9F1455F4576C09A1DE06CBD4E9B3804F9184EF91978F3A9A92D7AD4B71656109",
        ));

        $publishResponse = $beamsClient->publishToInterests(
            array("sales"),
            array("web" => array("notification" => array(
              "title" => "Antree",
              "body" => "Yuhuu! Pekerjaan " . $antrian->job->job_name . " dengan tiket " . $antrian->ticket_order . " (" . $antrian->order->title ."), dari sales ". $antrian->sales->sales_name ." udah selesai !",
              "deep_link" => "https://interatama.my.id/",
            )),
        ));

        return redirect()->route('antrian.index')->with('success-dokumentasi', 'Berhasil ditandai selesai !');
    }

    public function reminderProgress(){
        $beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
            "instanceId" => "0958376f-0b36-4f59-adae-c1e55ff3b848",
            "secretKey" => "9F1455F4576C09A1DE06CBD4E9B3804F9184EF91978F3A9A92D7AD4B71656109",
        ));

        $publishResponse = $beamsClient->publishToInterests(
            array("operator"),
            array("web" => array("notification" => array(
              "title" => "🔔 Kring.. Reminder!",
              "body" => "Yuk cek progress pekerjaanmu sekarang, jangan lupa upload progressnya ya !",
              "deep_link" => "https://interatama.my.id/",
            )),
        ));

        return response()->json('success', 200);
    }
}
