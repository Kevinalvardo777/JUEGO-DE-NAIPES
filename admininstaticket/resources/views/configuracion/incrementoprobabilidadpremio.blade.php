@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="../plugins/select2/select2.min.css">
<link rel="stylesheet" href="../plugins/timepicker/bootstrap-timepicker.min.css">
<link rel="stylesheet" href="../plugins/datetimepicker/bootstrap-datetimepicker.min.css">
@endsection
@section('header')
<h1>
<i class="fa fa-calculator"></i>   <i class="fa fa-gift"></i>  INCREMENTO PROBABILIDAD PREMIO
</h1>
<ol class="breadcrumb">
<li><a href="#"><i class="fa fa-dashboard"></i> Configuración</a></li>
<li class="active">Incremento Probabilidad Premio</li>
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
<form class="form-horizontal" action="/guardarIncrementoProbabilidadPremio" method="post" id="formIncrementoProbabilidadPremio" enctype="multipart/form-data">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="inc_pro_pre_id" id="inc_pro_pre_id">
<div class="box-body">
<br/>
<div class="col-sm-6">
<div class="form-group" id="div_inc_pro_pre_nombre">
<label class="col-lg-2 control-label">Nombre</label>
<div class="col-lg-10">
<input type="text" id="inc_pro_pre_nombre" name="inc_pro_pre_nombre" minlength="3" maxlength="45" class="form-control">
<label  id="lbl_inc_pro_pre_nombre" class="control-label hide"></label>
</div>
</div>
<div class="form-group" id="div_inc_pro_pre_descripcion">
<label class="col-lg-2  control-label">Descripción</label>
<div class="col-lg-10">
<textarea style="resize:none;"  min="1" rows="2" id="inc_pro_pre_descripcion" name="inc_pro_pre_descripcion"  minlength="1" maxlength="250"  class="form-control"></textarea>
<label  id="lbl_inc_pro_pre_descripcion" class=" control-label hide"></label>
</div>
</div>
<div class="form-group" id="div_inc_pro_pre_fecha_incremento">
<label class="col-lg-2">Fecha</label>
<div class="col-lg-10">
<div class="input-group date" data-provide="datepicker">
<input type="text" class="form-control datepicker" id="inc_pro_pre_fecha_incremento" name="inc_pro_pre_fecha_incremento">
<div class="input-group-addon">
<span class="fa fa-calendar"></span>
</div>
</div>
<label  id="lbl_inc_pro_pre_fecha_incremento" class=" control-label hide"></label>
</div>
</div>
<div  class="form-group" id="div_inc_pro_pre_hora_inicio_incremento">
<label class="col-lg-2">Hora Inicio</label>
<div class="col-lg-10">
<div class="input-group">
<input type="text" id="inc_pro_pre_hora_inicio_incremento" name="inc_pro_pre_hora_inicio_incremento" class="form-control timepicker">
<div class="input-group-addon">
<i class="fa fa-clock-o"></i>
</div>
</div>
<label  id="lbl_inc_pro_pre_hora_inicio_incremento" class=" control-label hide"></label>
</div>
</div>
<div class="form-group" id="div_inc_pro_pre_hora_fin_incremento">
<label class="col-lg-2 control-label">Hora Fin</label>
<div class="col-lg-10">
<div class="input-group">
<input type="text" id="inc_pro_pre_hora_fin_incremento" name="inc_pro_pre_hora_fin_incremento" class="form-control timepicker">
<div class="input-group-addon">
<i class="fa fa-clock-o"></i>
</div>
</div>
<label  id="lbl_inc_pro_pre_hora_fin_incremento" class=" control-label hide"></label>
</div>
</div>
</div>
<div class="col-sm-6">
<div class="form-group" id="div_inc_pro_pre_cantidad">
<label class="col-lg-2" control-label>Cantidad</label>
<div class="col-lg-10">
<input type="number" id="inc_pro_pre_cantidad" name="inc_pro_pre_cantidad" minlength="1" maxlength="5"  class="form-control">
<label  id="lbl_inc_pro_pre_cantidad" class="control-label hide"></label>
</div>
</div>
<div class="form-group" id="div_categoria_id">
<label class="col-lg-2  control-label">Categoría</label>
<div class="col-lg-10">
<select id="categoria_id" name="categoria_id" class="form-control select2 " style="width: 100%;" tabindex="-1" aria-hidden="true">
@foreach($lstCategorias as $p)
@if($p->estado_id == $estadoActivo)
<option value="{{$p->categoria_id}}">{{$p->categoria_nombre}} : Probabilidad {{$p->categoria_porcentaje_probabilidad}} %</option>
@else
<option disabled="disabled" title="Categoría {{$p->categoria_nombre}},no puede ser utilizada porque se encuentra inactiva." value="{{$p->categoria_id}}">{{$p->nombre}} : Probabilidad {{$p->probabilidad}} %</option>
@endif
@endforeach
</select>
<label  id="lbl_categoria_id" class=" control-label hide"></label>
</div>
</div>
<div  class="form-group" id="div_inc_pro_pre_porcentaje_probabilidad">
<label class="col-lg-2">Probabilidad</label>
<div class="col-lg-10">
<div class="input-group">
<input type="number" id="inc_pro_pre_porcentaje_probabilidad" name="inc_pro_pre_porcentaje_probabilidad" class="form-control">
<div class="input-group-addon">
<i >%</i>
</div>
</div>
<label  id="lbl_inc_pro_pre_porcentaje_probabilidad" class=" control-label hide"></label>
</div>
</div>
<div class="form-group" id="div_ciclo_evento_premio_id">
<label class="col-lg-2  control-label">Ciclo Evento Premio</label>
<div class="col-lg-10">
<select id="ciclo_evento_premio_id" name="ciclo_evento_premio_id" class="form-control select2 " style="width: 100%;" tabindex="-1" aria-hidden="true">
@foreach($lstCicloEventoPremio as $t)
@if($t->estado_id == $estadoActivo)
<option value="{{$t->ciclo_evento_premio_id}}">{{$t->cicloEvento->ciclo->ciclo_nombre}} - {{$t->cicloEvento->evento->evento_nombre}} - {{$t->premio->premio_nombre}}</option>
@else
<option disabled="disabled" title="Ciclo Evvento Premio {{$t->cicloEvento->ciclo->ciclo_nombre}} , no puede ser utilizado porque se encuentra inactiva." value="{{$p->evento_id}}">{{$p->evento_nombre}}</option>
@endif
@endforeach
</select>
<label  id="lbl_ciclo_evento_premio_id" class=" control-label hide"></label>
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
<th>CANT.</th>
<th>%</th>
<th>CATEGORÍA</th>
<th>EVENTO</th>
<th>PREMIO</th>
<th>F. INCENTIVO</th>
<th>IMAGEN</th>
<th>ESTADO</th>
<th></th>
</tr>
</thead>
<tbody>
@if($lstIncrementoProbabilidadesPremios)
@foreach($lstIncrementoProbabilidadesPremios as $c)
<tr data-id="{{$c}}">
<td>{{$c->inc_pro_pre_nombre}} </td>
<td>{{$c->inc_pro_pre_cantidad}} </td>
<td>{{$c->inc_pro_pre_porcentaje_probabilidad}} % </td>
<td>@if($c->categoria!=null){{$c->categoria->categoria_nombre}}  @else{{$c->categoria}}@endif</td>
<td>@if($c->evento!=null){{$c->evento->evento_nombre}}  @else{{$c->evento}}@endif</td>
<td>@if($c->premio!=null){{$c->premio->premio_nombre}}  @else{{$c->premio}}@endif</td>
<td>{{$c->inc_pro_pre_fecha_incremento}} </td>
<td><img width="100px" height="100px" class="" src="{{$objUrlRecursos->parametro_general_valor}}{{$c->inc_pro_pre_url_imagen}}"/></td>
<td>@if($c->estado!=null){{$c->estado->estado_nombre}}@else{{$c->estado}}@endif</td>
<td style="text-align: center">
<button type="button" class="btn btn-warning btn-sm btn-C"  title="Editar"><i class="fa fa-pencil"></i></button>
@if($c->logIncrementoProbabilidadPremio->count() > 0)
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
<script src="../plugins/daterangepicker/moment.min.js"></script>
<script src="../plugins/datetimepicker/bootstrap-datetimepicker.js"></script>
<script src="../plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });
    $(document).ready(function() {
        $('#tbl_C').DataTable({});
    });
    $('#inc_pro_pre_fecha_incremento').datetimepicker({
        format: "YYYY-MM-DD"
    });
    $(".timepicker").timepicker({
        showInputs: false,
        showSeconds: true,
        minuteStep: 1,
        secondStep: 1,
        showMeridian: false,
        interval: 1,
        defaultTime: false
    });
    $("#btn_cancelar").click(function() {
        location.reload();
    });
    var exists = false;
    $("#btn_guardar").click(function() {
        var p1 = $("#inc_pro_pre_nombre").val(),
            p2 = $("#inc_pro_pre_descripcion").val(),
            p3 = $("#inc_pro_pre_cantidad").val(),
            p4 = $("#inc_pro_pre_porcentaje_probabilidad").val(),
            p5 = $("#inc_pro_pre_fecha_incremento").val(),
            p6 = $("#ciclo_evento_premio_id").val(),
            p8 = $("input:radio[name=estado_id]:checked").val(),
            p9 = $("#categoria_id").val(),
            p10 = $("#inc_pro_pre_hora_inicio_incremento").val(),
            p11 = $("#inc_pro_pre_hora_fin_incremento").val();
        $('#mensajes').removeClass('alert alert-danger');
        $('#mensajes').addClass('hide');
        $('#textoMensaje').text("");
        var bValid = false;
        var bValid1 = validarCampoLleno(p1, "Nombre", "inc_pro_pre_nombre");
        var bValid2 = validarCampoLleno(p2, "Descripción", "inc_pro_pre_descripcion");
        var bValid3 = validarCampoLleno(p3, "Cantidad", "inc_pro_pre_cantidad");
        var bValid4 = validarCampoLleno(p4, "Probabilidad", "inc_pro_pre_porcentaje_probabilidad");
        var bValid5 = validarCampoLleno(p5, "Fecha", "inc_pro_pre_fecha_incremento");
        var bValid6 = validarCampoLleno(p6, "Ciclo Evento Premio", "ciclo_evento_premio_id");
        var bValid8 = validarCampoLleno(p8, "Estado", "estado_id");
        var bValid9 = validarCampoLleno(p9, "Categoría", "categoria_id");
        var bValid10 = validarCampoLleno(p10, "Hora Inicio", "inc_pro_pre_hora_inicio_incremento");
        var bValid11 = validarCampoLleno(p11, "Hora Fin", "inc_pro_pre_hora_fin_incremento");
        bValid = bValid1 && bValid2 && bValid3 && bValid4 && bValid5 && bValid6 && bValid8 && bValid9 && bValid10 && bValid11;
        if (bValid) {
            $.ajax({
                url: '/validarIncrementoProbabilidadPremio',
                type: 'post',
                "_token": "{{ csrf_token() }}",
                async: true,
                data: {
                    'inc_pro_pre_id': null,
                    'inc_pro_pre_nombre': p1,
                    'inc_pro_pre_descripcion': p2,
                    'inc_pro_pre_cantidad': p3,
                    'inc_pro_pre_porcentaje_probabilidad': p4,
                    'inc_pro_pre_fecha_incremento': p5,
                    'ciclo_evento_premio_id': p6,
                    'estado_id': p8,
                    'categoria_id': p9,
                    'inc_pro_pre_hora_inicio_incremento': p10,
                    'inc_pro_pre_hora_fin_incremento': p11
                },
                beforeSend: function() {
                    $.blockUI({
                        message: '<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    var gnrMensaje = $("#gnrMensaje");
                    toTop();
                    if (jqXHR.status == '401') {
                        gnrMensaje.text(textStatus + " - " + errorThrown + " ,Su sesión ha caducado, por favor de click en Salir y vuelva a Ingresar.");
                        $('#gnrError').modal('show')
                    } else {
                        $('#mensajes').addClass('alert alert-danger');
                        $('#mensajes').removeClass('hide');
                        var textoMensaje = $("#textoMensaje");
                        textoMensaje.text("ERROR: " + jqXHR.status + " - " + textStatus + " - " + errorThrown);
                    }
                },
                success: function(j) {
                    console.log(j);
                    if (j !== "") {
                        $("#mensajes").addClass("alert alert-danger");
                        $("#mensajes").removeClass("hide");
                        $("#textoMensaje").text("");
                        for (var h in j) {
                            $("#textoMensaje").append(j[h] + "<br/>");
                        }
                    } else {
                        $("#formIncrementoProbabilidadPremio").submit();
                    }
                },
                complete: function() {
                    setTimeout($.unblockUI, 1000);
                }
            });
        }
    });
    $(".btn-Log_C").click(function() {
        var fila = $(this).parents("tr");
        var cadena = JSON.stringify(fila.data("id"));
        var obj = eval("(" + cadena + ")");
        var log = obj.log_incremento_probabilidad_premio;
        var t = $('#log').DataTable();
        t.clear();
        for (var i = 0; i < log.length; i++) {
            t.row.add([log[i].registro_anterior, log[i].registro_nuevo, log[i].tipo_accion, log[i].fecha_ingresa]).draw(false);
        }
        $('#myModal').modal('show');
    });
    $(".btn-C").click(function() {
        var fila = $(this).parents("tr");
        var cadena = JSON.stringify(fila.data("id"));
        var obj = eval("(" + cadena + ")");
        $('#mensajes').removeClass('alert alert-danger');
        $('#mensajes').addClass('hide');
        $('#textoMensaje').text("");
        $.ajax({
            url: "/buscarIncrementoProbabilidadPremio",
            type: "post",
            _token: "{{ csrf_token() }}",
            async: true,
            data: {
                inc_pro_pre_id: obj.inc_pro_pre_id
            },
            beforeSend: function() {
                $.blockUI({
                    message: '<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                var gnrMensaje = $("#gnrMensaje");
                toTop();
                if (jqXHR.status == "401") {
                    gnrMensaje.text(textStatus + " - " + errorThrown + " ,Su sesión ha caducado, por favor de click en Salir y vuelva a Ingresar.");
                    $("#gnrError").modal("show");
                } else {
                    $("#mensajes").addClass("alert alert-danger");
                    $("#mensajes").removeClass("hide");
                    var textoMensaje = $("#textoMensaje");
                    textoMensaje.text("ERROR: " + jqXHR.status + " - " + textStatus + " - " + errorThrown);
                }
            },
            success: function(data) {
                if (data[0] != '') {
                    $("#inc_pro_pre_id").val(data.inc_pro_pre_id);
                    $("#inc_pro_pre_nombre").val(data.inc_pro_pre_nombre);
                    $("#inc_pro_pre_descripcion").val(data.inc_pro_pre_descripcion);
                    $("#inc_pro_pre_cantidad").val(data.inc_pro_pre_cantidad);
                    $("#inc_pro_pre_porcentaje_probabilidad").val(data.inc_pro_pre_porcentaje_probabilidad);
                    $("#inc_pro_pre_fecha_incremento").val(data.inc_pro_pre_fecha_incremento);
                    $("#inc_pro_pre_hora_inicio_incremento").val(data.inc_pro_pre_hora_inicio_incremento);
                    $("#inc_pro_pre_hora_fin_incremento").val(data.inc_pro_pre_hora_fin_incremento);
                    $("#ciclo_evento_premio_id").val(data.ciclo_evento_premio_id).change();
                    $("#categoria_id").val(data.categoria_id).change();
                    $('input:radio[name=estado_id][value=' + data.estado_id + ']').attr('checked', true);
                    $("#btn_actualizar").removeClass("hide");
                    $("#btn_guardar").addClass("hide");
                    $("#enlace").click();
                }
            },
            complete: function() {
                setTimeout($.unblockUI, 1000);
            }
        });
    });
    $("#btn_actualizar").click(function() {
        var p0 = $("#inc_pro_pre_id").val(),
            p1 = $("#inc_pro_pre_nombre").val(),
            p2 = $("#inc_pro_pre_descripcion").val(),
            p3 = $("#inc_pro_pre_cantidad").val(),
            p4 = $("#inc_pro_pre_porcentaje_probabilidad").val(),
            p5 = $("#inc_pro_pre_fecha_incremento").val(),
            p6 = $("#ciclo_evento_premio_id").val(),
            p8 = $("input:radio[name=estado_id]:checked").val(),
            p9 = $("#categoria_id").val(),
            p10 = $("#inc_pro_pre_hora_inicio_incremento").val(),
            p11 = $("#inc_pro_pre_hora_fin_incremento").val();
        $('#mensajes').removeClass('alert alert-danger');
        $('#mensajes').addClass('hide');
        $('#textoMensaje').text("");
        var bValid = false;
        var bValid1 = validarCampoLleno(p1, "Nombre", "inc_pro_pre_nombre");
        var bValid2 = validarCampoLleno(p2, "Descripción", "inc_pro_pre_descripcion");
        var bValid3 = validarCampoLleno(p3, "Cantidad", "inc_pro_pre_cantidad");
        var bValid4 = validarCampoLleno(p4, "Probabilidad", "inc_pro_pre_porcentaje_probabilidad");
        var bValid5 = validarCampoLleno(p5, "Fecha", "inc_pro_pre_fecha_incremento");
        var bValid6 = validarCampoLleno(p6, "Ciclo Evento Premio", "ciclo_evento_premio_id");
        var bValid8 = validarCampoLleno(p8, "Estado", "estado_id");
        var bValid9 = validarCampoLleno(p9, "Categoría", "categoria_id");
        var bValid10 = validarCampoLleno(p10, "Hora Inicio", "inc_pro_pre_hora_inicio_incremento");
        var bValid11 = validarCampoLleno(p11, "Hora Fin", "inc_pro_pre_hora_fin_incremento");
        bValid = bValid1 && bValid2 && bValid3 && bValid4 && bValid5 && bValid6 && bValid8 && bValid9 && bValid10 && bValid11;
        if (bValid) {
            $.ajax({
                url: '/validarIncrementoProbabilidadPremio',
                type: 'post',
                "_token": "{{ csrf_token() }}",
                async: true,
                data: {
                    'inc_pro_pre_id': p0,
                    'inc_pro_pre_nombre': p1,
                    'inc_pro_pre_descripcion': p2,
                    'inc_pro_pre_cantidad': p3,
                    'inc_pro_pre_porcentaje_probabilidad': p4,
                    'inc_pro_pre_fecha_incremento': p5,
                    'ciclo_evento_premio_id': p6,
                    'estado_id': p8,
                    'categoria_id': p9,
                    'inc_pro_pre_hora_inicio_incremento': p10,
                    'inc_pro_pre_hora_fin_incremento': p11
                },
                beforeSend: function() {
                    $.blockUI({
                        message: '<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    var gnrMensaje = $("#gnrMensaje");
                    toTop();
                    if (jqXHR.status == '401') {
                        gnrMensaje.text(textStatus + " - " + errorThrown + " ,Su sesión ha caducado, por favor de click en Salir y vuelva a Ingresar.");
                        $('#gnrError').modal('show');
                    } else {
                        $('#mensajes').addClass('alert alert-danger');
                        $('#mensajes').removeClass('hide');
                        var textoMensaje = $("#textoMensaje");
                        textoMensaje.text("ERROR: " + jqXHR.status + " - " + textStatus + " - " + errorThrown);
                    }
                },
                success: function(j) {
                    if (j !== "") {
                        $("#mensajes").addClass("alert alert-danger");
                        $("#mensajes").removeClass("hide");
                        $("#textoMensaje").text("");
                        for (var h in j) {
                            $("#textoMensaje").append(j[h] + "<br/>");
                        }
                    } else {
                        $("#formIncrementoProbabilidadPremio").attr("action", "/actualizarIncrementoProbabilidadPremio");
                        $("#formIncrementoProbabilidadPremio").submit();
                    }
                },
                complete: function() {
                    setTimeout($.unblockUI, 1000);
                }
            });
        }
    });
</script>
@endsection
