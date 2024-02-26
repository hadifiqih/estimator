<div class="modal fade" id="detailAntrian{{ $antrian->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Detail #{{ $antrian->ticket_order }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="tanggalOrder">Tanggal Order</label>
                <input id="tanggalOrder{{ $antrian->id }}" name="tanggalOrder" type="text" class="form-control" value="{{ $antrian->created_at->format('d F Y') }}" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="nama-project{{ $antrian->id }}">Nama Project</label>
                <input id="nama-project{{ $antrian->id }}" name="nama-project{{ $antrian->id }}" type="text" class="form-control" value="{{ $antrian->order->title }}" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="sales{{ $antrian->id }}">Sales</label>
                <input id="sales{{ $antrian->id }}" name="sales{{ $antrian->id }}" type="text" class="form-control" value="{{ $antrian->sales->sales_name }}" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="nama-pelanggan{{ $antrian->id }}">Nama Pelanggan
                    @if($antrian->customer->frekuensi_order == 0)
                    <span class="badge badge-danger">New Leads</span>
                    @elseif($antrian->customer->frekuensi_order == 1)
                    <span class="badge badge-warning">New Customer</span>
                    @elseif($antrian->customer->frekuensi_order >= 2)
                    <span class="badge badge-success">Repeat Order</span>
                    @endif
                </label>
                <input id="nama-pelanggan{{ $antrian->id }}" name="nama-pelanggan{{ $antrian->id }}" type="text" class="form-control" value="{{ $antrian->customer->nama }}" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="telepon{{ $antrian->id }}">Telepon/WA</label>
                <input id="telepon{{ $antrian->id }}" name="telepon{{ $antrian->id }}" type="text" class="form-control" value="{{ $antrian->customer->telepon }}" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="alamat{{ $antrian->id }}">Alamat Pelanggan</label>
                <textarea id="alamat{{ $antrian->id }}" name="alamat{{ $antrian->id }}" class="form-control" rows="3" readonly>{{ $antrian->customer->alamat }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label" for="sumber-pelanggan{{ $antrian->id }}">Sumber Pelanggan</label>
                <input id="sumber-pelanggan{{ $antrian->id }}" name="sumber-pelanggan{{ $antrian->id }}" type="text" class="form-control" value="{{ $antrian->customer->infoPelanggan }}" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="nama-produk{{ $antrian->id }}">Nama Produk</label>
                <input id="nama-produk{{ $antrian->id }}" name="nama-produk{{ $antrian->id }}" type="text" class="form-control" value="{{ $antrian->job->job_name }}" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="jumlah-produk{{ $antrian->id }}">Jumlah Produk (Qty)</label>
                <input id="jumlah-produk{{ $antrian->id }}" name="jumlah-produk{{ $antrian->id }}" type="text" class="form-control" value="{{ $antrian->qty }}" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="keterangan{{ $antrian->id }}">Keterangan / Spesifikasi Produk</label>
                <textarea id="keterangan{{ $antrian->id }}" name="keterangan{{ $antrian->id }}" class="form-control" rows="6" readonly>{{ $antrian->note }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label" for="mulai{{ $antrian->id }}">Mulai</label>
                <input id="mulai{{ $antrian->id }}" name="mulai{{ $antrian->id }}" type="text" class="form-control" value="{{ $antrian->start_job }}" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="deadline{{ $antrian->id }}">Deadline</label>
                <input id="deadline{{ $antrian->id }}" name="deadline{{ $antrian->id }}" type="text" class="form-control" value="{{ $antrian->end_job }}" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="desainer{{ $antrian->id }}">Desainer</label>
                <input id="desainer{{ $antrian->id }}" name="desainer{{ $antrian->id }}" type="text" class="form-control" value="{{ $antrian->order->employee->name }}" readonly>
            </div>
            <hr>
            <div class="form-group">
                <span class="form-label font-weight-bold"> Tempat : </span>
                    @php
                        $tempat = explode(',', $antrian->working_at);
                        foreach ($tempat as $item) {
                                if($item == 'Surabaya'){
                                    if($item == end($tempat)){
                                        echo '<a class="btn btn-sm btn-danger ml-2 mr-2">Surabaya</a>';
                                    }
                                    else{
                                        echo '<a class="btn btn-sm btn-danger ml-2 mr-2">Surabaya</a>';
                                    }
                                }elseif ($item == 'Kediri') {
                                    if($item == end($tempat)){
                                        echo '<a class="btn btn-sm btn-warning ml-2 mr-2">Kediri</a>';
                                    }
                                    else{
                                        echo '<a class="btn btn-sm btn-warning ml-2 mr-2">Kediri</a>';
                                    }
                                }elseif ($item == 'Malang') {
                                    if($item == end($tempat)){
                                        echo '<a class="btn btn-sm btn-success ml-2 mr-2">Malang</a>';
                                    }
                                    else{
                                        echo '<a class="btn btn-sm btn-success ml-2 mr-2">Malang</a>';
                                    }
                                }
                            }
                    @endphp
            </div>
            <hr>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-4 rounded-2">
                <span class="form-label font-weight-bold">Operator</span>
                <p>
                @if($antrian->operator_id)
                    @php
                        $operatorId = explode(',', $antrian->operator_id);
                        foreach ($operatorId as $item) {
                            if($item == 'rekanan'){
                                echo '<i class="fas fa-user-circle"></i> Rekanan';
                            }
                            else{
                                $antriann = App\Models\Employee::find($item);
                                //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                if($antriann->id == end($operatorId)){
                                    echo '<i class="fas fa-user-circle"></i>  ' . $antriann->name;
                                }
                                else{
                                    echo '<i class="fas fa-user-circle"></i>  ' . $antriann->name . "<br>";
                                }
                            }
                        }
                    @endphp
                    @else
                    -
                    @endif
                </p>
            </div>
            <div class="col-md-4 rounded-2">
                <span class="form-label font-weight-bold">Finishing</span>
                <p>
                @if($antrian->finisher_id)
                    @php
                        $finisherId = explode(',', $antrian->finisher_id);
                        foreach ($finisherId as $item) {
                            if($item == 'rekanan'){
                                echo '<i class="fas fa-user-circle"></i> Rekanan';
                            }
                            else{
                                $antriann = App\Models\Employee::find($item);
                                //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                if($antriann->id == end($finisherId)){
                                    echo '<i class="fas fa-user-circle"></i>  ' . $antriann->name;
                                }
                                else{
                                    echo '<i class="fas fa-user-circle"></i>  ' . $antriann->name . "<br>";
                                }
                            }
                        }
                    @endphp
                    @else
                    -
                    @endif
                </p>
            </div>
            <div class="col-md-4 rounded-2">
                <span class="form-label font-weight-bold">Pengawas / QC</span>
                <p>
                @if($antrian->qc_id)
                    @php
                        $qcId = explode(',', $antrian->qc_id);
                        foreach ($qcId as $item) {
                                $antriann = App\Models\Employee::find($item);
                                //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                if($antriann->id == end($qcId)){
                                    echo '<i class="fas fa-user-circle"></i>  ' . $antriann->name;
                                }
                                else{
                                    echo '<i class="fas fa-user-circle"></i>  ' . $antriann->name . "<br>";
                                }
                            }
                    @endphp
                    @else
                        -
                    @endif
                </p>
            </div>
            </div>
            </div>
            <hr>
            <div class="form-group">
                <span class="form-label font-weight-bold">Mesin : </span>
                @if($antrian->machine_code)
                        @php
                            $machineCode = explode(',', $antrian->machine_code);

                            foreach ($machineCode as $item) {
                                    $antriann = App\Models\Machine::where('machine_code', $item)->first();
                                    //tampilkan name dari tabel machines, jika nama terakhir tidak perlu koma
                                    if($antriann->machine_code == end($machineCode)){
                                        echo '<a class="btn btn-sm btn-dark ml-2 mr-2">' . $antriann->machine_name . '</a>';
                                    }
                                    else{
                                        echo '<a class="btn btn-sm btn-dark ml-2 mr-2">' . $antriann->machine_name . '</a>';
                                    }
                                }
                        @endphp
                    @else
                        -
                    @endif
            </div>
            <hr>
            <div class="form-group">
                <label class="form-label" for="nominal-omset{{ $antrian->id }}">Nominal Omset</label>
                <input id="nominal-omset{{ $antrian->id }}" name="nominal-omset{{ $antrian->id }}" type="text" class="form-control" value="Rp {{ number_format($antrian->omset, 0, ',', '.') }}" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="harga-produk{{ $antrian->id }}">Harga Produk</label>
                <input id="harga-produk{{ $antrian->id }}" name="harga-produk{{ $antrian->id }}" type="text" class="form-control" value="Rp {{ number_format($antrian->harga_produk, 0, ',', '.') }}" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="status-pembayaran{{ $antrian->id }}">Status Pembayaran</label>
                <input id="status-pembayaran{{ $antrian->id }}" name="status-pembayaran{{ $antrian->id }}" type="text" class="form-control" value="{{ $antrian->payment->payment_status }}" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="metode{{ $antrian->id }}">Metode Pembayaran</label>
                <input id="metode{{ $antrian->id }}" name="metode{{ $antrian->id }}" type="text" class="form-control" value="{{ $antrian->payment->payment_method }}" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="bayar{{ $antrian->id }}">Nominal Pembayaran</label>
                <input id="bayar{{ $antrian->id }}" name="bayar{{ $antrian->id }}" type="text" class="form-control" value="Rp {{ number_format($antrian->payment->payment_amount, 0, ',', '.') }}" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="sisa{{ $antrian->id }}">Sisa Pembayaran</label>
                <input id="sisa{{ $antrian->id }}" name="sisa{{ $antrian->id }}" type="text" class="form-control" value="Rp {{ number_format($antrian->payment->remaining_payment, 0, ',', '.') }}" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="pasang{{ $antrian->id }}">Biaya Jasa Pasang</label>
                <input id="pasang{{ $antrian->id }}" name="pasang{{ $antrian->id }}" type="text" class="form-control" value="Rp {{ $antrian->payment->installation_cost == null ? '-' : number_format($antrian->payment->installation_cost, 0, ',', '.') }}" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="pengiriman{{ $antrian->id }}">Biaya Jasa Pengiriman</label>
                <input id="pengiriman{{ $antrian->id }}" name="pengiriman{{ $antrian->id }}" type="text" class="form-control" value="Rp {{ $antrian->payment->shipping_cost == null ? '-' : number_format($antrian->payment->shipping_cost, 0, ',', '.') }}" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="alamat-kirim{{ $antrian->id }}">Alamat Pengiriman</label>
                <textarea id="alamat-kirim{{ $antrian->id }}" name="alamat-kirim{{ $antrian->id }}" class="form-control" rows="6" readonly>{{ $antrian->alamat_pengiriman == null ? '-' : $antrian->alamat_pengiriman }}</textarea>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label>Preview ACC Desain</label>
                        <div class="text-muted font-italic">{{ strlen($antrian->order->acc_desain) > 25 ? substr($antrian->order->acc_desain, 0, 25) . '...' : $antrian->order->acc_desain }}<button type="button" class="btn btn-sm btn-primary ml-3" data-toggle="modal" data-target="#modal-accdesain{{ $antrian->id }}">Lihat</button></div>
                    </div>
                    <div class="col-md-6">
                        <label>Preview Bukti Pembayaran</label>
                        <div class="text-muted font-italic">{{ strlen($antrian->payment->payment_proof) > 25 ? substr($antrian->payment->payment_proof, 0, 25) . '...' : $antrian->payment->payment_proof }}<button type="button" class="btn btn-sm btn-primary ml-3" data-toggle="modal" data-target="#modal-buktiPembayaran{{ $antrian->id }}">Lihat</button></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
    </div>
    </div>
</div>