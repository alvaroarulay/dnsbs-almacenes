<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documentos;

class DocumentosController extends Controller
{
    public function guardar(Request $request)
    {
        $request->validate([
            'documento' => 'required|mimes:pdf|max:20480', // 20MB
        ]);

        $productoId = $request->producto_id;

        // Nombre único y auditable
        $nombreArchivo = 'producto_' . $productoId . '_' . time() . '.pdf';

        // Guardar en storage/app/public/documentos
        $ruta = $request->file('documento')->storeAs(
            'documentos',
            $nombreArchivo,
            'public'
        );

        // Registrar en BD (opcional)
        Documentos::create([
            'producto_id' => $productoId,
            'ruta' => $ruta,
            'nombre' => $nombreArchivo,
        ]);

        return response()->json([
            'message' => 'Documento guardado correctamente',
            'ruta' => $ruta,
        ]);
    } 
}
