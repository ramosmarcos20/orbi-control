<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueueReport extends Model
{
    use HasFactory;
    protected $table = 'queue_report';
    protected $primaryKey = 'id';
    protected $fillable = [
        'total_voucher', 
        'unauthorized_voucher', 
        'authorized_voucher', 
        'details',
    ];
}
