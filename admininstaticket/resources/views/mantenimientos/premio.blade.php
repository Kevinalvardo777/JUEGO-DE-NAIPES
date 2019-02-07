@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="../plugins/select2/select2.min.css">
@endsection
@section('header')
<h1>
<i class="fa fa-gift"></i>   PREMIOS
</h1>
<ol class="breadcrumb">
<li><a href="#"><i class="fa fa-suitcase"></i> Mantenimientos</a></li>
<li class="active">Premios</li>
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
<form class="form-horizontal" action="/guardarPremio" method="post" id="formPremio" enctype="multipart/form-data">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="premio_id" id="premio_id">
<div class="box-body">
<br/>
<div class="col-sm-6">
<div class="form-group" id="div_premio_nombre">
<label class="col-lg-2 control-label">Nombre</label>
<div class="col-lg-10">
<input type="text" id="premio_nombre" name="premio_nombre" minlength="3" maxlength="45" class="form-control">
<label  id="lbl_premio_nombre" class="control-label hide"></label>
</div>
</div>
<div class="form-group" id="div_categoria_id">
<label  class="col-lg-2">Categoría</label>
<div class="col-lg-10">
<select id="categoria_id" name="categoria_id" class="form-control select2" style="width: 100%;" tabindex="-1" aria-hidden="true">
@foreach($lstCategoriasActivas as $categoriasActivas)
<option value="{{$categoriasActivas->categoria_id}}"> {{$categoriasActivas->categoria_nombre}} - {{$categoriasActivas->categoria_porcentaje_probabilidad}}% </option>
@endforeach
</select>
</div>
<label  id="lbl_categoria_id" class=" control-label hide"></label>
</div>
<div class="form-group" id="div_premio_url_imagen">
<label class="col-lg-2 ">Imagen</label>
<div class="col-lg-10">
<input type="file" id="premio_url_imagen" name="premio_url_imagen"  minlength="3" maxlength="150" class="form-control">
<label  id="lbl_premio_url_imagen" class=" control-label hide"></label>
</div>
</div>
<div class="form-group">
<label class="col-lg-2 "></label>
<div class="col-lg-10">
<img id="url_imagen" width="280px" height="280px" class="" src="../img/reg.png"/>
</div>
</div>
</div>
<div class="col-sm-6">
<div class="form-group" id="div_premio_descripcion">
<label class="col-lg-2  control-label">Descripción</label>
<div class="col-lg-10">
<textarea style="resize:none;"  min="1" rows="3" id="premio_descripcion" name="premio_descripcion" minlength="1" maxlength="250"  class="form-control"></textarea>
<label  id="lbl_premio_descripcion" class=" control-label hide"></label>
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
<th>IMAGEN</th>
<th>ESTADO</th>
<th>CATEGORÍA</th>
<th></th>
</tr>
</thead>
<tbody>
@if($lstPremios)
@foreach($lstPremios as $c)
<tr data-id="{{$c}}">
<td>{{$c->premio_nombre}} </td>
<td>{{$c->premio_descripcion}} </td>
<td><img width="100px" height="100px" class="" src="{{$objUrlRecursos->parametro_general_valor}}{{$c->premio_url_imagen}}"/></td>
<td>@if($c->estado!=null){{$c->estado->estado_nombre}}@else{{$c->estado}}@endif</td>
<td>@if($c->categoria!=null){{$c->categoria->categoria_nombre}}@else{{$c->categoria}}@endif</td>
<td style="text-align: center">
<button type="button" class="btn btn-warning btn-sm btn-C"  title="Editar"><i class="fa fa-pencil"></i></button>
@if($c->logPremio->count() > 0)
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
<script>$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}});$(document).ready(function(){$('#tbl_C').DataTable({})});$('.select2').select2({placeholder:'Seleccione una opción',});$("#btn_cancelar").click(function(){location.reload()});$("#premio_url_imagen").change(function(){var b=this.files[0];$("#div_premio_url_imagen").removeClass("has-error");$("#lbl_premio_url_imagen").addClass("hide");$("#lbl_premio_url_imagen").text("");if(b!=null){if(!window.FileReader){exists=false;$("#div_premio_url_imagen").addClass("has-error");$("#lbl_premio_url_imagen").removeClass("hide");$("#lbl_premio_url_imagen").text("Error ! El navegador no soporta la lectura de archivos.");return}if(!(/\.(jpg|png|gif)$/i).test(b.name)){exists=false;$("#div_premio_url_imagen").addClass("has-error");$("#lbl_premio_url_imagen").removeClass("hide");$("#lbl_premio_url_imagen").text("Error ! El archivo a adjuntar no es una imagen.")}else{var a=new Image();a.onload=function(){if(this.width.toFixed(0)!=800&&this.height.toFixed(0)!=800){exists=false;$("#div_premio_url_imagen").addClass("has-error");$("#lbl_premio_url_imagen").removeClass("hide");$("#lbl_premio_url_imagen").text("Error ! Las medidas deben ser: 800 * 800.")}else{if(b.size>602400){exists=false;$("#div_premio_url_imagen").addClass("has-error");$("#lbl_premio_url_imagen").removeClass("hide");$("#lbl_premio_url_imagen").text("Error ! El peso de la imagen no puede exceder los 300kb.")}else{exists=true;var c=new FileReader();c.onload=function(d){$("#url_imagen").attr("src",d.target.result)};c.readAsDataURL(b)}}};a.src=URL.createObjectURL(b)}}});$("#btn_guardar").click(function(){var p1=$("#premio_nombre").val(),p2=$("#premio_descripcion").val(),p3=$("#categoria_id").val(),p4=$("#premio_url_imagen").val(),p5=$("input:radio[name=estado_id]:checked").val();$('#mensajes').removeClass('alert alert-danger');$('#mensajes').addClass('hide');$('#textoMensaje').text("");var bValid=false;var bValid1=validarCampoLleno(p1,"Nombre","premio_nombre");var bValid2=validarCampoLleno(p2,"Descripción","premio_descripcion");var bValid3=validarCampoLleno(p3,"Categoría","categoria_id");var bValid4=validarCampoLleno(p4,"Imagen","premio_url_imagen");var bValid5=validarCampoLleno(p5,"Estado","estado_id");var bValid6=exists;bValid=bValid1&&bValid2&&bValid3&&bValid4&&bValid5&&bValid6;if(bValid){$.ajax({url:'/validarPremio',type:'post',"_token":"{{ csrf_token() }}",async:true,data:{'premio_id':null,'premio_nombre':p1,'premio_descripcion':p2,'categoria_id':p3,'premio_url_imagen':p4,'estado_id':p5},beforeSend:function(){$.blockUI({message:'<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'})},error:function(jqXHR,textStatus,errorThrown){var gnrMensaje=$("#gnrMensaje");toTop();if(jqXHR.status=='401'){gnrMensaje.text(textStatus+" - "+errorThrown+" ,Su sesión ha caducado, por favor de click en Salir y vuelva a Ingresar.");$('#gnrError').modal('show')}else{$('#mensajes').addClass('alert alert-danger');$('#mensajes').removeClass('hide');var textoMensaje=$("#textoMensaje");textoMensaje.text("ERROR: "+jqXHR.status+" - "+textStatus+" - "+errorThrown)}},success:function(j){if(j!==""){$("#mensajes").addClass("alert alert-danger");$("#mensajes").removeClass("hide");$("#textoMensaje").text("");for(var h in j){$("#textoMensaje").append(j[h]+"<br/>")}}else{$("#formPremio").submit()}},complete:function(){setTimeout($.unblockUI,1000)}})}});$(".btn-Log_C").click(function(){var fila=$(this).parents("tr");var cadena=JSON.stringify(fila.data("id"));var obj=eval("("+cadena+")");var log=obj.log_premio;var t=$('#log').DataTable();t.clear();for(var i=0;i<log.length;i++){t.row.add([log[i].registro_anterior,log[i].registro_nuevo,log[i].tipo_accion,log[i].fecha_ingresa]).draw(false)}$('#myModal').modal('show')});$(".btn-C").click(function(){var fila=$(this).parents("tr");var cadena=JSON.stringify(fila.data("id"));var obj=eval("("+cadena+")");$('#mensajes').removeClass('alert alert-danger');$('#mensajes').addClass('hide');$('#textoMensaje').text("");$.ajax({url:"/buscarPremio",type:"post",_token:"{{ csrf_token() }}",async:true,data:{premio_id:obj.premio_id},beforeSend:function(){$.blockUI({message:'<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'})},error:function(jqXHR,textStatus,errorThrown){var gnrMensaje=$("#gnrMensaje");toTop();if(jqXHR.status=="401"){gnrMensaje.text(textStatus+" - "+errorThrown+" ,Su sesión ha caducado, por favor de click en Salir y vuelva a Ingresar.");$("#gnrError").modal("show")}else{$("#mensajes").addClass("alert alert-danger");$("#mensajes").removeClass("hide");var textoMensaje=$("#textoMensaje");textoMensaje.text("ERROR: "+jqXHR.status+" - "+textStatus+" - "+errorThrown)}},success:function(data){if(data[0]!=''){validarCampoLleno(data[0].premio_nombre,"Nombre","premio_nombre");validarCampoLleno(data[0].premio_descripcion,"Descripción","premio_descripcion");validarCampoLleno(data[0].estado_id,"Estado","estado_id");validarCampoLleno(data[0].categoria_id,"Categoría","categoria_id");var val_imagen=validarCampoLleno(data[0].premio_url_imagen,"Imagen","premio_url_imagen");if(val_imagen){exists=true}else{exists=false}var nom_imagen=data[1]+data[0].premio_url_imagen;$("#premio_id").val(data[0].premio_id);$("#premio_nombre").val(data[0].premio_nombre);$("#premio_descripcion").val(data[0].premio_descripcion);$('#url_imagen').attr("src",nom_imagen);$('input:radio[name=estado_id][value='+data[0].estado_id+']').attr('checked',true);$("#categoria_id").val(data[0].categoria_id).change();$("#btn_actualizar").removeClass("hide");$("#btn_guardar").addClass("hide");$("#enlace").click()}},complete:function(){setTimeout($.unblockUI,1000)}})});$("#btn_actualizar").click(function(){var p0=$("#premio_id").val(),p1=$("#premio_nombre").val(),p2=$("#premio_descripcion").val(),p3=$("#categoria_id").val(),p4=$("#premio_url_imagen").val(),p5=$("input:radio[name=estado_id]:checked").val();$('#mensajes').removeClass('alert alert-danger');$('#mensajes').addClass('hide');$('#textoMensaje').text("");var bValid=false;var bValid1=validarCampoLleno(p1,"Nombre","premio_nombre");var bValid2=validarCampoLleno(p2,"Descripción","premio_descripcion");var bValid3=validarCampoLleno(p3,"Categoría","categoria_id");var bValid4=exists;var bValid5=validarCampoLleno(p5,"Estado","estado_id");bValid=bValid1&&bValid2&&bValid3&&bValid4&&bValid5;if(bValid){$.ajax({url:'/validarPremio',type:'post',"_token":"{{ csrf_token() }}",async:true,data:{'premio_id':p0,'premio_nombre':p1,'premio_descripcion':p2,'categoria_id':p3,'premio_url_imagen':p4,'estado_id':p5},beforeSend:function(){$.blockUI({message:'<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'})},error:function(jqXHR,textStatus,errorThrown){var gnrMensaje=$("#gnrMensaje");toTop();if(jqXHR.status=='401'){gnrMensaje.text(textStatus+" - "+errorThrown+" ,Su sesión ha caducado, por favor de click en Salir y vuelva a Ingresar.");$('#gnrError').modal('show')}else{$('#mensajes').addClass('alert alert-danger');$('#mensajes').removeClass('hide');var textoMensaje=$("#textoMensaje");textoMensaje.text("ERROR: "+jqXHR.status+" - "+textStatus+" - "+errorThrown)}},success:function(j){if(j!==""){$("#mensajes").addClass("alert alert-danger");$("#mensajes").removeClass("hide");$("#textoMensaje").text("");for(var h in j){$("#textoMensaje").append(j[h]+"<br/>")}}else{$("#formPremio").attr("action","/actualizarPremio");$("#formPremio").submit()}},complete:function(){setTimeout($.unblockUI,1000)}})}});</script>
@endsection
