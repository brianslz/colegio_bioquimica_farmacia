<?php

namespace App\Http\Controllers;
use App\User;
use App\Afiliado;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;


class UserController extends Controller
{
    public function index(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $afiliados = User::join('afiliados','users.id','=','afiliados.id')
            ->join('roles','users.idrol','=','roles.id')
            ->select('afiliados.id','afiliados.apellido_paterno','afiliados.apellido_materno',
            'afiliados.nombres','afiliados.ci',
            'afiliados.email','users.usuario','users.password',
            'users.condicion','users.idrol','roles.nombre as rol')
            ->orderBy('afiliados.id', 'desc')->paginate(3);
        }
        else{
            $afiliados = User::join('afiliados','users.id','=','afiliados.id')
            ->join('roles','users.idrol','=','roles.id')
            ->select('afiliados.id','afiliados.apellido_paterno','afiliados.apellido_materno',
            'afiliados.nombres','afiliados.ci',
            'afiliados.email','users.usuario','users.password',
            'users.condicion','users.idrol','roles.nombre as rol')            
            ->where('afiliados.'.$criterio, 'like', '%'. $buscar . '%' )
            ->orderBy('afiliados.id', 'desc')->paginate(3);
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

    public function indexAdmin(Request $request)
    {
        //$users = User::where('idrol','=',1)
        //->orderBy('id', 'desc')->get();

        $users = User::join('afiliados','users.id','=','afiliados.id')
        ->where('users.idrol','=',1)
        ->select('users.*','afiliados.apellido_paterno','afiliados.apellido_materno','afiliados.nombres')
        ->get();

        return ['users' => $users];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        
        try{
            DB::beginTransaction();
            $afiliado = new Afiliado();
            $afiliado->nombre = $request->nombre;
            $afiliado->tipo_documento = $request->tipo_documento;
            $afiliado->num_documento = $request->num_documento;
            $afiliado->direccion = $request->direccion;
            $afiliado->telefono = $request->telefono;
            $afiliado->email = $request->email;
            $afiliado->save();

            $user = new User();
            $user->usuario = $request->usuario;
            $user->password = bcrypt( $request->password);
            $user->condicion = '1';
            $user->idrol = $request->idrol;          
            $user->id = $afiliado->id;
            $user->save();
            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function storeAdmin(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        
        try{
            DB::beginTransaction();
            $user = new User();
            $user->usuario = $request->usuario;
            $user->password = bcrypt( $request->password);
            $user->condicion = '1';
            $user->idrol = '1';          
            $user->id = $afiliado->id;
            $user->save();
            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        
        try{
            DB::beginTransaction();

            //Buscar primero el proveedor a modificar
            $user = User::findOrFail($request->id);

            $afiliado = Afiliado::findOrFail($user->id);

            $afiliado->nombre = $request->nombre;
            $afiliado->tipo_documento = $request->tipo_documento;
            $afiliado->num_documento = $request->num_documento;
            $afiliado->direccion = $request->direccion;
            $afiliado->telefono = $request->telefono;
            $afiliado->email = $request->email;
            $afiliado->save();

            
            $user->usuario = $request->usuario;
            $user->password = bcrypt( $request->password);
            $user->condicion = '1';
            $user->idrol = $request->idrol;
            $user->save();


            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }

    }

    //updateAdmin cambia la contraseña de administradores
    public function updateAdmin(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

            //Buscar primero el proveedor a modificar
            $user = User::findOrFail($request->id);
            $user->password = bcrypt($request->contrasena);
            $user->save();

    }


    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condicion = '0';
        $user->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condicion = '1';
        $user->save();
    }
}
