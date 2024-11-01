<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Services\DatabaseConnectionService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleXMLElement;
use PDO;

class CountController extends Controller
{
    private function switchDatabaseConnection($companyId)
    {
        $changeConnection = new DatabaseConnectionService();
        $changeConnection->changeDataBaseConnection($companyId);
    }

    public function index()
    {
        $company_list = Company::select('id', 'ruc', 'empresa')->orderBy('empresa', 'asc')->get();
        return view('count.index', compact('company_list'));
    }

    public function filterTable(Request $request)
    {
        $request->validate([
            'company_id' => 'nullable|integer',
            'status'     => 'nullable|string',
            'date_range' => 'nullable|string',
            'tipo'       => 'nullable|string'
        ]);
    
        $companyId = $request->company_id;
        $status    = $request->status;
        $dateRange = $request->date_range;
        $tipo      = $request->tipo;
    
        if ($companyId == 0) {
            return response()->json([
                'draw'            => intval($request->input('draw')),
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => []
            ]);
        }
    
        $columns = [
            'tipo_comp_elec',
            'estado_sri_comp_elec',
            'mensaje_sri_comp_elec',
            'clave_comp_elec',
            'num_factura',
            'fecha_registro',
        ];
    
        $company = Company::find($companyId);
        if (!$company) {
            return response()->json([
                'draw'            => intval($request->input('draw')),
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => []
            ]);
        }

        try {
            $this->switchDatabaseConnection($companyId);
    
            $ruc = $company->ruc;
        
            if ($dateRange) {
                $dates = explode(' - ', $dateRange);
                if (count($dates) === 2) {
                    try {
                        $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                        $endDate   = \Carbon\Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();
                    } catch (\Exception $e) {
                        return response()->json(['error' => 'Invalid date range format'], 400);
                    }
                }
            }
        
            $query = DB::connection('empresa_temp')->table($status == 'autorizada' ? 'oi_comp_elec_historial' : 'oi_comp_elec');
        
            if (isset($startDate) && isset($endDate)) {
                $query->whereBetween('fecha_registro', [$startDate, $endDate]);
            }
        
            if ($tipo !== 'All') {
                $query->where('tipo_comp_elec', $tipo);
            }

            if ($company->own_db == false) {
                $query->where('ruc_empresa', $ruc);
            }
        
            $totalData = $query->count();
        
            $orderIndex = $request->input('order.0.column') ?? 0;
            $order      = $columns[$orderIndex] ?? 'tipo_comp_elec';
            $dir        = $request->input('order.0.dir') ?? 'asc';
        
            if (!empty($request->input('search.value'))) {
                $search = $request->input('search.value');
                $query->where(function ($q) use ($search) {
                    $q->where('tipo_comp_elec', 'LIKE', "%{$search}%")
                      ->orWhere('clave_comp_elec', 'LIKE', "%{$search}%")
                      ->orWhere('num_factura', 'LIKE', "%{$search}%")
                      ->orWhere('fecha_registro', 'LIKE', "%{$search}%");
                });
            }
    
            $totalFiltered = $query->count();
        
            // Paginación y orden
            $limit = $request->input('length');
            $start = $request->input('start');
        
            $compElec   = $query->offset($start)->limit($limit)->orderBy($order, $dir)->get();
            $data = [];

            foreach ($compElec as $key => $elec) 
            {   
                $options = '
                    <td class="d-flex gap-1">
                        <button type="button" class="btn btn-light border" 
                                onclick="viewXml(' . $companyId . ', \'' . $elec->clave_comp_elec . '\')" 
                                title="Ver XML">
                            <i class="bi bi-filetype-xml"></i>
                        </button>
                        <button type="button" class="btn btn-light border" 
                                onclick="newAccessKey(' . $companyId . ', \'' . $elec->clave_comp_elec . '\')" 
                                title="Generar Clave de Acceso">
                            <i class="bi bi-key"></i>
                        </button>
                        <button type="button" class="btn btn-light border" 
                                onclick="resent(' . $companyId . ', \'' . $elec->clave_comp_elec . '\')" 
                                title="Reenviar XML">
                            <i class="bi bi-send"></i>
                        </button>
                    </td>
                ';
                
                if ($elec->mensaje_sri_comp_elec == '' || 
                    $elec->mensaje_sri_comp_elec == 'RESPUESTA :ERROR ESTABLECIMIENTO CERRADO' ||
                    $elec->mensaje_sri_comp_elec == 'RESPUESTA :ERROR SECUENCIAL REGISTRADO' ||
                    $elec->estado_sri_comp_elec == 'AUTORIZADO' ||
                    $status == 'autorizada') {
                
                    $options = '<td class="d-flex gap-1"></td>';
                }
            
    
                $fechaFormateada = Carbon::parse($elec->fecha_registro)->format('d/m/Y H:i:s');
        
                $message = $elec->estado_sri_comp_elec && $elec->mensaje_sri_comp_elec 
                    ? "<div>Estado: {$elec->estado_sri_comp_elec}</div><div>Mensaje: {$elec->mensaje_sri_comp_elec}</div>"
                    : '<div>En proceso</div>';
        
                $data[] = [
                    'key'           => ++$key,
                    'tipo'          => $elec->tipo_comp_elec,
                    'ruc'           => $ruc,
                    'message'       => $message,
                    'clave'         => $elec->clave_comp_elec,
                    'no_documento'  => $elec->num_factura,
                    'fecha'         => $fechaFormateada,
                    'options'       => $options,
                ];
            }
        
            // Respuesta JSON
            return response()->json([
                'data'            => $data,
                'draw'            => intval($request->input('draw')),
                'recordsTotal'    => intval($totalData),
                'recordsFiltered' => intval($totalFiltered),
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function getXml($id, $key)
    {
        try {
            $this->switchDatabaseConnection($id);

            $company = Company::find($id);

            $query  = DB::connection('empresa_temp')
            ->table('oi_comp_elec')
            ->select('xml_comp_elec')
            ->where('clave_comp_elec', $key);
            
            if (!$company->own_db) {
                $query->where('ruc_empresa', $company->ruc);
            }
            
            $data = $query->first();
            
            if ($data) {
                return response()->json([
                    'id'    => $id,
                    'key'   => $key,
                    'xml'   => $data->xml_comp_elec
                ]);
            } else {
                return response()->json([
                    'message' => 'No se encontró el XML.'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateXml(Request $request)
    {
        $request->validate([
            'id'    => ['required'],
            'key'   => ['required', 'string', 'max:49'],
            'xml'   => ['required', 'string'],
        ]);
    
        $id     = $request->id;
        $key    = $request->key;
        $xml    = $request->xml;
    
        DB::beginTransaction();
        try {
            $this->switchDatabaseConnection($id);
            
            $xmlObject      = new SimpleXMLElement($xml);
            $infoTributaria = $xmlObject->infoTributaria;
            $claveAcceso    = (string) $infoTributaria->claveAcceso;

            $company = Company::find($id);

            $query  = DB::connection('empresa_temp')
                    ->table('oi_comp_elec')
                    ->where('clave_comp_elec', $key);
            
            if (!$company->own_db) {
                $query->where('ruc_empresa', $company->ruc);
            }
            
            $query->update([
                'xml_comp_elec'         => $xml,
                'clave_comp_elec'       => $claveAcceso,
                'estado_sri_comp_elec'  => '',
                'mensaje_sri_comp_elec' => '',
                'estado_autorizar'      => '',
                'estado_validar'        => '',
            ]);
            
    
            DB::commit();

            return response()->json([
                'success'   => true,
                'message'   => 'XML actualizado correctamente.'
            ], 200);
    
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al intentar actualizar el XML. ' . $th->getMessage()
            ], 500);
        }
    }

    public function newAccessKey($id, $key)
    {
        try {
            $this->switchDatabaseConnection($id);
        
            $company = Company::find($id);

            $query  = DB::connection('empresa_temp')
                    ->table('oi_comp_elec')
                    ->where('clave_comp_elec', $key);
            
            if (!$company->own_db) {
                $query->where('ruc_empresa', $company->ruc);
            }
            
            $xml = $query->first()->xml_comp_elec;
            
            if (!$xml) {
                throw new Exception("XML no encontrado para la clave proporcionada.");
            }
        
            $xmlObject = new SimpleXMLElement($xml);
        
            $infoTributaria = $xmlObject->infoTributaria;
    
            $ruc            = (string) $infoTributaria->ruc;
            $estab          = (string) $infoTributaria->estab;
            $ptoEmi         = (string) $infoTributaria->ptoEmi;
            $codDoc         = (string) $infoTributaria->codDoc;
            $secuencial     = (string) $infoTributaria->secuencial;
        
            $fechaEmision = '';
            
            switch ($codDoc) {
                case '01':
                    $infoSpecific = $xmlObject->infoFactura;
                    $fechaEmision = (string) $infoSpecific->fechaEmision;
                    break;
        
                case '04': // Nota de Crédito
                    $infoSpecific = $xmlObject->infoNotaCredito;
                    $fechaEmision = (string) $infoSpecific->fechaEmision;
                    break;
        
                case '05': // Nota de Débito
                    $infoSpecific = $xmlObject->infoNotaDebito;
                    $fechaEmision = (string) $infoSpecific->fechaEmision;
                    break;
        
                case '06': // Guía de Remisión
                    $infoSpecific = $xmlObject->infoGuiaRemision;
                    $fechaEmision = (string) $infoSpecific->fechaIniTransporte;
                    break;
        
                case '07': // Comprobante de Retención
                    $infoSpecific = $xmlObject->infoCompRetencion;
                    $fechaEmision = (string) $infoSpecific->fechaEmision;
                    break;
        
                default:
                    throw new Exception("Tipo de documento no soportado.");
            }
        
            $fechaEmisionPegada = str_replace('/', '', $fechaEmision);
        
            $serieNumerico  = $estab . $ptoEmi;
            $codigoNumerico = mt_rand(10000000, 99999999); // Generar código numérico aleatorio
            $tipoEmision    = 1; // Se asume tipo de emisión '1'
        
            $claveAcceso = $fechaEmisionPegada . $codDoc . $ruc . '2' . $serieNumerico . $secuencial . $codigoNumerico . $tipoEmision;
        
            // Definir el peso y realizar la validación de módulo 11
            $acumulador     = 0;
            $peso           = "765432765432765432765432765432765432765432765432";
            $claveAcceso    = str_replace(' ', '', $claveAcceso); // Asegurarse de que no haya espacios
        
            // Calcular el dígito verificador usando módulo 11
            for ($i = 0; $i < strlen($claveAcceso); $i++) {
                $acumulador += $claveAcceso[$i] * $peso[$i];
            }
        
            $modulo = $acumulador % 11;
            $digito = 11 - $modulo;
        
            if ($digito == 11) {
                $digito = 0;
            } elseif ($digito == 10) {
                $digito = 1;
            }
        
            $nuevaClave = $claveAcceso . $digito;

            return response()->json([
                'key'       => $nuevaClave,
                'status'    => true,
                'message'   => 'Clave de acceso generada correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'    => 'error',
                'message'   => $th->getMessage()
            ], 500);
        }
    }

    public function resent($id, $key)
    {
        $data = ['id' => $id, 'key' => $key];
        $rules = [
            'id' => 'required|integer',
            'key' => 'required|string|size:49',
        ];

        $validator = \Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        DB::beginTransaction();
        try {
            $company = Company::findOrFail($id);

            $this->switchDatabaseConnection($id);

            $query = DB::connection('empresa_temp')
                ->table('oi_comp_elec')
                ->where('clave_comp_elec', $key);

            if (!$company->own_db) {
                $query->where('ruc_empresa', $company->ruc);
            }

            $query->update([
                'estado_sri_comp_elec' => '',
                'mensaje_sri_comp_elec' => '',
                'estado_autorizar' => '',
                'estado_validar' => '',
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'XML reenviado.'
            ], 200);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al intentar reenviar el XML. ' . $th->getMessage()
            ], 500);
        }
    }

    
}
