@extends('layouts.app')

@section('title', 'Desain Baru | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Antrian Desain')

@section('breadcrumb', 'Tambah Desain')

@section('content')

<div class="card card-warning">
  <h5 class="card-header">Tambah Antrian Desain</h5>

  {{-- Tampilkan jika ada error apapun --}}
  @if ($errors->any())
  <div class="alert alert-danger" role="alert">
    <ul>
      {{-- Tampilkan semua error yang ada --}}
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  <div class="card-body">
    <form action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      {{-- Inputan untuk judul desain --}}
      <div class="mb-3">
        <label for="title" class="form-label">Judul Gambar (Keyword)<span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="title" name="title" placeholder="Judul Desain">
      </div>

      {{-- Input Sales bertipe Hidden --}}
      <input type="hidden" name="sales" value="{{ $sales->id }}">

      <div class="mb-3">
        <label for="kategori" class="form-label">Kategori</label>
        <select class="custom-select rounded-2" name="kategori" id="kategori">
            <option selected disabled>--Pilih Kategori--</option>
            <option value="Stempel">Stempel</option>
            <option value="Non Stempel">Non Stempel</option>
            <option value="Advertising">Advertising</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="job" class="form-label">Jenis Produk</label>
        <select class="custom-select rounded-2" name="job" id="job">

        </select>
      </div>
      <button type="button" class="btn btn-sm btn-outline-primary mb-3" data-toggle="modal" data-target="#exampleModalProduk">
        Tambah Produk
      </button>

      <div class="mb-3">
        <label for="description" class="form-label">Keterangan</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
      </div>

      <h6 class="font-weight-bold">Jenis Pekerjaan<span class="text-danger">*</span></h6>
      <div class="form-check form-check-inline mb-3">
        <input class="form-check-input" type="radio" name="jenisPekerjaan" id="inlineRadio1" value="baru">
        <label class="form-check-label" for="inlineRadio1">Desain Baru</label>
      </div>
      <div class="form-check form-check-inline mb-3">
        <input class="form-check-input" type="radio" name="jenisPekerjaan" id="inlineRadio2" value="edit">
        <label class="form-check-label" for="inlineRadio2">Edit Desain</label>
      </div>

      {{-- Uplaoad File Referensi Desain --}}
      <div class="mb-3" id="refDesain">
        <h6><strong>File Ref. Desain</strong><span class="text-secondary font-italic"> (.jpeg / .jpg / .png / .cdr )</span></h6>
        <div class="input-group">
          <div class="custom-file">
            <input type="file" class="custom-file-input" id="refDesain" name="refdesain">
            <label class="custom-file-label" for="refDesain"><span class="text-secondary">Pilih File..</span></label>
          </div>
        </div>
      </div>

      {{-- Menangani jika ada error file tidak ditemukan --}}
      @if ($errors->has('design'))
        <div class="alert alert-danger" role="alert">
            {{ $errors->first('design') }}
        </div>
      @endif

      {{-- Checkbox untuk pesanan prioritas / tidak --}}
      <div class="mb-3">
        <div class="custom-control custom-checkbox">
          <input class="custom-control-input custom-control-input-danger" type="checkbox" id="defaultCheck" value="1" name="priority">
          <label class="custom-control-label" for="defaultCheck">
            Prioritas
          </label>
        </div>
      </div>

      {{-- Tombol Submit --}}
      <button type="submit" class="btn btn-primary submitButton">Submit</button><span id="loader" class="loader" style="display: none;"></span>
    </form>
  </div>
  {{-- Modal Tambah Jenis Produk --}}
  <div class="modal fade" id="exampleModalProduk" tabindex="-1" role="dialog" aria-labelledby="exampleModalProdukLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalProdukLabel">Tambah Produk Baru</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="produkForm" action="{{ route('tambahProdukByModal') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="modalNamaProduk">Nama Produk</label>
                <input type="text" class="form-control" id="modalNamaProduk" placeholder="Nama Pekerjaan" name="modalNamaProduk">
            </div>
            <div class="form-group">
                <label for="modalJenisProduk">Kategori Produk</label>
                <select class="custom-select rounded-0" id="modalJenisProduk" name="modalJenisProduk">
                    <option value="Stempel">Stempel</option>
                    <option value="Advertising">Advertising</option>
                    <option value="Non Stempel">Non Stempel</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary submitButton">Tambah</button><span id="loader" class="loader" style="display: none;"></span>
        </div>
        </form>
        </div>
    </div>
    </div>
</div>
@endsection

@section('script')

<script>
  $(document).ready(function() {
    bsCustomFileInput.init();

    $('#produkForm').submit(function(e) {
      e.preventDefault();
      var modalNamaProduk = $('#modalNamaProduk').val();
      var modalJenisProduk = $('#modalJenisProduk').val();

      $('.loader').show();
      $('.submitButton').attr('disabled', true);

      $.ajax({
        url: "{{ route('tambahProdukByModal') }}",
        type: "POST",
        data: {
          "_token": "{{ csrf_token() }}",
          "namaProduk": modalNamaProduk,
          "jenisProduk": modalJenisProduk,
        },
        success: function(data) {
          $('#exampleModalProduk').modal('hide');
          //menghapus inputan pada modal
            $('#modalNamaProduk').val('');
            $('#modalJenisProduk').val('');
          //muncul sweetalert2 success
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: data.message,
                showConfirmButton: false,
                timer: 3000
            });

            //reload halaman
            setInterval(() => {
                location.reload();
            }, 2500);

        },
        error: function(data) {
          //muncul sweetalert2 error
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
            });
        }
      });
    });

  });
</script>
<script>
    const kategoriSelect = $('#kategori');
    const jobSelect = $('#job');

    kategoriSelect.on('change', function() {
        const selectedCategoryId = kategoriSelect.val();
        jobSelect.empty().append('<option selected disabled>Pilih Jenis Produk</option>');

        if (selectedCategoryId) {
            $.ajax({
                url: `/get-jobs-by-category/${selectedCategoryId}`,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    $.each(data, function(index, job) {
                        jobSelect.append($('<option>', {
                            value: job.id,
                            text: job.job_name
                        }));
                    });
                }
            });
        }
    });
</script>

@endsection
