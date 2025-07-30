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
        $backupPath = storage_path('app/backups/');
        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }
        try {
            $date = Carbon::now();
            $database = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host = env('DB_HOST');
            $fileName = $date . '.sql';
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

        if (!$base) {
            return response()->json(['error' => 'Backup no encontrado'], 404);
        }

        $backupPath = storage_path('app/backups/' . $base->archivo);

        if (!file_exists($backupPath)) {
            return response()->json(['error' => 'Archivo de backup no encontrado'], 404);
        }

        try {
            $database = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host     = env('DB_HOST');

            // Escapar ruta y parámetros
            $escapedPath = escapeshellarg($backupPath);
            $escapedUser = escapeshellarg($username);
            $escapedDb   = escapeshellarg($database);
            $escapedHost = escapeshellarg($host);
            $escapedPass = escapeshellarg($password);

            // Construir comando
            $command = "PGPASSWORD={$escapedPass} psql -U {$escapedUser} -h {$escapedHost} -d {$escapedDb} -f {$escapedPath}";

            exec($command, $output, $return_var);

            if ($return_var !== 0) {
                return response()->json(['error' => 'Error al restaurar: ' . implode("\n", $output)], 500);
            }

            return response()->json(['success' => 'Backup restaurado exitosamente.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al restaurar: ' . $e->getMessage()], 500);
        }

    }
}

