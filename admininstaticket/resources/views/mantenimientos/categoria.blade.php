@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="../plugins/select2/select2.min.css">
@endsection
@section('htmlheader_title')
Categorías
@endsection
@section('header')
<h1>
<i class="fa fa-sort-alpha-asc"></i>   CATEGORÍAS
</h1>
<ol class="breadcrumb">
<li><a href="#"><i class="fa fa-suitcase"></i> Mantenimientos</a></li>
<li class="active">Categorías</li>
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
<form class="form-horizontal" action="/guardarCategoria" method="post" id="formCategoria">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="categoria_id" id="categoria_id">
<div class="box-body">
<br/>
<div class="col-sm-6">
<div class="form-group" id="div_categoria_nombre">
<label class="col-lg-2 control-label">Nombre</label>
<div class="col-lg-10">
<input type="text" id="categoria_nombre" name="categoria_nombre" minlength="1" maxlength="45" class="form-control">
<label  id="lbl_categoria_nombre" class="control-label hide"></label>
</div>
</div>
<div class="form-group" id="div_categoria_rango_minimo">
<label class="col-lg-2 control-label">Mínimo</label>
<div class="col-lg-10">
<input type="number" id="categoria_rango_minimo" name="categoria_rango_minimo" minlength="1" class="form-control">
<label  id="lbl_categoria_rango_minimo" class="control-label hide"></label>
</div>
</div>
<div class="form-group" id="div_categoria_descripcion">
<label class="col-lg-2  control-label">Descripción</label>
<div class="col-lg-10">
<textarea style="resize:none;"  min="1" rows="2" id="categoria_descripcion" name="categoria_descripcion"  minlength="1" maxlength="250" class="form-control"></textarea>
<label  id="lbl_categoria_descripcion" class=" control-label hide"></label>
</div>
</div>
</div>
<div class="col-sm-6">
<div  class="form-group" id="div_categoria_porcentaje_probabilidad">
<label class="col-lg-2">Probabilidad</label>
<div class="col-lg-10">
<div class="input-group">
<input type="text" id="categoria_porcentaje_probabilidad" name="categoria_porcentaje_probabilidad" class="form-control timepicker">
<div class="input-group-addon">
<i >%</i>
</div>
</div>
<label  id="lbl_categoria_porcentaje_probabilidad" class=" control-label hide"></label>
</div>
</div>
<div class="form-group" id="div_categoria_rango_maximo">
<label class="col-lg-2 control-label">Máximo</label>
<div class="col-lg-10">
<input type="number" id="categoria_rango_maximo" name="categoria_rango_maximo" minlength="1" class="form-control">
<label  id="lbl_categoria_rango_maximo" class="control-label hide"></label>
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
<button type="button" id="btn_actualizar" class="btn btn-warning hide"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;ACTUALIZAR</button>
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
<th>% PROBABILIDAD</th>
<th>RANGO MINIMO</th>
<th>RANGO MAXIMO</th>
<th>ESTADO</th>
<th></th>
</tr>
</thead>
<tbody>
@if($lstCategorias)
@foreach($lstCategorias as $c)
<tr data-id="{{$c}}">
<td>{{$c->categoria_nombre}} </td>
<td>{{$c->categoria_descripcion}} </td>
<td>{{$c->categoria_porcentaje_probabilidad}}%</td>
<td>{{$c->categoria_rango_minimo}}</td>
<td>{{$c->categoria_rango_maximo}}</td>
<td>@if($c->estado!=null){{$c->estado->estado_nombre}}@else{{$c->estado}}@endif</td>
<td style="text-align: center">
<button type="button" class="btn btn-warning btn-sm btn-C"  title="Editar"><i class="fa fa-pencil"></i></button>
@if($c->logCategoria->count() > 0)
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
<script src="../plugins/select2/select2.full.min.js"></script>
<script>$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}});$(document).ready(function(){$('#tbl_C').DataTable({})});$(".select2").select2({placeholder:'Seleccione una opción'});$("#btn_cancelar").click(function(){location.reload()});$("#btn_guardar").click(function(){var p1=$("#categoria_nombre").val(),p2=$("#categoria_descripcion").val(),p3=$("#categoria_porcentaje_probabilidad").val(),p4=$("input:radio[name=estado_id]:checked").val(),p5=$("#categoria_rango_minimo").val(),p6=$("#categoria_rango_maximo").val();$('#mensajes').removeClass('alert alert-danger');$('#mensajes').addClass('hide');$('#textoMensaje').text("");var bValid=false;var bValid1=validarCampoLleno(p1,"Nombre","categoria_nombre");var bValid2=validarCampoLleno(p2,"Descripción","categoria_descripcion");var bValid3=validarCampoLleno(p3,"Porcentaje Probabilidad","categoria_porcentaje_probabilidad");var bValid5=validarCampoLleno(p5,"Mínimo","categoria_rango_minimo");var bValid6=validarCampoLleno(p6,"Máximo","categoria_rango_maximo");var bValid4=validarCampoLleno(p4,"Estado","estado_id");bValid=bValid1&&bValid2&&bValid3&&bValid4&&bValid5&&bValid6;if(bValid){$.ajax({url:'/validarCategoria',type:'post',"_token":"{{ csrf_token() }}",async:true,data:{'valor':null,'nombre':p1,'descripcion':p2,'porcentaje_probabilidad':p3,'estado_id':p4,'min':p5,'max':p6},beforeSend:function(){$.blockUI({message:'<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'})},error:function(jqXHR,textStatus,errorThrown){var gnrMensaje=$("#gnrMensaje");toTop();if(jqXHR.status=='401'){gnrMensaje.text(textStatus+" - "+errorThrown+" ,Su sesión ha caducado, por favor de click en Salir y vuelva a Ingresar.");$('#gnrError').modal('show')}else{$('#mensajes').addClass('alert alert-danger');$('#mensajes').removeClass('hide');var textoMensaje=$("#textoMensaje");textoMensaje.text("ERROR: "+jqXHR.status+" - "+textStatus+" - "+errorThrown)}},success:function(j){if(j!==""){$("#mensajes").addClass("alert alert-danger");$("#mensajes").removeClass("hide");$("#textoMensaje").text("");for(var h in j){$("#textoMensaje").append(j[h]+"<br/>")}}else{$("#formCategoria").submit()}},complete:function(){setTimeout($.unblockUI,1000)}})}});$(".btn-Log_C").click(function(){var fila=$(this).parents("tr");var cadena=JSON.stringify(fila.data("id"));var obj=eval("("+cadena+")");var log=obj.log_categoria;var t=$('#log').DataTable();t.clear();for(var i=0;i<log.length;i++){t.row.add([log[i].registro_anterior,log[i].registro_nuevo,log[i].tipo_accion,log[i].fecha_ingresa]).draw(false)}$('#myModal').modal('show')});$(".btn-C").click(function(){var fila=$(this).parents("tr");var cadena=JSON.stringify(fila.data("id"));var obj=eval("("+cadena+")");$('#mensajes').removeClass('alert alert-danger');$('#mensajes').addClass('hide');$('#textoMensaje').text("");$.ajax({url:"/buscarCategoria",type:"post",_token:"{{ csrf_token() }}",async:true,data:{valor:obj.categoria_id},beforeSend:function(){$.blockUI({message:'<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento porfavor </h4>'})},error:function(jqXHR,textStatus,errorThrown){var gnrMensaje=$("#gnrMensaje");toTop();if(jqXHR.status=="401"){gnrMensaje.text(textStatus+" - "+errorThrown+" ,Su sesión ha caducado, porfavor de click en Salir y vuelva a Ingresar.");$("#gnrError").modal("show")}else{$("#mensajes").addClass("alert alert-danger");$("#mensajes").removeClass("hide");var textoMensaje=$("#textoMensaje");textoMensaje.text("ERROR: "+jqXHR.status+" - "+textStatus+" - "+errorThrown)}},success:function(data){if(data!=''){validarCampoLleno(data.categoria_nombre,"Nombre","categoria_nombre");validarCampoLleno(data.categoria_descripcion,"Descripción","categoria_descripcion");validarCampoLleno(data.estado_id,"Estado","estado_id");validarCampoLleno(data.categoria_porcentaje_probabilidad,"Porcentaje Probabilidad","categoria_porcentaje_probabilidad");validarCampoLleno(data.categoria_rango_minimo,"Mínimo","categoria_rango_minimo");validarCampoLleno(data.categoria_rango_maximo,"Máximo","categoria_rango_maximo");$("#categoria_id").val(data.categoria_id);$("#categoria_nombre").val(data.categoria_nombre);$("#categoria_descripcion").val(data.categoria_descripcion);$("#categoria_porcentaje_probabilidad").val(data.categoria_porcentaje_probabilidad);$("#categoria_rango_minimo").val(data.categoria_rango_minimo);$("#categoria_rango_maximo").val(data.categoria_rango_maximo);$('input:radio[name=estado_id][value='+data.estado_id+']').attr('checked',true);$("#btn_actualizar").removeClass("hide");$("#btn_guardar").addClass("hide");$("#enlace").click()}},complete:function(){setTimeout($.unblockUI,1000)}})});$("#btn_actualizar").click(function(){var p0=$("#categoria_id").val(),p1=$("#categoria_nombre").val(),p2=$("#categoria_descripcion").val(),p3=$("#categoria_porcentaje_probabilidad").val(),p4=$("input:radio[name=estado_id]:checked").val(),p5=$("#categoria_rango_minimo").val(),p6=$("#categoria_rango_maximo").val();$('#mensajes').removeClass('alert alert-danger');$('#mensajes').addClass('hide');$('#textoMensaje').text("");var bValid=false;var bValid1=validarCampoLleno(p1,"Nombre","categoria_nombre");var bValid2=validarCampoLleno(p2,"Descripción","categoria_descripcion");var bValid3=validarCampoLleno(p3,"Porcentaje Probabilidad","categoria_porcentaje_probabilidad");var bValid4=validarCampoLleno(p4,"Estado","estado_id");var bValid5=validarCampoLleno(p5,"Mínimo","categoria_rango_minimo");var bValid6=validarCampoLleno(p6,"Máximo","categoria_rango_maximo");bValid=bValid1&&bValid2&&bValid3&&bValid4&&bValid5&&bValid6;if(bValid){$.ajax({url:'/validarCategoria',type:'post',"_token":"{{ csrf_token() }}",async:true,data:{'valor':p0,'nombre':p1,'descripcion':p2,'porcentaje_probabilidad':p3,'estado_id':p4,'min':p5,'max':p6},beforeSend:function(){$.blockUI({message:'<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento porfavor </h4>'})},error:function(jqXHR,textStatus,errorThrown){var gnrMensaje=$("#gnrMensaje");toTop();if(jqXHR.status=='401'){gnrMensaje.text(textStatus+" - "+errorThrown+" ,Su sesión ha caducado, porfavor de click en Salir y vuelva a Ingresar.");$('#gnrError').modal('show')}else{$('#mensajes').addClass('alert alert-danger');$('#mensajes').removeClass('hide');var textoMensaje=$("#textoMensaje");textoMensaje.text("ERROR: "+jqXHR.status+" - "+textStatus+" - "+errorThrown)}},success:function(j){if(j!==""){$("#mensajes").addClass("alert alert-danger");$("#mensajes").removeClass("hide");$("#textoMensaje").text("");for(var h in j){$("#textoMensaje").append(j[h]+"<br/>")}}else{$("#formCategoria").attr("action","/actualizarCategoria");$("#formCategoria").submit()}},complete:function(){setTimeout($.unblockUI,1000)}})}});</script>
@endsection
