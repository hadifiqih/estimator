<?php

namespace App\Models;

use App\Models\Job;
use App\Models\Sales;
use App\Models\Design;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Documentation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Antrian extends Model
{
    use HasFactory;

    protected $table = 'antrians';

    protected $fillable = [
        'ticket_order',
        'nama_customer',
        'no_customer',
        'info_customer',
        'sales_id',
        'job_id',
        'note',
        'acc_design',
        'status',
        'employee_id',
        'customer_id',
        'design_id',
        'omset',
    ];

    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payment()
    {
        return $this->hasMany(Payment::class, 'ticket_order', 'ticket_order');
    }

    public function operator()
    {
        return $this->belongsTo(Employee::class, 'operator_id');
    }

    public function finishing()
    {
        return $this->belongsTo(Employee::class, 'finisher_id');
    }

    public function quality()
    {
        return $this->belongsTo(Employee::class, 'qc_id');
    }

    public function design()
    {
        return $this->hasOne(Design::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function documentation()
    {
        return $this->hasMany(Documentation::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    //ambil job_type dari tabel job
    public function getJobTypeAttribute()
    {
        return $this->job->job_type;
    }

}
