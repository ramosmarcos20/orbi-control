<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Services\DatabaseConnectionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function index()
    {
        $company_list = Company::get();
        return view('company.index', compact('company_list'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ruc'       => ['required', 'string', 'max:13', 'regex:/^[a-zA-Z0-9]+$/'],
            'empresa'   => ['required', 'string', 'max:100'],
            'host'      => ['required', 'string', 'max:100'],
            'base'      => ['required', 'string', 'max:100'],
            'bdusuario' => ['required', 'string', 'max:100'],
            'bdclave'   => ['required', 'string', 'max:100'],
        ]);
    
        try {
                        
            $validatedData['own_db'] = ($request->own_db) ? true : false;
            $validatedData['estado'] = 1;

            Company::create($validatedData);

            return response()->json([
                'message' => 'Empresa registrada exitosamente',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $company = Company::find($id);
        if (!$company) {
            return response()->json(['error' => 'Empresa no encontrada'], 404);
        }

        return response()->json($company);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'ruc'       => ['required', 'string', 'max:13', 'regex:/^[a-zA-Z0-9]+$/'],
            'empresa'   => ['required', 'string', 'max:100'],
            'host'      => ['required', 'string', 'max:100'],
            'base'      => ['required', 'string', 'max:100'],
            'bdusuario' => ['required', 'string', 'max:100'],
            'bdclave'   => ['required', 'string', 'max:100'],
        ]);

        $company = Company::find($id);
    
        if (!$company) {
            return response()->json(['error' => 'Empresa no encontrada.'], 404);
        }
    
        try {
            $validatedData['own_db'] = ($request->own_db) ? true : false;
            $validatedData['estado'] = 1;
            
            $company->update($validatedData);
            return response()->json([
                'message' => 'Empresa actualizada exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar la empresa: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $company = Company::find($id);
    
        if (!$company) {
            return response()->json(['error' => 'Empresa no encontrada.'], 404);
        }
    
        try {
            $company->delete();
            return response()->json([
                'message' => 'Empresa eliminada exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar la empresa: ' . $e->getMessage()
            ], 500);
        }
    }    
}
