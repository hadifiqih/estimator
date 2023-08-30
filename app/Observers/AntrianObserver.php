<?php

namespace App\Observers;

use App\Models\Antrian;

use App\Events\SendGlobalNotification;

class AntrianObserver
{
    public $afterCommit = true;
    /**
     * Handle the Antrian "created" event.
     */
    public function created(Antrian $antrian): void
    {
        //mengambil sales_name dari sales yang memiliki sales_id yang sama dengan sales_id dari antrian yang baru dibuat
        $salesName = $antrian->sales->sales_name;

        $notification = [
            'title' => 'Antrian Workshop',
            'body' => 'Orderan baru dari : ' . $salesName,
        ];

        //Mengirimkan notifikasi ke semua user ketika ada penambahan antrian
        event(new SendGlobalNotification($notification));

    }

    /**
     * Handle the Antrian "updated" event.
     */
    public function updated(Antrian $antrian): void
    {
        //Melakukan reload halaman ketika ada penambahan antrian

    }

    /**
     * Handle the Antrian "deleted" event.
     */
    public function deleted(Antrian $antrian): void
    {
        //
    }

    /**
     * Handle the Antrian "restored" event.
     */
    public function restored(Antrian $antrian): void
    {
        //
    }

    /**
     * Handle the Antrian "force deleted" event.
     */
    public function forceDeleted(Antrian $antrian): void
    {
        //
    }
}
