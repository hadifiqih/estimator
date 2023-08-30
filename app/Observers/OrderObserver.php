<?php

namespace App\Observers;

use App\Models\Order;

use App\Events\SendGlobalNotification;

class OrderObserver
{
    public $afterCommit = true;
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        //mengambil sales_name dari model Order
        $salesName = $order->sales->sales_name;

        $notification = [
            'title' => 'Antrian Desain',
            'body' => 'Desain baru dari : ' . $salesName,
        ];

        //Mengirimkan notifikasi ke semua user ketika ada penambahan antrian
        event(new SendGlobalNotification($notification));
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        //Menampilkan notifikasi ketika ada perubahan pada model Order
        $notification = [
            'title' => 'Antrian Desain',
            'body' => 'Orderan dengan ticket #' . $order->ticket_order . ' telah diperbarui',
        ];

        //Mengirimkan notifikasi ke semua user ketika ada perubahan pada model Order
        event(new SendGlobalNotification($notification));
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
