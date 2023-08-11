@extends('layouts.app')

@section('title', 'Antrian | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Antrian')

@section('breadcrumb', 'Antrian Stempel')

@section('content')

{{-- Alert success-update --}}
@if(session('success-update'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  {{ session('success-update') }}
</div>
@endif

{{-- Alert successToAntrian --}}
@if(session('successToAntrian'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  {{ session('successToAntrian') }}
</div>
@endif

{{-- Alert successToSelesai --}}

{{-- Content Table --}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs mb-2" id="custom-content-below-tab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Dikerjakan</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Selesai</a>
                    </li>
                  </ul>
                <div class="tab-content" id="custom-content-below-tabContent">
                    <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Antrian Stempel</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="dataAntrian" class="table table-responsive table-bordered table-hover" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Sales</th>
                                            <th scope="col">Jenis Produk</th>
                                            <th scope="col">Deadline</th>
                                            <th scope="col">Desain</th>
                                            <th scope="col">Operator</th>
                                            <th scope="col">Finishing</th>
                                            <th scope="col">QC</th>
                                            <th scope="col">Tempat</th>
                                            @if(auth()->user()->role == 'admin')
                                                <th scope="col">Aksi</th>
                                            @elseif(auth()->user()->role == 'dokumentasi')
                                                <th scope="col">Aksi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($antrians as $antrian)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $antrian->sales->sales_name }}</td>
                                                <td>{{ $antrian->job->job_name }}</td>

                                                <td id="waktu{{ $antrian->id }}" class="text-center">

                                                </td>

                                                {{-- Jika file desain belum diupload, maka akan muncul tombol Upload Desain, jika sudah maka akan muncul unduh desain --}}
                                                @if($antrian->design_id == null)
                                                    <td><a class="btn btn-outline-dark"
                                                            href="{{ url('design/edit/'.$antrian->id) }}">Upload</a>
                                                    </td>
                                                @else
                                                    <td><a class="btn btn-dark btn-sm"
                                                            href="{{ asset('storage/file-cetak/'.$antrian->order->file_cetak) }}"
                                                            download="{{ $antrian->order->file_cetak }}">Download</a></td>
                                                @endif

                                                <td>
                                                    @if($antrian->operator_id)
                                                        {{ $antrian->operator->name }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>

                                                <td>
                                                    @if($antrian->finisher_id)
                                                        {{ $antrian->finishing->name }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>

                                                <td>
                                                    @if($antrian->qc_id)
                                                        {{ $antrian->quality->name }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $antrian->working_at }}</td>

                                                @if(auth()->user()->role == 'admin')
                                                <td>
                                                    <div class="btn-group">
                                                    <button type="button" class="btn btn-warning">Ubah</button>
                                                    <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <div class="dropdown-menu" role="menu">
                                                        <a class="dropdown-item" href="{{ url('antrian/'.$antrian->id. '/edit') }}"><i class="fas fa-xs fa-pen"></i> Edit</a>
                                                    <form
                                                        action="{{ route('antrian.destroy', $antrian->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data antrian ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item"
                                                            data-id="{{ $antrian->id }}">
                                                            <i class="fas fa-xs fa-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                    </div>
                                                    </div>
                                                </td>
                                                @elseif(auth()->user()->role == 'dokumentasi')
                                                    <td>
                                                        {{-- Tombol Upload Dokumentasi --}}
                                                        @if($antrian->timer_stop == null)
                                                            <a type="button" class="btn btn-outline-dark btn-sm"
                                                                href="{{ route('antrian.showDokumentasi', $antrian->id) }}">Upload</a>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <!-- /.card -->
                            </div>
                            <!-- /.col -->
                        </div>
                    </div>
                    <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Antrian Stempel</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="dataAntrianSelesai" class="table table-responsive table-bordered table-hover" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Sales</th>
                                            <th scope="col">Jenis Produk</th>
                                            <th scope="col">Desain</th>
                                            <th scope="col">Operator</th>
                                            <th scope="col">Finishing</th>
                                            <th scope="col">QC</th>
                                            <th scope="col">Dokumentasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($antrianSelesai as $antrian)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $antrian->sales->sales_name }}</td>
                                                <td>{{ $antrian->job->job_name }}</td>

                                                {{-- Jika file desain belum diupload, maka akan muncul tombol Upload Desain, jika sudah maka akan muncul unduh desain --}}
                                                @if($antrian->design_id == null)
                                                    <td><a class="btn btn-outline-dark"
                                                            href="{{ url('design/edit/'.$antrian->id) }}">Upload</a>
                                                    </td>
                                                @else
                                                    <td><a class="btn btn-dark btn-sm"
                                                            href="{{ route('design.download', $antrian->id) }}"
                                                            >Download</a></td>
                                                @endif

                                                <td>
                                                    @if($antrian->operator_id)
                                                        {{ $antrian->operator->name }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>

                                                <td>
                                                    @if($antrian->finisher_id)
                                                        {{ $antrian->finishing->name }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>

                                                <td>
                                                    @if($antrian->qc_id)
                                                        {{ $antrian->quality->name }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>

                                                <td>
                                                    @if($antrian->timer_stop != null)
                                                        <div class="text-center"><a class="btn btn-primary btn-sm" href="{{ route('documentation.preview', $antrian->id) }}">Lihat</a></div>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <!-- /.card -->
                            </div>
                            <!-- /.col -->
                        </div>
                    </div>
                </div>
    </div>
    <!-- /.container-fluid -->
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.7/dayjs.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#dataAntrian").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            });
            $("#dataAntrianSelesai").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            });
        });
    </script>
    <script>
    @foreach($antrians as $antrian)
    @if($antrian->end_job != null)
    // Set the date we're counting down to
    var countDownDate{{ $antrian->id }} = new Date("{{ $antrian->end_job }}").getTime();
    var endJob{{ $antrian->id }} = new Date("{{ $antrian->end_job }}");
    var timerStop{{ $antrian->id }} = new Date("{{ $antrian->timer_stop }}");
    // Update the count down every 1 second
    var x{{ $antrian->id }} = setInterval(function() {
        // Get today's date and time
        var now{{ $antrian->id }} = new Date().getTime();
        // Find the distance between now and the count down date
        var distance{{ $antrian->id }} = countDownDate{{ $antrian->id }} - now{{ $antrian->id }};
        // Time calculations for days, hours, minutes and seconds
        var days{{ $antrian->id }} = Math.floor(distance{{ $antrian->id }} / (1000 * 60 * 60 * 24));
        var hours{{ $antrian->id }} = Math.floor((distance{{ $antrian->id }} % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes{{ $antrian->id }} = Math.floor((distance{{ $antrian->id }} % (1000 * 60 * 60)) / (1000 * 60));
        var seconds{{ $antrian->id }} = Math.floor((distance{{ $antrian->id }} % (1000 * 60)) / 1000);
        // Output the result in an element with id="demo"
        document.getElementById("waktu{{ $antrian->id }}").innerHTML = days{{ $antrian->id }} + "d " + hours{{ $antrian->id }} + "h " +
            minutes{{ $antrian->id }} + "m " + seconds{{ $antrian->id }} + "s ";
        // If the count down is over, write some text
        if (distance{{ $antrian->id }} < 0) {
            if (endJob{{ $antrian->id }} < timerStop{{ $antrian->id }}) {
                clearInterval(x{{ $antrian->id }});
                document.getElementById("waktu{{ $antrian->id }}").innerHTML = "<span class='badge bg-danger'>TERLAMBAT</span>";
                //memperbarui deadline_status menjadi terlambat
                $.ajaxSetup({
                    headers: { 'csrftoken' : '{{ csrf_token() }}' }
                });
                $.ajax({
                    url: "{{ url('antrian/'.$antrian->id.'/updateDeadline') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        deadline_status: "2"
                    },
                    success: function(response) {
                        console.log(response);
                    }
                });
            } else {
                clearInterval(x{{ $antrian->id }});
                document.getElementById("waktu{{ $antrian->id }}").innerHTML = "<span class='badge bg-success'>SESUAI</span>";
                //memperbarui deadline_status menjadi sesuai
                $.ajaxSetup({
                    headers: { 'csrftoken' : '{{ csrf_token() }}' }
                });
                $.ajax({
                    url: "{{ url('antrian/'.$antrian->id.'/updateDeadline') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        deadline_status: "1"
                    },
                    success: function(response) {
                        console.log(response);
                    }
                });
            }
        }
    }, 1000);
    @else
    document.getElementById("waktu{{ $antrian->id }}").innerHTML = "<span class='badge bg-success'>-</span>";
    @endif
    @endforeach
</script>
@endsection
