// Inicia el array de asignaciones y turnos
$nuevasAsignaciones = []; // Crea un array vacío que almacenará las nuevas asignaciones realizadas en este proceso

// Función de búsqueda insensible a mayúsculas/minúsculas
function searchAsignadoId($needle, $haystack) {
    foreach ($haystack as $key => $value) {
        // strcasecmp: compara dos strings sin diferenciar entre mayúsculas y minúsculas.
        // Si $needle y $value son iguales (sin importar el caso), retorna 0, así que usamos === 0.
        if (strcasecmp($value, $needle) === 0) {
            return $key; // Si encuentra una coincidencia, devuelve la clave (ID)
        }
    }
    return false; // Si no encuentra coincidencias, devuelve false
}

// Paso 1. Obtiene las asignaciones mapeando `id` a `nombre`
$asignaciones = Asignacion::pluck('nombre', 'id')->toArray();
// pluck: selecciona solo dos columnas (nombre e id) de la tabla `asignacions`.
// toArray convierte el resultado en un array simple, donde las claves son los IDs y los valores los nombres.

// Paso 2. Obtiene todas las peticiones sin asignar
$peticionesSinAsignar = Peticion::whereNull('funcionario_id')->get(['id', 'tipo_peticion']);
// whereNull: filtra las peticiones donde `funcionario_id` es NULL, indicando que están sin asignar.
// get(['id', 'tipo_peticion']): obtiene solo las columnas `id` y `tipo_peticion` para optimizar el rendimiento.

// Paso 3. Agrupa las peticiones sin asignar por `tipo_peticion`
$peticionesAgrupadas = $peticionesSinAsignar->groupBy('tipo_peticion');
// groupBy: agrupa las peticiones por tipo para asignarlas según el tipo específico.

// Paso 4. Obtiene los funcionarios agrupados por `asignado_id`
$funcionarios = Funcionario::whereIn('asignado_id', array_keys($asignaciones))
    ->orderBy('id') // orderBy: ordena los resultados por `id` ascendente.
    ->get(['id', 'asignado_id']) // get: obtiene solo las columnas `id` y `asignado_id`.
    ->groupBy('asignado_id');
// whereIn: selecciona a los funcionarios cuyo `asignado_id` esté en el array de `asignaciones`.
// groupBy: agrupa el resultado por `asignado_id`, permitiendo asignaciones cíclicas por cada tipo de funcionario.

// Paso 5. Asigna cada grupo de peticiones según `tipo_peticion`
foreach ($peticionesAgrupadas as $tipoPeticion => $peticiones) {
    $asignadoId = searchAsignadoId($tipoPeticion, $asignaciones); 
    // Llama a la función searchAsignadoId para obtener el ID asociado al tipo de petición.

    // Verifica existencia de funcionarios en el `asignado_id`
    $funcionariosParaTipo = $funcionarios->get($asignadoId, collect());
    // get: intenta recuperar el grupo de funcionarios para este `asignado_id`.
    // collect(): crea una colección vacía si no encuentra funcionarios para evitar errores.

    if ($asignadoId !== false && $funcionariosParaTipo->isNotEmpty()) {
        // Obtiene el último turno desde la tabla `turnos_asignacion`
        $turno = DB::table('turnos_asignacion') // DB::table: accede a la tabla `turnos_asignacion`.
            ->where('asignado_id', $asignadoId) // where: filtra los registros por `asignado_id`.
            ->value('turno') ?? 0; // value: obtiene el valor de la columna `turno`.
        // Si `turno` es null (es decir, el registro no existe), asigna el valor 0.

        $funcionariosIds = $funcionariosParaTipo->pluck('id')->toArray(); 
        // pluck('id') extrae solo los IDs de los funcionarios y toArray los convierte a un array simple.

        // Asigna cada petición a un funcionario en orden cíclico
        foreach ($peticiones as $peticion) {
            $funcionarioId = $funcionariosIds[$turno]; 
            // Asigna el funcionario correspondiente según el índice `$turno`.
            Peticion::where('id', $peticion->id)->update(['funcionario_id' => $funcionarioId]);
            // update: actualiza el `funcionario_id` en la petición con el `id` actual del funcionario.

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
                'fecha_asignacion' => now(), // now(): obtiene la fecha y hora actual.
            ]);

            // Avanza el turno al siguiente funcionario y actualiza el índice del turno
            $turno = ($turno + 1) % count($funcionariosIds); 
            // (% count(...)): asegura que el turno sea cíclico, reiniciándose al alcanzar el límite de funcionarios.
        }

        // Guarda el turno actualizado en la base de datos
        DB::table('turnos_asignacion')
            ->updateOrInsert(
                ['asignado_id' => $asignadoId],
                ['turno' => $turno]
            );
        // updateOrInsert: inserta un registro si no existe, o lo actualiza si ya existe.
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
// Retorna un array con todos los datos procesados, incluyendo un mensaje que indica si hubo nuevas asignaciones.
