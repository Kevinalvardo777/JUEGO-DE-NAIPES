@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="../plugins/select2/select2.min.css">
@endsection
@section('htmlheader_title')
Ciclo Evento
@endsection
@section('header')
<h1>
    <i class="fa fa-history"></i>   <i class="fa fa-picture-o"></i>   PARAMETROS GENERALES
</h1>
<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Configuración</a></li>
    <li class="active">Parametros Generales</li>
</ol>
@endsection
@section('main-content')
<div class="col-md-12">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li  class="active"><a id="enlace" href="#tab_1" data-toggle="tab">Actualización</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <form class="form-horizontal" action="/actualizarParametrosGenerales" method="post" id="formCicloEvento">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="ciclo_evento_id" id="ciclo_evento_id">
                    <div class="box-body">
                        <br/>
                        <div class="col-sm-6">
                            <div class="form-group" id="div_ciclo_id">
                                <label  class="col-lg-2">Ruta recursos</label>
                                <div class="col-lg-10">
                                    <input type="text" id="ruta_recursos" name="ruta_recursos" minlength="1" class="form-control" value="{{$recursos}}" required>
                                    <label  id="ruta_recursos" class="control-label hide"></label>
                                </div>
                                <label  id="lbl_ciclo_id" class=" control-label hide"></label>
                            </div>
                            <div class="form-group" id="div_total_premios">
                                <label class="col-lg-2 control-label">Url ruta</label>
                                <div class="col-lg-10">
                                    <input type="text" id="url_recursos" name="url_recursos" minlength="1" class="form-control" value="{{$url}}" required>
                                    <label  id="url_recursos" class="control-label hide"></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group" id="div_evento_id">
                                <label  class="col-lg-2">Evento</label>
                                <div class="col-lg-10">
                                    <select id="evento_ciclo_id" name="evento_ciclo_id" class="form-control select2" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                        <option value="-1"> Seleccione evento</option>
                                        @if($lstCiclosEventos)

                                        @foreach($lstCiclosEventos as $c)
                                        <option value="{{$c->ciclo_evento_id}}" {{$evento==$c->ciclo_evento_id? 'selected':''}}> {{$c->evento->evento_nombre}} - ID:{{$c->evento_id}} </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <label  id="lbl_v" class=" control-label hide"></label>
                            </div>
                            <div class="form-group" id="div_estado_id">
                                <label for="inputEmail3" class="col-lg-2 control-label">Estado</label>
                                <div class="col-lg-10">
                                    <select id="impresora" name="impresora" class="form-control select2" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                        <option value="SI" {{$impresora=='SI'? 'selected':''}}> SI</option>
                                        <option value="NO" {{$impresora=='NO'? 'selected':''}}> NO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 text-right">
                                    <button type="submit" id="btn_actualizar" class="btn btn-warning"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;ACTUALIZAR</button>
                                </div> 
                            </div> 
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="../plugins/select2/select2.full.min.js"></script>
<script>
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    }
});
$(document).ready(function () {

});
$('.select2').select2({
//placeholder: 'Seleccione una opción'
});
</script>
@endsection
