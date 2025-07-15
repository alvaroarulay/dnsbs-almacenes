<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use XBase\TableReader;
use Illuminate\Support\Facades\DB;

class ResponsableSeeder extends Seeder
{    
    public function run()
    {
        $table = new TableReader(storage_path('app/vsiaf/dbfs/RESP.DBF'),['encoding' => 'cp1252']);
        while ($record = $table->nextRecord()) {
            DB::table('resp')->insert([
            'unidad' => $record->get('unidad'),
            'codresp' => $record->get('codresp'),
            'codofic' => $record->get('codofic'),
            'CI' => $record->get('ci'),
            'NOMBRE' => $record->get('nomresp'),
            'CARGO' => $record->get('cargo'),
            'ID_OFIC'=>$record->get('codofic'),
            'created_at'=> now(),
            'updated_at'=> now(),
          ]);
        }
    }
}