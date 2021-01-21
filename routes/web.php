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
//Ruta Login
Route::group(['middleware' => ['guest']], function () {
    Route::get('/','Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
});

Route::group(['middleware' => ['auth']], function () {

    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main');

    Route::group(['middleware' => ['Administrador']], function () {
        //Rutas de Categoria
        Route::get('/categoria', 'CategoriaController@index');
        Route::post('/categoria/registrar', 'CategoriaController@store');
        Route::put('/categoria/actualizar', 'CategoriaController@update');
        Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
        Route::put('/categoria/activar', 'CategoriaController@activar');
        Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');

        //Rutas de Transporte
        Route::get('/transporte', 'TransporteController@index');
        Route::post('/transporte/registrar', 'TransporteController@store');
        Route::put('/transporte/actualizar', 'TransporteController@update');
        Route::put('/transporte/desactivar', 'TransporteController@desactivar');
        Route::put('/transporte/activar', 'TransporteController@activar');
        Route::get('/transporte/selectTransporte', 'TransporteController@selectTransporte');

        //Rutas de Tiendas
        Route::get('/tienda', 'TiendaController@index');
        Route::post('/tienda/registrar', 'TiendaController@store');
        Route::put('/tienda/actualizar', 'TiendaController@update');
        Route::put('/tienda/desactivar', 'TiendaController@desactivar');
        Route::put('/tienda/activar', 'TiendaController@activar');
        Route::get('/tienda/selectTienda', 'TiendaController@selectTienda');

        //Rutas de alertas
        Route::get('/alerta', 'AlertaController@index');
        Route::post('/alerta/registrar', 'AlertaController@store');
        Route::put('/alerta/actualizar', 'AlertaController@update');
        Route::put('/alerta/desactivar', 'AlertaController@desactivar');
        Route::put('/alerta/activar', 'AlertaController@activar');
        Route::get('/alerta/selectAlerta', 'AlertaController@selectAlerta');
        Route::get('/alerta/listarAlertaEnvio', 'AlertaController@listarAlertaEnvio');
        Route::get('/alerta/buscarAlerta', 'AlertaController@buscarAlerta');
        Route::get('/alerta/buscarAlertaEnvio', 'AlertaController@buscarAlertaEnvio');

        //Rutas de Roles
        Route::get('/rol', 'RolController@index');
        Route::get('/rol/selectRol', 'RolController@selectRol');

        //Rutas Usuarios
        Route::get('/user', 'UserController@index');
        Route::post('/user/registrar', 'UserController@store');
        Route::put('/user/actualizar', 'UserController@update');
        Route::put('/user/desactivar', 'UserController@desactivar');
        Route::put('/user/activar', 'UserController@activar');
        Route::get('/user/selectUsuario', 'UserController@selectUsuario');

        //Rutas de envios
        Route::get('/envio', 'EnvioController@index');
        Route::post('/envio/registrar', 'EnvioController@store');
        Route::put('/envio/desactivar', 'EnvioController@desactivar');
        Route::get('/envio/obtenerCabecera', 'EnvioController@obtenerCabecera');
        Route::get('/envio/obtenerDetalles', 'EnvioController@obtenerDetalles');
    });

    Route::group(['middleware' => ['Usuario']], function () {
        //Rutas de alertas
        Route::get('/alerta', 'AlertaController@index');
        Route::post('/alerta/registrar', 'AlertaController@store');
        Route::put('/alerta/actualizar', 'AlertaController@update');
        Route::put('/alerta/desactivar', 'AlertaController@desactivar');
        Route::put('/alerta/activar', 'AlertaController@activar');
        Route::get('/alerta/selectAlerta', 'AlertaController@selectAlerta');
        Route::get('/alerta/listarAlertaEnvio', 'AlertaController@listarAlertaEnvio');
        Route::get('/alerta/buscarAlerta', 'AlertaController@buscarAlerta');
        Route::get('/alerta/buscarAlertaEnvio', 'AlertaController@buscarAlertaEnvio');


        //Rutas de envios
        Route::get('/envio', 'EnvioController@index');
        Route::post('/envio/registrar', 'EnvioController@store');
        Route::put('/envio/desactivar', 'EnvioController@desactivar');
        Route::get('/envio/obtenerCabecera', 'EnvioController@obtenerCabecera');
        Route::get('/envio/obtenerDetalles', 'EnvioController@obtenerDetalles');
    });

    //Route::get('/home', 'HomeController@index')->name('home');
});
