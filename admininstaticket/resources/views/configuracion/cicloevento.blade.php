@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="../plugins/select2/select2.min.css">
@endsection
@section('htmlheader_title')
Ciclo Evento
@endsection
@section('header')
<h1>
    <i class="fa fa-history"></i>   <i class="fa fa-picture-o"></i>   CICLO EVENTO
</h1>
<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Configuración</a></li>
    <li class="active">Ciclo Evento</li>
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
                <form class="form-horizontal" action="/guardarCicloEvento" method="post" id="formCicloEvento">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="ciclo_evento_id" id="ciclo_evento_id">
                    <div class="box-body">
                        <br/>
                        <div class="col-sm-6">
                            <div class="form-group" id="div_ciclo_id">
                                <label  class="col-lg-2">Ciclo</label>
                                <div class="col-lg-10">
                                    <select id="ciclo_id" name="ciclo_id" class="form-control select2" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                        @foreach($lstCiclo as $lstCicloAc)
                                        <option value="{{$lstCicloAc->ciclo_id}}"> {{$lstCicloAc->ciclo_nombre}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <label  id="lbl_ciclo_id" class=" control-label hide"></label>
                            </div>
                            <div class="form-group" id="div_total_premios">
                                <label class="col-lg-2 control-label">Tot. Premios</label>
                                <div class="col-lg-10">
                                    <input type="number" id="total_premios" name="total_premios" minlength="1" class="form-control">
                                    <label  id="lbl_total_premios" class="control-label hide"></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group" id="div_evento_id">
                                <label  class="col-lg-2">Evento</label>
                                <div class="col-lg-10">
                                    <select id="evento_id" name="evento_id" class="form-control select2" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                        @foreach($lstEventos as $lstEventosAc)
                                        <option value="{{$lstEventosAc->evento_id}}"> {{$lstEventosAc->evento_nombre}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <label  id="lbl_v" class=" control-label hide"></label>
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
                                <label  class="col-lg-2 control-label">Juego</label>
                                <div class="col-lg-10 radio">
                                    <label> <input id="j_puerta" type="checkbox" name="j_puerta"  value="S" />Juego Puerta</label>
                                    <label> <input id="j_carta" type="checkbox" name="j_carta"  value="S" />Juego Carta</label>
                                    <label id="lbl_juegos" class="control-label hide"></label>
                                </div>
                            </div>
							<div class="form-group" id="div_total_turnos">
                                <label class="col-lg-2 control-label">Turnos</label>
                                <div class="col-lg-10">
								
								 <select id="total_turnos" name="total_turnos" class="form-control select2" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                        <option value="3"> 3 </option>
<option value="5"> 5</option>
                                    </select>
								
								
                                   <!-- <input type="number" id="total_turnos" name="total_turnos" minlength="3" class="form-control">
                                    <label  id="lbl_total_turnos" class="control-label hide"></label>-->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12 text-center">
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
                                    <th>CICLO</th>
                                    <th>EVENTO</th>
                                    <th>TOT. PREMIOS</th>
                                    <th>J. PUERTA</th>
                                    <th>J. CARTA</th>
									<th>J.C TURNOS</th>
                                    <th>ESTADO</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($lstCiclosEventos)
                                @foreach($lstCiclosEventos as $c)
                                <tr data-id="{{$c}}">
                                    <td>{{$c->ciclo->ciclo_nombre}} </td>
                                    <td>{{$c->evento->evento_nombre}} </td>
                                    <td>{{$c->total_premios}}</td>
                                    <td>{{$c->juego_puerta}}</td>
                                    <td>{{$c->juego_carta}}</td>
									<td>{{$c->turnos}}</td>
                                    <td>@if($c->estado!=null){{$c->estado->estado_nombre}}@else{{$c->estado}}@endif</td>
                                    <td style="text-align: center">
                                        <button type="button" class="btn btn-warning btn-sm btn-C"  title="Editar"><i class="fa fa-pencil"></i></button>
                                        @if($c->logCicloEvento->count() > 0)
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
<script>
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    }
});
$(document).ready(function () {
    $('#tbl_C').DataTable({})
});
$('.select2').select2({
    placeholder: 'Seleccione una opción'
});
$("#btn_cancelar").click(function () {
    location.reload()
});
$("#btn_guardar").click(function () {
    var p1 = $("#ciclo_id").val(),
            p2 = $("#evento_id").val(),
            p3 = $("#total_premios").val(),
            p4 = $("input:radio[name=estado_id]:checked").val(),
			p6 = $("#total_turnos").val();
    $('#mensajes').removeClass('alert alert-danger');
    $('#mensajes').addClass('hide');
    $('#textoMensaje').text("");
    var bValid = false;
    var bValid1 = validarCampoLleno(p1, "Ciclo", "ciclo_id");
    var bValid2 = validarCampoLleno(p2, "Evento", "evento_id");
    var bValid3 = validarCampoLleno(p3, "Tot. Premios", "total_premios");
    var bValid4 = validarCampoLleno(p4, "Estado", "estado_id");
    var bValid5 = $("#j_puerta").is(':checked') || $("#j_carta").is(':checked');
	var bValid6 = validarCampoLleno(p6, "Turnos", "total_turnos");
    if (!bValid5) {
        validarCampoLleno(null, "Juegos", "juegos");
    }

    bValid = bValid1 && bValid2 && bValid3 && bValid4 && bValid5 && bValid6;
    if (bValid) {
        $.ajax({
            url: '/validarCicloEvento',
            type: 'post',
            "_token": "{{ csrf_token() }}",
            async: true,
            data: {
                'ciclo_evento_id': null,
                'ciclo_id': p1,
                'evento_id': p2,
                'total_premios': p3,
                'estado_id': p4,
				'turnos':p6
            },
            beforeSend: function () {
                $.blockUI({
                    message: '<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'
                })
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var gnrMensaje = $("#gnrMensaje");
                toTop();
                if (jqXHR.status == '401') {
                    gnrMensaje.text(textStatus + " - " + errorThrown + " ,Su sesión ha caducado, por favor de click en Salir y vuelva a Ingresar.");
                    $('#gnrError').modal('show')
                } else {
                    $('#mensajes').addClass('alert alert-danger');
                    $('#mensajes').removeClass('hide');
                    var textoMensaje = $("#textoMensaje");
                    textoMensaje.text("ERROR: " + jqXHR.status + " - " + textStatus + " - " + errorThrown)
                }
            },
            success: function (j) {
                if (j !== "") {
                    $("#mensajes").addClass("alert alert-danger");
                    $("#mensajes").removeClass("hide");
                    $("#textoMensaje").text("");
                    for (var h in j) {
                        $("#textoMensaje").append(j[h] + "<br/>")
                    }
                } else {
                    $("#formCicloEvento").submit()
                }
            },
            complete: function () {
                setTimeout($.unblockUI, 1000)
            }
        })
    }
});
$(".btn-Log_C").click(function () {
    var fila = $(this).parents("tr");
    var cadena = JSON.stringify(fila.data("id"));
    var obj = eval("(" + cadena + ")");
    var log = obj.log_ciclo_evento;
    var t = $('#log').DataTable();
    t.clear();
    for (var i = 0; i < log.length; i++) {
        t.row.add([log[i].registro_anterior, log[i].registro_nuevo, log[i].tipo_accion, log[i].fecha_ingresa]).draw(false)
    }
    $('#myModal').modal('show')
});
$(".btn-C").click(function () {
    var fila = $(this).parents("tr");
    var cadena = JSON.stringify(fila.data("id"));
    var obj = eval("(" + cadena + ")");
    $('#mensajes').removeClass('alert alert-danger');
    $('#mensajes').addClass('hide');
    $('#textoMensaje').text("");
    $.ajax({
        url: "/buscarCicloEvento",
        type: "post",
        _token: "{{ csrf_token() }}",
        async: true,
        data: {
            ciclo_evento_id: obj.ciclo_evento_id
        },
        beforeSend: function () {
            $.blockUI({
                message: '<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento porfavor </h4>'
            })
        },
        error: function (jqXHR, textStatus, errorThrown) {
            var gnrMensaje = $("#gnrMensaje");
            toTop();
            if (jqXHR.status == "401") {
                gnrMensaje.text(textStatus + " - " + errorThrown + " ,Su sesión ha caducado, porfavor de click en Salir y vuelva a Ingresar.");
                $("#gnrError").modal("show")
            } else {
                $("#mensajes").addClass("alert alert-danger");
                $("#mensajes").removeClass("hide");
                var textoMensaje = $("#textoMensaje");
                textoMensaje.text("ERROR: " + jqXHR.status + " - " + textStatus + " - " + errorThrown)
            }
        },
        success: function (data) {
            if (data != '') {
                validarCampoLleno(data.ciclo_id, "Ciclo", "ciclo_id");
                validarCampoLleno(data.evento_id, "Evento", "evento_id");
                validarCampoLleno(data.estado_id, "Estado", "estado_id");
                validarCampoLleno(data.total_premios, "Total de Premios", "total_premios");
                $("#ciclo_evento_id").val(data.ciclo_evento_id);
                $("#ciclo_id").val(data.ciclo_id).change();
                $("#evento_id").val(data.evento_id).change();
                $("#total_premios").val(data.total_premios);
				$("#total_turnos").val(data.turnos).change();
                if(data.juego_puerta=="S"){
                $("#j_puerta").prop("checked",true);
            }else{
                $("#j_puerta").prop("checked",false);
            }
            if(data.juego_carta=="S"){
                $("#j_carta").prop("checked",true);
            }else{
                $("#j_carta").prop("checked",false);
            }
                $('input:radio[name=estado_id][value=' + data.estado_id + ']').attr('checked', true);
                $("#btn_actualizar").removeClass("hide");
                $("#btn_guardar").addClass("hide");
                $("#enlace").click()
            }
        },
        complete: function () {
            setTimeout($.unblockUI, 1000)
        }
    })
});
$("#btn_actualizar").click(function () {
    var p0 = $("#ciclo_evento_id").val(),
            p1 = $("#ciclo_id").val(),
            p2 = $("#evento_id").val(),
            p3 = $("#total_premios").val(),
            p4 = $("input:radio[name=estado_id]:checked").val(),
			p6 = $("#total_turnos").val();
    $('#mensajes').removeClass('alert alert-danger');
    $('#mensajes').addClass('hide');
    $('#textoMensaje').text("");
    var bValid = false;
    var bValid1 = validarCampoLleno(p1, "Ciclo", "ciclo_id");
    var bValid2 = validarCampoLleno(p2, "Evento", "evento_id");
    var bValid3 = validarCampoLleno(p3, "total_premios", "total_premios");
    var bValid4 = validarCampoLleno(p4, "Estado", "estado_id");
    var bValid5 = $("#j_puerta").is(':checked') || $("#j_carta").is(':checked');
	var bValid6 = validarCampoLleno(p6, "Turnos", "total_turnos");
    if (!bValid5) {
        validarCampoLleno(null, "Juegos", "juegos");
    }
    bValid = bValid1 && bValid2 && bValid3 && bValid4 && bValid5 && bValid6;
    if (bValid) {
        $.ajax({
            url: '/validarCicloEvento',
            type: 'post',
            "_token": "{{ csrf_token() }}",
            async: true,
            data: {
                'ciclo_evento_id': p0,
                'ciclo_id': p1,
                'evento_id': p2,
                'total_premios': p3,
                'estado_id': p4,
				'turnos':p6
            },
            beforeSend: function () {
                $.blockUI({
                    message: '<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'
                })
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var gnrMensaje = $("#gnrMensaje");
                toTop();
                if (jqXHR.status == '401') {
                    gnrMensaje.text(textStatus + " - " + errorThrown + " ,Su sesión ha caducado, por favor de click en Salir y vuelva a Ingresar.");
                    $('#gnrError').modal('show')
                } else {
                    $('#mensajes').addClass('alert alert-danger');
                    $('#mensajes').removeClass('hide');
                    var textoMensaje = $("#textoMensaje");
                    textoMensaje.text("ERROR: " + jqXHR.status + " - " + textStatus + " - " + errorThrown)
                }
            },
            success: function (j) {
                if (j !== "") {
                    $("#mensajes").addClass("alert alert-danger");
                    $("#mensajes").removeClass("hide");
                    $("#textoMensaje").text("");
                    for (var h in j) {
                        $("#textoMensaje").append(j[h] + "<br/>")
                    }
                } else {
                    $("#formCicloEvento").attr("action", "/actualizarCicloEvento");
                    $("#formCicloEvento").submit()
                }
            },
            complete: function () {
                setTimeout($.unblockUI, 1000)
            }
        })
    }
});
</script>
@endsection
