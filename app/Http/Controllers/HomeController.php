<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Services\DatabaseConnectionService;
use App\Models\QueueReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $data = QueueReport::first();
        
        $total_voucher          = $data->total_voucher ?? 0; 
        $unauthorized_voucher   = $data->unauthorized_voucher ?? 0;
        $authorized_voucher     = $data->authorized_voucher ?? 0;
        
        $details = json_decode($data->details, true);
    
        // Ordenar $details de mayor a menor por 'no_autorizadas'
        if (!empty($details)) {
            usort($details, function ($a, $b) {
                return $b['no_autorizadas'] <=> $a['no_autorizadas'];
            });
        }
    
        $currentMonth           = Carbon::now()->translatedFormat('F Y');
        $currentDate            = Carbon::now()->format('d/m/Y');
    
        return view('index', [
            'total_facturas'                => $total_voucher,
            'total_no_autorizadas'          => $unauthorized_voucher,
            'total_facturas_autorizadas'    => $authorized_voucher,
            'currentMonth'                  => $currentMonth,
            'currentDate'                   => $currentDate,
            'detalles'                      => $details // $details ahora está ordenado
        ]);
    }
    
    
    
}
