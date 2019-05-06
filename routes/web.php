<?php

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

Route::group(['middleware'=>['guest']],function(){
    Route::get('/','Auth\LoginController@showLoginForm');
    Route::get('/login','Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
});//fin middleware GUEST(INVITADO)


Route::group(['middleware'=>['auth']],function(){

    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    
    Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main');

    
    Route::group(['middleware'=>['Afiliado']],function(){

        Route::get('/afiliado', 'AfiliadoController@index');
        Route::get('/afiliado/perfil', 'AfiliadoController@perfil');
        Route::get('/afiliado/perfilPdf', 'AfiliadoController@perfilPdf')->name('perfil_pdf');
        Route::get('/aporte/getAportesUsuario', 'PagosController@getAportesUsuario');
        Route::get('/pago/aportesPdf', 'PagosController@aportesPdf')->name('aportes_pdf');


    });//fin middleware AFILIADO

    
    Route::group(['middleware'=>['Administrador']],function(){

        Route::get('/afiliado', 'AfiliadoController@index');
        Route::get('/afiliado/perfil', 'AfiliadoController@perfil');
        Route::get('/afiliado/perfilPdf', 'AfiliadoController@perfilPdf')->name('perfil_pdf');
        Route::get('/afiliado/verificarCi', 'AfiliadoController@verificarCi');
        Route::get('/afiliado/validarCodigo', 'AfiliadoController@validarCodigo');

        
        Route::post('/afiliado/registrar', 'AfiliadoController@store');
        Route::put('/afiliado/actualizar', 'AfiliadoController@update');
        Route::put('/afiliado/deshabilitar', 'AfiliadoController@deshabilitar');
        Route::put('/afiliado/habilitar', 'AfiliadoController@habilitar');      
        
        Route::get('/afiliadoAporte', 'AfiliadoController@afiliadoAporte');
        Route::get('/Aporte/ultimoPago', 'AfiliadoController@ultimoPago');
        Route::post('/aporte/registrar', 'PagosController@store');
        Route::post('/aporte/actualizar', 'PagosController@update');
        Route::get('/aporte/getAportes', 'PagosController@getAportes');
        Route::get('/aporte/getAportesUsuario', 'PagosController@getAportesUsuario');
        
        
        Route::get('/pagos', 'PagosController@index');
        Route::post('/pago/registrar', 'PagosController@store');
        //Route::put('/pago/actualizar', 'PagosController@update');
        Route::get('/pago/reciboPdf', 'PagosController@recibo')->name('recibo_pdf');
        Route::get('/pago/aportesPdf', 'PagosController@aportesPdf')->name('aportes_pdf');
        
        
        Route::get('/titulos', 'TitulosController@index');
        Route::post('/titulos/registrar', 'TitulosController@store');
        Route::post('/titulos/actualizar', 'TitulosController@update');
        Route::post('/titulos/eliminar', 'TitulosController@destroy');
        
        Route::get('/reporte/gestion', 'AfiliadoController@reporteAfiliadosGestion');
        Route::get('/reporte/deudores', 'AfiliadoController@deudores');
        Route::get('/reporte/nodeudores', 'AfiliadoController@nodeudores');
        
        Route::get('/reporte/antiguedad25', 'AfiliadoController@antiguedad25');
        Route::get('/reporte/antiguedad50', 'AfiliadoController@antiguedad50');
        
        Route::get('/rol', 'RolController@index');
        Route::get('/rol/selectRol', 'RolController@selectRol');
        Route::get('/rol/getRol', 'AfiliadoController@getRol');
        
        Route::get('/user', 'UserController@index');
        Route::post('/user/registrar', 'UserController@store');
        Route::put('/user/actualizar', 'UserController@update');
        Route::put('/user/desactivar', 'UserController@desactivar');
        Route::put('/user/activar', 'UserController@activar');

        //rutas userAdmin  
        Route::get('/userAdmin', 'UserController@indexAdmin');
        Route::put('/userAdmin/actualizar', 'UserController@updateAdmin');      
   
    });//fin middleware Adminstrador
    
});//fin middleware AUTH(AUTENTICADO)

//Route::get('/home', 'HomeController@index')->name('home');
