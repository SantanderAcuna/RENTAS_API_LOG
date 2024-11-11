<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Contribuyente;
use App\Models\Funcionario;
use App\Models\Peticion;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PeticionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all = Peticion::all();

        return $all;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        //$s=findorFail($funcionario->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Intentamos encontrar el registro de 'Funcionario' con el ID proporcionado.
            // Si se encuentra, se asigna a la variable $funcionario.
            // Si no se encuentra, se lanza una excepción ModelNotFoundException.

            $peticion = Peticion::findOrFail($id);
            $funcionario = Funcionario::findOrFail($peticion->funcionario_id);
            $contribuyente = Contribuyente::findOrFail($peticion->contribuyente_id);
            
            //$asignacion = Asignacion::all();
            
            

            // Si el registro se encuentra, se devuelve una respuesta JSON con los datos del funcionario.
            return response()->json([
                'Status' => 200,
                'mensaje' => 'Informacion encontrada',
                'data' => [
                    'Id' => $peticion->id,
                    'contribuyente' => $contribuyente->nombre,
                    'Tipo de peticion' => $peticion->tipo_peticion,
                    'Fecha de peticion' => $peticion->fecha_asignacion,
                    "Asignada a: " => $funcionario->nombre,
                    //"asignado_id: " => $asignacion->funciionario->nombre,
                    'Fecha de vencimmiento' => $peticion->fecha_vencimiento,
                ]
            ]);
        } catch (ModelNotFoundException $e) {

            // Si ocurre una excepción porque no se encontró el registro, se captura aquí.
            // Se devuelve una respuesta JSON con un mensaje de error y un código de estado HTTP 404.

            return response()->json([
                'status' => 404,
                'mensaje' => 'No existe información',
                'data'=>[]
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
