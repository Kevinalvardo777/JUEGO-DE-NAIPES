@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
@endsection
@section('header')
<h1>
<i class="fa fa-users"></i>   USUARIOS
</h1>
<ol class="breadcrumb">
<li><a href="#"><i class="fa fa-dashboard"></i> Configuración</a></li>
<li class="active">Usuarios</li>
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
<form class="form-horizontal" action="/guardarUsuario" method="post" id="formUsuario">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="usuario_id" id="usuario_id">
<div class="box-body">
<br/>
<div class="col-sm-6">
<div class="form-group" id="div_usuario_username">
<label class="col-lg-2 control-label">Username</label>
<div class="col-lg-10">
<input type="text" id="usuario_username" name="usuario_username" minlength="3" maxlength="150" class="form-control">
<label  id="lbl_usuario_username" class="control-label hide"></label>
</div>
</div>
<div class="form-group" id="div_usuario_nombre">
<label class="col-lg-2 control-label">Nombre</label>
<div class="col-lg-10">
<input type="text" id="usuario_nombre" name="usuario_nombre" minlength="3" maxlength="150" class="form-control">
<label  id="lbl_usuario_nombre" class="control-label hide"></label>
</div>
</div>
<div class="form-group" id="div_usuario_apellido">
<label class="col-lg-2 control-label">Apellido</label>
<div class="col-lg-10">
<input type="text" id="usuario_apellido" name="usuario_apellido" minlength="3" maxlength="150" class="form-control">
<label  id="lbl_usuario_apellido" class="control-label hide"></label>
</div>
</div>
<div class="form-group" id="div_usuario_telefono">
<label class="col-lg-2 control-label">Teléfono</label>
<div class="col-lg-10">
<input type="number" id="usuario_telefono" name="usuario_telefono" minlength="3" maxlength="150" class="form-control">
<label  id="lbl_usuario_telefono" class="control-label hide"></label>
</div>
</div>
<div class="form-group" id="div_tipo_usuario_id">
<label class="col-lg-2  control-label">Tipo</label>
<div class="col-lg-10">
<select id="tipo_usuario_id" name="tipo_usuario_id" class="form-control select2 " style="width: 100%;" tabindex="-1" aria-hidden="true">
@foreach($lstTipoUsuarios as $p)
<option value="{{$p->tipo_usuario_id}}">{{$p->tipo_usuario_nombre}}</option>
@endforeach
</select>
<label  id="lbl_tipo_usuario_id" class=" control-label hide"></label>
</div>
</div>
</div>
<div class="col-sm-6">
<div class="form-group" id="div_usuario_email">
<label class="col-lg-2 control-label">Email</label>
<div class="col-lg-10">
<input type="email" id="usuario_email" name="usuario_email" minlength="3" maxlength="150" class="form-control">
<label  id="lbl_usuario_email" class="control-label hide"></label>
</div>
</div>
<div class="form-group" id="div_usuario_password">
<label class="col-lg-2 control-label">Contraseña</label>
<div class="col-lg-10">
<input type="text" id="usuario_password" name="usuario_password"  minlength="3" maxlength="10" class="form-control">
<label  id="lbl_usuario_password" class=" control-label hide"></label>
</div>
</div>
<div class="form-group" id="div_usuario_password2">
<label class="col-lg-2">Confirmar Contraseña</label>
<div class="col-lg-10">
<input type="text" id="usuario_password2" name="usuario_password2" maxlength="10" class="form-control">
<label  id="lbl_usuario_password2" class=" control-label hide"></label>
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
<th>USUARIO</th>
<th>NOMBRE</th>
<th>APELLIDO</th>
<th>TIPO USUARIO</th>
<th>ESTADO</th>
<th></th>
</tr>
</thead>
<tbody>
@if($lstUsuarios)
@foreach($lstUsuarios as $c)
<tr data-id="{{$c}}">
<td>{{$c->usuario_username}} </td>
<td>{{$c->usuario_nombre}} </td>
<td>{{$c->usuario_apellido}} </td>
<td>@if($c->tipoUsuario!=null){{$c->tipoUsuario->tipo_usuario_nombre}}@else{{$c->tipoUsuario}}@endif</td>
<td>@if($c->estado!=null){{$c->estado->estado_nombre}}@else{{$c->estado}}@endif</td>
<td style="text-align: center">
<button type="button" class="btn btn-warning btn-sm btn-C"  title="Editar"><i class="fa fa-pencil"></i></button>
@if($c->logUsuario->count() > 0)
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
<script>$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}});$(document).ready(function(){$('#tbl_C').DataTable({})});$("#btn_cancelar").click(function(){location.reload()});$("#usuario_email").on("change",function(){validarCampoRegExp("usuario_email",$(this).val(),/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,4})$/,"El formato del email del usuario no es correcto. Ejemplo: xxxxx@xxx.xxx")});$("#btn_guardar").click(function(){var p1=$("#usuario_username").val(),p2=$("#usuario_password").val(),p3=$("#usuario_password2").val(),p4=$("#usuario_nombre").val(),p5=$("#usuario_apellido").val(),p6=$("#usuario_telefono").val(),p7=$("#usuario_email").val(),p8=$("#tipo_usuario_id").val(),p9=$("input:radio[name=estado_id]:checked").val();$('#mensajes').removeClass('alert alert-danger');$('#mensajes').addClass('hide');$('#textoMensaje').text("");var bValid=false;var bValid1=validarCampoLleno(p7,"Email","usuario_email");if(bValid1)bValid1=validarCampoRegExp("usuario_email",p7,/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,4})$/,"El formato del email del usuario no es correcto. Ejemplo: xxxxx@xxx.xxx");var bValid2=validarCampoLleno(p1,"Username","usuario_username");var bValid3=validarCampoLleno(p2,"Contraseña","usuario_password");var bValid4=validarCampoLleno(p3,"Confirmación Contraseña","usuario_password2");var bValid5=validarCampoLleno(p4,"Nombre","usuario_nombre");var bValid6=validarCampoLleno(p5,"Apellido","usuario_apellido");var bValid7=validarCampoLleno(p6,"Teléfono","usuario_telefono");var bValid8=validarCampoLleno(p8,"Tipo","tipo_usuario_id");var bValid9=validarCampoLleno(p9,"Estado","estado_id");var bValid10=false;if(p2===p3){bValid10=true}else{$('#mensajes').addClass('alert alert-danger');$('#mensajes').removeClass('hide');var textoMensaje=$("#textoMensaje");textoMensaje.text("Las contraseñas no coinciden.");bValid10=false}bValid=bValid1&&bValid2&&bValid3&&bValid4&&bValid5&&bValid6&&bValid7&&bValid8&&bValid9&&bValid10;if(bValid){$.ajax({url:'/validarUsuario',type:'post',"_token":"{{ csrf_token() }}",async:true,data:{'usuario_id':null,'usuario_username':p1,'usuario_password':p2,'usuario_password2':p3,'usuario_nombre':p4,'usuario_apellido':p5,'usuario_telefono':p6,'usuario_email':p7,'tipo_usuario_id':p8,'estado_id':p9},beforeSend:function(){$.blockUI({message:'<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'})},error:function(jqXHR,textStatus,errorThrown){var gnrMensaje=$("#gnrMensaje");toTop();if(jqXHR.status=='401'){gnrMensaje.text(textStatus+" - "+errorThrown+" ,Su sesión ha caducado, por favor de click en Salir y vuelva a Ingresar.");$('#gnrError').modal('show')}else{$('#mensajes').addClass('alert alert-danger');$('#mensajes').removeClass('hide');var textoMensaje=$("#textoMensaje");textoMensaje.text("ERROR: "+jqXHR.status+" - "+textStatus+" - "+errorThrown)}},success:function(j){if(j!==""){$("#mensajes").addClass("alert alert-danger");$("#mensajes").removeClass("hide");$("#textoMensaje").text("");for(var h in j){$("#textoMensaje").append(j[h]+"<br/>")}}else{$("#formUsuario").submit()}},complete:function(){setTimeout($.unblockUI,1000)}})}});$(".btn-Log_C").click(function(){var fila=$(this).parents("tr");var cadena=JSON.stringify(fila.data("id"));var obj=eval("("+cadena+")");var log=obj.log_usuario;var t=$('#log').DataTable();t.clear();for(var i=0;i<log.length;i++){t.row.add([log[i].registro_anterior,log[i].registro_nuevo,log[i].tipo_accion,log[i].fecha_ingresa]).draw(false)}$('#myModal').modal('show')});$(".btn-C").click(function(){var fila=$(this).parents("tr");var cadena=JSON.stringify(fila.data("id"));var obj=eval("("+cadena+")");$('#mensajes').removeClass('alert alert-danger');$('#mensajes').addClass('hide');$('#textoMensaje').text("");$.ajax({url:"/buscarUsuario",type:"post",_token:"{{ csrf_token() }}",async:true,data:{usuario_id:obj.usuario_id},beforeSend:function(){$.blockUI({message:'<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'})},error:function(jqXHR,textStatus,errorThrown){var gnrMensaje=$("#gnrMensaje");toTop();if(jqXHR.status=="401"){gnrMensaje.text(textStatus+" - "+errorThrown+" ,Su sesión ha caducado, por favor de click en Salir y vuelva a Ingresar.");$("#gnrError").modal("show")}else{$("#mensajes").addClass("alert alert-danger");$("#mensajes").removeClass("hide");var textoMensaje=$("#textoMensaje");textoMensaje.text("ERROR: "+jqXHR.status+" - "+textStatus+" - "+errorThrown)}},success:function(data){if(data!=''){$("#usuario_id").val(data.usuario_id);$("#usuario_username").val(data.usuario_username);$("#usuario_password").val(data.usuario_password);$("#usuario_password2").val(data.usuario_password);$("#usuario_nombre").val(data.usuario_nombre);$("#usuario_apellido").val(data.usuario_apellido);$("#usuario_telefono").val(data.usuario_telefono);$("#usuario_email").val(data.usuario_email);$("#tipo_usuario_id").val(data.tipo_usuario_id);$('input:radio[name=estado_id][value='+data.estado_id+']').attr('checked',true);$("#btn_actualizar").removeClass("hide");$("#btn_guardar").addClass("hide");$("#enlace").click()}},complete:function(){setTimeout($.unblockUI,1000)}})});$("#btn_actualizar").click(function(){var p0=$("#usuario_id").val(),p1=$("#usuario_username").val(),p2=$("#usuario_password").val(),p3=$("#usuario_password2").val(),p4=$("#usuario_nombre").val(),p5=$("#usuario_apellido").val(),p6=$("#usuario_telefono").val(),p7=$("#usuario_email").val(),p8=$("#tipo_usuario_id").val(),p9=$("input:radio[name=estado_id]:checked").val();$('#mensajes').removeClass('alert alert-danger');$('#mensajes').addClass('hide');$('#textoMensaje').text("");var bValid=false;var bValid1=validarCampoLleno(p7,"Email","usuario_email");if(bValid1)bValid1=validarCampoRegExp("usuario_email",p7,/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,4})$/,"El formato del email del usuario no es correcto. Ejemplo: xxxxx@xxx.xxx");var bValid2=validarCampoLleno(p1,"Username","usuario_username");var bValid3=validarCampoLleno(p2,"Contraseña","usuario_password");var bValid4=validarCampoLleno(p3,"Confirmación Contraseña","usuario_password2");var bValid5=validarCampoLleno(p4,"Nombre","usuario_nombre");var bValid6=validarCampoLleno(p5,"Apellido","usuario_apellido");var bValid7=validarCampoLleno(p6,"Teléfono","usuario_telefono");var bValid8=validarCampoLleno(p8,"Tipo","tipo_usuario_id");var bValid9=validarCampoLleno(p9,"Estado","estado_id");var bValid10=false;if(p2===p3){bValid10=true}else{$('#mensajes').addClass('alert alert-danger');$('#mensajes').removeClass('hide');var textoMensaje=$("#textoMensaje");textoMensaje.text("Las contraseñas no coinciden.");bValid10=false}bValid=bValid1&&bValid2&&bValid3&&bValid4&&bValid5&&bValid6&&bValid7&&bValid8&&bValid9&&bValid10;if(bValid){$.ajax({url:'/validarUsuario',type:'post',"_token":"{{ csrf_token() }}",async:true,data:{'usuario_id':p0,'usuario_username':p1,'usuario_password':p2,'usuario_password2':p3,'usuario_nombre':p4,'usuario_apellido':p5,'usuario_telefono':p6,'usuario_email':p7,'tipo_usuario_id':p8,'estado_id':p9},beforeSend:function(){$.blockUI({message:'<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento porfavor </h4>'})},error:function(jqXHR,textStatus,errorThrown){var gnrMensaje=$("#gnrMensaje");toTop();if(jqXHR.status=='401'){gnrMensaje.text(textStatus+" - "+errorThrown+" ,Su sesión ha caducado, porfavor de click en Salir y vuelva a Ingresar.");$('#gnrError').modal('show')}else{$('#mensajes').addClass('alert alert-danger');$('#mensajes').removeClass('hide');var textoMensaje=$("#textoMensaje");textoMensaje.text("ERROR: "+jqXHR.status+" - "+textStatus+" - "+errorThrown)}},success:function(j){if(j!==""){$("#mensajes").addClass("alert alert-danger");$("#mensajes").removeClass("hide");$("#textoMensaje").text("");for(var h in j){$("#textoMensaje").append(j[h]+"<br/>")}}else{$("#formUsuario").attr("action","/actualizarUsuario");$("#formUsuario").submit()}},complete:function(){setTimeout($.unblockUI,1000)}})}});</script>
@endsection
