<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personal;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Almacenes;
use App\Models\Establecimiento;
use App\Models\Ciudad;
use App\Models\Articulos;
use Jenssegers\Date\Date;

class PersonalController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $query = Personal::join('almacen', 'personal.id_almacen', '=', 'almacen.id')
            ->select('personal.*', 'almacen.nomalmacen as almacen')->where('almacen.seleccionado','=',1);;
        if ($buscar=='') {
            $personal = $query->paginate(10);
        } else {
            $personal = $query->where('personal.'.$criterio, 'like', '%' . $buscar . '%')->paginate(10);
        }
        return [
            'pagination' => [
                'total'         => $personal->total(),
                'current_page'  => $personal->currentPage(),
                'per_page'      => $personal->perPage(),
                'last_page'     => $personal->lastPage(),
                'from'          => $personal->firstItem(),
                'to'            => $personal->lastItem(),
            ],
            'personal' => $personal
        ];

    }
    public function buscar($cod){
      return response()->json(Personal::where('codper','=',$cod)->get());
    }
     public function codigoper(){
        $id = Personal::max('id');
        $query = Personal::where('id', '=', $id)->value('codper');
        //dd($query);
        [$prefijo, $numero] = explode('-', $query);
        $codanterior = intval($numero) + 1;
        $codanterior = $prefijo . '-' . $codanterior;

        return response()->json(['codigo'=>$codanterior]);
    }
    public function store(Request $request)
    {
        try {
            $almacen = Almacenes::where('seleccionado','=',1)->first();
            $this->validate($request, [
                'codper' => 'required|max:50',
                'nomper' => 'required|max:50',
                'ciper' => 'required|max:20',
                'telper' => 'nullable|max:20',
                'dirper' => 'nullable|max:100',
                'emailper' => 'nullable|email|max:50'
            ]);
            $personal = new Personal();
            $personal->codper = $request->codper;
            $personal->nomper = $request->nomper;
            $personal->ciper = $request->ciper;
            $personal->telper = $request->telper;
            $personal->dirper = $request->dirper;
            $personal->emailper = $request->emailper;
            $personal->cargo = $request->cargo;
            $personal->id_almacen = $almacen->id;
            $personal->save();
            return response()->json(['message' => 'Personal guardado correctamente!']);
         } catch (\Illuminate\Database\QueryException $e) {
            $errorMessage = $e->getMessage();
            if (strpos($errorMessage, 'foreign key constraint fails') !== false) {
                return response()->json(['message' => 'No se puede eliminar porque está siendo utilizado en otra tabla.']);
            }
            if ($e->getCode() == 1451) {
                return response()->json(['message' => 'No se puede eliminar porque está siendo utilizado en otra tabla.']);
            }
            return response()->json(['message' => 'Excepción capturada: ' . $e->getMessage()]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Excepción capturada: ' . $e->getMessage()]);
        }
    }
    public function update(Request $request)
    {
        try {
        
            if (!$request->id) {
                return response()->json(['message' => 'ID de personal no proporcionado'], 400);
            }
            $personal = Personal::findOrFail($request->id);
            $personal->nomper = $request->nomper;
            $personal->ciper = $request->ciper;
            $personal->telper = $request->telper;
            $personal->dirper = $request->dirper;
            $personal->emailper = $request->emailper;
            $personal->cargo = $request->cargo;
            $personal->id_almacen = 1;
            $personal->save();
            return response()->json(['message' => 'Personal actualizado correctamente!']);
        } catch (\Illuminate\Database\QueryException $e) {
            $errorMessage = $e->getMessage();
            if (strpos($errorMessage, 'foreign key constraint fails') !== false) {
                return response()->json(['message' => 'No se puede eliminar porque está siendo utilizado en otra tabla.']);
            }
            if ($e->getCode() == 1451) {
                return response()->json(['message' => 'No se puede eliminar porque está siendo utilizado en otra tabla.']);
            }
            return response()->json(['message' => 'Excepción capturada: ' . $e->getMessage()]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Excepción capturada: ' . $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {
            $articulo = Articulos::findOrFail($id);
            $articulo->delete();
            return response()->json(['message' => 'Articulo eliminado correctamente!']);
        }  catch (\Illuminate\Database\QueryException $e) {
            $errorMessage = $e->getMessage();
            if (strpos($errorMessage, 'foreign key constraint fails') !== false) {
                return response()->json(['message' => 'No se puede eliminar la Unidad porque está siendo utilizado en otra tabla.']);
            }
            if ($e->getCode() == 1451) {
                return response()->json(['message' => 'No se puede eliminar el Unidad porque está siendo utilizado en otra tabla.']);
            }
            return response()->json(['message' => 'Excepción capturada: ' . $e->getMessage()]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Excepción capturada: ' . $e->getMessage()]);
        }
    }
    public function pdfPersonal(){
        Date::setLocale('es');
        $fechaTitulo = Date::now()->format('l j F Y');
        $fechDerecha = Date::now()->format('d/M/Y');
        $datos = Personal::join('almacen', 'personal.id_almacen', '=', 'almacen.id')
            ->select('personal.*', 'almacen.nomalmacen as almacen')->where('almacen.seleccionado','=',1)->orderBy('personal.id', 'asc')->get();
        $titulo = 'Listado de Articulos';
        $almacen = Almacenes::where('seleccionado','=',1)->first();
        $establecimiento = Establecimiento::where('id','=',$almacen->id_establecimiento)->first();
        $ciudad = Ciudad::where('id','=',$establecimiento->id_ciudad)->first();
        $pdf=Pdf::loadView('plantillapdf.reportepersonal',[
            'datos'=>$datos,
            'titulo'=>$titulo,
            'fechaTitulo'=>$fechaTitulo,
            'fechaDerecha'=>$fechDerecha,
            'almacen'=>$almacen->nomalmacen,
            'establecimiento'=>$establecimiento->nomestab,
            'ciudad'=>$ciudad->nomciudad,
            ]);
        $pdf->set_paper('letter', 'portrait');
        return $pdf->stream();
        /*$titulo = 'Listado de Personal';
        $almacen = Almacenes::find(1);
        $establecimiento = Establecimiento::find(1);
        $html = view('plantillapdf.mainpersonal', [ 'datos' => $datos, 'almacen' => $almacen->nomalmacen, 'establecimiento' => $establecimiento->nomestab ])->render();
        $headerHtml = view('plantillapdf.header', [
            'fechaTitulo' => $fechaTitulo,
            'titulo' => $titulo,
            'fechaDerecha' => $fechDerecha,
        ])->render();
        $footerhtml = view('plantillapdf.footer')->render();*/

        return Pdf::loadHTML($html)
            ->setOption('header-html', $headerHtml)
            ->setOption('footer-html', $footerhtml)
            ->setOption('margin-top', '40mm') // espacio para el header
            ->setOption('margin-bottom', '20mm') // espacio para el footer
            ->setOption('header-spacing', 5)  // espacio entre header y contenido
            ->setPaper('letter', 'portrait') // tamaño de papel y orientación
            ->setOption('footer-right', 'Página: [page] de [topage]')
            ->setOption('footer-font-size', 8)
            ->inline('documento.pdf');
        }
}
