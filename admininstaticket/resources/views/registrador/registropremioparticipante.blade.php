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
<i class="fa fa-gift"></i>   PREMIO
</h1>
@endsection
@section('main-content')
<div class="col-md-12">
<div class="nav-tabs-custom">
<ul class="nav nav-tabs">
<li  class="active"><a id="enlace" href="#tab_1" data-toggle="tab">Ingreso/Consulta</a></li>
</ul>
<div class="tab-content">
<div class="tab-pane active" id="tab_1">
<form class="form-horizontal" action="/guardarRegistroPremioParticipante" method="post" id="formRegistroPremioParticipante" enctype="multipart/form-data">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="evento_id" id="evento_id" value="{{$objEvento->evento_id}}">
<input type="hidden" name="juego_numero" id="juego_numero">
<input type="hidden" name="premio_id" id="premio_id">
<input type="hidden" name="boleto_numero" id="boleto_numero">
<input type="hidden" name="participante_id" id="participante_id">
<input type="hidden" name="participante_boleto_det_id" id="participante_boleto_det_id">
<input type="hidden" name="premio_participante_id" id="premio_participante_id">
<input type="hidden" name="ciclo_evento_premio_id" id="ciclo_evento_premio_id">
<input type="hidden" name="inc_pro_pre_id" id="inc_pro_pre_id">
<div class="box-body">
<br/>
<div class="col-sm-12">
<div class="col-sm-6">
<div class="col-sm-12">
<div class="form-group col-sm-7" id="div_scanner_input">
<label class="col-lg-2 text-right">Juego</label>
<div class="col-lg-10">
<input type="text" id="scanner_input" name="scanner_input" class="form-control">
<label  id="lbl_scanner_input" class="control-label hide"></label>
</div>
</div>
<div class="form-group col-sm-5">
<button type="button" data-toggle="modal" data-target="#livestream_scanner" class="btn btn-warning "><i class="fa fa-barcode"></i>&nbsp;&nbsp;ESCANEAR</button> 
<button type="button" id="btn_buscar_premio" class="btn btn-primary "><i class="fa fa-search"></i>&nbsp;&nbsp;BUSCAR</button> 
</div>
</div> 
<div class="col-sm-12">
<div class="form-group" id="div_premio_nombre">
<label class="col-lg-2 control-label">Premio</label>
<div class="col-lg-10">
<input type="text" id="premio_nombre" name="premio_nombre" minlength="10" maxlength="150" class="form-control" disabled="true">
<label  id="lbl_premio_nombre" class="control-label hide"></label>
</div>  
</div>
</div>
<div class="col-sm-12">
<div class="form-group">
<img id="url_imagen_premio" width="250px" class="responsive resize" src="../img/reg.png"/>
</div>
</div>
</div>
<div class="col-sm-6">
<div class="col-sm-12">
<div class="form-group col-sm-7" id="div_scanner_inputB">
<label class="col-lg-2 text-right">Boleto</label>
<div class="col-lg-10">
<input type="text" id="scanner_inputB" name="scanner_inputB" class="form-control">
<label  id="lbl_scanner_inputB" class="control-label hide"></label>
</div>
</div>
<div class="form-group col-sm-5">
<button type="button" data-toggle="modal" data-target="#livestream_scanner" id="escannearB" class="btn btn-warning hide"><i class="fa fa-barcode"></i>&nbsp;&nbsp;ESCANEAR</button> 
<button type="button" id="btn_buscar_participante" class="btn btn-primary hide"><i class="fa fa-search"></i>&nbsp;&nbsp;BUSCAR</button> 
</div>
</div>
<div class="col-sm-12">
<div class="form-group" id="div_participante_cedula">
<label class="col-lg-2 control-label">Cédula</label>
<div class="col-lg-10">
<input type="text" id="participante_cedula" name="participante_cedula" minlength="10" maxlength="150" class="form-control" disabled="true">
<label  id="lbl_participante_cedula" class="control-label hide"></label>
</div>  
</div>
</div>
<div class="col-sm-12">
<div class="form-group" id="div_participante_email">
<label class="col-lg-2 control-label">Email</label>
<div class="col-lg-10">
<input type="text" id="participante_email" name="participante_email" minlength="10" maxlength="150" class="form-control" disabled="true">
<label  id="lbl_participante_email" class="control-label hide"></label>
</div>  
</div>
</div>
<div class="col-sm-12">
<div class="form-group" id="div_participante_celular">
<label class="col-lg-2 control-label">Celular</label>
<div class="col-lg-10">
<input type="text" id="participante_celular" name="participante_celular" minlength="10" maxlength="150" class="form-control" disabled="true">
<label  id="lbl_participante_celular" class="control-label hide"></label>
</div>  
</div>
</div>
<div class="col-sm-12">
<div class="form-group" id="div_participante_telefono">
<label class="col-lg-2 control-label">Teléfono</label>
<div class="col-lg-10">
<input type="text" id="participante_telefono" name="participante_telefono" minlength="10" maxlength="150" class="form-control" disabled="true">
<label  id="lbl_participante_telefono" class="control-label hide"></label>
</div>  
</div>
</div>
<div class="col-sm-12">
<div class="form-group" id="div_participante_nombre">
<label class="col-lg-2 control-label">Nombre</label>
<div class="col-lg-10">
<input type="text" id="participante_nombre" name="participante_nombre" minlength="10" maxlength="150" class="form-control" disabled="true">
<label  id="lbl_participante_nombre" class="control-label hide"></label>
</div>  
</div>
</div>
</div>
<div class="form-group">
<div class="col-md-12 text-right">
<button type="button" id="btn_guardar" class="btn btn-warning hide"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;GUARDAR</button>
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
<script>$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}});var tiposcan=0;$(document).ready(function(){$('#scanner_input').val("");$('#premio_id').val("");$('#premio_participante_id').val("");$('#ciclo_evento_premio_id').val("");$('#inc_pro_pre_id').val("");$('#premio_nombre').val("");$('#scanner_inputB').val("");$('#boleto_numero').val("");$('#participante_id').val("");$('#participante_boleto_det_id').val("");$('#participante_cedula').val("");$('#participante_nombre').val("");$('#participante_email').val("");$('#participante_telefono').val("");$('#participante_celular').val("");tiposcan=0;

$("#btn_buscar_participante").click(function(){
	buscarParticipante();
	console.log("btn");
});

$("#btn_buscar_premio").click(function(){
	buscarPremio();
});
function buscarPremio(){
	var p1=$("#scanner_input").val();
    $("#juego_numero").val(p1);
    $('#mensajes').removeClass('alert alert-danger');
    $('#mensajes').addClass('hide');
    $('#textoMensaje').text("");
    var p0=$("#scanner_input").val();
    var p1=$("#evento_id").val();
    var bValid=false;
    var bValid1=validarCampoLleno(p0, "Juego", "scanner_input");
    var bValid2=validarCampoLleno(p1, "Evento", "evento_id");
    bValid=bValid1&&bValid2;
    if(bValid) {
        $.ajax( {
            url:"/buscarPremioParticipante", type:"post", _token:"{{ csrf_token() }}", async:true, data: {
                scanner_input: p0, evento_id:p1
            }
            , beforeSend:function() {
                $.blockUI( {
                    message: 'PROCESANDO... Espere un momento porfavor '
                }
                )
            }
            , error:function(jqXHR, textStatus, errorThrown) {
                var gnrMensaje=$("#gnrMensaje");
                toTop();
                if(jqXHR.status=="401") {
                    gnrMensaje.text(textStatus+" - "+errorThrown+" ,Su sesión ha caducado, porfavor de click en Salir y vuelva a Ingresar.");
                    $("#gnrError").modal("show")
                }
                else {
                    $("#mensajes").addClass("alert alert-danger");
                    $("#mensajes").removeClass("hide");
                    var textoMensaje=$("#textoMensaje");
                    textoMensaje.text("ERROR: "+jqXHR.status+" - "+textStatus+" - "+errorThrown)
                }
            }
            , success:function(data) {
                $('#mensajes').removeClass('alert alert-danger');
                $('#mensajes').addClass('hide');
                $('#textoMensaje').text("");
                $('#boleto_numero').val("");
                $('#scanner_inputB').val("");
                $('#participante_id').val("");
                $('#participante_boleto_det_id').val("");
                $('#participante_cedula').val("");
                $('#participante_nombre').val("");
                $('#participante_email').val("");
                $('#participante_telefono').val("");
                $('#participante_celular').val("");
                $('#premio_id').val("");
                $('#premio_nombre').val("");
                $('#premio_participante_id').val("");
                $('#ciclo_evento_premio_id').val("");
                $('#inc_pro_pre_id').val("");
                $("#btn_guardar").addClass("hide");
                $("#btn_cancelar").addClass("hide");
                $('#escannearB').addClass('hide');
				$('#btn_buscar_participante').addClass('hide');
                if(data!='') {
                    console.log(data);
                    $("#mensajes").addClass("alert alert-info");
                    $("#mensajes").removeClass("hide");
                    var textoMensaje=$("#textoMensaje");
                    textoMensaje.text("Existe dato de Juego.");
                    $("#premio_id").val(data[0].premio.premio_id);
                    $('#premio_participante_id').val(data[0].premio_participante_id);
                    $('#ciclo_evento_premio_id').val(data[0].ciclo_evento_premio_id);
                    $('#inc_pro_pre_id').val(data[0].inc_pro_pre_id);
                    $("#premio_nombre").val(data[0].premio.premio_nombre);
                    var nom_imagen=data[1]+data[0].premio.premio_url_imagen;
                    $('#url_imagen_premio').attr("src", nom_imagen);
                    if(data[0].participante_id!=null&&data[0].participante_boleto_det_id!=null) {
                        tiposcan=0;
                        $('#participante_id').val(data[0].participante.participiante_id);
                        $('#participante_cedula').val(data[0].participante.participante_cedula);
                        $('#participante_nombre').val(data[0].participante.participante_nombre);
                        $('#participante_email').val(data[0].participante.participante_email);
                        $('#participante_telefono').val(data[0].participante.participante_telefono);
                        $('#participante_celular').val(data[0].participante.participante_celular);
                        $('#boleto_numero').val(data[0].participante_boleto_detalle.participante_boleto_det_numero_boleto);
                        $('#scanner_inputB').val(data[0].participante_boleto_detalle.participante_boleto_det_numero_boleto);
                        $('#participante_boleto_det_id').val(data[0].participante_boleto_detalle.participante_boleto_det_id)
                    }
                    else {
                        tiposcan=1;
                        $('#escannearB').removeClass('hide');
						$('#btn_buscar_participante').removeClass('hide');
                        $("#mensajes").addClass("alert alert-info");
                        $("#mensajes").removeClass("hide");
                        var textoMensaje=$("#textoMensaje");
                        textoMensaje.text("No existen datos de participante. Escanear el código del boleto.")
                    }
                }
                else {
                    tiposcan=0;
                    $("#mensajes").addClass("alert alert-info");
                    $("#mensajes").removeClass("hide");
                    var textoMensaje=$("#textoMensaje");
                    textoMensaje.text("No existen datos de premio ganado. No es posible buscar un participante.")
                }
            }
            , complete:function() {
                setTimeout($.unblockUI, 1000)
            }
        }
        )
    }
}

function buscarParticipante(){

var p1=$("#scanner_inputB").val();
    $("#boleto_numero").val(p1);
    $('#mensajes').removeClass('alert alert-danger');
    $('#mensajes').addClass('hide');
    $('#textoMensaje').text("");
    var p0=$("#scanner_inputB").val();
    var p1=$("#evento_id").val();
    var bValid=false;
    var bValid1=validarCampoLleno(p0, "Boleto", "scanner_inputB");
    var bValid2=validarCampoLleno(p1, "Evento", "evento_id");
    bValid=bValid1&&bValid2;
	console.log("enviar cnsulta");
    if(bValid) {
		
        $.ajax( {
            url:"/buscarParticipanteBoletoDetalle", type:"post", _token:"{{ csrf_token() }}", async:true, data: {
                scanner_inputB: p0, evento_id:p1
            }
            , beforeSend:function() {
                $.blockUI( {
                    message: 'PROCESANDO... Espere un momento porfavor '
                }
                )
            }
            , error:function(jqXHR, textStatus, errorThrown) {
                var gnrMensaje=$("#gnrMensaje");
                toTop();
                if(jqXHR.status=="401") {
                    gnrMensaje.text(textStatus+" - "+errorThrown+" ,Su sesión ha caducado, porfavor de click en Salir y vuelva a Ingresar.");
                    $("#gnrError").modal("show")
                }
                else {
                    $("#mensajes").addClass("alert alert-danger");
                    $("#mensajes").removeClass("hide");
                    var textoMensaje=$("#textoMensaje");
                    textoMensaje.text("ERROR: "+jqXHR.status+" - "+textStatus+" - "+errorThrown)
                }
            }
            , success:function(data) {
                tiposcan=1;
				console.log(data);
                $('#mensajes').removeClass('alert alert-danger');
                $('#mensajes').addClass('hide');
                $('#textoMensaje').text("");
                $('#boleto_numero').val("");
                $('#participante_id').val("");
                $('#participante_boleto_det_id').val("");
                $('#participante_cedula').val("");
                $('#participante_nombre').val("");
                $('#participante_email').val("");
                $('#participante_telefono').val("");
                $('#participante_celular').val("");
                $("#btn_guardar").addClass("hide");
                $("#btn_cancelar").addClass("hide");
                if(data!='') {
                    $("#mensajes").addClass("alert alert-info");
                    $("#mensajes").removeClass("hide");
                    var textoMensaje=$("#textoMensaje");
                    textoMensaje.text("Existe dato de Participante.");
                    $('#participante_id').val(data.participante.participiante_id);
                    $('#participante_cedula').val(data.participante.participante_cedula);
                    $('#participante_nombre').val(data.participante.participante_nombre);
                    $('#participante_email').val(data.participante.participante_email);
                    $('#participante_telefono').val(data.participante.participante_telefono);
                    $('#participante_celular').val(data.participante.participante_celular);
                    $('#boleto_numero').val(data.participante_boleto_det_numero_boleto);
                    $('#scanner_inputB').val(data.participante_boleto_det_numero_boleto);
                    $('#participante_boleto_det_id').val(data.participante_boleto_det_id);
                    $("#btn_guardar").removeClass("hide");
                    $("#btn_cancelar").removeClass("hide")
                }
                else {
                    $("#mensajes").addClass("alert alert-info");
                    $("#mensajes").removeClass("hide");
                    var textoMensaje=$("#textoMensaje");
                    textoMensaje.text("No existen datos de participante relacionado con este boleto.")
                }
            }
            , complete:function() {
                setTimeout($.unblockUI, 1000)
            }
        }
        )
    }

}





});$(function(){$('#livestream_scanner').on('shown.bs.modal',function(e){console.log(tiposcan);if(tiposcan==0){var liveStreamConfig={inputStream:{type:"LiveStream",constraints:{width:{min:640},height:{min:480},aspectRatio:{min:1,max:100},facingMode:"environment"}},locator:{patchSize:"medium",halfSample:true},numOfWorkers:(navigator.hardwareConcurrency?navigator.hardwareConcurrency:4),decoder:{"readers":[{"format":"code_39_reader","config":{supplements:['code_128_reader','code_93_reader']}}]},locate:true};var fileConfig=$.extend({},liveStreamConfig,{inputStream:{size:800}});Quagga.init(liveStreamConfig,function(err){if(err){$('#livestream_scanner .modal-body .error').html('<div class="alert alert-danger"><strong><i class="fa fa-exclamation-triangle"></i> '+err.name+'</strong>: '+err.message+'</div>');Quagga.stop();return}Quagga.start()})}else{var liveStreamConfig={inputStream:{type:"LiveStream",constraints:{width:{min:640},height:{min:480},aspectRatio:{min:1,max:100},facingMode:"environment"}},locator:{patchSize:"medium",halfSample:true},numOfWorkers:(navigator.hardwareConcurrency?navigator.hardwareConcurrency:4),decoder:{"readers":[{"format":"code_128_reader","config":{supplements:['code_39_reader','code_93_reader']}}]},locate:true};var fileConfig=$.extend({},liveStreamConfig,{inputStream:{size:800}});Quagga.init(liveStreamConfig,function(err){if(err){$('#livestream_scanner .modal-body .error').html('<div class="alert alert-danger"><strong><i class="fa fa-exclamation-triangle"></i> '+err.name+'</strong>: '+err.message+'</div>');Quagga.stop();return}Quagga.start()})}});Quagga.onProcessed(function(result){var drawingCtx=Quagga.canvas.ctx.overlay,drawingCanvas=Quagga.canvas.dom.overlay;if(result){if(result.boxes){drawingCtx.clearRect(0,0,parseInt(drawingCanvas.getAttribute("width")),parseInt(drawingCanvas.getAttribute("height")));result.boxes.filter(function(box){return box!==result.box}).forEach(function(box){Quagga.ImageDebug.drawPath(box,{x:0,y:1},drawingCtx,{color:"green",lineWidth:2})})}if(result.box){Quagga.ImageDebug.drawPath(result.box,{x:0,y:1},drawingCtx,{color:"#00F",lineWidth:2})}if(result.codeResult&&result.codeResult.code){Quagga.ImageDebug.drawPath(result.line,{x:'x',y:'y'},drawingCtx,{color:'red',lineWidth:3})}}});Quagga.onDetected(function(result){if(result.codeResult.code){$('#mensajes').removeClass('alert alert-danger');$('#mensajes').addClass('hide');$('#textoMensaje').text("");$('#boleto_numero').val("");$('#participante_id').val("");$('#participante_boleto_det_id').val("");$('#participante_cedula').val("");$('#participante_nombre').val("");$('#participante_email').val("");$('#participante_telefono').val("");$('#participante_celular').val("");if(tiposcan==0){$('#premio_id').val("");$('#premio_nombre').val("");$('#premio_participante_id').val("");$('#ciclo_evento_premio_id').val("");$('#inc_pro_pre_id').val("");$('#scanner_input').val("");$("#btn_guardar").addClass("hide");$("#btn_cancelar").addClass("hide");$('#escannearB').addClass('hide');$('#btn_buscar_participante').addClass('hide');$('#scanner_input').val(result.codeResult.code);$('#juego_numero').val(result.codeResult.code);Quagga.stop();$('#scanner_input').change()}else{$('#scanner_inputB').val(result.codeResult.code);$('#boleto_numero').val(result.codeResult.code);Quagga.stop();$('#scanner_inputB').change()}setTimeout(function(){$('#livestream_scanner').modal('hide')},1000)}});$('#livestream_scanner').on('hide.bs.modal',function(){if(Quagga){Quagga.stop()}});$("#livestream_scanner input:file").on("change",function(e){if(e.target.files&&e.target.files.length){Quagga.decodeSingle($.extend({},fileConfig,{src:URL.createObjectURL(e.target.files[0])}),function(result){alert(result.codeResult.code)})}})});$("#btn_cancelar").click(function(){location.reload()});$("#scanner_input").on("change",function(){var p1=$("#scanner_input").val();$("#juego_numero").val(p1);$('#mensajes').removeClass('alert alert-danger');$('#mensajes').addClass('hide');$('#textoMensaje').text("");var p0=$("#scanner_input").val();var p1=$("#evento_id").val();var bValid=false;var bValid1=validarCampoLleno(p0,"Juego","scanner_input");var bValid2=validarCampoLleno(p1,"Evento","evento_id");bValid=bValid1&&bValid2;if(bValid){$.ajax({url:"/buscarPremioParticipante",type:"post",_token:"{{ csrf_token() }}",async:true,data:{scanner_input:p0,evento_id:p1},beforeSend:function(){$.blockUI({message:'<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento porfavor </h4>'})},error:function(jqXHR,textStatus,errorThrown){var gnrMensaje=$("#gnrMensaje");toTop();if(jqXHR.status=="401"){gnrMensaje.text(textStatus+" - "+errorThrown+" ,Su sesión ha caducado, porfavor de click en Salir y vuelva a Ingresar.");$("#gnrError").modal("show")}else{$("#mensajes").addClass("alert alert-danger");$("#mensajes").removeClass("hide");var textoMensaje=$("#textoMensaje");textoMensaje.text("ERROR: "+jqXHR.status+" - "+textStatus+" - "+errorThrown)}},success:function(data){$('#mensajes').removeClass('alert alert-danger');$('#mensajes').addClass('hide');$('#textoMensaje').text("");$('#boleto_numero').val("");$('#scanner_inputB').val("");$('#participante_id').val("");$('#participante_boleto_det_id').val("");$('#participante_cedula').val("");$('#participante_nombre').val("");$('#participante_email').val("");$('#participante_telefono').val("");$('#participante_celular').val("");$('#premio_id').val("");$('#premio_nombre').val("");$('#premio_participante_id').val("");$('#ciclo_evento_premio_id').val("");$('#inc_pro_pre_id').val("");$("#btn_guardar").addClass("hide");$("#btn_cancelar").addClass("hide");$('#escannearB').addClass('hide');$('#btn_buscar_participante').addClass('hide');if(data!=''){console.log(data);$("#mensajes").addClass("alert alert-info");$("#mensajes").removeClass("hide");var textoMensaje=$("#textoMensaje");textoMensaje.text("Existe dato de Juego.");$("#premio_id").val(data[0].premio.premio_id);$('#premio_participante_id').val(data[0].premio_participante_id);$('#ciclo_evento_premio_id').val(data[0].ciclo_evento_premio_id);$('#inc_pro_pre_id').val(data[0].inc_pro_pre_id);$("#premio_nombre").val(data[0].premio.premio_nombre);var nom_imagen=data[1]+data[0].premio.premio_url_imagen;$('#url_imagen_premio').attr("src",nom_imagen);if(data[0].participante_id!=null&&data[0].participante!=null&&data[0].participante_boleto_det_id!=null){
	console.log(data[0]);
	tiposcan=0;$('#participante_id').val(data[0].participante.participiante_id);$('#participante_cedula').val(data[0].participante.participante_cedula);$('#participante_nombre').val(data[0].participante.participante_nombre);$('#participante_email').val(data[0].participante.participante_email);$('#participante_telefono').val(data[0].participante.participante_telefono);$('#participante_celular').val(data[0].participante.participante_celular);$('#boleto_numero').val(data[0].participante_boleto_detalle.participante_boleto_det_numero_boleto);$('#scanner_inputB').val(data[0].participante_boleto_detalle.participante_boleto_det_numero_boleto);$('#participante_boleto_det_id').val(data[0].participante_boleto_detalle.participante_boleto_det_id)
	}else{tiposcan=1;$('#escannearB').removeClass('hide');$('#btn_buscar_participante').removeClass('hide');$("#mensajes").addClass("alert alert-info");$("#mensajes").removeClass("hide");var textoMensaje=$("#textoMensaje");textoMensaje.text("No existen datos de participante. Escanear el código del boleto.")}}else{tiposcan=0;$("#mensajes").addClass("alert alert-info");$("#mensajes").removeClass("hide");var textoMensaje=$("#textoMensaje");textoMensaje.text("No existen datos de premio ganado. No es posible buscar un participante.")}},complete:function(){setTimeout($.unblockUI,1000)}})}});$("#scanner_inputB").on("change",function(){var p1=$("#scanner_inputB").val();$("#boleto_numero").val(p1);$('#mensajes').removeClass('alert alert-danger');$('#mensajes').addClass('hide');$('#textoMensaje').text("");var p0=$("#scanner_inputB").val();var p1=$("#evento_id").val();var bValid=false;var bValid1=validarCampoLleno(p0,"Boleto","scanner_inputB");var bValid2=validarCampoLleno(p1,"Evento","evento_id");bValid=bValid1&&bValid2;if(bValid){$.ajax({url:"/buscarParticipanteBoletoDetalle",type:"post",_token:"{{ csrf_token() }}",async:true,data:{scanner_inputB:p0,evento_id:p1},beforeSend:function(){$.blockUI({message:'<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento porfavor </h4>'})},error:function(jqXHR,textStatus,errorThrown){var gnrMensaje=$("#gnrMensaje");toTop();if(jqXHR.status=="401"){gnrMensaje.text(textStatus+" - "+errorThrown+" ,Su sesión ha caducado, porfavor de click en Salir y vuelva a Ingresar.");$("#gnrError").modal("show")}else{$("#mensajes").addClass("alert alert-danger");$("#mensajes").removeClass("hide");var textoMensaje=$("#textoMensaje");textoMensaje.text("ERROR: "+jqXHR.status+" - "+textStatus+" - "+errorThrown)}},success:function(data){tiposcan=1;$('#mensajes').removeClass('alert alert-danger');$('#mensajes').addClass('hide');$('#textoMensaje').text("");$('#boleto_numero').val("");$('#participante_id').val("");$('#participante_boleto_det_id').val("");$('#participante_cedula').val("");$('#participante_nombre').val("");$('#participante_email').val("");$('#participante_telefono').val("");$('#participante_celular').val("");$("#btn_guardar").addClass("hide");$("#btn_cancelar").addClass("hide");if(data!=''){$("#mensajes").addClass("alert alert-info");$("#mensajes").removeClass("hide");var textoMensaje=$("#textoMensaje");textoMensaje.text("Existe dato de Participante.");$('#participante_id').val(data.participante.participiante_id);$('#participante_cedula').val(data.participante.participante_cedula);$('#participante_nombre').val(data.participante.participante_nombre);$('#participante_email').val(data.participante.participante_email);$('#participante_telefono').val(data.participante.participante_telefono);$('#participante_celular').val(data.participante.participante_celular);$('#boleto_numero').val(data.participante_boleto_det_numero_boleto);$('#scanner_inputB').val(data.participante_boleto_det_numero_boleto);$('#participante_boleto_det_id').val(data.participante_boleto_det_id);$("#btn_guardar").removeClass("hide");$("#btn_cancelar").removeClass("hide")}else{$("#mensajes").addClass("alert alert-info");$("#mensajes").removeClass("hide");var textoMensaje=$("#textoMensaje");textoMensaje.text("No existen datos de participante relacionado con este boleto.")}},complete:function(){setTimeout($.unblockUI,1000)}})}});$("#btn_guardar").click(function(){var p1=$("#evento_id").val(),p2=$("#juego_numero").val(),p3=$("#premio_id").val(),p4=$("#premio_participante_id").val(),p5=$("#ciclo_evento_premio_id").val(),p6=$("#inc_pro_pre_id").val(),p7=$("#boleto_numero").val(),p8=$("#participante_id").val(),p9=$("#participante_boleto_det_id").val();console.log(p3);console.log(p8);console.log(p2);console.log(p9);$('#mensajes').removeClass('alert alert-danger');$('#mensajes').addClass('hide');$('#textoMensaje').text("");var bValid=false;var bValid1=validarCampoLleno(p1,"Evento","evento_id");var bValid2=validarCampoLleno(p2,"Juego","juego_numero");var bValid3=validarCampoLleno(p3,"Premio","premio_id");var bValid4=validarCampoLleno(p4,"Premio Participante","premio_participante_id");bValid=bValid1&&bValid2&&bValid3&&bValid4;if(bValid){$.ajax({url:'/validarPremioParticipante',type:'post',"_token":"{{ csrf_token() }}",async:true,data:{'evento_id':p1,'juego_numero':p2,'premio_id':p3,'premio_participante_id':p4,'ciclo_evento_premio_id':p5,'inc_pro_pre_id':p6,'boleto_numero':p7,'participante_id':p8,'participante_boleto_det_id':p9},beforeSend:function(){$.blockUI({message:'<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'})},error:function(jqXHR,textStatus,errorThrown){var gnrMensaje=$("#gnrMensaje");toTop();if(jqXHR.status=='401'){gnrMensaje.text(textStatus+" - "+errorThrown+" ,Su sesión ha caducado, por favor de click en Salir y vuelva a Ingresar.");$('#gnrError').modal('show')}else{$('#mensajes').addClass('alert alert-danger');$('#mensajes').removeClass('hide');var textoMensaje=$("#textoMensaje");textoMensaje.text("ERROR: "+jqXHR.status+" - "+textStatus+" - "+errorThrown)}},success:function(j){if(j!==""){$("#mensajes").addClass("alert alert-danger");$("#mensajes").removeClass("hide");$("#textoMensaje").text("");for(var h in j){$("#textoMensaje").append(j[h]+"<br/>")}}else{$("#formRegistroPremioParticipante").submit()}},complete:function(){setTimeout($.unblockUI,1000)}})}});</script>
@endsection
