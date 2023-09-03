@extends('layouts.app')

@section('title', 'Antrian | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Antrian')

@section('breadcrumb', 'Antrian Desain')

@section('style')
  <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/dropzone/min/dropzone.min.css">
@endsection

@section('content')

@if(session('success-take'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success-take') }}
    </div>
@endif

{{-- Jika ada sesi success-order --}}
@if(session('success-design'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success-design') }}
    </div>
@endif

{{-- Jika ada sesi error --}}
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
    </div>
@endif


<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs mb-2" id="custom-content-below-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Menunggu</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Dikerjakan</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-messages-tab" data-toggle="pill" href="#custom-content-below-messages" role="tab" aria-controls="custom-content-below-messages" aria-selected="false">Selesai</a>
                </li>
            </ul>
            <div class="tab-content" id="custom-content-below-tabContent">

            <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                <div class="card">
                <div class="card-header">
                  <h2 class="card-title">Antrian Desain</h2>
                  {{-- Tombol tambah order --}}
                    @if(Auth::user()->role == 'sales')
                        <a href="{{ url('order/create') }}" class="btn btn-sm btn-warning float-right"><strong>Tambah Desain</strong></a>
                    @endif
                </div>
                <div class="card-body">
                {{-- Menampilkan Antrian Desain --}}
                <table id="tableAntrianDesain" class="table table-bordered table-hover table-responsive">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Keyword</th>
                            <th>Ref. Desain</th>
                            <th>Jenis Pekerjaan</th>
                            <th>Status</th>
                            <th>Waktu Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listDesain as $desain)
                        <tr>
                            <td>
                                {{ $loop->iteration }}
                                {{-- Jika is_priority = 1, maka tambahkan icon star war warna kuning disebelah nomer urut --}}
                                @if($desain->is_priority == '1')
                                    <i class="fas fa-star text-warning"></i>
                                @endif
                            </td>

                            <td>{{ $desain->title }}</td>

                            {{-- Jika desain tidak kosong, maka tampilkan nama file desain, jika kosong, maka tampilkan tanda strip (-) --}}
                            @if($desain->desain != null)
                                @php
                                    $refImage = strlen($desain->desain) > 15 ? substr($desain->desain, 0, 15) . '...' : $desain->desain;
                                @endphp
                                    <td scope="row"><a href="{{ asset('storage/ref-desain/'.$desain->desain) }}" target="_blank">{{ $refImage }}</a></td>
                            @else
                                    <td scope="row">-</td>
                            @endif

                            <td>
                                @php
                                if($desain->type_work){
                                    if($desain->type_work == 'baru'){
                                        echo 'Desain Baru';
                                    }elseif($desain->type_work == 'edit'){
                                        echo 'Edit Desain';
                                    }
                                }else{
                                    echo '-';
                                }
                                @endphp
                            </td>

                            {{-- Jika status = 0, maka tampilkan badge warning, jika status = 1, maka tampilkan badge primary, jika status = 2, maka tampilkan badge success --}}
                            @if($desain->status == '0')
                                <td><span class="badge badge-warning">Menunggu</span></td>
                            @elseif($desain->status == '1')
                                <td><span class="badge badge-primary">Dikerjakan</span></td>
                            @elseif($desain->status == '2')
                                <td><span class="badge badge-success">Selesai</span></td>
                            @endif

                            <td>{{ $desain->created_at }}</td>

                            {{-- Jika role = desain, maka tampilkan tombol aksi --}}
                                <td>
                                    <a href="{{ url('order/'. $desain->id .'/edit') }}" class="btn btn-sm btn-primary" {{ Auth::user()->role != 'sales' ? "style=display:none" : '' }}><i class="fas fa-edit"></i></a>
                                    <a href="{{ url('order/'. $desain->id .'/take') }}" class="btn btn-sm btn-success" {{ Auth::user()->role != 'desain' ? "style=display:none" : '' }}><i class="fas fa-hand-paper"></i></a>
                                    {{-- Tombol Modal Detail Keterangan Desain --}}
                                    <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailWorking{{ $desain->id }}">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- End Menampilkan Antrian Desain --}}
                {{-- Modal Keterangan Desain --}}
                @foreach ($listDesain as $desain)
                <div class="modal fade" id="detailWorking{{ $desain->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title">Detail #{{ $desain->ticket_order }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="createdAtWorking{{ $desain->id }}">Waktu Dibuat</label>
                                        <input type="text" class="form-control" id="createdAtWorking{{ $desain->id }}" name="createdAt" value="{{ $desain->created_at }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lastUpdateWorking{{ $desain->id }}">Terakhir Diupdate</label>
                                        <input type="text" class="form-control" id="lastUpdateWorking{{ $desain->id }}" name="lastUpdate" value="{{ $desain->updated_at }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="namaSalesWorking{{ $desain->id }}">Nama Sales</label>
                                <input type="text" class="form-control" id="namaSalesWorking{{ $desain->id }}" name="namaSales" value="{{ $desain->sales->sales_name }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="judulDesainWorking{{ $desain->id }}">Judul Desain</label>
                                <input type="text" class="form-control" id="judulDesainWorking{{ $desain->id }}" name="judulDesain" value="{{ $desain->title }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="jenisProdukWorking{{ $desain->id }}">Jenis Produk</label>
                                <input type="text" class="form-control" id="jenisProdukWorking{{ $desain->id }}" name="jenisPekerjaan" value="{{ $desain->job->job_name }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="keteranganWorking{{ $desain->id }}">Keterangan</label>
                                <textarea class="form-control" id="keteranganWorking{{ $desain->id }}" name="keterangan" rows="3" readonly>{{ $desain->description }}</textarea>
                            </div>
                            <div class="form-group">
                                <h6><strong>Referensi Desain</strong></h6><br>
                                <img src="{{ asset('storage/ref-desain/'. $desain->desain) }}" class="img-fluid" alt="Preview Image">
                            </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                    </div>
                </div>
                @endforeach
                    </div>
                </div>
            </div>


            <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                <div class="card">
                    <div class="card-header">
                      <h2 class="card-title">Antrian Desain</h2>
                      {{-- Tombol tambah order --}}
                        @if(Auth::user()->role == 'sales')
                            <a href="{{ url('order/create') }}" class="btn btn-sm btn-warning float-right"><strong>Tambah Desain</strong></a>
                        @endif
                    </div>
                    <div class="card-body">
                    {{-- Menampilkan Antrian Desain --}}
                    <table id="tableAntrianDikerjakan" class="table table-bordered table-hover table-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Sales</th>
                                <th>Keyword</th>
                                <th>Ref. Desain</th>
                                <th>Jenis Pekerjaan</th>
                                <th>Desainer</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($listDikerjakan as $desain)
                            <tr>
                                <td>
                                    {{ $loop->iteration }}
                                    {{-- Jika is_priority = 1, maka tambahkan icon star war warna kuning disebelah nomer urut --}}
                                    @if($desain->is_priority == '1')
                                        <i class="fas fa-star text-warning"></i>
                                    @endif
                                </td>

                                <td>{{ $desain->sales->sales_name }}</td>

                                <td>{{ $desain->title }}</td>

                                @if($desain->desain != null)
                                    @php
                                        $refImage = strlen($desain->desain) > 15 ? substr($desain->desain, 0, 15) . '...' : $desain->desain;
                                    @endphp
                                        <td scope="row"><a href="{{ asset('storage/ref-desain/'.$desain->desain) }}" target="_blank">{{ $refImage }}</a></td>
                                @else
                                        <td scope="row">-</td>
                                @endif

                                <td>{{ $desain->job->job_name }}</td>

                                <td>{{ $desain->user->name }}</td>

                                @if($desain->status == '0')
                                    <td><span class="badge badge-warning">Menunggu</span></td>
                                @elseif($desain->status == '1')
                                    <td><span class="badge badge-primary">Dikerjakan</span></td>
                                @elseif($desain->status == '2')
                                    <td><span class="badge badge-success">Selesai</span></td>
                                @endif

                                <td>
                                    <a href="{{ url('order/'. $desain->id .'/edit') }}" class="btn btn-sm btn-primary" {{ Auth::user()->role != 'sales' ? "style=display:none" : '' }}><i class="fas fa-edit"></i></a>
                                    {{-- Button untuk menampilkan modal Upload --}}
                                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalUpload{{ $desain->id }}" {{ Auth::user()->role != 'desain' ? "style=display:none" : '' }}><i class="fas fa-upload"></i></button>
                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailWorking{{ $desain->id }}">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                    {{-- Modal Keterangan Desain --}}
                                    <div class="modal fade" id="detailWorking{{ $desain->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h5 class="modal-title">Detail #{{ $desain->ticket_order }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="createdAtWorking{{ $desain->id }}">Waktu Dibuat</label>
                                                            <input type="text" class="form-control" id="createdAtWorking{{ $desain->id }}" name="createdAt" value="{{ $desain->created_at }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="lastUpdateWorking{{ $desain->id }}">Terakhir Diupdate</label>
                                                            <input type="text" class="form-control" id="lastUpdateWorking{{ $desain->id }}" name="lastUpdate" value="{{ $desain->updated_at }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="namaSalesWorking{{ $desain->id }}">Nama Sales</label>
                                                    <input type="text" class="form-control" id="namaSalesWorking{{ $desain->id }}" name="namaSales" value="{{ $desain->sales->sales_name }}" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="judulDesainWorking{{ $desain->id }}">Judul Desain</label>
                                                    <input type="text" class="form-control" id="judulDesainWorking{{ $desain->id }}" name="judulDesain" value="{{ $desain->title }}" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="jenisProdukWorking{{ $desain->id }}">Jenis Produk</label>
                                                    <input type="text" class="form-control" id="jenisProdukWorking{{ $desain->id }}" name="jenisPekerjaan" value="{{ $desain->job->job_name }}" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="keteranganWorking{{ $desain->id }}">Keterangan</label>
                                                    <textarea class="form-control" id="keteranganWorking{{ $desain->id }}" name="keterangan" rows="3" readonly>{{ $desain->description }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <h6><strong>Referensi Desain</strong></h6><br>
                                                    <img src="/storage/ref-desain/{{ $desain->desain }}" class="img-fluid" alt="Responsive image">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- End Menampilkan Antrian Desain --}}
                    {{-- Modal Upload --}}
                    @foreach ($listDikerjakan as $desain)
                    <div class="modal fade" id="modalUpload{{ $desain->id }}" aria-labelledby="modalUpload{{ $desain->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">File Upload #{{ $desain->id }}</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    {{-- Dropzone JS --}}
                                    <form action="{{ route('design.upload') }}" class="dropzone" id="my-dropzone{{ $desain->id }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $desain->id }}">
                                    </form>
                                    <p class="text-sm text-danger mt-1 mb-0"><strong>Perhatian!</strong></p>
                                    <p class="text-sm text-secondary font-italic">*Pastikan file yang diunggah sudah benar, jika ada kesalahan cetak dari file yang diupload, maka biaya kerugian cetak ditanggung pribadi.</p>
                                </div>

                                <div class="modal-footer justify-content-between">
                                    <a type="button" href="{{ route('submit.file-cetak', $desain->id) }}" class="btn btn-primary">Upload</a>
                                </div>

                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    {{-- End Modal Upload --}}
                    @endforeach
                  </div>
                </div>
            </div>
            <div class="tab-pane fade" id="custom-content-below-messages" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
                <div class="card">
                    <div class="card-header">
                      <h2 class="card-title">Antrian Desain</h2>
                      {{-- Tombol tambah order --}}
                        @if(Auth::user()->role == 'sales')
                            <a href="{{ url('order/create') }}" class="btn btn-sm btn-warning float-right"><strong>Tambah Desain</strong></a>
                        @endif
                    </div>
                    <div class="card-body">
                    {{-- Menampilkan Antrian Desain --}}
                    <table id="tableAntrianSelesai" class="table table-bordered table-hover table-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Sales</th>
                                <th>Keyword</th>
                                <th>Ref. Desain</th>
                                <th>Jenis Pekerjaan</th>
                                <th>Desainer</th>
                                <th>Status</th>
                                @if(Auth::user()->role == 'sales')
                                <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($listSelesai as $desain)
                            <tr>
                                <td>
                                    {{ $loop->iteration }}
                                    {{-- Jika is_priority = 1, maka tambahkan icon star war warna kuning disebelah nomer urut --}}
                                    @if($desain->is_priority == '1')
                                        <i class="fas fa-star text-warning"></i>
                                    @endif
                                </td>

                                <td>{{ $desain->sales->sales_name }}</td>

                                <td>{{ $desain->title }}</td>

                                @if($desain->desain != null)
                                    @php
                                        $refImage = strlen($desain->desain) > 15 ? substr($desain->desain, 0, 15) . '...' : $desain->desain;
                                    @endphp
                                        <td scope="row"><a href="{{ asset('storage/ref-desain/'.$desain->desain) }}" target="_blank">{{ $refImage }}</a></td>
                                @else
                                        <td scope="row">-</td>
                                @endif

                                <td>{{ $desain->job->job_name }}</td>

                                <td>{{ $desain->user->name }}</td>

                                @if($desain->status == '0')
                                    <td><span class="badge badge-warning">Menunggu</span></td>
                                @elseif($desain->status == '1')
                                    <td><span class="badge badge-primary">Dikerjakan</span></td>
                                @elseif($desain->status == '2')
                                    <td><span class="badge badge-success">Selesai</span></td>
                                @endif

                                @if(Auth::user()->role == 'sales')
                                <td>
                                    @if($desain->toWorkshop == 0)
                                    <a href="{{ route('order.toAntrian', $desain->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-arrow-circle-right"></i></a>
                                    @else
                                    <a href="#" class="btn btn-sm btn-success"><i class="fas fa-check"></i></a>
                                    @endif
                                </td>
                                @endif

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- End Menampilkan Antrian Desain --}}
                  </div>
                </div>
            </div>
            </div>

            </div>
        </div>
        </div>
