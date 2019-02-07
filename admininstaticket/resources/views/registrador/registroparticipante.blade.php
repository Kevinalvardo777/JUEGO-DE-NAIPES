@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="../plugins/select2/select2.min.css">
@endsection
@section('htmlheader_title')
Registro Participante
@endsection
@section('header')
<h1>
<i class="fa fa-users"></i>   PARTICIPANTE
</h1>
@endsection
@section('main-content')
<div class="col-md-12">
<div class="nav-tabs-custom">
<ul class="nav nav-tabs">
<li  class="active"><a id="enlace" href="#tab_1" data-toggle="tab">Ingreso/Actualización</a></li>
</ul>
<div class="tab-content">
<div class="tab-pane active" id="tab_1">
<form class="form-horizontal" action="/guardarRegistroParticipante" method="post" id="formRegistroParticipante" enctype="multipart/form-data">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="evento_id" id="evento_id" value="{{$objEvento->evento_id}}">
<input type="hidden" name="mig_inf_par_id" id="mig_inf_par_id">
<input type="hidden" name="participiante_id" id="participiante_id">
<input type="hidden" name="boleto_numero" id="boleto_numero">
<input type="hidden" name="tipo" id="tipo">
<div class="box-body">
<br/>
<div class="col-sm-12">
<div class="form-group col-sm-9" id="div_scanner_input">
<label class="col-lg-2 text-right">Boleto</label>
<div class="col-lg-10">
<input type="text" id="scanner_input" name="scanner_input" class="form-control">
<label  id="lbl_scanner_input" class="control-label hide"></label>
</div>
</div>
<div class="form-group col-sm-3">
<button type="button" data-toggle="modal" data-target="#livestream_scanner" class="btn btn-warning "><i class="fa fa-barcode"></i>&nbsp;&nbsp;ESCANEAR</button> 
<button type="button" id="btn_buscar" class="btn btn-primary "><i class="fa fa-search"></i>&nbsp;&nbsp;BUSCAR</button> 
</div>
</div>
<div class="col-sm-6">
<div class="form-group hide" id="div_participante_cedula">
<label class="col-lg-2 control-label">Cédula</label>
<div class="col-lg-10">
<input type="text" id="participante_cedula" name="participante_cedula" minlength="10" maxlength="13" class="form-control">
<label  id="lbl_participante_cedula" class="control-label hide"></label>
</div>  
</div>
<div class="form-group hide" id="div_participante_nombre">
<label class="col-lg-2 control-label">Nombre</label>
<div class="col-lg-10">
<input type="text" id="participante_nombre" name="participante_nombre" minlength="10" maxlength="150" class="form-control">
<label  id="lbl_participante_nombre" class="control-label hide"></label>
</div>  
</div>
<div class="form-group hide" id="div_participante_email">
<label class="col-lg-2 control-label">Email</label>
<div class="col-lg-10">
<input type="email" id="participante_email" name="participante_email" minlength="10" maxlength="100" class="form-control">
<label  id="lbl_participante_email" class="control-label hide"></label>
</div>  
</div>
</div>
<div class="col-sm-6">
<div class="form-group hide" id="div_participante_telefono">
<label class="col-lg-2 control-label">Télefono</label>
<div class="col-lg-10">
<input type="text" id="participante_telefono" name="participante_telefono" minlength="10" maxlength="10" class="form-control">
<label  id="lbl_participante_telefono" class="control-label hide"></label>
</div>  
</div>
<div class="form-group hide" id="div_participante_celular">
<label class="col-lg-2 control-label">Celular</label>
<div class="col-lg-10">
<input type="text" id="participante_celular" name="participante_celular" minlength="10" maxlength="10" class="form-control">
<label  id="lbl_participante_celular" class="control-label hide"></label>
</div>  
</div>
<div class="form-group hide" id="div_evento_nombre">
<label class="col-lg-2 control-label">Evento</label>
<div class="col-lg-10">
<input type="text" disabled="true" id="evento_nombre" name="evento_nombre" minlength="10" maxlength="150" class="form-control" value="{{$objEvento->evento_nombre}}">
<label  id="lbl_evento_nombre" class="control-label hide"></label>
</div>  
</div>
</div>
<div class="col-sm-12">
<div class="form-group hide" id="botones">
<div class="col-md-12 text-right">
<button type="button" id="btn_guardar" class="btn btn-warning hide"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;GUARDAR</button>
<button type="button" id="btn_actualizar" class="btn btn-warning  hide"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;ACTUALIZAR</button>
<button type="button" id="btn_cancelar" class="btn btn-primary hide"><i class="fa fa-refresh"></i>&nbsp;&nbsp;CANCELAR</button>
</div> 
</div> 
</div>
<div class="modal" id="livestream_scanner">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
<h4 class="modal-title">Escanear Codigo de Barras</h4>
</div>
<div class="modal-body" style="position: static">
<div id="interactive" class="viewport"></div>
<div class="error"></div>
</div>
<div class="modal-footer">
<label class="btn btn-default pull-left">
<i class="fa fa-camera"></i> Usar cámara app
<input type="file" accept="image/*;capture=camera" capture="camera" class="hidden" />
</label>
<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
</div>
</div>
</div>
</div>
</div>
</div>
</form>
</div>
</div>
</div>
@endsection
@section('script')
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="../plugins/select2/select2.full.min.js"></script>
<script type="text/javascript" src="js/quagga.min.js"></script>  
<style>#interactive.viewport{position:relative;width:100%;height:auto;overflow:hidden;text-align:center}#interactive.viewport>canvas,#interactive.viewport>video{max-width:100%;width:100%}canvas.drawing,canvas.drawingBuffer{position:absolute;left:0;top:0}</style>
<script type="text/javascript">
$(function() {
	var liveStreamConfig = {
			inputStream: {
				type : "LiveStream",
				constraints: {
					width: {min: 640},
					height: {min: 480},
					aspectRatio: {min: 1, max: 100},
					facingMode: "environment" // or "user" for the front camera
				}
			},
			locator: {
				patchSize: "medium",
				halfSample: true
			},
			numOfWorkers: (navigator.hardwareConcurrency ? navigator.hardwareConcurrency : 4),
			decoder: {
				"readers":[
					{"format":"code_128_reader","config":{ 
                                                supplements: [
                                                            'code_39_reader', 'code_93_reader'
                                                ]
                                            }
                                        }
				]
			},
			locate: true
		};
	var fileConfig = $.extend(
			{}, 
			liveStreamConfig,
			{
				inputStream: {
					size: 800
				}
			}
		);
	$('#livestream_scanner').on('shown.bs.modal', function (e) {
		Quagga.init(
			liveStreamConfig, 
			function(err) {
				if (err) {
					$('#livestream_scanner .modal-body .error').html('<div class="alert alert-danger"><strong><i class="fa fa-exclamation-triangle"></i> '+err.name+'</strong>: '+err.message+'</div>');
					Quagga.stop();
					return;
				}
				Quagga.start();
			}
		);
    });
	Quagga.onProcessed(function(result) {
		var drawingCtx = Quagga.canvas.ctx.overlay,
			drawingCanvas = Quagga.canvas.dom.overlay;
 
		if (result) {
			if (result.boxes) {
				drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
				result.boxes.filter(function (box) {
					return box !== result.box;
				}).forEach(function (box) {
					Quagga.ImageDebug.drawPath(box, {x: 0, y: 1}, drawingCtx, {color: "green", lineWidth: 2});
				});
			}
 
			if (result.box) {
				Quagga.ImageDebug.drawPath(result.box, {x: 0, y: 1}, drawingCtx, {color: "#00F", lineWidth: 2});
			}
 
			if (result.codeResult && result.codeResult.code) {
				Quagga.ImageDebug.drawPath(result.line, {x: 'x', y: 'y'}, drawingCtx, {color: 'red', lineWidth: 3});
			}
		}
	});
	Quagga.onDetected(function(result) {    		
		if (result.codeResult.code){
                        $('#scanner_input').val("");
                        $('#mensajes').removeClass('alert alert-danger');
                        $('#mensajes').addClass('hide');
                        $('#textoMensaje').text("");
                        $('#div_participante_cedula').addClass('hide');
                        $('#div_participante_nombre').addClass('hide');
                        $('#div_participante_email').addClass('hide');
                        $('#div_participante_telefono').addClass('hide');
                        $('#div_participante_celular').addClass('hide');
                        $('#div_evento_nombre').addClass('hide');
                        $('#botones').addClass('hide');
                        $('#btn_actualizar').addClass('hide');
                        $("#btn_guardar").addClass("hide");
                        $("#btn_cancelar").addClass("hide");
                        $('#mig_inf_par_id').val();
                        $('#participante_nombre').val();
                        $('#participante_email').val();
                        $('#participante_telefono').val();
                        $('#participante_celular').val();
			$('#scanner_input').val(result.codeResult.code);
			Quagga.stop();	
                        $('#scanner_input').change();
			setTimeout(function(){ $('#livestream_scanner').modal('hide'); }, 1000);			
		}
	});
    $('#livestream_scanner').on('hide.bs.modal', function(){
    	if (Quagga){
    		Quagga.stop();	
    	}
    });
	$("#livestream_scanner input:file").on("change", function(e) {
		if (e.target.files && e.target.files.length) {
			Quagga.decodeSingle($.extend({}, fileConfig, {src: URL.createObjectURL(e.target.files[0])}), function(result) {alert(result.codeResult.code);});
		}
	});
});
</script>
<script>$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}});$(document).ready(function(){$('#tbl_C').DataTable({});$('#scanner_input').val("")});$("#btn_cancelar").click(function(){location.reload()});$("#participante_email").on("change",function(){validarCampoRegExp("participante_email",$(this).val(),/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,4})$/,"El formato del email del participante no es correcto. Ejemplo: xxxxx@xxx.xxx")});$("#scanner_input").on("change",function(){});$("#btn_buscar").click(function(){$('#div_participante_cedula').addClass('hide');$('#div_participante_nombre').addClass('hide');$('#div_participante_email').addClass('hide');$('#div_participante_telefono').addClass('hide');$('#div_participante_celular').addClass('hide');$('#div_evento_nombre').addClass('hide');$('#btn_actualizar').addClass('hide');$("#btn_guardar").addClass("hide");$("#btn_cancelar").addClass("hide");$('#botones').addClass('hide');$('#mig_inf_par_id').val();$('#mensajes').removeClass('alert alert-danger');$('#mensajes').addClass('hide');$('#textoMensaje').text("");var p0=$("#scanner_input").val();var p1=$("#evento_id").val();var bValid=false;var bValid1=validarCampoLleno(p0,"Boleto","scanner_input");var bValid2=validarCampoLleno(p1,"Evento","evento_id");bValid=bValid1&&bValid2;if(bValid){$.ajax({url:"/buscarCodigoBarra",type:"post",_token:"{{ csrf_token() }}",async:true,data:{scanner_input:p0,evento_id:p1},beforeSend:function(){$.blockUI({message:'<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento porfavor </h4>'})},error:function(jqXHR,textStatus,errorThrown){var gnrMensaje=$("#gnrMensaje");toTop();if(jqXHR.status=="401"){gnrMensaje.text(textStatus+" - "+errorThrown+" ,Su sesión ha caducado, porfavor de click en Salir y vuelva a Ingresar.");$("#gnrError").modal("show")}else{$("#mensajes").addClass("alert alert-danger");$("#mensajes").removeClass("hide");var textoMensaje=$("#textoMensaje");textoMensaje.text("ERROR: "+jqXHR.status+" - "+textStatus+" - "+errorThrown)}},success:function(data){if(data!=''){if(data[0]==2){$('#div_participante_cedula').removeClass('hide');$('#div_participante_nombre').removeClass('hide');$('#div_participante_email').removeClass('hide');$('#div_participante_telefono').removeClass('hide');$('#div_participante_celular').removeClass('hide');$('#div_evento_nombre').removeClass('hide');$('#botones').removeClass('hide');$('#btn_actualizar').addClass('hide');$("#btn_guardar").removeClass("hide");$("#btn_cancelar").removeClass("hide");$("#mensajes").addClass("alert alert-info");$("#mensajes").removeClass("hide");var textoMensaje=$("#textoMensaje");textoMensaje.text("No existen datos de participante relacionado con este boleto.");$('#mig_inf_par_id').val(data[1].mig_inf_par_id);$('#participiante_id').val("");$('#participante_cedula').val(data[1].mig_inf_par_cedula);$('#participante_nombre').val(data[1].mig_inf_par_nombre);$('#participante_email').val(data[1].mig_inf_par_email);$('#participante_telefono').val(data[1].mig_inf_par_telefono);$('#participante_celular').val(data[1].mig_inf_par_celular);$('#tipo').val(data[0]);$('#participante_cedula').attr("disabled",false);$('#participante_cedula').change()}else{$('#div_participante_cedula').removeClass('hide');$('#div_participante_nombre').removeClass('hide');$('#div_participante_email').removeClass('hide');$('#div_participante_telefono').removeClass('hide');$('#div_participante_celular').removeClass('hide');$('#div_evento_nombre').removeClass('hide');$('#botones').addClass('hide');$('#btn_actualizar').addClass('hide');$("#btn_guardar").addClass("hide");$("#btn_cancelar").addClass("hide");$('#mig_inf_par_id').val(data[1].mig_inf_par_id);$('#participiante_id').val(data[2].participante.participiante_id);$('#participante_cedula').val(data[2].participante.participante_cedula);$('#participante_nombre').val(data[2].participante.participante_nombre);$('#participante_email').val(data[2].participante.participante_email);$('#participante_telefono').val(data[2].participante.participante_telefono);$('#participante_celular').val(data[2].participante.participante_celular);$('#participante_cedula').attr("disabled",true);$("#mensajes").addClass("alert alert-info");$("#mensajes").removeClass("hide");var textoMensaje=$("#textoMensaje");textoMensaje.text("Existe datos de participante relacionado con este boleto.");$('#tipo').val(data[0])}}else{$("#mensajes").addClass("alert alert-info");$("#mensajes").removeClass("hide");var textoMensaje=$("#textoMensaje");textoMensaje.text("No existen datos de boleto. No es posible registrar Particpante")}},complete:function(){setTimeout($.unblockUI,1000)}})}});$("#participante_cedula").change(function(){var p0=$("#participante_cedula").val();var p1=$("#evento_id").val();var p3=$("#mig_inf_par_id").val();var bValid=false;var bValid1=validarCampoLleno(p0,"Cédula","participante_cedula");var bValid2=validarCampoLleno(p1,"Evento","evento_id");var bValid3=validarCampoLleno(p3,"Migración","mig_inf_par_id");bValid=bValid1&&bValid2&&bValid3;if(bValid){$.ajax({url:"/buscarParticipante",type:"post",_token:"{{ csrf_token() }}",async:true,data:{participante_cedula:p0},beforeSend:function(){$.blockUI({message:'<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento porfavor </h4>'})},error:function(jqXHR,textStatus,errorThrown){var gnrMensaje=$("#gnrMensaje");toTop();if(jqXHR.status=="401"){gnrMensaje.text(textStatus+" - "+errorThrown+" ,Su sesión ha caducado, porfavor de click en Salir y vuelva a Ingresar.");$("#gnrError").modal("show")}else{$("#mensajes").addClass("alert alert-danger");$("#mensajes").removeClass("hide");var textoMensaje=$("#textoMensaje");textoMensaje.text("ERROR: "+jqXHR.status+" - "+textStatus+" - "+errorThrown)}},success:function(data){if(data!=''){if(data[0]==3){$('#participiante_id').val(data[1].participiante_id);$('#participante_cedula').val(data[1].participante_cedula);$('#participante_nombre').val(data[1].participante_nombre);$('#participante_email').val(data[1].participante_email);$('#participante_telefono').val(data[1].participante_telefono);$('#participante_celular').val(data[1].participante_celular);$('#tipo').val(data[0]);$("#btn_actualizar").removeClass("hide");$("#btn_cancelar").removeClass("hide");$("#btn_guardar").addClass("hide");$("#mensajes").addClass("alert alert-info");$("#mensajes").removeClass("hide");var textoMensaje=$("#textoMensaje");textoMensaje.text("Existen datos de participante relacionado con otro boleto actualice para añadir este boleto al participante.");$('#tipo').val(data[0])}else{$('#participiante_id').val("");$("#btn_actualizar").addClass("hide");$("#btn_cancelar").removeClass("hide");$("#btn_guardar").removeClass("hide");$("#mensajes").addClass("alert alert-info");$("#mensajes").removeClass("hide");var textoMensaje=$("#textoMensaje");textoMensaje.text("No Existen datos de participante relacionado con este boleto.");$('#tipo').val(Number(2))}}else{$("#mensajes").addClass("alert alert-info");$("#mensajes").removeClass("hide");var textoMensaje=$("#textoMensaje");textoMensaje.text("No existen datos de boleto. No es posible registrar Particpante")}},complete:function(){setTimeout($.unblockUI,1000)}})}});$("#btn_guardar").click(function(){var p1=$("#participante_cedula").val(),p2=$("#participante_email").val(),p3=$("#participante_celular").val(),p4=$("#scanner_input").val();$('#mensajes').removeClass('alert alert-danger');$('#mensajes').addClass('hide');$('#textoMensaje').text("");var bValid=false;var bValid1=validarCampoLleno(p1,"Cédula","participante_cedula");var bValid2=validarCampoLleno(p2,"Email","participante_email");var bValid3=validarCampoLleno(p3,"Celular","participante_celular");var bValid4=validarCampoLleno(p4,"Boleto","scanner_input");bValid=bValid1&&bValid2&&bValid3&&bValid4;if(bValid){$("#boleto_numero").val(p4);$.ajax({url:'/validarRegistroParticipante',type:'post',"_token":"{{ csrf_token() }}",async:true,data:{'participante_cedula':p1,'participante_email':p2,'participante_celular':p3,'scanner_input':p4},beforeSend:function(){$.blockUI({message:'<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'})},error:function(jqXHR,textStatus,errorThrown){var gnrMensaje=$("#gnrMensaje");toTop();if(jqXHR.status=='401'){gnrMensaje.text(textStatus+" - "+errorThrown+" ,Su sesión ha caducado, por favor de click en Salir y vuelva a Ingresar.");$('#gnrError').modal('show')}else{$('#mensajes').addClass('alert alert-danger');$('#mensajes').removeClass('hide');var textoMensaje=$("#textoMensaje");textoMensaje.text("ERROR: "+jqXHR.status+" - "+textStatus+" - "+errorThrown)}},success:function(j){if(j!==""){$("#mensajes").addClass("alert alert-danger");$("#mensajes").removeClass("hide");$("#textoMensaje").text("");for(var h in j){$("#textoMensaje").append(j[h]+"<br/>")}}else{$("#formRegistroParticipante").submit()}},complete:function(){setTimeout($.unblockUI,1000)}})}});$("#btn_actualizar").click(function(){var p1=$("#participante_cedula").val(),p2=$("#participante_email").val(),p3=$("#participante_celular").val(),p4=$("#scanner_input").val();$('#mensajes').removeClass('alert alert-danger');$('#mensajes').addClass('hide');$('#textoMensaje').text("");var bValid=false;var bValid1=validarCampoLleno(p1,"Cédula","participante_cedula");var bValid2=validarCampoLleno(p2,"Email","participante_email");var bValid3=validarCampoLleno(p3,"Celular","participante_celular");var bValid4=validarCampoLleno(p4,"Boleto","scanner_input");bValid=bValid1&&bValid2&&bValid3&&bValid4;if(bValid){$("#boleto_numero").val(p4);$.ajax({url:'/validarRegistroParticipante',type:'post',"_token":"{{ csrf_token() }}",async:true,data:{'participante_cedula':p1,'participante_email':p2,'participante_celular':p3,'scanner_input':p4},beforeSend:function(){$.blockUI({message:'<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'})},error:function(jqXHR,textStatus,errorThrown){var gnrMensaje=$("#gnrMensaje");toTop();if(jqXHR.status=='401'){gnrMensaje.text(textStatus+" - "+errorThrown+" ,Su sesión ha caducado, por favor de click en Salir y vuelva a Ingresar.");$('#gnrError').modal('show')}else{$('#mensajes').addClass('alert alert-danger');$('#mensajes').removeClass('hide');var textoMensaje=$("#textoMensaje");textoMensaje.text("ERROR: "+jqXHR.status+" - "+textStatus+" - "+errorThrown)}},success:function(j){if(j!==""){$("#mensajes").addClass("alert alert-danger");$("#mensajes").removeClass("hide");$("#textoMensaje").text("");for(var h in j){$("#textoMensaje").append(j[h]+"<br/>")}}else{$("#formRegistroParticipante").attr("action","/actualizarRegistroParticipante");$("#formRegistroParticipante").submit()}},complete:function(){setTimeout($.unblockUI,1000)}})}});</script>
@endsection
