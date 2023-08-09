<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table ='orders';
    protected $fillable = [
        'sales_id',
        'job_id',
        'description',
        'is_priority',
        'desain',
        'status',
        'created_at',
        'updated_at',
    ];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }

    public function sales(){
        return $this->belongsTo(Sales::class);
    }

    public function design(){
        return $this->hasOne(Design::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function job(){
        return $this->belongsTo(Job::class);
    }

    public function antrian(){
        return $this->hasOne(Antrian::class);
    }
}
