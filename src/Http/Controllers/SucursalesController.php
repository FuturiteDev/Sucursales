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

            $values = $request->all();
            $values['estatus'] = 1;

            $this->sucursales->updateOrCreate(['id' => $request->id], $values);

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
