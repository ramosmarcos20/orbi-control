<?php

namespace App\Console\Commands;

use App\Services\DatabaseConnectionService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Models\QueueReport;
use App\Models\Company;
use Carbon\Carbon;

class ProcessCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Despacha el job para procesar los datos de la empresa';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $company_list = Company::where('estado', 1)->get();
        $currentMonth = Carbon::now()->month;
        $currentYear  = Carbon::now()->year;

        $infoArray                  = [];
        $total_facturas             = 0;
        $total_facturas_autorizadas = 0;
        $total_no_autorizadas       = 0;

        Log::channel('job_log')->info('Inicio del proceso del job');
        try {
        
            foreach ($company_list as $company) 
            {
                $change_connection = new DatabaseConnectionService();
                $change_connection->changeDataBaseConnection($company->id);
                
                $comp_elec = $this->compElecConecction($currentMonth, $currentYear, $company);
                $historial = $this->historialConecction($currentMonth, $currentYear, $company);
        
                Log::channel('job_log')->info('Datos encontrados: ' . json_encode([
                    'empresa'   => $company->empresa,
                    'comp_elec' => $comp_elec['count'],
                    'historial' => $historial['count']
                ]));
        
                if ($comp_elec['count'] > 0 || $historial['count'] > 0) {
                    $total_no_autorizadas += $comp_elec['count'];
                    $total_facturas_autorizadas += $historial['count'];
                    $total_facturas += $comp_elec['count'] + $historial['count'];
        
                    $infoArray[] = [
                        'empresa'           => $company->empresa,
                        'no_autorizadas'    => $comp_elec['count'],
                        'autorizadas'       => $historial['count'],
                    ];
                }
            }
        
            $queueReport = QueueReport::firstOrNew(['id' => 1]);

            $queueReport->total_voucher         = $total_facturas;
            $queueReport->unauthorized_voucher  = $total_no_autorizadas;
            $queueReport->authorized_voucher    = $total_facturas_autorizadas;
            $queueReport->details               = json_encode($infoArray);
            $queueReport->save();
            
            Log::channel('job_log')->info('Proceso completado exitosamente');
            $filePath = storage_path('logs/job_log.log');
            file_put_contents($filePath, '');
        } catch (\Throwable $th) {
            Log::channel('job_log')->error('Error durante el proceso: ' . $th->getMessage());
            throw $th;
        }
    }

    private function compElecConecction($currentMonth, $currentYear, $company)
    {   
        $query = DB::connection('empresa_temp')
                    ->table('oi_comp_elec')
                    ->whereMonth('fecha_registro', $currentMonth)
                    ->whereYear('fecha_registro', $currentYear)
                    ->select(
                        'tipo_comp_elec', 
                        'clave_comp_elec'
                    )
                    ->orderBy('fecha_registro', 'asc');
                    
        if (!$company->own_db) {
            $query->where('ruc_empresa', $company->ruc);
        }

        $results = $query->get(); // Ejecutamos la consulta

        return [
            'count' => $results->count(),
            'data'  => $results
        ];
    }

    private function historialConecction($currentMonth, $currentYear, $company)
    {
        $query = DB::connection('empresa_temp')
                    ->table('oi_comp_elec_historial')
                    ->whereMonth('fecha_registro', $currentMonth)
                    ->whereYear('fecha_registro', $currentYear)
                    ->select(
                        'tipo_comp_elec', 
                        'clave_comp_elec'
                    )
                    ->orderBy('fecha_registro', 'asc');
                    
        if (!$company->own_db) {
            $query->where('ruc_empresa', $company->ruc);
        }

        $results = $query->get();

        return [
            'count' => $results->count(),
            'data'  => $results
        ];
    }

    
}
