<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Backup;


class BackupController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $query= Backup::select('id','archivo','usuar');
        if ($buscar==''){
            $base = $query->paginate(10);
        }
        else{
            $base = $query->where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(10);
        }
        return [
                'pagination' => [
                'total'        => $base->total(),
                'current_page' => $base->currentPage(),
                'per_page'     => $base->perPage(),
                'last_page'    => $base->lastPage(),
                'from'         => $base->firstItem(),
                'to'           => $base->lastItem(),
            ],
            'base' => $base,
        ];
    }
    public function store(Request $request){
        $backupPath = storage_path('app/backups');
        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }
        try {
            $date = Carbon::now();
            $database = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host = env('DB_HOST');
            $backupPath = storage_path('app/backups');
            $fileName = $date->format('Ymd') . '.sql';
            $command = "PGPASSWORD='{$password}' pg_dump --username={$username} --host={$host} --dbname={$database} --format=plain --file={$backupPath}/{$fileName}";
            exec($command); 
            $backup = new Backup();
            $backup->archivo=$fileName;
            $backup->usuar=\Auth::user()->username;
            $backup->save();
            return response()->download(storage_path('app/backups/'.$fileName));
            
            } catch (Exception $e) {

               return print_r("hubo un error");
            }
    }
    public function procesar($id){
        $base = Backup::where('id', $id)->first();

        if (!$base || !$base->archivo) {
            return response()->json(['error' => 'Archivo no encontrado en base de datos.'], 404);
        }

        $ruta = storage_path("app/backups/" . $base->archivo);

        if (!file_exists($ruta)) {
            return response()->json(['error' => 'Archivo no existe en el sistema.'], 404);
        }

        $tabla = 'entradas'; // Puedes hacerlo dinámico si lo deseas

        $contenido = file_get_contents($ruta);

        $bloques = preg_split('/(?=^CREATE TABLE|^INSERT INTO)/m', $contenido);

        $bloquesFiltrados = collect($bloques)->filter(function ($bloque) use ($tabla) {
            return str_starts_with(trim($bloque), "INSERT INTO") && str_contains($bloque, "`$tabla`");
        })->implode("\n");

        if (empty($bloquesFiltrados)) {
            return response()->json(['error' => "No se encontraron datos para la tabla `$tabla`."]);
        }

        try {
            // Vaciar la tabla
            DB::statement("TRUNCATE TABLE `$tabla`");

            // Insertar los datos filtrados
            DB::unprepared($bloquesFiltrados);

            return response()->json(['success' => "Tabla `$tabla` restaurada exitosamente."]);
        } catch (\Exception $e) {

            return response()->json(['error' => 'Error al ejecutar SQL: ' . $e->getMessage()], 500);
        }
    
    }
 }

