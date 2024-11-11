<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Funcionario;
use App\Models\Peticion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FuncionarioController extends Controller
{
    /**
  
     * Display a listing of the resource.

     */
    public function index()
    {


        // Inicia el array de asignaciones y turnos
        $nuevasAsignaciones = [];

        // Función de búsqueda insensible a mayúsculas/minúsculas
        function searchAsignadoId($needle, $haystack)
        {
            foreach ($haystack as $key => $value) {
                if (strcasecmp($value, $needle) === 0) {
                    return $key;
                }
            }
            return false;
        }

        // Paso 1. Obtiene las asignaciones mapeando `id` a `nombre`
        $asignaciones = Asignacion::pluck('nombre', 'id')->toArray();

        // Paso 2. Obtiene todas las peticiones sin asignar
        $peticionesSinAsignar = Peticion::whereNull('funcionario_id')->get(['id', 'tipo_peticion']);

        // Paso 3. Agrupa las peticiones sin asignar por `tipo_peticion`
        $peticionesAgrupadas = $peticionesSinAsignar->groupBy('tipo_peticion');

        // Paso 4. Obtiene los funcionarios agrupados por `asignado_id`
        $funcionarios = Funcionario::whereIn('asignado_id', array_keys($asignaciones))
            ->orderBy('id')
            ->get(['id', 'asignado_id'])
            ->groupBy('asignado_id');

        // Paso 5. Asigna cada grupo de peticiones según `tipo_peticion`
        foreach ($peticionesAgrupadas as $tipoPeticion => $peticiones) {
            // Obtiene el `asignado_id` para el tipo de petición
            $asignadoId = searchAsignadoId($tipoPeticion, $asignaciones);

            // Verifica existencia de funcionarios en el `asignado_id`
            $funcionariosParaTipo = $funcionarios->get($asignadoId, collect());

            if ($asignadoId !== false && $funcionariosParaTipo->isNotEmpty()) {
                // Recupera el último turno persistente desde la tabla `turnos_asignacion`
                $turno = DB::table('turnos_asignacion')
                    ->where('asignado_id', $asignadoId)
                    ->value('turno') ?? 0; // Si no existe el registro, empieza en 0

                $funcionariosIds = $funcionariosParaTipo->pluck('id')->toArray();

                // Asigna cada petición a un funcionario en orden cíclico
                foreach ($peticiones as $peticion) {
                    $funcionarioId = $funcionariosIds[$turno];
                    Peticion::where('id', $peticion->id)->update(['funcionario_id' => $funcionarioId]);

                    // Guarda la asignación en el array de nuevas asignaciones
                    $nuevasAsignaciones[] = [
                        'peticion_id' => $peticion->id,
                        'tipo_peticion' => $tipoPeticion,
                        'funcionario_id' => $funcionarioId,
                    ];

                    // Inserta el registro en el historial de asignaciones
                    DB::table('historial_asignaciones')->insert([
                        'asignado_id' => $asignadoId,
                        'funcionario_id' => $funcionarioId,
                        'peticion_id' => $peticion->id,
                        'fecha_asignacion' => now(),
                    ]);

                    // Avanza el turno al siguiente funcionario y actualiza el índice del turno
                    $turno = ($turno + 1) % count($funcionariosIds);
                }

                // Guarda el turno actualizado en la base de datos
                DB::table('turnos_asignacion')
                    ->updateOrInsert(
                        ['asignado_id' => $asignadoId],
                        ['turno' => $turno]
                    );
            }
        }

        // Retorna el panorama completo de las asignaciones
        return [
            'asignaciones' => $asignaciones,
            'peticionesSinAsignar' => $peticionesSinAsignar,
            'funcionariosPorAsignadoId' => $funcionarios,
            'nuevasAsignaciones' => $nuevasAsignaciones,
            'mensaje' => empty($nuevasAsignaciones)
                ? 'No hay nuevas peticiones para asignar a los funcionarios'
                : 'Se asignaron ' . count($nuevasAsignaciones) . ' nuevas peticiones a los funcionarios correspondientes'
        ];
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
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

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
