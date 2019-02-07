@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
@endsection
@section('header')
<h1>
<i class="fa fa-history"></i>   CICLOS
</h1>
<ol class="breadcrumb">
<li><a href="#"><i class="fa fa-suitcase"></i> Mantenimientos</a></li>
<li class="active">Ciclos</li>
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
<form class="form-horizontal" action="/guardarCiclo" method="post" id="formCiclo">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="ciclo_id" id="ciclo_id">
<input type="hidden" name="ciclo_fecha_inicio" id="ciclo_fecha_inicio">
<input type="hidden" name="ciclo_fecha_fin" id="ciclo_fecha_fin">
<div class="box-body">
<br/>
<div class="col-sm-6">
<div class="form-group" id="div_ciclo_nombre">
<label class="col-lg-2 control-label">Nombre</label>
<div class="col-lg-10">
<input type="text" id="ciclo_nombre" name="ciclo_nombre" minlength="3" maxlength="45" class="form-control">
<label  id="lbl_ciclo_nombre" class="control-label hide"></label>
</div>
</div>
<div class="form-group" id="div_duracion">
<label class="col-lg-2 control-label">Duración</label>
<div class="col-lg-10">
<div class="input-group">
<input type="text" id="duracion" name="duracion" class="form-control timepicker">
<div class="input-group-addon">
<i class="fa fa-calendar"></i>
</div>
</div>
<label  id="lbl_duracion" class=" control-label hide"></label>
</div>
</div>
</div>
<div class="col-sm-6">
<div class="form-group" id="div_ciclo_descripcion">
<label class="col-lg-2 control-label ">Descripción</label>
<div class="col-lg-10">
<textarea style="resize:none;"  min="1" rows="4" id="ciclo_descripcion" name="ciclo_descripcion"  minlength="1"  maxlength="250"  class="form-control"></textarea>
<label  id="lbl_ciclo_descripcion" class=" control-label hide"></label>
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
<table id="tbl_C" class="table table-bordered table-striped dataTable" cellspacing="0" >
<thead>
<tr>
<th>NOMBRE</th>
<th>DESCRIPCIÓN</th>
<th>F. INICIO</th>
<th>F. FIN</th>
<th></th>
</tr>
</thead>
<tbody>
@if($lstCiclo)
@foreach($lstCiclo as $c)
<tr data-id="{{$c}}">
<td>{{$c->ciclo_nombre}} </td>
<td>{{$c->ciclo_descripcion}} </td>
<td>{{$c->ciclo_fecha_inicio}} </td>
<td>{{$c->ciclo_fecha_fin}} </td>
<td style="text-align: center">
<button type="button" class="btn btn-warning btn-sm btn-C"  title="Editar"><i class="fa fa-pencil"></i></button>
@if($c->logCiclo->count() > 0)
<button type="button" class="btn btn-default btn-sm btn-Log_C"  title="Log"><i class="fa fa-bars"></i></button>
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
<script src="../plugins/daterangepicker/moment.min.js"></script>
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<script>$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}});$(document).ready(function(){$('#tbl_C').DataTable({})});$("#btn_cancelar").click(function(){location.reload()});$('#duracion').daterangepicker({"locale":{"format":"YYYY-MM-DD","separator":" - ","applyLabel":"Aceptar","cancelLabel":"Cancelar","fromLabel":"Desde","toLabel":"Hasta","customRangeLabel":"","daysOfWeek":["Do","Lu","Ma","Mi","Ju","Vi","Sa"],"monthNames":["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],"firstDay":1}});$("#btn_guardar").click(function(){var p1=$("#ciclo_nombre").val(),p2=$("#ciclo_descripcion").val(),p3=$("#duracion").val();$('#mensajes').removeClass('alert alert-danger');$('#mensajes').addClass('hide');$('#textoMensaje').text("");var bValid=false;var bValid1=validarCampoLleno(p1,"Nombre","ciclo_nombre");var bValid2=validarCampoLleno(p2,"Descripción","ciclo_descripcion");var bValid3=validarCampoLleno(p3,"Duración","duracion");bValid=bValid1&&bValid2&&bValid3;if(bValid){var res=p3.split(" - ");$.ajax({url:'/validarCiclo',type:'post',"_token":"{{ csrf_token() }}",async:true,data:{'ciclo_id':null,'ciclo_nombre':p1,'ciclo_descripcion':p2,'ciclo_fecha_inicio':res[0],'ciclo_fecha_fin':res[1]},beforeSend:function(){$.blockUI({message:'<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'})},error:function(jqXHR,textStatus,errorThrown){var gnrMensaje=$("#gnrMensaje");toTop();if(jqXHR.status=='401'){gnrMensaje.text(textStatus+" - "+errorThrown+" ,Su sesión ha caducado, por favor de click en Salir y vuelva a Ingresar.");$('#gnrError').modal('show')}else{$('#mensajes').addClass('alert alert-danger');$('#mensajes').removeClass('hide');var textoMensaje=$("#textoMensaje");textoMensaje.text("ERROR: "+jqXHR.status+" - "+textStatus+" - "+errorThrown)}},success:function(j){if(j!==""){$("#mensajes").addClass("alert alert-danger");$("#mensajes").removeClass("hide");$("#textoMensaje").text("");for(var h in j){$("#textoMensaje").append(j[h]+"<br/>")}}else{$("#ciclo_fecha_inicio").val(res[0]);$("#ciclo_fecha_fin").val(res[1]);$("#formCiclo").submit()}},complete:function(){setTimeout($.unblockUI,1000)}})}});$(".btn-Log_C").click(function(){var fila=$(this).parents("tr");var cadena=JSON.stringify(fila.data("id"));var obj=eval("("+cadena+")");var log=obj.log_ciclo;var t=$('#log').DataTable();t.clear();for(var i=0;i<log.length;i++){t.row.add([log[i].registro_anterior,log[i].registro_nuevo,log[i].tipo_accion,log[i].fecha_ingresa]).draw(false)}$('#myModal').modal('show')});$(".btn-C").click(function(){var fila=$(this).parents("tr");var cadena=JSON.stringify(fila.data("id"));var obj=eval("("+cadena+")");$('#mensajes').removeClass('alert alert-danger');$('#mensajes').addClass('hide');$('#textoMensaje').text("");$.ajax({url:"/buscarCiclo",type:"post",_token:"{{ csrf_token() }}",async:true,data:{ciclo_id:obj.ciclo_id},beforeSend:function(){$.blockUI({message:'<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'})},error:function(jqXHR,textStatus,errorThrown){var gnrMensaje=$("#gnrMensaje");toTop();if(jqXHR.status=="401"){gnrMensaje.text(textStatus+" - "+errorThrown+" ,Su sesión ha caducado, por favor de click en Salir y vuelva a Ingresar.");$("#gnrError").modal("show")}else{$("#mensajes").addClass("alert alert-danger");$("#mensajes").removeClass("hide");var textoMensaje=$("#textoMensaje");textoMensaje.text("ERROR: "+jqXHR.status+" - "+textStatus+" - "+errorThrown)}},success:function(data){if(data!=''){validarCampoLleno(data.ciclo_nombre,"Nombre","ciclo_nombre");validarCampoLleno(data.ciclo_descripcion,"Descripción","ciclo_descripcion");validarCampoLleno(data.ciclo_fecha_inicio+" "+data.ciclo_fecha_fin,"Duración","duracion");$("#ciclo_id").val(data.ciclo_id);$("#ciclo_nombre").val(data.ciclo_nombre);$("#ciclo_descripcion").val(data.ciclo_descripcion);$('#duracion').daterangepicker({"locale":{"format":"YYYY-MM-DD","separator":" - ","applyLabel":"Aceptar","cancelLabel":"Cancelar","fromLabel":"Desde","toLabel":"Hasta","customRangeLabel":"","daysOfWeek":["Do","Lu","Ma","Mi","Ju","Vi","Sa"],"monthNames":["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],"firstDay":1},startDate:data.ciclo_fecha_inicio,endDate:data.ciclo_fecha_fin});$("#btn_actualizar").removeClass("hide");$("#btn_guardar").addClass("hide");$("#enlace").click()}},complete:function(){setTimeout($.unblockUI,1000)}})});$("#btn_actualizar").click(function(){var p0=$("#ciclo_id").val(),p1=$("#ciclo_nombre").val(),p2=$("#ciclo_descripcion").val(),p3=$("#duracion").val();$('#mensajes').removeClass('alert alert-danger');$('#mensajes').addClass('hide');$('#textoMensaje').text("");var bValid=false;var bValid1=validarCampoLleno(p1,"Nombre","ciclo_nombre");var bValid2=validarCampoLleno(p2,"Descripción","ciclo_descripcion");var bValid3=validarCampoLleno(p3,"Duración","duracion");bValid=bValid1&&bValid2&&bValid3;if(bValid){var res=p3.split(" - ");$.ajax({url:'/validarCiclo',type:'post',"_token":"{{ csrf_token() }}",async:true,data:{'ciclo_id':p0,'ciclo_nombre':p1,'ciclo_descripcion':p2,'ciclo_fecha_inicio':res[0],'ciclo_fecha_fin':res[1]},beforeSend:function(){$.blockUI({message:'<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'})},error:function(jqXHR,textStatus,errorThrown){var gnrMensaje=$("#gnrMensaje");toTop();if(jqXHR.status=='401'){gnrMensaje.text(textStatus+" - "+errorThrown+" ,Su sesión ha caducado, por favor de click en Salir y vuelva a Ingresar.");$('#gnrError').modal('show')}else{$('#mensajes').addClass('alert alert-danger');$('#mensajes').removeClass('hide');var textoMensaje=$("#textoMensaje");textoMensaje.text("ERROR: "+jqXHR.status+" - "+textStatus+" - "+errorThrown)}},success:function(j){if(j!==""){$("#mensajes").addClass("alert alert-danger");$("#mensajes").removeClass("hide");$("#textoMensaje").text("");for(var h in j){$("#textoMensaje").append(j[h]+"<br/>")}}else{$("#ciclo_fecha_inicio").val(res[0]);$("#ciclo_fecha_fin").val(res[1]);$("#formCiclo").attr("action","/actualizarCiclo");$("#formCiclo").submit()}},complete:function(){setTimeout($.unblockUI,1000)}})}});</script>
@endsection
