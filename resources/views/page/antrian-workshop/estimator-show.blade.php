@extends('layouts.app')

@section('title', 'Antrian | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Antrian')

@section('breadcrumb', 'Estimator By Job')

@section('content')
<div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Antrian</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="tanggalOrder">Tanggal Order</label>
                    <input id="tanggalOrder" name="tanggalOrder" type="text" class="form-control" value="{{ $antrian->created_at->format('d F Y') }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label" for="nama-project">Nama Project</label>
                    <input id="nama-project" name="nama-project" type="text" class="form-control" value="{{ $antrian->order->title }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label" for="sales">Sales</label>
                    <input id="sales" name="sales" type="text" class="form-control" value="{{ $antrian->sales->sales_name }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label" for="nama-pelanggan">Nama Pelanggan
                        @if($antrian->customer->frekuensi_order == 0)
                        <span class="badge badge-danger">New Leads</span>
                        @elseif($antrian->customer->frekuensi_order == 1)
                        <span class="badge badge-warning">New Customer</span>
                        @elseif($antrian->customer->frekuensi_order >= 2)
                        <span class="badge badge-success">Repeat Order</span>
                        @endif
                    </label>
                    <input id="nama-pelanggan" name="nama-pelanggan" type="text" class="form-control" value="{{ $antrian->customer->nama }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label" for="telepon">Telepon/WA</label>
                    <input id="telepon" name="telepon" type="text" class="form-control" value="{{ $antrian->customer->telepon }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label" for="alamat">Alamat Pelanggan</label>
                    <textarea id="alamat" name="alamat" class="form-control" rows="3" readonly>{{ $antrian->customer->alamat }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="sumber-pelanggan">Sumber Pelanggan</label>
                    <input id="sumber-pelanggan" name="sumber-pelanggan" type="text" class="form-control" value="{{ $antrian->customer->infoPelanggan }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label" for="nama-produk">Nama Produk</label>
                    <input id="nama-produk" name="nama-produk" type="text" class="form-control" value="{{ $antrian->job->job_name }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label" for="jumlah-produk">Jumlah Produk (Qty)</label>
                    <input id="jumlah-produk" name="jumlah-produk" type="text" class="form-control" value="{{ $antrian->qty }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label" for="keterangan">Keterangan / Spesifikasi Produk</label>
                    <textarea id="keterangan" name="keterangan" class="form-control" rows="6" readonly>{{ $antrian->note }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="mulai">Mulai</label>
                    <input id="mulai" name="mulai" type="text" class="form-control" value="{{ $antrian->start_job }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label" for="deadline">Deadline</label>
                    <input id="deadline" name="deadline" type="text" class="form-control" value="{{ $antrian->end_job }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label" for="desainer">Desainer</label>
                    <input id="desainer" name="desainer" type="text" class="form-control" value="{{ $antrian->order->employee->name }}" readonly>
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
                    <label class="form-label" for="nominal-omset">Nominal Omset</label>
                    <input id="nominal-omset" name="nominal-omset" type="text" class="form-control" value="Rp {{ number_format($antrian->omset, 0, ',', '.') }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label" for="harga-produk">Harga Produk</label>
                    <input id="harga-produk" name="harga-produk" type="text" class="form-control" value="Rp {{ number_format($antrian->harga_produk, 0, ',', '.') }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label" for="status-pembayaran">Status Pembayaran</label>
                    <input id="status-pembayaran" name="status-pembayaran" type="text" class="form-control" value="{{ $antrian->payment->payment_status }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label" for="metode">Metode Pembayaran</label>
                    <input id="metode" name="metode" type="text" class="form-control" value="{{ $antrian->payment->payment_method }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label" for="bayar">Nominal Pembayaran</label>
                    <input id="bayar" name="bayar" type="text" class="form-control" value="Rp {{ number_format($antrian->payment->payment_amount, 0, ',', '.') }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label" for="sisa">Sisa Pembayaran</label>
                    <input id="sisa" name="sisa" type="text" class="form-control" value="Rp {{ number_format($antrian->payment->remaining_payment, 0, ',', '.') }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label" for="pasang">Biaya Jasa Pasang</label>
                    <input id="pasang" name="pasang" type="text" class="form-control" value="Rp {{ $antrian->payment->installation_cost == null ? '-' : number_format($antrian->payment->installation_cost, 0, ',', '.') }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label" for="pengiriman">Biaya Jasa Pengiriman</label>
                    <input id="pengiriman" name="pengiriman" type="text" class="form-control" value="Rp {{ $antrian->payment->shipping_cost == null ? '-' : number_format($antrian->payment->shipping_cost, 0, ',', '.') }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label" for="alamat-kirim">Alamat Pengiriman</label>
                    <textarea id="alamat-kirim" name="alamat-kirim" class="form-control" rows="6" readonly>{{ $antrian->alamat_pengiriman == null ? '-' : $antrian->alamat_pengiriman }}</textarea>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Preview ACC Desain</label>
                            <div class="text-muted font-italic">{{ strlen($antrian->order->acc_desain) > 25 ? substr($antrian->order->acc_desain, 0, 25) . '...' : $antrian->order->acc_desain }}<button id="lihatAcc" type="button" class="btn btn-sm btn-primary ml-3">Lihat</button></div>
                        </div>
                        <div class="col-md-6">
                            <label>Preview Bukti Pembayaran</label>
                            <div class="text-muted font-italic">{{ strlen($antrian->payment->payment_proof) > 25 ? substr($antrian->payment->payment_proof, 0, 25) . '...' : $antrian->payment->payment_proof }}<button type="button" class="btn btn-sm btn-primary ml-3" data-toggle="modal" data-target="#modal-buktiPembayaran">Lihat</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-accdesain">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Preview File Acc Desain</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <img class="img-fluid" src="storage/acc-desain/{{ $antrian->order->acc_desain }}">
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#lihatAcc').on('click', function() {
            $('#modal-tampil-acc').modal('show');
        });
    })
</script>
@endsection