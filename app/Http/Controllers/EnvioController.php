<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Envio;
use App\DetalleEnvio;
use App\User;

class EnvioController extends Controller
{
    //
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar=='') {
            $envios= Envio::join('users','envios.idusuario','=','users.id')
            ->select('envios.id','users.usuario','envios.tipo_identificacion',
            'envios.num_envio','envios.fecha_envio','envios.impuesto','envios.total',
            'envios.estado')->orderBy('envios.id','desc')->paginate(10);
        } else {
            $envios= Envio::join('users','envios.idusuario','=','users.id')
            ->select('envios.id','users.usuario','envios.tipo_identificacion',
            'envios.num_envio','envios.fecha_envio','envios.impuesto','envios.total',
            'envios.estado')->where('envios.'.$criterio,'like','%'.$buscar.'%')
            ->orderBy('envios.id','desc')->paginate(10);
        }

        return [
            'pagination' => [
                'total' => $envios->total(),
                'current_page' => $envios->currentPage(),
                'per_page' => $envios->perPage(),
                'last_page' => $envios->lastPage(),
                'from' => $envios->firstItem(),
                'to' => $envios->lastItem()
            ], 'envios' => $envios
        ];
    }

    public function obtenerCabecera(Request $request){
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;
        $envio= Envio::join('users','envios.idusuarios','=','users.id')
        ->select('envios.id','users.usuario','envios.tipo_identificacion',
        'envios.num_envio','envios.fecha_envio','envios.impuesto','envios.total',
        'envios.estado')
        ->where('envios.id','=',$id)
        ->orderBy('envios.id', 'desc')->take(1)->get();

        return ['envio' => $envio];
    }

    public function obtenerDetalles(Request $request){
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;
        $detalles = DetalleEnvio::join('alertas','detalle_envios.idalerta','=','alertas.id')
        ->select('detalle_envios.peso','detalle_envios.cantidad','detalle_envios.descuento','alertas.descripcion as producto')
        ->where('detalle_envios.idenvio','=',$id)
        ->orderBy('detalle_envios.id', 'desc')->get();

        return ['detalles' => $detalles];
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

        try{
            DB::beginTransaction();

            $mytime= Carbon::now('America/Guayaquil');

            $envio = new Envio();
            $envio->idusuario = \Auth::user()->id;
            $envio->tipo_identificacion = $request->tipo_identificacion;
            $envio->num_envio = $request->num_envio;
            $envio->fecha_envio = $mytime->toDateString();
            $envio->impuesto = $request->impuesto;
            $envio->total = $request->total;
            $envio->estado ='Enviado';
            $envio->save();

            $detalles = $request->data;//array de detalles
            //recorre todos los elementos

            foreach($detalles as $ep=>$det)
            {
                $detalle = new DetalleEnvio();
                $detalle->idenvio = $envio->id;
                $detalle->idproducto = $det['idalerta'];
                $detalle->peso = $det['peso'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->descuento = $det['descuento'];
                $detalle->save();
            }
            DB::commit();


        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $envio = Envio::findOrFail($request->id);
        $envio->estado = 'Anulado';
        $envio->save();
    }
}
