<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CodigoContableController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/* Route::get('/register', [RegisterController::class, 'show']);
Route::post('/action-register', [RegisterController::class, 'register']); */


Route::group(['namespace' => 'App\Http\Controllers'], function()
{   
    /**
     * Home Routes
     */
    //
    Route::get('/', 'LoginController@show')->name('login.show');
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('/login', 'LoginController@show')->name('login.show');
    Route::post('/login', 'LoginController@login')->name('login.perform');

    Route::group(['middleware' => ['guest']], function() {
        /**
         * Register Routes
         */
        Route::get('/register', 'RegisterController@show')->name('register.show');
        Route::post('/register', 'RegisterController@register')->name('register.perform');

        /**
         * Login Routes
         */
        
        

        Route::get('/actuales/actualver/{id}', 'ActualController@verinvitado')->name('actuales.actualver');

    });
    Route::group(['middleware' => ['auth']], function() {
    
        /**
         * Logout Routes
         */
      
        Route::get('/home', 'HomeController@index')->name('home.index');
        Route::get('/logout', 'LogoutController@perform')->name('logout.perform');
  
                
        Route::get('/escritorio', 'EscritorioController@index')->name('escritorio');

       
        Route::get('/ciudades','CiudadesController@index');

        Route::get('/rol', 'RolesController@index');
        Route::get('/rol/selectRol', 'RolesController@selectRol');

        Route::get('/users', 'UsersController@index');
        Route::post('/user/registrar', 'UsersController@store');
        Route::put('/user/actualizar', 'UsersController@update');
        Route::put('/user/desactivar', 'UsersController@desactivar');
        Route::put('/user/activar', 'UsersController@activar');

        Route::get('/establecimientos/{id}','EstablecimientoController@index');
        Route::post('/establecimiento/registrar','EstablecimientoController@store');
        Route::put('/establecimiento/actualizar','EstablecimientoController@update');

        Route::get('/almacen/titulo','AlmacenesController@titulo');
        Route::get('/almacen/{id}','AlmacenesController@index');
        Route::post('/almacen/registrar','AlmacenesController@store');
        Route::put('/almacen/actualizar','AlmacenesController@update');
        Route::get('/almacen/seleccionar/{id}','AlmacenesController@select');

        Route::get('/partidas','PartidasController@index');
        Route::get('/partidas/todos','PartidasController@todos');
        Route::post('/partidas/registrar','PartidasController@store');
        Route::delete('/partidas/eliminar/{id}','PartidasController@destroy');
        Route::put('/partidas/actualizar','PartidasController@update');
        Route::get('/partidas/pdf','PartidasController@pdfPartidas');
        
        Route::get('/unidades','UnidadesController@index');
        Route::get('/unidades/todos/{id}','UnidadesController@todos');
        Route::post('/unidades/registrar','UnidadesController@store');
        Route::delete('/unidades/eliminar/{id}','UnidadesController@destroy');
        Route::put('/unidades/actualizar','UnidadesController@update');
        Route::get('/unidades/pdf','UnidadesController@pdfUnidades');

        Route::get('/provedores','ProvedoresController@index');
        Route::post('/provedores/registrar','ProvedoresController@store');
        Route::delete('/provedores/eliminar/{id}','ProvedoresController@destroy');
        Route::put('/provedores/actualizar','ProvedoresController@update');
        Route::get('/provedores/pdf','ProvedoresController@pdfProvedores');
        Route::get('/provedores/buscar/{nit}','ProvedoresController@buscar');

        Route::get('/articulos','ArticulosController@index');
        Route::post('/articulos/registrar','ArticulosController@store');
        Route::delete('/articulos/eliminar/{id}','ArticulosController@destroy');
        Route::put('/articulos/actualizar','ArticulosController@update');
        Route::get('/articulos/pdf','ArticulosController@pdfArticulos');
        Route::get('/articulos/partidas/{id}','ArticulosController@partidas');
        Route::get('/articulos/buscar/{cod}','ArticulosController@buscar');

        Route::get('/personal','PersonalController@index');
        Route::post('/personal/registrar','PersonalController@store');
        Route::delete('/personal/eliminar/{id}','PersonalController@destroy');
        Route::put('/personal/actualizar','PersonalController@update');
        Route::get('/personal/pdf','PersonalController@pdfPersonal');
        Route::get('/personal/codigo','PersonalController@codigoper');
        Route::get('/personal/buscar/{ci}','PersonalController@buscar');

        Route::get('/entradas','EntradasController@index');
        Route::get('/entradas/notas','EntradasController@notas');
        Route::post('/entradas/registrar','EntradasController@store');
        Route::delete('/entradas/eliminar/{nro}/{anio}','EntradasController@destroy');
        Route::put('/entradas/actualizar','EntradasController@update');
        Route::get('/entradas/pdf','EntradasController@pdfEntradas');
        Route::get('/entradas/codigo','EntradasController@codigoper');
        Route::get('/entradas/entradapdf/{fecha}/{anio}/{numeroanual}','EntradasController@pdfentrada');
        Route::get('/entradas/items','EntradasController@items');
        Route::get('/entradas/grafica','EntradasController@grafica');
        Route::get('/entradas/pdftotal','EntradasController@pdftotal');

        Route::get('/salidas','SalidasController@index');
        Route::get('/salidas/notas','SalidasController@notas');
        Route::post('/salidas/registrar','SalidasController@store');
        Route::delete('/salidas/eliminar/{nro}/{anio}','SalidasController@destroy');
        Route::put('/salidas/actualizar','SalidasController@update');
        Route::get('/salidas/pdf','SalidasController@pdfSalidas');
        Route::get('/salidas/codigo','SalidasController@codigoper');
        Route::get('/salidas/salidapdf/{fecha}/{anio}/{numeroanual}','SalidasController@pdfsalida');
        Route::get('/salidas/items','SalidasController@items');

        Route::get('/backup','BackupController@index');
        Route::get('/download-sql', 'BackupController@store');
        Route::get('/procesar-sql/{id}','BackupController@procesar');
        });
    });

