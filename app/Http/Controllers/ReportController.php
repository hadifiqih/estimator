<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Antrian;

use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function pilihTanggal()
    {
        return view('page.antrian-workshop.pilih-tanggal');
    }

    public function exportLaporanWorkshopPDF(Request $request)
    {
        $tanggal = $request->tanggal;
        //Mengambil data antrian dengan relasi customer, sales, payment, operator, finishing, job, order pada tanggal yang dipilih dan menghitung total omset dan total order
        $antrians = Antrian::with('customer', 'sales', 'payment', 'operator', 'finishing', 'job', 'order')
            ->whereDate('created_at', $tanggal)
            ->get();

        $totalOmset = 0;
        $totalQty = 0;
        foreach ($antrians as $antrian) {
            $totalOmset += $antrian->omset;
            $totalQty += $antrian->qty_produk;
        }

        // return view('page.laporan-workshop', compact('antrians', 'totalOmset', 'totalQty'));
        $pdf = PDF::loadview('page.antrian-workshop.laporan-workshop', compact('antrians', 'totalOmset', 'totalQty'));
        return $pdf->stream($tanggal . '-laporan-workshop.pdf');
        // return $pdf->download($tanggal . '-laporan-workshop.pdf');
    }
}