@endsection

@section('script')

<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script>
        @foreach($listDikerjakan as $desain)
        Dropzone.options.myDropzone{{ $desain->id }} = { // camelized version of the `id`
            paramName: "fileCetak", // The name that will be used to transfer the file
            clickable: true,
            acceptedFiles: ".jpeg, .jpg, .png, .pdf, .cdr, .ai, .psd",
            dictInvalidFileType: "Type file ini tidak dizinkan",
            addRemoveLinks: true,
            dictRemoveFile: "Hapus file",
            timeout: 5000,
        };
        @endforeach
    </script>

<script>
    $(function () {
        $("#tableAntrianDesain").DataTable({
            "responsive": true,
            "autoWidth": false,

        });
        $("#tableAntrianDikerjakan").DataTable({
            "responsive": true,
            "autoWidth": false,
        });
        $("#tableAntrianSelesai").DataTable({
            "responsive": true,
            "autoWidth": false,
        });
    });
</script>


@if(session('success-submit'))
<script>
$(function() {
    var Toast = Swal.mixin({
      toast: true,
      position: 'top-right',
      showConfirmButton: false,
      timer: 5000
    });
      Toast.fire({
        icon: 'success',
        title: '{{ session('success-submit') }}'
      });
});
</script>
@endif

@if(session('error-filecetak'))
<script>
$(function() {
    var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 5000
    });
      Toast.fire({
        icon: 'error',
        title: '{{ session('error-filecetak') }}'
      });
});
</script>
@endif
@endsection
