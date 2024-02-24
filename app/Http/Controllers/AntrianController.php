<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Job;
use App\Models\User;
use App\Models\Order;
use App\Models\Sales;
use App\Models\Design;
use App\Models\Antrian;
use App\Models\Machine;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Anservice;
use App\Models\Dokumproses;

use Illuminate\Http\Request;
use App\Models\Documentation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AntrianWorkshop;
use App\Http\Resources\AntrianResource;
use Illuminate\Support\Facades\Storage;
use App\Notifications\AntrianDiantrikan;
use Illuminate\Support\Facades\Notification;
use Yajra\DataTables\Facades\DataTables;


class AntrianController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function antrianByJob()
    {
        $jobs = Job::all();
        return view('page.antrian-workshop.estimator-by-job', compact('jobs'));
    }

    public function estimatorFilterByJob($id)
    {
        $antrians = Antrian::where('job_id', $id)->get();

        return Datatables::of($antrians)
            ->addColumn('ticket_order', function($antrian){
                return $antrian->ticket_order;
            })
            ->addColumn('sales', function($antrian){
                return $antrian->sales->sales_name;
            })
            ->addColumn('customer', function($antrian){
                return $antrian->customer->customer_name;
            })
            ->addColumn('jenis_produk', function($antrian){
                return $antrian->job->job_name;
            })
            ->addColumn('qty', function($antrian){
                return $antrian->qty;
            })
            ->addColumn('deadline', function($antrian){
                return $antrian->deadline;
            })
            ->addColumn('file_desain', function($antrian){
                return $antrian->order->acc_desain;
            })
            ->addColumn('desainer', function($antrian){
                return $antrian->order->desainer;
            })
            ->addColumn('operator', function($antrian){
                $operator = explode(',', $antrian->operator_id);
                $operatorName = [];
                foreach($operator as $op){
                    $operatorName[] = Employee::find($op)->operator->name;
                }
                return implode(', ', $operatorName);
            })
            ->addColumn('finishing', function($antrian){
                $finisher = explode(',', $antrian->finisher_id);
                $finisherName = [];
                foreach($finisher as $fin){
                    $finisherName[] = Employee::find($fin)->finishing->name;
                }
                return implode(', ', $finisherName);
            })
            ->addColumn('qc', function($antrian){
                return $antrian->quality->name;
            })
            ->addColumn('tempat', function($antrian){
                return $antrian->working_at;
            })
            ->addColumn('catatan_admin', function($antrian){
                return $antrian->admin_note;
            })
            ->rawColumns(['file_desain', 'jenis_produk'])
            ->make(true);
    }

    public function index()
    {
        if(auth()->user()->role == 'sales'){
            $sales = Sales::where('user_id', auth()->user()->id)->first();
            $salesId = $sales->id;

            $antrians = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing')
            ->orderBy('created_at', 'desc')
            ->where('status', '1')
            ->where('sales_id', $salesId)
            ->get();

            $antrianSelesai = Antrian::with('sales', 'customer', 'job', 'design', 'operator', 'finishing', 'order')
                            ->orderByDesc('created_at')
                            ->where('status', '2')
                            ->where('sales_id', $salesId)
                            ->get();
                            
        }elseif(auth()->user()->role == 'admin' || auth()->user()->role == 'dokumentasi'){
            $antrians = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing')
            ->orderBy('created_at', 'desc')
            ->where('status', '1')
            ->get();

            $antrianSelesai = Antrian::with('sales', 'customer', 'job', 'design', 'operator', 'finishing', 'order')
                            ->orderByDesc('created_at')
                            ->where('status', '2')
                            ->take(25)
                            ->get();
            
        }elseif(auth()->user()->role == 'stempel' || auth()->user()->role == 'advertising'){
            
            $antrians = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing')
            ->orderBy('created_at', 'desc')
            ->where('status', '1')
            ->get();

            $antrianSelesai = Antrian::with('sales', 'customer', 'job', 'design', 'operator', 'finishing', 'order')
                            ->orderBy('created_at', 'desc')
                            ->where('status', '2')
                            ->take(25)
                            ->get();
                            
        }elseif(auth()->user()->role == 'estimator'){
        
            $antrians = Antrian::with('payment','sales', 'customer', 'job', 'design', 'operator', 'finishing', 'dokumproses')
            ->where('status', '1')
            ->orderByDesc('created_at')
            ->get();
    
            $antrianSelesai = Antrian::with('payment','sales', 'customer', 'job', 'design', 'operator', 'finishing', 'dokumproses')
            ->where('status', '2')
            ->orderByDesc('created_at')
            ->take(650)
            // ->whereBetween('created_at', [now()->subMonth(1), now()])
            ->get();

        }else{
            
            $antrians = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing')
            ->orderBy('created_at', 'desc')
            ->where('status', '1')
            ->get();

            $antrianSelesai = Antrian::with('sales', 'customer', 'job', 'design', 'operator', 'finishing', 'order')
                            ->orderBy('created_at', 'desc')
                            ->where('status', '2')
                            ->get();
        }
        
        return view('page.antrian-workshop.index', compact('antrians', 'antrianSelesai'));
        
    }

    public function filterProcess(Request $request)
    {
        $jobType = $request->input('kategori');

        $antrians = Antrian::with('payment','sales', 'customer', 'job', 'design', 'operator', 'finishing', 'order')
            ->whereHas('job', function ($query) use ($jobType) {
                $query->where('job_type', $jobType);
            })
            ->where('status', '1')
            ->get();

        $antrianSelesai = Antrian::with('payment','sales', 'customer', 'job', 'design', 'operator', 'finishing', 'order')
            ->whereHas('job', function ($query) use ($jobType) {
                $query->where('job_type', $jobType);
            })
            ->where('status', '2')
            ->get();

        $filtered = $jobType;

        return view('page.antrian-workshop.index', compact('antrians', 'antrianSelesai', 'filtered'));
    }

    //--------------------------------------------------------------------------
    //Fungsi untuk menampilkan halaman tambah antrian service
    //--------------------------------------------------------------------------

    public function serviceIndex(){
        $servisBaru = Anservice::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing')
        ->get();

        return view('page.antrian-service.index', compact('servisBaru'));
    }

    public function serviceCreate(){

        return view('page.antrian-service.create');
    }

    //--------------------------------------------------------------------------
    //Estimator
    //--------------------------------------------------------------------------
    public function estimatorPage()
    {
        $jobs = Job::all();
        return view('page.antrian-workshop.estimator-data', compact('jobs'));
    }

    public function estimatorIndex()
    {
        $antrians = Antrian::with('employee')->orderByDesc('created_at')->get();

        return Datatables::of($antrians)
            ->addColumn('ticket_order', function($antrian){
                return $antrian->ticket_order;
            })
            ->addColumn('sales', function($antrian){
                return $antrian->sales->sales_name;
            })
            ->addColumn('customer', function($antrian){
                return $antrian->customer->nama;
            })
            ->addColumn('jenis_produk', function($antrian){
                return $antrian->job->job_name;
            })
            ->addColumn('qty', function($antrian){
                return $antrian->qty;
            })
            ->addColumn('deadline', function($antrian){
                return $antrian->end_job;
            })
            ->addColumn('file_desain', function($antrian){
                return $antrian->order->file_cetak;
            })
            ->addColumn('desainer', function($antrian){
                return $antrian->order->employee->name;
            })
            ->addColumn('operator', function($antrian){
                $operatorId = explode(',', $antrian->operator_id);
                    foreach ($operatorId as $item) {
                        if($item == 'rekanan'){
                            echo '- Rekanan';
                        }
                        else{
                            $antriann = App\Models\Employee::find($item);
                            //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                            if($antriann->id == end($operatorId)){
                                echo '- ' . $antriann->name;
                            }
                            else{
                                echo '- ' . $antriann->name . "<br>";
                            }
                        }
                    }
            })
            ->addColumn('finishing', function($antrian){
                $finisherID = explode(',', $antrian->finisher_id);
                //jika jumlah finisher lebih dari 1, maka tampilkan nama finisher yang lebih dari 1
                $finisherName = [];
                foreach($finisherID as $fin){
                    if($fin == 'rekanan'){
                        $finisherName[] = 'rekanan';
                    }else{
                        $finisherName[] = Employee::find($fin)->name;
                    }
                }
                $finishers = implode(', ', $finisherName);
                return $finishers;
            })
            ->addColumn('qc', function($antrian){
                return $antrian->quality->name;
            })
            ->addColumn('tempat', function($antrian){
                return $antrian->working_at;
            })
            ->addColumn('catatan_admin', function($antrian){
                return $antrian->admin_note;
            })
            ->rawColumns(['file_desain', 'jenis_produk'])
            ->make(true);
    }

    public function estimatorFilter(Request $request)
    {
        $jobType = $request->input('kategori');
        $filtered = $jobType;

        $fileBaruMasuk = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing')
        ->whereHas('job', function ($query) use ($jobType) {
            $query->where('job_type', $jobType);
        })
        ->where('status', '1')
        ->orderByDesc('created_at')
        ->get();

        $progressProduksi = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing', 'dokumproses')
        ->whereHas('job', function ($query) use ($jobType) {
            $query->where('job_type', $jobType);
        })
        ->where('status', '1')
        ->orderByDesc('created_at')
        ->get();

        $selesaiProduksi = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing', 'dokumproses')
        ->whereHas('job', function ($query) use ($jobType) {
            $query->where('job_type', $jobType);
        })
        ->where('status', '2')
        ->orderByDesc('created_at')
        ->get();

        return view('page.antrian-workshop.estimator-index', compact('fileBaruMasuk', 'progressProduksi', 'selesaiProduksi', 'filtered'));
    }

    //--------------------------------------------------------------------------
    //Admin Sales
    //--------------------------------------------------------------------------

    public function omsetGlobal()
    {
        $listSales = Sales::all();
        //mengambil tanggal awal dan tanggal akhir dari bulan ini
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        //menyimpan tanggal menjadi array
        $dateRange = [];
        $date = $startDate;
        while($date->lte($endDate)){
            $dateRange[] = $date->format('Y-m-d');
            $date->addDay();
        }

        //mengambil total omset per hari dari seluru sales
        $omsetPerHari = [];
        foreach($dateRange as $date){
            $omset = Antrian::whereDate('created_at', $date)->sum('omset');
            $omsetPerHari[] = $omset;
        }

        return view('page.admin-sales.omset-global', compact('listSales', 'omsetPerHari', 'dateRange'));
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
        //Mencari data order berdasarkan id order yang diinputkan
        $order = Order::where('id', $request->input('idOrder'))->first();
        $ticketOrder = $order->ticket_order;

        //Melakukan Check Antrian
        $checkAntrian = Antrian::where('ticket_order', $ticketOrder)->first();
        if($checkAntrian){
            return redirect()->back()->with('error', 'Data antrian sudah ada !');
        }

        //Mengambil data customer berdasarkan nama customer yang diinputkan
        $idCustomer = Customer::where('id', $request->input('nama'))->first();
        if($idCustomer){
            //Jika customer sudah ada, maka frekuensi order ditambah 1
            $repeat = $idCustomer->frekuensi_order + 1;
            $idCustomer->frekuensi_order = $repeat;
            $idCustomer->save();
        }

        //Jika ada request file bukti pembayaran, maka simpan file tersebut
        if($request->file('buktiPembayaran')){
            $buktiPembayaran = $request->file('buktiPembayaran');
            $namaBuktiPembayaran = $buktiPembayaran->getClientOriginalName();
            $namaBuktiPembayaran = time() . '_' . $namaBuktiPembayaran;
            $path = 'bukti-pembayaran/' . $namaBuktiPembayaran;
            Storage::disk('public')->put($path, file_get_contents($buktiPembayaran));
        }else{
            $namaBuktiPembayaran = null;
        }

            //Membuat payment baru dan menyimpan data pembayaran
            $payment = new Payment();
            $payment->ticket_order = $ticketOrder;
            $totalPembayaran = str_replace(['Rp ', '.'], '', $request->input('totalPembayaran'));
            $pembayaran = str_replace(['Rp ', '.'], '', $request->input('jumlahPembayaran'));

            // menyimpan inputan biaya jasa pengiriman
            if($request->input('biayaPengiriman') == null){
                $biayaPengiriman = 0;
            }else{
                $biayaPengiriman = str_replace(['Rp ', '.'], '', $request->input('biayaPengiriman'));
            }

            // menyimpan inputan biaya jasa pemasangan
            if($request->input('biayaPemasangan') == null){
                $biayaPemasangan = 0;
            }else{
                $biayaPemasangan = str_replace(['Rp ', '.'], '', $request->input('biayaPemasangan'));
            }

            // menyimpan inputan biaya jasa pengemasan
            if($request->input('biayaPengemasan') == null){
                $biayaPengemasan = 0;
            }else{
                $biayaPengemasan = str_replace(['Rp ', '.'], '', $request->input('biayaPengemasan'));
            }

            // menyimpan inputan sisa pembayaran
            $sisaPembayaran = str_replace(['Rp ', '.'], '', $request->input('sisaPembayaran'));

            // Menyimpan file purcase order
            if($request->file('filePO')){
                $purchaseOrder = $request->file('filePO');
                $namaPurchaseOrder = $purchaseOrder->getClientOriginalName();
                $namaPurchaseOrder = time() . '_' . $namaPurchaseOrder;
                $path = 'purchase-order/' . $namaPurchaseOrder;
                Storage::disk('public')->put($path, file_get_contents($purchaseOrder));
            }else{
                $namaPurchaseOrder = null;
            }

            $payment->total_payment = $totalPembayaran;
            $payment->payment_amount = $pembayaran;
            $payment->shipping_cost = $biayaPengiriman;
            $payment->installation_cost = $biayaPemasangan;
            $payment->remaining_payment = $sisaPembayaran;
            $payment->payment_method = $request->input('jenisPembayaran');
            $payment->payment_status = $request->input('statusPembayaran');
            $payment->payment_proof = $namaBuktiPembayaran;
            $payment->save();
        

        $accDesain = $request->file('accDesain');
        $namaAccDesain = $accDesain->getClientOriginalName();
        $namaAccDesain = time() . '_' . $namaAccDesain;
        $path = 'acc-desain/' . $namaAccDesain;
        Storage::disk('public')->put($path, file_get_contents($accDesain));

        $order->acc_desain = $namaAccDesain;
        $order->toWorkshop = 1;
        $order->save();

        $hargaProduk = str_replace(['Rp ', '.'], '', $request->input('hargaProduk'));
        $omset = ((int)$hargaProduk * (int)$request->input('qty')) + (int)$biayaPemasangan + (int)$biayaPengemasan;

        $antrian = new Antrian();
        $antrian->ticket_order = $ticketOrder;
        $antrian->sales_id = $request->input('sales');
        $antrian->customer_id = $idCustomer->id;
        $antrian->job_id = $request->input('namaPekerjaan');
        $antrian->note = $request->input('keterangan');
        $antrian->omset = $omset;
        $antrian->qty = $request->input('qty');
        $antrian->order_id = $request->input('idOrder');
        if($request->input('alamatPengiriman') != null){
            $antrian->alamat_pengiriman = $request->input('alamatPengiriman');
        }
        if($request->input('filePO')){
            $antrian->file_po = $namaPurchaseOrder;
        }
        $antrian->harga_produk = $hargaProduk;
        $antrian->packing_cost = $biayaPengemasan;
        $antrian->save();


        $user = User::where('role', 'admin')->first();
        $user->notify(new AntrianWorkshop($antrian, $order, $payment));
        // Menampilkan push notifikasi saat selesai
        $beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
            "instanceId" => "0958376f-0b36-4f59-adae-c1e55ff3b848",
            "secretKey" => "9F1455F4576C09A1DE06CBD4E9B3804F9184EF91978F3A9A92D7AD4B71656109",
        ));

        $publishResponse = $beamsClient->publishToInterests(
            array('admin'),
            array("web" => array("notification" => array(
              "title" => "📣 Cek sekarang, ada antrian baru !",
              "body" => "Cek antrian workshop sekarang, jangan sampai lupa diantrikan ya !",
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
        }elseif($jenis == 'digital printing'){
            $operators = 'rekanan';
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

        if($antrian->end_job == null){
            $isEdited = 0;
        }else{
            $isEdited = 1;
        }

        return view('page.antrian-workshop.edit', compact('antrian', 'operatorId', 'finisherId', 'qualityId', 'operators', 'qualitys', 'machines', 'tempat', 'isEdited'));
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
        if($request->isEdited == 0){
            foreach($users as $user){
                $publishResponse = $beamsClient->publishToUsers(
                    array($user),
                    array("web" => array("notification" => array(
                    "title" => "📣 Cek sekarang, ada antrian baru !",
                    "body" => "Cek pekerjaan baru sekarang, cepat kerjakan biar cepet pulang !",
                    )),
                ));

                $user = str_replace('user-', '', $user);
                $user = User::find($user);
                if($user != 'rekanan'){
                    $user->notify(new AntrianDiantrikan($antrian));
                }
            }
        }else{
            foreach($users as $user){
                if($user != 'user-rekananSBY' || $user != 'user-rekananKDR' || $user != 'user-rekananMLG'){
                    $publishResponse = $beamsClient->publishToUsers(
                        array($user),
                        array("web" => array("notification" => array(
                        "title" => "📣 Hai, ada update antrian!",
                        "body" => "Ada perubahan pada antrian " . $antrian->ticket_order . " (" . $antrian->order->title ."), cek sekarang !",
                        )),
                    ));
                }

                $user = str_replace('user-', '', $user);
                $user = User::find($user);
                if($user != 'rekanan'){
                    $user->notify(new AntrianDiantrikan($antrian));
                }
            }
        }

        return redirect()->route('antrian.index')->with('success-update', 'Data antrian berhasil diupdate!');
    }

    public function updateDeadline(Request $request)
    {
        $antrian = Antrian::find($request->id);
        if (now() > $antrian->end_job) {
            $status = 2;
        }
        $antrian->deadline_status = $status;
        $antrian->save();

        return response()->json(['message' => 'Success'], 200);
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
            Storage::disk('public')->put($path, file_get_contents($file));

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
        $search = $request->search;

        if($search == ''){
            $machines = Machine::get();
        }else{
            $machines = Machine::orderby('machine_code','asc')->select('machine_code', 'machine_name')->where('machine_name', 'like', '%' .$search . '%')->get();
        }

        $response = array();
        foreach($machines as $machine){
            $response[] = array(
                "id" => $machine->machine_code,
                "text" => $machine->machine_name
            );
        }
        return response()->json($response);
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
        Storage::disk('public')->put($pathGambar, file_get_contents($gambar));
        }else{
            $namaGambar = null;
        }

        if($request->file('fileVideo')){
        $video = $request->file('fileVideo');
        $namaVideo = time()."_".$video->getClientOriginalName();
        $pathVideo = 'dokum-proses/'.$namaVideo;
        Storage::disk('public')->put($pathVideo, file_get_contents($video));
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
        // $beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
        //     "instanceId" => "0958376f-0b36-4f59-adae-c1e55ff3b848",
        //     "secretKey" => "9F1455F4576C09A1DE06CBD4E9B3804F9184EF91978F3A9A92D7AD4B71656109",
        // ));

        // $publishResponse = $beamsClient->publishToInterests(
        //     array("operator"),
        //     array("web" => array("notification" => array(
        //       "title" => "🔔 Kring.. Reminder!",
        //       "body" => "Yuk cek progress pekerjaanmu sekarang, jangan lupa upload progressnya ya !",
        //       "deep_link" => "https://interatama.my.id/",
        //     )),
        // ));

        return response()->json('success', 200);
    }
}
