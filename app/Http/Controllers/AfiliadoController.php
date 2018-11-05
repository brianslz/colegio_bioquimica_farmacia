<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Afiliado;
use App\Pago;
use App\User;
use App\SeguimientoEstado;

class AfiliadoController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if ($buscar==''){
            $afiliados = Afiliado::orderBy('id', 'desc')->paginate(10);
        }
        else{
            $afiliados = Afiliado::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(10);
        }
        return [
            'pagination' => [
                'total'        => $afiliados->total(),
                'current_page' => $afiliados->currentPage(),
                'per_page'     => $afiliados->perPage(),
                'last_page'    => $afiliados->lastPage(),
                'from'         => $afiliados->firstItem(),
                'to'           => $afiliados->lastItem(),
            ],
            'afiliados' => $afiliados
        ];
    }


    public function store(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        //try{
        //    DB::beginTransaction();
            
            $afiliado = new Afiliado();
            $afiliado->apellido_paterno=$request->apellido_paterno;
            $afiliado->apellido_materno=$request->apellido_materno;
            $afiliado->nombres=$request->nombres;
            $afiliado->fecha_nac=$request->fecha_nac;
            $afiliado->lugar_nac=$request->lugar_nac;
            $afiliado->ci=$request->ci;
            $afiliado->departamento=$request->departamento;
            $afiliado->nacionalidad=$request->nacionalidad;
            $afiliado->estado_civil=$request->estado_civil;
            $afiliado->telefono=$request->telefono;
            $afiliado->celular=$request->celular;
            $afiliado->email=$request->email;

            $afiliado->fecha_min_salud=$request->fecha_min_salud;
            $afiliado->matricula=$request->matricula;
            $afiliado->lugar_trabajo=$request->lugar_trabajo;
            $afiliado->direccion_trabajo=$request->direccion_trabajo;

            $afiliado->modalidad=$request->modalidad;
            $afiliado->fecha_modalidad=$request->fecha_modalidad;
            $afiliado->created_at=$request->created_at;
            $afiliado->observaciones=$request->observaciones;
            $afiliado->codigounico=$request->codigounico;
            $afiliado->condicion=1; 
            $afiliado->estado="Activo";
            $afiliado->save();
            //guardamos datos del Usuario
            $user = new User();
            $user->usuario = $request->usuario;
            $user->password = bcrypt( $request->password);
            $user->condicion = '1';
            $user->idrol = $request->idrol;          
            $user->id = $afiliado->id;
            $user->save();

            //guardamos datos del seguimiento de estado 
            //siempre se registra como activo la primera vez

            DB::table('seguimiento_estado')->insert(
                [

                    'id_afiliado' => $afiliado->id,
                    'tipo_estado' => 'ACTIVO',
                    'fecha_inicio' => $afiliado->created_at
                    
                ]
            );


            
        //    DB::commit();
//
        //} catch (Exception $e){
        //    DB::rollBack();
        //}
    }


    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

            //Buscar primero el proveedor a modificar
            $afiliado = Afiliado::findOrFail($request->id);
            //datos personales
            $afiliado->apellido_paterno=$request->apellido_paterno;
            $afiliado->apellido_materno=$request->apellido_materno;
            $afiliado->nombres=$request->nombres;
            $afiliado->fecha_nac=$request->fecha_nac;
            $afiliado->lugar_nac=$request->lugar_nac;
            $afiliado->ci=$request->ci;
            $afiliado->departamento=$request->departamento;
            $afiliado->nacionalidad=$request->nacionalidad;
            $afiliado->estado_civil=$request->estado_civil;
            $afiliado->telefono=$request->telefono;
            $afiliado->celular=$request->celular;
            $afiliado->email=$request->email;

            $afiliado->fecha_min_salud=$request->fecha_min_salud;
            $afiliado->matricula=$request->matricula;
            $afiliado->lugar_trabajo=$request->lugar_trabajo;
            $afiliado->direccion_trabajo=$request->direccion_trabajo;

            $afiliado->modalidad=$request->modalidad;
            $afiliado->fecha_modalidad=$request->fecha_modalidad;
            $afiliado->created_at=$request->created_at;
            $afiliado->observaciones=$request->observaciones;
            $afiliado->codigounico=$request->codigounico;
            $afiliado->condicion=1; 
            $afiliado->estado="Activo";
            $afiliado->save();

            $user = User::findOrFail($request->id);
            $user->usuario = $request->codigounico;
            $user->password = bcrypt( $request->ci);
            $user->condicion = '1';
            $user->idrol = $request->idrol;
            //return $user;
            $user->save();
    }

    public function deshabilitar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $ultimoSeguimiento = new SeguimientoEstado();
        $ultimoSeguimiento = SeguimientoEstado::where('id_afiliado', $request->id)->latest('id_seg')->first();
        $ultimoSeguimiento->fecha_fin = $request->fecha_motivo;
        $ultimoSeguimiento->save();

        $afiliado = Afiliado::findOrFail($request->id);
        $afiliado->estado = $request->motivo;
        $afiliado->fecha_modalidad = $request->fecha_motivo;
        $afiliado->condicion = '0';
        $afiliado->save();

        $seguimiento = new SeguimientoEstado();
        $seguimiento->id_afiliado = $request->id;
        $seguimiento->tipo_estado = $request->motivo;
        $seguimiento->fecha_inicio = $request->fecha_motivo;
        $seguimiento->save();

    }

    public function habilitar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');


        $ultimoSeguimiento = new SeguimientoEstado();
        $ultimoSeguimiento = SeguimientoEstado::where('id_afiliado', $request->id)->latest('id_seg')->first();
        $ultimoSeguimiento->fecha_fin = $request->fecha_motivo;
        $ultimoSeguimiento->save();
        
        $afiliado = Afiliado::findOrFail($request->id);
        $afiliado->estado = 'Activo';
        $afiliado->modalidad = $request->motivo;
        $afiliado->fecha_modalidad = $request->fecha_motivo;
        $afiliado->condicion = '1';
        $afiliado->save();

        $seguimiento = new SeguimientoEstado();
        $seguimiento->id_afiliado = $request->id;
        $seguimiento->tipo_estado = $request->motivo;
        $seguimiento->fecha_inicio = $request->fecha_motivo;
        $seguimiento->save();

    }

    public function afiliadoAporte(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;        
        if ($buscar==''){
            $afiliados =  Afiliado::select('id','apellido_paterno','apellido_materno','nombres','ci','codigounico','modalidad','fecha_modalidad','condicion')
            ->orderBy('id', 'desc')
            ->paginate(10);
        }
        else{
            $afiliados =  Afiliado::where($criterio, 'like', '%'. $buscar . '%')
            ->select('id','apellido_paterno','apellido_materno','nombres','ci','codigounico','modalidad','fecha_modalidad','condicion')
            ->orderBy('id', 'desc')
            ->paginate(10);
        }
        return [
            'pagination' => [
                'total'        => $afiliados->total(),
                'current_page' => $afiliados->currentPage(),
                'per_page'     => $afiliados->perPage(),
                'last_page'    => $afiliados->lastPage(),
                'from'         => $afiliados->firstItem(),
                'to'           => $afiliados->lastItem(),
            ],
            'afiliados' => $afiliados
        ];
    }

    public function ultimoPago(Request $request){

        if (!$request->ajax()) return redirect('/');

        $pago = Pago::where('idafiliado','=', $request->id)
        ->select('idafiliado','fecha_vencimiento')->orderBy('fecha_vencimiento', 'desc')->first();
        return $pago;
    }
    
    public function verificarCi(Request $request){
        if (!$request->ajax()) return redirect('/');
        $afiliado = Afiliado::where('ci','=', $request->ci)
        ->select('id','nombres')->get();
        return ['afiliado' => $afiliado];
    }
    
    
    public function perfil(Request $request){
        if (!$request->ajax()) return redirect('/');
        $titulos = DB::table('titulos as t')
        ->where('t.idafiliado', '=', $request->id)
        ->get();

        $afiliado = DB::table('afiliados as a')
        ->where('a.id','=', $request->id)
        ->get();

        $seguimientoEstado = DB::table('seguimiento_estado as s')
        ->where('s.id_afiliado','=', $request->id)
        ->get();

        return [
            'afiliado' => $afiliado,
            'titulos'=>$titulos,
            'seguimiento'=>$seguimientoEstado
        ];

    }
    
    public function perfilPdf(Request $request){
        //if (!$request->ajax()) return redirect('/');
        $titulos = DB::table('titulos as t')
        ->where('t.idafiliado', '=', $request->id)
        ->get();
        
        $afiliado = Afiliado::find($request->id);

        $pdf = \PDF::loadView('pdf.perfil',['afiliado'=>$afiliado,'titulos'=>$titulos]);
        return $pdf->download('perfil.pdf');
    }

    public function getRol(Request $request){
        $rol = User::find($request->id);
        return $rol->idrol;
    }

    public function reporteAfiliadosGestion(Request $request){

        $afiliados = Afiliado::whereYear('fecha_modalidad', $request->gestion)
        ->where('condicion','=',1)
        ->get();

        $cantNuevos = Afiliado::whereYear('fecha_modalidad', $request->gestion)
        ->where('condicion','=',1)
        ->where('Modalidad','like','NUEVO')
        ->count();

        $cantTraspaso = Afiliado::whereYear('fecha_modalidad', $request->gestion)
        ->where('condicion','=',1)
        ->where('Modalidad','like','TRASPASO')
        ->count();
       
        $pdf = \PDF::loadView('pdf.gestion',['gestion'=>$request->gestion,'afiliados'=>$afiliados,'cantNuevos'=>$cantNuevos,'cantTraspaso'=>$cantTraspaso]);
        return $pdf->download('Afiliados_Gestion_'.$request->gestion.'.pdf');
    }

    public function deudores(Request $request){
        
        $ultimoPago = DB::table('pagos')
                   ->select('idafiliado', DB::raw('MAX(fecha_vencimiento) as fv'))
                   ->groupBy('idafiliado');

        $afiliados = DB::table('afiliados')
                ->joinSub($ultimoPago, 'ultimo_pago', function($join) {
                    $join->on('afiliados.id', '=', 'ultimo_pago.idafiliado');
                })->whereDate('ultimo_pago.fv','<' , $request->fecha )
                ->get();

        $cant = DB::table('afiliados')
            ->joinSub($ultimoPago, 'ultimo_pago', function($join) {
                $join->on('afiliados.id', '=', 'ultimo_pago.idafiliado');
            })->whereDate('ultimo_pago.fv','<' , $request->fecha )
            ->count();


        $pdf = \PDF::loadView('pdf.deudores',['afiliados'=>$afiliados,'fecha'=>$request->fecha,'cant'=>$cant ]);
        return $pdf->download('Deudores.pdf');
    }

    public function nodeudores(Request $request){        
        $ultimoPago = DB::table('pagos')
                   ->select('idafiliado', DB::raw('MAX(fecha_vencimiento) as fv'))
                   ->groupBy('idafiliado');

        $afiliados = DB::table('afiliados')
                ->joinSub($ultimoPago, 'ultimo_pago', function($join) {
                    $join->on('afiliados.id', '=', 'ultimo_pago.idafiliado');
                })->whereDate('ultimo_pago.fv','>=' ,'2018-07-24')
                ->get();

        $cant = DB::table('afiliados')
                ->joinSub($ultimoPago, 'ultimo_pago', function($join) {
                    $join->on('afiliados.id', '=', 'ultimo_pago.idafiliado');
                })->whereDate('ultimo_pago.fv','>=' ,'2018-07-24')
                ->count();

        $pdf = \PDF::loadView('pdf.nodeudores',['afiliados'=>$afiliados,'fecha'=>$request->fecha,'cant'=>$cant ]);
        return $pdf->download('Afiliados_no_deuda.pdf');
    }

    public function antiguedad25(Request $request){

        $gestion = $request->gestion ;
        $afiliados = Afiliado::whereYear('fecha_modalidad', 2015)
        //->where('condicion','=',1)
        ->get();

        dd($afiliados,$gestion);

        $cantNuevos = Afiliado::whereYear('fecha_modalidad', $gestion)
        ->where('condicion','=',1)
        ->where('Modalidad','like','NUEVO')
        ->count();

        $cantTraspaso = Afiliado::whereYear('fecha_modalidad', $gestion)
        ->where('condicion','=',1)
        ->where('Modalidad','like','TRASPASO')
        ->count();
       
        $pdf = \PDF::loadView('pdf.gestion',['gestion'=>$gestion,'afiliados'=>$afiliados,'cantNuevos'=>$cantNuevos,'cantTraspaso'=>$cantTraspaso]);
        return $pdf->download('Afiliados_Gestion.pdf');
    }

    public function antiguedad50(Request $request){
        $gestion = $request->gestion - 50 ;
        $afiliados = Afiliado::whereYear('fecha_modalidad', $gestion)
        ->where('condicion','=',1)
        ->get();

        $cantNuevos = Afiliado::whereYear('fecha_modalidad', $gestion)
        ->where('condicion','=',1)
        ->where('Modalidad','like','NUEVO')
        ->count();

        $cantTraspaso = Afiliado::whereYear('fecha_modalidad', $gestion)
        ->where('condicion','=',1)
        ->where('Modalidad','like','TRASPASO')
        ->count();
       
        $pdf = \PDF::loadView('pdf.gestion',['gestion'=>$gestion,'afiliados'=>$afiliados,'cantNuevos'=>$cantNuevos,'cantTraspaso'=>$cantTraspaso]);
        return $pdf->download('Afiliados_Gestion.pdf');
    }
 
}
