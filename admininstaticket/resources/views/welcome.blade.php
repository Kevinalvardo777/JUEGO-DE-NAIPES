@extends('layouts.app')
@section('htmlheader_title')
Bienvenidos
@endsection
@section('main-content')
@if( Auth::user()->tipo_usuario_id == $objUsuarioAdministrador->parametro_general_valor )
<div style="text-align: center;width: 100%;margin-left: auto;margin-right: auto;">
<img src="../img/logopeque.png" class="resize responsive" width="50%"></img>
<img src="../img/puertas.png" class="resize responsive" width="90%"></img>
<h3 > BIENVENIDO!!!</h3>
<h3 > USTED ESTÁ EN EL SISTEMA</h3>
<div class="textowelcome">
<h4>
<i><img src="../img/icousuairo.png" class="resize responsive" ></img></i>&nbsp;&nbsp;{{Auth::user()->usuario_username}}
</h4>
</div>
</div>
@else
<div style="text-align: center;width: 100%;margin-left: auto;margin-right: auto">
<h3> {{$objEvento->evento_nombre}}</h3>
<img  class="responsive resize"  src="{{$objUrlRecursos->parametro_general_valor}}{{$objEvento->evento_url_imagen}}"/>
<h3>BIENVENIDO!!!</h3>
<h3>USTED ESTÁ EN EL SISTEMA</h3>
<div class="textowelcome">
<h4>
<i><img src="../img/icousuairo.png" class="resize responsive" ></img></i>&nbsp;&nbsp;{{Auth::user()->usuario_username}}
</h4>
</div>
</div>
@endif           
@endsection
