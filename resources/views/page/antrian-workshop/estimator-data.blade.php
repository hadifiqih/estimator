@extends('layouts.app')

@section('title', 'Antrian | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Antrian')

@section('breadcrumb', 'Estimator By Job')

@section('content')
<style>
    .select2-container .select2-selection--single {
        height: 38px;
    }
</style>
<div class="container">
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="kategori">Jenis Pekerjaan</label>
            <select class="form-control select2" name="kategori" id="kategori">
                <option value="">Pilih Jenis Pekerjaan</option>
                @foreach($jobs as $job)
                    <option value="{{ $job->id }}">{{ $job->job_name }}</option>
                @endforeach
            </select>
        </div>
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
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('page.antrian-workshop.modal.modal-detail-antrian')
@endsection

@section('script')
<script>
    //tampilkan modal
    function modalDetail(id) {
            //show modal
            $('#modalDetailAntrian').modal('show');
            //ajax
            $.ajax({
                url: "{{ route('estimator.show') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {
                    $('#modalDetailAntrian').modal('show');
                    $('#tanggalOrder').val(data.created_at);
                    $('#ticketOrder').text(data.ticket_order);
                    $('#namaProject').val(data.order.title);
                    $('#sales').val(data.sales.sales_name);
                    $('#namaPelanggan').val(data.customer.nama);
                    $('#telepon').val(data.customer.telepon);
                    $('#alamat').val(data.customer.alamat);
                    $('#sumberPelanggan').val(data.customer.infoPelanggan);
                    $('#namaProduk').val(data.job.job_name);
                    $('#jumlahProduk').val(data.qty);
                    $('#keterangan').val(data.note);
                    $('#mulai').val(data.start_job);
                    $('#deadline').val(data.end_job);
                    $('#desainer').val(data.order.employee.name);
                    $('#tempat').val(data.working_at);
                    $('#operator').val(data.operator);
                    $('#finishing').val(data.finishing);
                    $('#qc').val(data.qc);
                    $('#mesin').val(data.machine.machine_name);
                    $('#nominalOmset').val(data.omset);
                    $('#hargaProduk').val(data.harga_produk);
                    $('#statusPembayaran').val(data.payment.payment_status);
                    $('#metode').val(data.payment.payment_method);
                    $('#bayar').val(data.payment.payment_amount);
                    $('#sisa').val(data.payment.remaining_payment);
                    $('#pasang').val(data.payment.installation_cost);
                    $('#pengiriman').val(data.payment.shipping_cost);
                    $('#alamatKirim').val(data.alamat_pengiriman);
                }
            });
        }

    $(document).ready(function() {
        var table = $('#tableEstimator').DataTable({
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
                { data: 'catatan_admin', name: 'catatan_admin' },
                { data: 'action', name: 'action' }
            ]
        });

        //update table
        $('#kategori').on('change', function() {
            var kategori = $(this).val();
            table.ajax.url("{{ route('estimator.all') }}?kategori=" + kategori).load();
        });

        //select2
        $('.select2').select2();
    });
</script>
@endsection