<?php

namespace Ongoing\Sucursales\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Log;

use Ongoing\Sucursales\Repositories\SucursalesRepositoryEloquent;



class SucursalesController extends Controller
{
    protected $sucursales;

    public function __construct(
        SucursalesRepositoryEloquent $sucursales
    ) {
        $this->sucursales = $sucursales;
    }
    
    function index() {
        Gate::authorize('access-granted', '/sucursales');
        $sucursales = $this->getAll();
        return view('sucursales::sucursales', ['sucursales' => $sucursales]);
    }

    public function getAll(){
        try {
            $sucursales = $this->sucursales->where(['estatus' => 1])->get();

            return response()->json([
                'status' => true,
                'results' => $sucursales
            ], 200);
        } catch (\Exception $e) {
            Log::info("SucursalesController->getAll() | " . $e->getMessage(). " | " . $e->getLine());
            
            return response()->json([
                'status' => false,
                'message' => "[ERROR] SucursalesController->getAll() | " . $e->getMessage(). " | " . $e->getLine(),
                'results' => null
            ], 500);
        }
    }

    /**
     * /api/sucursales/save
     *
     * Guarda una sucursal
     *
     * @return JSON
     **/
    public function save(Request $request){
        try {

            $values = $request->except(['sucursal_id']);
            $values = $request->only(['nombre', 'direccion', 'matriz', 'color']);
            $values['estatus'] = 1;

            $nombreExistente = $this->sucursales->where('nombre', $values['nombre'])
                ->where('estatus', 1)
                ->where('id', '!=', $request->sucursal_id)
                ->exists();

            if ($nombreExistente) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ya existe una sucursal activa con ese nombre.',
                    'results' => null
                ], 400);
            }

            if ($values['matriz'] == 1) {
                $this->sucursales->where('matriz', 1)->update(['matriz' => 0]);
            } else {                
                $values['matriz'] = 0;
            }

            if ($request->sucursal_id) {
                $sucursal = $this->sucursales->find($request->sucursal_id);
                if (!$sucursal) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Sucursal no encontrada.',
                        'results' => null
                    ], 404);
                }
                $sucursal->update($values);
            } else {
                $sucursal = $this->sucursales->create($values);
            }
            
            return response()->json([
                'status' => true,
                'message' => "Sucursal guardada.",
                'results' => $this->sucursales->where(['estatus' => 1])->get()
            ], 200);
        } catch (\Exception $e) {
            Log::info("SucursalesController->save() | " . $e->getMessage(). " | " . $e->getLine());
            
            return response()->json([
                'status' => false,
                'message' => "[ERROR] SucursalesController->save() | " . $e->getMessage(). " | " . $e->getLine(),
                'results' => null
            ], 500);
        }
    }

    /**
     * /api/sucursales/delete
     *
     * Deshabilita una sucursal
     *
     * @return JSON
     **/
    public function delete(Request $request)
    {
        try {

            $this->sucursales->where('id', $request->sucursal_id)->update(['estatus' => 0]);

            return response()->json([
                'status' => true,
                'message' => "Sucursal eliminada.",
                'results' => $this->sucursales->where('estatus', 1)->get()
            ], 200);
        } catch (\Exception $e) {
            Log::info("SucursalesController->delete() | " . $e->getMessage() . " | " . $e->getLine());
            return response()->json([
                'status' => false,
                'message' => "[ERROR] SucursalesController->delete() | " . $e->getMessage() . " | " . $e->getLine(),
                'results' => null
            ], 500);
        }
    }
}
