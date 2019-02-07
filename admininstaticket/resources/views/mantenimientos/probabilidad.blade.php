@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="../plugins/timepicker/bootstrap-timepicker.min.css">
@endsection
@section('header')
<h1>
<i class="fa fa-calculator"></i>   PROBABILIDADES
</h1>
<ol class="breadcrumb">
<li><a href="#"><i class="fa fa-suitcase"></i> Mantenimientos</a></li>
<li class="active">Probabilidades</li>
</ol>
@endsection
@section('main-content')
<div class="col-md-12">
<div class="nav-tabs-custom">
<ul class="nav nav-tabs">
<li  class="active"><a id="enlace" href="#tab_1" data-toggle="tab">Ingreso/Actualización</a></li>
<li><a href="#tab_2" data-toggle="tab">Buscar</a></li>
</ul>
<div class="tab-content">
<div class="tab-pane active" id="tab_1">
<form class="form-horizontal" action="/guardarProbabilidad" method="post" id="formProbabilidad">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="probabilidad_id" id="probabilidad_id">
<div class="box-body">
<br/>
<div class="col-sm-6">
<div  class="form-group" id="div_probabilidad_porcentaje">
<label class="col-lg-2">Probabilidad</label>
<div class="col-lg-10">
<div class="input-group">
<input type="number" id="probabilidad_porcentaje" name="probabilidad_porcentaje" class="form-control">
<div class="input-group-addon">
<i >%</i>
</div>
</div>
<label  id="lbl_probabilidad_porcentaje" class=" control-label hide"></label>
</div>
</div>
<div class="form-group" id="div_probabilidad_hora_inicio">
<label class="col-lg-2">Hora Inicio</label>
<div class="col-lg-10">
<div class="input-group">
<input type="text" id="probabilidad_hora_inicio" name="probabilidad_hora_inicio" class="form-control timepicker">
<div class="input-group-addon">
<i class="fa fa-clock-o"></i>
</div>
</div>
<label  id="lbl_probabilidad_hora_inicio" class=" control-label hide"></label>
</div>
</div>
</div>
<div class="col-sm-6">
<div class="form-group" id="div_probabilidad_hora_fin">
<label class="col-lg-2 control-label">Hora Fin</label>
<div class="col-lg-10">
<div class="input-group">
<input type="text" id="probabilidad_hora_fin" name="probabilidad_hora_fin" class="form-control timepicker">
<div class="input-group-addon">
<i class="fa fa-clock-o"></i>
</div>
</div>
<label  id="lbl_probabilidad_hora_fin" class=" control-label hide"></label>
</div>
</div>
<div class="form-group" id="div_estado_id">
<label for="inputEmail3" class="col-lg-2 control-label">Estado</label>
<div class="col-lg-10 radio">
@if($lstEstado)
@foreach($lstEstado as $estado)
<label> <input type="radio" name="estado_id"  class="radio-division" value="{{$estado->estado_id}}" />{{$estado->estado_nombre}}</label>
@endforeach
@endif
<label id="lbl_estado_id" class="control-label hide"></label>
</div>
</div>
<div class="form-group">
<div class="col-md-12 text-right">
<button type="button" id="btn_guardar" class="btn btn-warning "><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;GUARDAR</button>
<button type="button" id="btn_actualizar" class="btn btn-warning  hide"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;ACTUALIZAR</button>
<button type="button" id="btn_cancelar" class="btn btn-primary "><i class="fa fa-refresh"></i>&nbsp;&nbsp;CANCELAR</button>
</div> 
</div> 
</div>
</div>
</form>
</div>
<div class="tab-pane" id="tab_2">
<div class="box-body">
<div class="col-sm-12 table-responsive">
<table id="tbl_UP" class="table table-bordered table-striped dataTable" cellspacing="0" >
<thead>
<tr>
<th>PROBABILIDAD</th>
<th>H. INICIO</th>
<th>H. FIN</th>
<th>ESTADO</th>
<th></th>
</tr>
</thead>
<tbody>
@if($lstProbabilidades)
@foreach($lstProbabilidades as $p)
<tr data-id="{{$p}}">
<td>{{$p->probabilidad_porcentaje}}% </td>
<td>{{$p->probabilidad_hora_inicio}} </td>
<td>{{$p->probabilidad_hora_fin}} </td>
<td>@if($p->estado!=null){{$p->estado->estado_nombre}}@else{{$p->estado}}@endif</td>
<td style="text-align: center">
<button type="button" class="btn btn-warning btn-sm btn-P"  title="Editar"><i class="fa fa-pencil"></i></button>
@if($p->logProbabilidades->count() > 0)
<button type="button" class="btn btn-default btn-sm btn-Log_P"  title="Log"><i class="fa fa-bars"></i></button>
@endif
</td>
</tr>
@endforeach
@endif
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
@endsection
@section('script')
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="../plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script>$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}});$(document).ready(function(){$('#tbl_UP').DataTable({})});$(".timepicker").timepicker({showInputs:false,showSeconds:true,minuteStep:1,secondStep:1,showMeridian:false,interval:1,defaultTime:false});$("#btn_cancelar").click(function(){location.reload()});$("#btn_guardar").click(function(){var p1=$("#probabilidad_porcentaje").val(),p2=$("#probabilidad_hora_inicio").val(),p3=$("#probabilidad_hora_fin").val(),p4=$("input:radio[name=estado_id]:checked").val();$('#mensajes').removeClass('alert alert-danger');$('#mensajes').addClass('hide');$('#textoMensaje').text("");var bValid=false;var bValid1=validarCampoLleno(p1,"Probabilidad","probabilidad_porcentaje");var bValid2=validarCampoLleno(p2,"Hora Inicio","probabilidad_hora_inicio");var bValid3=validarCampoLleno(p3,"Hora Fin","probabilidad_hora_fin");var bValid4=validarCampoLleno(p4,"Estado","estado_id");bValid=bValid1&&bValid4&&bValid2&&bValid3;if(bValid){$.ajax({url:'/validarProbabilidad',type:'post',"_token":"{{ csrf_token() }}",async:true,data:{'probabilidad_id':null,'probabilidad_porcentaje':p1,'probabilidad_hora_inicio':p2,'probabilidad_hora_fin':p3,'estado_id':p4},beforeSend:function(){$.blockUI({message:'<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'})},error:function(jqXHR,textStatus,errorThrown){var gnrMensaje=$("#gnrMensaje");toTop();if(jqXHR.status=='401'){gnrMensaje.text(textStatus+" - "+errorThrown+" ,Su sesión ha caducado, por favor de click en Salir y vuelva a Ingresar.");$('#gnrError').modal('show')}else{$('#mensajes').addClass('alert alert-danger');$('#mensajes').removeClass('hide');var textoMensaje=$("#textoMensaje");textoMensaje.text("ERROR: "+jqXHR.status+" - "+textStatus+" - "+errorThrown)}},success:function(j){if(j!==""){$("#mensajes").addClass("alert alert-danger");$("#mensajes").removeClass("hide");$("#textoMensaje").text("");for(var h in j){$("#textoMensaje").append(j[h]+"<br/>")}}else{$("#formProbabilidad").submit()}},complete:function(){setTimeout($.unblockUI,1000)}})}});$(".btn-Log_P").click(function(){var fila=$(this).parents("tr");var cadena=JSON.stringify(fila.data("id"));var obj=eval("("+cadena+")");var log=obj.log_probabilidades;var t=$('#log').DataTable();t.clear();for(var i=0;i<log.length;i++){t.row.add([log[i].registro_anterior,log[i].registro_nuevo,log[i].tipo_accion,log[i].fecha_ingresa]).draw(false)}$('#myModal').modal('show')});$(".btn-P").click(function(){var fila=$(this).parents("tr");var cadena=JSON.stringify(fila.data("id"));var obj=eval("("+cadena+")");$('#mensajes').removeClass('alert alert-danger');$('#mensajes').addClass('hide');$('#textoMensaje').text("");$.ajax({url:"/buscarProbabilidad",type:"post",_token:"{{ csrf_token() }}",async:true,data:{probabilidad_id:obj.probabilidad_id},beforeSend:function(){$.blockUI({message:'<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento porfavor </h4>'})},error:function(jqXHR,textStatus,errorThrown){var gnrMensaje=$("#gnrMensaje");toTop();if(jqXHR.status=="401"){gnrMensaje.text(textStatus+" - "+errorThrown+" ,Su sesión ha caducado, porfavor de click en Salir y vuelva a Ingresar.");$("#gnrError").modal("show")}else{$("#mensajes").addClass("alert alert-danger");$("#mensajes").removeClass("hide");var textoMensaje=$("#textoMensaje");textoMensaje.text("ERROR: "+jqXHR.status+" - "+textStatus+" - "+errorThrown)}},success:function(data){if(data!=''){validarCampoLleno(data.probabilidad_porcentaje,"Probabilidad","probabilidad_porcentaje");validarCampoLleno(data.estado_id,"Estado","estado_id");$("#probabilidad_id").val(data.probabilidad_id);$("#probabilidad_porcentaje").val(data.probabilidad_porcentaje);$("#probabilidad_hora_inicio").val(data.probabilidad_hora_inicio);$("#probabilidad_hora_fin").val(data.probabilidad_hora_fin);$('input:radio[name=estado_id][value='+data.estado_id+']').attr('checked',true);$("#btn_actualizar").removeClass("hide");$("#btn_guardar").addClass("hide");$("#enlace").click()}},complete:function(){setTimeout($.unblockUI,1000)}})});$("#btn_actualizar").click(function(){var p0=$("#probabilidad_id").val(),p1=$("#probabilidad_porcentaje").val(),p2=$("#probabilidad_hora_inicio").val(),p3=$("#probabilidad_hora_fin").val(),p4=$("input:radio[name=estado_id]:checked").val();$('#mensajes').removeClass('alert alert-danger');$('#mensajes').addClass('hide');$('#textoMensaje').text("");var bValid=false;var bValid1=validarCampoLleno(p1,"Probabilidad","probabilidad_porcentaje");var bValid2=validarCampoLleno(p2,"Hora Inicio","probabilidad_hora_inicio");var bValid3=validarCampoLleno(p3,"Hora Fin","probabilidad_hora_fin");var bValid4=validarCampoLleno(p4,"Estado","estado_id");bValid=bValid1&&bValid4;if(bValid){$.ajax({url:'/validarProbabilidad',type:'post',"_token":"{{ csrf_token() }}",async:true,data:{'probabilidad_id':p0,'probabilidad_porcentaje':p1,'probabilidad_hora_inicio':p2,'probabilidad_hora_fin':p3,'estado_id':p4},beforeSend:function(){$.blockUI({message:'<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'})},error:function(jqXHR,textStatus,errorThrown){var gnrMensaje=$("#gnrMensaje");toTop();if(jqXHR.status=='401'){gnrMensaje.text(textStatus+" - "+errorThrown+" ,Su sesión ha caducado, por favor de click en Salir y vuelva a Ingresar.");$('#gnrError').modal('show')}else{$('#mensajes').addClass('alert alert-danger');$('#mensajes').removeClass('hide');var textoMensaje=$("#textoMensaje");textoMensaje.text("ERROR: "+jqXHR.status+" - "+textStatus+" - "+errorThrown)}},success:function(j){if(j!==""){$("#mensajes").addClass("alert alert-danger");$("#mensajes").removeClass("hide");$("#textoMensaje").text("");for(var h in j){$("#textoMensaje").append(j[h]+"<br/>")}}else{$("#formProbabilidad").attr("action","/actualizarProbabilidad");$("#formProbabilidad").submit()}},complete:function(){setTimeout($.unblockUI,1000)}})}});</script>
@endsection
