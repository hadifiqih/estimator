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
        <label for="title" class="form-label">Judul Desain (Keyword)</label>
        <input type="text" class="form-control" id="title" name="title" placeholder="Judul Desain">
      </div>

      <div class="mb-3">
        <label for="sales" class="form-label">Sales</label>
        <select class="custom-select rounded-2" name="sales" id="sales">
          <option selected disabled>Pilih Sales</option>
          @foreach($sales as $sale)
          <option value="{{ $sale->id }}">{{ $sale->sales_name }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label for="job" class="form-label">Jenis Produk</label>
        <select class="custom-select rounded-2" name="job" id="job">
          <option selected disabled>Pilih Jenis Produk</option>
          @foreach($jobs as $job)
          <option value="{{ $job->id }}">{{ $job->job_name }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Keterangan</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
      </div>

      {{-- Uplaoad File Referensi Desain --}}
      <div class="mb-3">
        <h6><strong>File Ref. Desain</strong></h6>
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
      <button type="submit" class="btn btn-primary">Tambah</button>
    </form>
  </div>
</div>
@endsection

@section('script')

<script>
  $(document).ready(function() {
    bsCustomFileInput.init();
  });
</script>

@endsection
