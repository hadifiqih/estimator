@extends('layouts.app')

@section('title', 'Antrian | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Tambah Antrian')

@section('breadcrumb', 'Tambah Antrian')

@section('content')
<div class="container-fluid">
    <form action="{{ route('antrian.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                  <h2 class="card-title">Data Pelanggan</h2>
                </div>
                <div class="card-body">
                    {{-- Tambah Pelanggan Baru --}}
                    <button type="button" class="btn btn-sm btn-primary mb-3" data-toggle="modal" data-target="#exampleModal">
                        Tambah Pelanggan Baru
                    </button>

                    {{-- Form Data Pelanggan --}}
                    <div class="form-group">
                        <label for="noHp">No. HP</label>
                        <input type="tel" class="form-control" id="noHp" name="noHp" placeholder="Nomor Telepon">
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama Pelanggan</label>
                        <select class="form-control select2" id="nama" name="nama" style="width: 100%">

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" class="form-control" id="alamat" placeholder="Alamat Pelanggan">
                    </div>
                    <div class="form-group">
                        <label for="instansi">Instansi</label>
                        <input type="text" class="form-control" id="instansi" placeholder="Instansi Pelanggan">
                    </div>
                    <div class="form-group">
                        <label for="infoPelanggan">Sumber Pelanggan</label>
                        <select class="custom-select rounded-0" id="infoPelanggan" name="infoPelanggan">
                            <option value="default" selected>Pilih Sumber Pelanggan</option>
                            <option value="Google">Google</option>
                            <option value="Facebook">Facebook</option>
                            <option value="Tokopedia">Tokopedia</option>
                            <option value="Shopee">Shopee</option>
                            <option value="Instagram">Instagram</option>
                            <option value="Tiktok">Tiktok</option>
                            <option value="Teman/Keluarga/Kerabat">Teman/Keluarga/Kerabat</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                      </div>
              </div>
            </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Data Pekerjaan</h2>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="namaPekerjaan">Nama Pekerjaan</label>
                        {{-- Nama Pekerjaan Select2 --}}
                        <select class="form-control select2" id="namaPekerjaan" name="namaPekerjaan" style="width: 100%">

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jenisPekerjaan">Jenis Pekerjaan</label>
                        <select class="custom-select rounded-0" id="jenisPekerjaan" name="jenisPekerjaan">
                            <option value="Stempel">Stempel</option>
                            <option value="Advertising">Advertising</option>
                            <option value="Non Stempel">Non Stempel</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan Spesfikasi</label>
                        <textarea class="form-control" id="keterangan" rows="5" placeholder="Keterangan" name="keterangan"></textarea>
                    </div>
              </div>
            </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Data Pembayaran</h2>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        {{-- Total Pembayaran --}}
                        <label for="totalPembayaran">Total Harga</label>
                        <input type="number" class="form-control rupiah" id="totalPembayaran" placeholder="Rp" name="totalPembayaran">
                    </div>
                    <div class="form-group">
                        <label for="jenisPembayaran">Jenis Pembayaran</label>
                        <select class="custom-select rounded-0" id="jenisPembayaran" name="jenisPembayaran">
                            <option value="Cash">Cash</option>
                            <option value="Transfer BCA">Transfer BCA</option>
                            <option value="Transfer BNI">Transfer BNI</option>
                            <option value="Transfer BRI">Transfer BRI</option>
                            <option value="Transfer Mandiri">Transfer Mandiri</option>
                            <option value="Saldo Tokopedia">Marketplace Tokopedia</option>
                            <option value="Saldo Shopee">Marketplace Shopee</option>
                            <option value="Saldo Shopee">Marketplace Bukalapak</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="statusPembayaran">Status Pembayaran</label>
                        <select class="custom-select rounded-0" id="statusPembayaran" name="statusPembayaran">
                            <option value="DP">DP</option>
                            <option value="Lunas">Lunas</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jumlahPembayaran">Jumlah Pembayaran</label>
                        <input type="text" class="form-control rupiah" id="jumlahPembayaran" placeholder="Rp" name="jumlahPembayaran">
                    </div>
                    {{-- Tampilkan sisa pembayaran jika status pembayaran = DP, tampilkan Lunas jika status pembayaran Lunas --}}
                    <div class="form-group">
                        <label for="sisaPembayaran">Sisa Pembayaran</label>
                        <input type="number" class="form-control rupiah" id="sisaPembayaran" placeholder="Rp" name="sisaPembayaran" readonly>
                    </div>

                    <div class="form-group">
                        {{-- Bukti Pembayaran / Transfer --}}
                        <label for="buktiPembayaran">Bukti Pembayaran</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="buktiPembayaran" name="buktiPembayaran">
                                <label class="custom-file-label" for="buktiPembayaran">Pilih File</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Data Desain</h2>
                </div>
                <div class="card-body">
                    <input type="hidden" name="idOrder" value="{{ $order->id }}">
                    <div class="form-group">
                        <label for="namaDesain">Nama Desain</label>
                        <input type="text" class="form-control" id="namaDesain" placeholder="Nama Desain" name="namaDesain" value="{{ $order->title }}" readonly>
                    </div>
                    <div class="form-group">
                        {{-- Desainer --}}
                        <label for="desainer">Desainer</label>
                        <input type="text" class="form-control" id="desainer" placeholder="Nama Desainer" name="desainer" value="{{ $order->user->name }}" readonly>
                    </div>
                    <div class="form-group">
                        {{-- File Desain --}}
                        <h6><strong>File Cetak</strong></h6>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="fileDesain" name="fileDesain" value="{{ $order->file_cetak }}" disabled>
                                <label class="custom-file-label" for="fileDesain">{{ $order->file_cetak }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {{-- File Desain --}}
                        <h6><strong>Preview File ACC</strong></h6>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="accDesain" name="accDesain">
                                <label class="custom-file-label" for="accDesain">Unggah Gambar</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
      {{-- Tombol Submit Antrikan --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-right">
                        <input type="hidden" name="sales" value="{{ $order->sales_id }}">
                        {{-- Tombol Submit --}}
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Pelanggan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form id="pelanggan-form" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="noHp">No. HP</label>
                        <input type="tel" class="form-control" id="modalTelepon" placeholder="Nomor Telepon" name="modalTelepon">
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama Pelanggan</label>
                        <input type="text" class="form-control" id="modalNama" placeholder="Nama Pelanggan" name="modalNama">
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" class="form-control" id="modalAlamat" placeholder="Alamat Pelanggan" name="modalAlamat">
                    </div>
                    <div class="form-group">
                        <label for="instansi">Instansi</label>
                        <input type="text" class="form-control" id="modalInstansi" placeholder="Instansi Pelanggan" name="modalInstansi">
                    </div>
                    <div class="form-group">
                        <label for="infoPelanggan">Sumber Pelanggan</label>
                        <select class="custom-select rounded-0" id="infoPelanggan" name="modalInfoPelanggan">
                            <option value="default" selected>Pilih Sumber Pelanggan</option>
                            <option value="Google">Google</option>
                            <option value="Facebook">Facebook</option>
                            <option value="Tokopedia">Tokopedia</option>
                            <option value="Shopee">Shopee</option>
                            <option value="Bukalapak">Bukalapak</option>
                            <option value="Instagram">Instagram</option>
                            <option value="Tiktok">Tiktok</option>
                            <option value="Youtube">Youtube</option>
                            <option value="Snackvideo">Snackvideo</option>
                            <option value="OLX">OLX</option>
                            <option value="Teman/Keluarga/Kerabat">Teman/Keluarga/Kerabat</option>
                            <option value="Iklan Facebook">Iklan Facebook</option>
                            <option value="Iklan Instagram">Iklan Instagram</option>
                            <option value="Iklan Google">Iklan Google</option>
                            <option value="Iklan Tiktok">Iklan Tiktok</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" id="submitPelanggan">Simpan</button>
                </div>
            </form>
            </div>
            </div>
        </div>

</div>

@endsection

@section('script')
    <script>
        $(document).ready(function(){
            $('#nama').select2({
                placeholder: 'Pilih Pelanggan',
                ajax:{
                    url:"{{ route('pelanggan.search') }}",
                    processResults: function(data){
                        $('#alamat').val('');
                        $('#noHp').val('');
                        return{
                            results: $.map(data, function(item){
                                return{
                                    id: item.id,
                                    text: item.nama,
                                }
                            })
                        }
                    },
                    cache: true
                }
            });

            $('#namaPekerjaan').select2({
                placeholder: 'Pilih Pekerjaan',
                ajax:{
                    url:"{{ route('job.search') }}",
                    processResults: function(data){
                        $('#namaPekerjaan').val('');
                        $('#keterangan').val('');
                        return{
                            results: $.map(data, function(item){
                                return{
                                    id: item.id,
                                    text: item.job_name,
                                }
                            })
                        }
                    },
                    cache: true
                }
            });

            $('#namaPekerjaan').change(function(){
                var selected = $(this).val();
                $.ajax({
                    url:"{{ route('job.searchByNama') }}",
                    type:"GET",
                    data:{
                        id:selected
                    },
                    success:function(response) {
                        $.each(response, function(index,item){
                            $('#jenisPekerjaan').val(item.job_type);
                            $('#keterangan').val(item.note);
                        })
                    }
                })
            });

            $('#nama').change(function(){
                var selected = $(this).val();
                $.ajax({
                    url:"{{ route('pelanggan.searchById') }}",
                    type:"GET",
                    data:{
                        id:selected
                    },
                    success:function(response) {

                        $.each(response, function(index,item){
                            $('#alamat').val(item.alamat);
                            $('#instansi').val(item.instansi);
                            $('#noHp').val(item.telepon);
                            $('#instansi').val(item.instansi);
                            $('#infoPelanggan').val(item.infoPelanggan);
                        })
                    }
                })
            });

    function formatNumber(input) {
      return input.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Fungsi untuk menghapus titik dan mengembalikan angka asli
    function unformatNumber(input) {
      return input.replace(/\./g, "");
    }

    // Event listener untuk input field
    $(".rupiah").on("input", function() {
      // Ambil nilai input
      var inputVal = $(this).val();

      // Hapus titik dari nilai input sebelum diubah menjadi angka asli
      var unformatted = unformatNumber(inputVal);

      // Format angka dengan menambahkan titik sebagai pemisah ribuan
      var formatted = formatNumber(unformatted);

      // Set nilai input kembali dengan angka yang telah diformat
      $(this).val(formatted);
    });

    $('#jumlahPembayaran').change( function(){
        var total = parseInt(unformatNumber($('#totalPembayaran').val()));
        var jumlah = parseInt(unformatNumber($('#jumlahPembayaran').val()));
        var sisaPembayaran = total - jumlah;
        $('#sisaPembayaran').val(formatNumber(sisaPembayaran));
    });

    $('#pelanggan-form').on('submit', function(e){
        e.preventDefault();
        const formData = $(this).serialize();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url:"{{ route('pelanggan.store') }}",
            type:"POST",
            data: formData,
            success:function(response){
                if(response.success){
                    $('#exampleModal').modal('hide');
                    //Mengosongkan Form pada Modal
                    $('#modalTelepon').val('');
                    $('#modalNama').val('');
                    $('#modalAlamat').val('');
                    $('#modalInstansi').val('');
                    $('#infoPelanggan').val('default');

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Data Pelanggan Berhasil Ditambahkan',
                        timer: 2500
                    });

                    setInterval(() => {
                        location.reload();
                    }, 2500);
                }

            }
        });
    });
});
</script>
<script>
    $(function () {
      bsCustomFileInput.init();
    });
</script>
@endsection
