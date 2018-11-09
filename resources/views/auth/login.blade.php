@extends('auth.contenido')
@section('login')
<div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card-group mb-0">
          <div class="card p-4">
          <form class="form-horizontal was-validated" method="POST" action="{{ route('login')}}">
          {{ csrf_field() }}
              <div class="card-body">
              <h1>Acceder</h1> 
              <p class="text-muted">Control de acceso al sistema</p>

              <div class="form-group mb-3{{$errors->has('usuario' ? 'is-invalid' : 'hila')}}">
                <span class="input-group-addon"><i class="icon-user"></i> &nbsp; Carnet de Afiliado </span>

                <div class="input-group">
                    <select class="form-control" name="us" id="us">
                      <option value="LP">LP</option>
                      <option value="OR">OR</option>
                      <option value="PT">PT</option>
                      <option value="CB">CB</option>
                      <option value="SC">SC</option>
                      <option value="BN">BN</option>
                      <option value="PA">PA</option>
                      <option value="TJ">TJ</option>
                      <option value="CH">CH</option>
                    </select>
                    <input type="text" name="usuario" id="usuario" class="form-control" placeholder="0000">
                </div>
              </div>
              <div class="form-group mb-4{{$errors->has('password' ? 'is-invalid' : '')}}">
                <span class="input-group-addon"><i class="icon-lock"></i> &nbsp; Cedula de Identidad </span>
                <input type="text" name="password" id="password" class="form-control" >
                <small></small>

                {!!$errors->first('usuario','<small style="color:red;" >:message</small>')!!}

                {!!$errors->first('password','<span class="invalid-feedback">:message</span>')!!}

              </div>
              <div class="row">
                <div  class="col-12 text-center">
                  <button type="submit" class="btn btn-primary">Acceder</button>
                </div>
              </div>
            </div>
          </form>
          </div>
          <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
            <div class="card-body text-center">
              <div>
                <h2>Sistema de Control de Aportes</h2>
                <img src="{{asset('img/logo_ps.png')}}" >
                 <h5>Colegio Departamental de Bioquimica y Farmacia</h5>
                 <h5>La Paz</h5>
                <div class="btn btn-primary">Bienvenido!</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection
