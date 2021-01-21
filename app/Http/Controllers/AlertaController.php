<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Alerta;

class AlertaController extends Controller
{
    //
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar=='') {
            $alertas= Alerta::join('categorias','alertas.idcategoria','=','categorias.id')
            ->join('transportes','alertas.idtransporte','=','transportes.id')
            ->join('tiendas','alertas.idtienda','=','tiendas.id')
            ->select('alertas.id','categorias.nombre as nombre_categoria',
            'transportes.nombre as nombre_transporte','tiendas.nombre as nombre_tienda','alertas.descripcion','alertas.stock',
            'alertas.condicion')->orderBy('alertas.id','desc')->paginate(10);
        } else {
            $alertas= Alerta::join('categorias','alertas.idcategoria','=','categorias.id')
            ->join('transportes','alertas.idtransporte','=','transportes.id')
            ->join('tiendas','alertas.idtienda','=','tiendas.id')
            ->select('alertas.id','categorias.nombre as nombre_categoria',
            'transportes.nombre as nombre_transporte','tiendas.nombre as nombre_tienda',
            'alertas.descripcion','alertas.stock','alertas.condicion')
            ->where('alertas.'.$criterio,'like','%'.$buscar.'%')->orderBy('alertas.id','desc')->paginate(10);
        }

        return [
            'pagination' => [
                'total' => $alertas->total(),
                'current_page' => $alertas->currentPage(),
                'per_page' => $alertas->perPage(),
                'last_page' => $alertas->lastPage(),
                'from' => $alertas->firstItem(),
                'to' => $alertas->lastItem()
            ], 'alertas' => $alertas
        ];
    }

    public function selectAlerta(Request $request){
        if (!$request->ajax()) return redirect('/');
        $alertas = Alerta::where('condicion','=','1')->select('id','nombre')->orderBy('nombre','asc')->get();

        return ['alertas' => $alertas];

    }

    public function listarAlertaEnvio(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $alertas = Alerta::join('categorias','alertas.idcategoria','=','categorias.id')
            ->join('transportes','alertas.idtransporte','=','transportes.id')
            ->join('tiendas','alertas.idtienda','=','tiendas.id')
            ->select('alertas.id','categorias.nombre as nombre_categoria',
            'transportes.nombre as nombre_transporte','tiendas.nombre as nombre_tienda',
            'alertas.descripcion','alertas.stock','alertas.condicion')
            ->where('alertas.stock','>','0')
            ->orderBy('alertas.id', 'desc')->paginate(10);
        }
        else{
            $alertas = Alerta::join('categorias','alertas.idcategoria','=','categorias.id')
            ->join('transportes','alertas.idtransporte','=','transportes.id')
            ->join('tiendas','alertas.idtienda','=','tiendas.id')
            ->select('alertas.id','categorias.nombre as nombre_categoria',
            'transportes.nombre as nombre_transporte','tiendas.nombre as nombre_tienda',
            'alertas.descripcion','alertas.stock','alertas.condicion')
            ->where('alertas.'.$criterio, 'like', '%'. $buscar . '%')
            ->where('alertas.stock','>','0')
            ->orderBy('alertas.id', 'desc')->paginate(10);
        }
        return ['alertas' => $alertas];
    }

    public function buscarAlerta(Request $request){
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $alertas = Alerta::where('descripcion','=', $filtro)
        ->select('id','descripcion')->orderBy('descripcion', 'asc')->take(1)->get();

        return ['alertas' => $alertas];
    }

    public function buscarAlertaEnvio(Request $request){
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $alertas = Alerta::where('descripcion','=', $filtro)
        ->select('id','descripcion','stock')
        ->where('stock','>','0')
        ->orderBy('descripcion', 'asc')
        ->take(1)->get();

        return ['alertas' => $alertas];
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
        $alerta = new Alerta();
        $alerta->idcategoria = $request->idcategoria;
        $alerta->idtransporte = $request->idtransporte;
        $alerta->idtienda = $request->idtienda;
        $alerta->descripcion = $request->descripcion;
        $alerta->stock = $request->stock;
        $alerta->condicion = '1';
        $alerta->save();
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
        $alerta = Alerta::findOrFail($request->id);
        $alerta->idcategoria = $request->idcategoria;
        $alerta->idtransporte = $request->idtransporte;
        $alerta->idtienda = $request->idtienda;
        $alerta->descripcion = $request->descripcion;
        $alerta->stock = $request->stock;
        $alerta->condicion = '1';
        $alerta->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $alerta = Alerta::findOrFail($request->id);
        $alerta->condicion = '0';
        $alerta->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $alerta = Alerta::findOrFail($request->id);
        $alerta->condicion = '1';
        $alerta->save();
    }
}
