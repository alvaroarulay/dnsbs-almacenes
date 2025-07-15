<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use XBase\TableReader;
use Illuminate\Support\Facades\DB;

class oficinaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = new TableReader(storage_path('app/vsiaf/dbfs/OFICINA.DBF'),['encoding' => 'cp1252']);
        while ($record = $table->nextRecord()) {
            DB::table('oficina')->insert([
            'unidad' => $record->get('unidad'),
            'codofic' => $record->get('codofic'),
            'nomofic' => $record->get('nomofic'),
            'id_piso'=>1,
            'created_at'=> now(),
            'updated_at'=> now(),
          ]);
        }
    }
}
