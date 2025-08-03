<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SaldoAnual extends Command
{
    protected $signature = 'entradas:generar-saldo-inicial';
    protected $description = 'Crear registros con saldo inicial usando el campo restante';
    public function handle()
    {
        
        $añoAnterior = now()->subYear()->year;
        $fechaSaldoInicial = Carbon::create(now()->year, 1, 1);

        // Obtener el último registro por artículo del año anterior
        $ultimos = DB::table('entradas')
            ->select('id_articulo', 'precio_unitario', 'restante','id_personal', 'id_proveedor')
            ->whereYear('fecha', $añoAnterior)
            ->where('restante', '>', 0)->get();

        foreach ($ultimos as $u) {
                DB::table('entradas')->insert([
                    'fecha' => $fechaSaldoInicial,
                    'cantidad' => $u->restante,
                    'precio_unitario' => $u->precio_unitario,
                    'restante' => $u->restante,
                    'numero_anual' => 0,
                    'anio' => $fechaSaldoInicial->year,
                    'saldo_inicial' => true,
                    'id_articulo' => $u->id_articulo,
                    'id_personal' => $u->id_personal,
                    'id_proveedor' => $u->id_proveedor,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                $this->info("Saldo inicial creado para artículo ID {$u->id_articulo}");
        }

        $this->info('Proceso finalizado.');
        
        return Command::SUCCESS;
    }
}
