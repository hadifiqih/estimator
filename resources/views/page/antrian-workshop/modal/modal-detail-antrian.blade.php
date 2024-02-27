<div class="modal fade" id="modalDetailAntrian" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Detail Orderan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="tanggalOrder">Tanggal Order</label>
                <input id="tanggalOrder" name="tanggalOrder" type="text" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="namaProject">Nama Project</label>
                <input id="namaProject" name="namaProject" type="text" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="sales">Sales</label>
                <input id="sales" name="sales" type="text" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="nama-pelanggan">Nama Pelanggan</label>
                <input id="namaPelanggan" name="namaPelanggan" type="text" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="telepon">Telepon/WA</label>
                <input id="telepon" name="telepon" type="text" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="alamat">Alamat Pelanggan</label>
                <textarea id="alamat" name="alamat" class="form-control" rows="3" readonly></textarea>
            </div>
            <div class="form-group">
                <label class="form-label" for="sumberPelanggan">Sumber Pelanggan</label>
                <input id="sumbePelanggan" name="sumberPelanggan" type="text" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="namaProduk">Nama Produk</label>
                <input id="namaProduk" name="namaProduk" type="text" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="jumlahProduk">Jumlah Produk (Qty)</label>
                <input id="jumlahProduk" name="jumlahProduk" type="text" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="keterangan">Keterangan / Spesifikasi Produk</label>
                <textarea id="keterangan" name="keterangan" class="form-control" rows="6" readonly></textarea>
            </div>
            <div class="form-group">
                <label class="form-label" for="mulai">Mulai</label>
                <input id="mulai" name="mulai" type="text" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="deadline">Deadline</label>
                <input id="deadline" name="deadline" type="text" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="desainer">Desainer</label>
                <input id="desainer" name="desainer" type="text" class="form-control" readonly>
            </div>
            <hr>
            <div class="form-group">
                <span class="form-label font-weight-bold"> Tempat : </span>
                    <a id="tempat" class="btn btn-primary btn-sm"></a>
            </div>
            <hr>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-4 rounded-2">
                <span class="form-label font-weight-bold">Operator</span>
                <p>
                    <i class="fas fa-user-circle"></i><span id="operator"></span>
                </p>
            </div>
            <div class="col-md-4 rounded-2">
                <span class="form-label font-weight-bold">Finishing</span>
                <p>
                    <i class="fas fa-user-circle"></i><span id="finishing"></span>
                </p>
            </div>
            <div class="col-md-4 rounded-2">
                <span class="form-label font-weight-bold">Pengawas / QC</span>
                <p>
                    <i class="fas fa-user-circle"></i><span id="qc"></span>
                </p>
            </div>
            </div>
            </div>
            <hr>
            <div class="form-group">
                <span class="form-label font-weight-bold">Mesin : </span>
                <span id="mesin"></span>
            </div>
            <hr>
            <div class="form-group">
                <label class="form-label" for="nominalOmset">Nominal Omset</label>
                <input id="nominalOmset" name="nominalOmset" type="text" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="hargaProduk">Harga Produk</label>
                <input id="hargaProduk" name="hargaProduk" type="text" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="statusPembayaran">Status Pembayaran</label>
                <input id="statusPembayaran" name="statusPembayaran" type="text" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="metode">Metode Pembayaran</label>
                <input id="metode" name="metode" type="text" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="bayar">Nominal Pembayaran</label>
                <input id="bayar" name="bayar" type="text" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="sisa">Sisa Pembayaran</label>
                <input id="sisa" name="sisa" type="text" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="pasang">Biaya Jasa Pasang</label>
                <input id="pasang" name="pasang" type="text" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="pengiriman">Biaya Jasa Pengiriman</label>
                <input id="pengiriman" name="pengiriman" type="text" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="alamatKirim">Alamat Pengiriman</label>
                <textarea id="alamatKirim" name="alamatKirim" class="form-control" rows="6" readonly></textarea>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label>Preview ACC Desain</label>
                        <div class="text-muted font-italic"><button type="button" class="btn btn-sm btn-primary ml-3" data-toggle="modal" data-target="#modal-accdesain">Lihat</button></div>
                    </div>
                    <div class="col-md-6">
                        <label>Preview Bukti Pembayaran</label>
                        <div class="text-muted font-italic"><button type="button" class="btn btn-sm btn-primary ml-3" data-toggle="modal" data-target="#modal-buktiPembayaran">Lihat</button></div>
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