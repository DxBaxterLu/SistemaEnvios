<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transporte;

class TransporteController extends Controller
{
    //
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar=='') {
            $transportes= Transporte::orderBy('id','desc')->paginate(10);
        } else {
            $transportes = Transporte::where($criterio,'like','%'.$buscar.'%')->orderBy('id','desc')->paginate(10);
        }

        return [
            'pagination' => [
                'total' => $transportes->total(),
                'current_page' => $transportes->currentPage(),
                'per_page' => $transportes->perPage(),
                'last_page' => $transportes->lastPage(),
                'from' => $transportes->firstItem(),
                'to' => $transportes->lastItem()
            ], 'transportes' => $transportes
        ];
    }

    public function selectTransporte(Request $request){
        if (!$request->ajax()) return redirect('/');
        $transportes = Transporte::where('condicion','=','1')->select('id','nombre')->orderBy('nombre','asc')->get();

        return ['transportes' => $transportes];

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $transporte = new Transporte();
        $transporte->nombre = $request->nombre;
        $transporte->telefono = $request->telefono;
        $transporte->origen = $request->origen;
        $transporte->email = $request->email;
        $transporte->descripcion = $request->descripcion;
        $transporte->condicion = '1';
        $transporte->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $transporte = Transporte::findOrFail($request->id);
        $transporte->nombre = $request->nombre;
        $transporte->telefono = $request->telefono;
        $transporte->origen = $request->origen;
        $transporte->email = $request->email;
        $transporte->descripcion = $request->descripcion;
        $transporte->condicion = '1';
        $transporte->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $transporte = Transporte::findOrFail($request->id);
        $transporte->condicion = '0';
        $transporte->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $transporte = Transporte::findOrFail($request->id);
        $transporte->condicion = '1';
        $transporte->save();
    }
}
