@extends('layouts.auth')
@section('htmlheader_title')
Log in
@endsection
@section('content')
<div>
<div>
<img src="../img/logopeque.png" class="responsive resize" ></img>
<h4 class="login-box-msg"> INGRESE CREDENCIALES </h4>
@if (count($errors) > 0)
<div class="alert alert-danger">
<ul>
@foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif
<form action="{{ url('/login') }}" method="post">
{{csrf_field()}}
<div class="form-group has-feedback alinearcentro">
<input type="text" class="form-control" placeholder="USUARIO_" name="usuario_username" style="height: 50px;"/>
</div>
<div class="form-group has-feedback alinearcentro" >
<input type="password" class="form-control" placeholder="CONTRASEÑA_" name="usuario_password" style="height: 50px;"/>
</div>
<div class="row">
<div class="col-xs-8 hide">
<div class="checkbox icheck">
<label>
<input type="checkbox" name="remember" > {{ trans('adminlte_lang::message.remember') }}
</label>
</div>s
</div><!-- /.col -->
<div class="alinearcentroboton">
<button type="submit" class="btn btn-warning btn-flat col-xs-12 " style="height: 50px;">INGRESAR</button>
</div><!-- /.col -->
</div>
</form>
</div><!-- /.login-box-body -->
</div><!-- /.login-box -->
@endsection
