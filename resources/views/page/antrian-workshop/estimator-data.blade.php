@extends('layouts.app')

@section('title', 'Antrian | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Antrian')

@section('breadcrumb', 'Estimator By Job')

@section('content')

<div class="container">
    <div class="row mb-3">
        <div class="col-md-4">
        <form id="filterByCategory" action="{{ route('estimator.filterByJob') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="kategori">Jenis Pekerjaan</label>
            <select class="form-control select2" name="kategori" id="kategori">
                <option value="">Pilih Jenis Pekerjaan</option>
                @foreach($jobs as $job)
                    <option value="{{ $job->id }}">{{ $job->job_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 align-self-end">
            @if(isset($filtered))
            <a href="{{ route('antrian.index') }}" class="btn btn-danger mt-1">Reset</a>
            @else
            <button type="submit" class="btn btn-primary mt-1">Filter</button>
            @endif
        </div>
        </form>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tableEstimator" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Ticket Order</th>
                                    <th>Sales</th>
                                    <th>Customer</th>
                                    <th>Jenis Produk</th>
                                    <th>Qty</th>
                                    <th>Deadline</th>
                                    <th>File Desain</th>
                                    <th>Desainer</th>
                                    <th>Operator</th>
                                    <th>Finishing</th>
                                    <th>QC</th>
                                    <th>Tempat</th>
                                    <th>Catatan Admin</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#tableEstimator').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('estimator.all') }}",
            },
            columns: [
                { data: 'ticket_order', name: 'ticket_order' },
                { data: 'sales', name: 'sales' },
                { data: 'customer', name: 'customer' },
                { data: 'jenis_produk', name: 'jenis_produk' },
                { data: 'qty', name: 'qty' },
                { data: 'deadline', name: 'deadline' },
                { data: 'file_desain', name: 'file_desain' },
                { data: 'desainer', name: 'desainer' },
                { data: 'operator', name: 'operator' },
                { data: 'finishing', name: 'finishing' },
                { data: 'qc', name: 'qc' },
                { data: 'tempat', name: 'tempat' },
                { data: 'catatan_admin', name: 'catatan_admin' }
            ]
        });
    });
</script>
@endsection