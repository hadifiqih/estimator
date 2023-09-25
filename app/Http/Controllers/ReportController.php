<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Antrian;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function pilihTanggal()
    {
        return view('page.antrian-workshop.pilih-tanggal');
    }

    public function pilihTanggalDesain()
    {
        return view('page.antrian-desain.pilih-tanggal');
    }

    public function exportLaporanDesainPDF(Request $request)
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

        $pdf = PDF::loadview('page.antrian-workshop.laporan-desain', compact('antrians', 'totalOmset', 'totalQty', 'tanggal'));
        return $pdf->stream($tanggal . '-laporan-desain.pdf');
        // return $pdf->download($tanggal . '-laporan-workshop.pdf');
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
        $pdf = PDF::loadview('page.antrian-workshop.laporan-workshop', compact('antrians', 'totalOmset', 'totalQty', 'tanggal'));
        return $pdf->stream($tanggal . '-laporan-workshop.pdf');
        // return $pdf->download($tanggal . '-laporan-workshop.pdf');
    }

    public function cetakEspk($id)
    {
        $antrian = Antrian::with('customer', 'sales', 'payment', 'operator', 'finishing', 'job', 'order')
            ->where('id', $id)
            ->first();

        $pdf = PDF::loadview('page.antrian-workshop.cetak-spk-workshop', compact('antrian'));
        return $pdf->stream($antrian->ticket_order . '-espk.pdf');

        // return view('page.antrian-workshop.cetak-spk-workshop', compact('antrian'));
    }

    public function reportSales()
    {
        $sales = Sales::where('user_id', auth()->user()->id)->first();
        $salesId = $sales->id;

        $totalOmset = 0;

        $date = date('Y-m-d'). ' 00:00:00';

        $antrians = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing')
            ->orderByDesc('created_at')
            ->where('status', '1')
            ->where('sales_id', $salesId)
            ->where('created_at', '>=', $date)
            ->get();

        foreach ($antrians as $antrian) {
            $totalOmset += $antrian->omset;
        }

        return view('page.antrian-workshop.ringkasan-sales', compact('antrians', 'totalOmset', 'date'));
    }

    public function reportSalesByDate()
    {
        if(request()->has('tanggal')) {
            $date = request('tanggal');
        } else {
            $date = date('Y-m-d'). ' 00:00:00';
        }

        $sales = Sales::where('user_id', auth()->user()->id)->first();
        $salesId = $sales->id;

        $antrians = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing')
            ->orderByDesc('created_at')
            ->where('status', '1')
            ->where('sales_id', $salesId)
            ->where('created_at', '>=', $date)
            ->get();

        $totalOmset = 0;
        foreach ($antrians as $antrian) {
            $totalOmset += $antrian->omset;
        }

        return view('page.antrian-workshop.ringkasan-sales', compact('antrians', 'totalOmset', 'date'));
    }

}
