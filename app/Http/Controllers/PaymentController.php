<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::all();

        return view ('antrian.payment.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $antrian = Antrian::where('ticket_order', $request->order_number)->first();

        $status = $request->payment_amount == $request->omset ? 1 : 0;
        dd($antrian->ticket_order,$request->omset, $status);

        //Membuat $fileName dengan kondisi jika status = 1 maka nama file + ticket_order = fullpayment.jpg Jika tidak maka nama file + ticket_order = downpayment.jpg
        $file = $request->file('payment_proof');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $fileName = $status == 1 ? 'fullpayment'. $fileName : 'downpayment' . $fileName;
        $path = $file->storeAs('public/payment-proof', $fileName);

        $validated = $request->validate([
            'order_number' => 'required',
            'omset' => 'required|numeric|min:0',
            'payment_amount' => 'required|numeric|min:0|max:' . $request->omset,
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bank' => 'required'
        ]);

        $validated['payment_status'] = $validated['payment_amount'] == $validated['omset'] ? 1 : 0;
        $validated['payment_proof'] = $path; // Menyimpan path ke payment_proof dalam field yang sesuai

        Payment::create($validated);
        
        return redirect()->route('payments.index')->with('success', 'Pembayaran dikonfirmasi !');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payments = Payment::where('ticket_order', $id)->first();

        return view('antrian.payment.edit', compact('payments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
