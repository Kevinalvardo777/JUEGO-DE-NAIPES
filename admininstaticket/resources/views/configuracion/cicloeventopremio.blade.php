@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="../plugins/select2/select2.min.css">
@endsection
@section('htmlheader_title')
Ciclo Evento Premio
@endsection
@section('header')
<h1>
<i class="fa fa-history"></i>   <i class="fa fa-picture-o"></i>  <i class="fa fa-gift"></i>  CICLO EVENTO PREMIO
</h1>
<ol class="breadcrumb">
<li><a href="#"><i class="fa fa-dashboard"></i> Configuración</a></li>
<li class="active">Ciclo Evento Premio</li>
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
<form class="form-horizontal" action="/guardarCicloEventoPremio" method="post" id="formCicloEventoPremio">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="ciclo_evento_premio_id" id="ciclo_evento_premio_id">
<div class="box-body">
<br/>
<div class="col-sm-6">
<div class="form-group" id="div_ciclo_evento_id">
<label  class="col-lg-2">Ciclo - Evento</label>
<div class="col-lg-10">
<select id="ciclo_evento_id" name="ciclo_evento_id" class="form-control select2" style="width: 100%;" tabindex="-1" aria-hidden="true">
@foreach($lstCiclosEvento as $lstCiclosEventoAc)
<option value="{{$lstCiclosEventoAc->ciclo_evento_id}}"> {{$lstCiclosEventoAc->ciclo->ciclo_nombre}} - {{$lstCiclosEventoAc->evento->evento_nombre}} </option>
@endforeach
</select>
</div>
<label  id="lbl_ciclo_evento_id" class=" control-label hide"></label>
</div>
<div class="form-group" id="div_ciclo_evento_premio_stock_total">
<label class="col-lg-2 control-label">Stock Total</label>
<div class="col-lg-10">
<input type="number" id="ciclo_evento_premio_stock_total" name="ciclo_evento_premio_stock_total" minlength="1" class="form-control">
<label  id="lbl_ciclo_evento_premio_stock_total" class="control-label hide"></label>
</div>
</div>
<div class="form-group hide" id="div_ciclo_evento_premio_stock_disponible">
<label class="col-lg-2 control-label">Stock Disp.</label>
<div class="col-lg-10">
<input type="number" id="ciclo_evento_premio_stock_disponible" name="ciclo_evento_premio_stock_disponible" minlength="1" class="form-control"  disabled>
<label  id="lbl_ciclo_evento_premio_stock_disponible" class="control-label hide"></label>
</div>
</div>
</div>
<div class="col-sm-6">
<div class="form-group" id="div_premio_id">
<label  class="col-lg-2">Premios</label>
<div class="col-lg-10">
<select id="premio_id" name="premio_id" class="form-control select2" style="width: 100%;" tabindex="-1" aria-hidden="true">
@foreach($lstPremios as $lstPremiosAc)
<option value="{{$lstPremiosAc->premio_id}}"> {{$lstPremiosAc->premio_nombre}} </option>
@endforeach
</select>
</div>
<label  id="lbl_premio_id" class=" control-label hide"></label>
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
<th>CICLO EVENTO</th>
<th>PREMIO</th>
<th>STOCK TOTAL</th>
<th>STOCK DISPONIBLE</th>
<th>FECHA CICLO</th>
<th>ESTADO</th>
<th></th>
</tr>
</thead>
<tbody>
@if($lstCiclosEventoPremios)
@foreach($lstCiclosEventoPremios as $c)
<tr data-id="{{$c}}">
<td>{{$c->cicloEvento->ciclo->ciclo_nombre}} - {{$c->cicloEvento->evento->evento_nombre}}  </td>
<td>{{$c->premio->premio_nombre}} </td>
<td>{{$c->ciclo_evento_premio_stock_total}}</td>
<td>{{$c->ciclo_evento_premio_stock_disponible}}</td>
<td>{{$c->cicloEvento->ciclo->ciclo_fecha_inicio}} - {{$c->cicloEvento->ciclo->ciclo_fecha_fin}}  </td>
<td>@if($c->estado!=null){{$c->estado->estado_nombre}}@else{{$c->estado}}@endif</td>
<td style="text-align: center">
<button type="button" class="btn btn-warning btn-sm btn-C"  title="Editar"><i class="fa fa-pencil"></i></button>
@if($c->logCicloEventoPremio->count() > 0)
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
    $(document).ready(function() {
        $('#tbl_C').DataTable({})
    });
    $('.select2').select2({
        placeholder: 'Seleccione una opción'
    });
    $("#btn_cancelar").click(function() {
        location.reload()
    });
    $("#btn_guardar").click(function() {
        var p1 = $("#ciclo_evento_id").val(),
            p2 = $("#premio_id").val(),
            p3 = $("#ciclo_evento_premio_stock_total").val(),
            p4 = $("input:radio[name=estado_id]:checked").val();
        $('#mensajes').removeClass('alert alert-danger');
        $('#mensajes').addClass('hide');
        $('#textoMensaje').text("");
        var bValid = false;
        var bValid1 = validarCampoLleno(p1, "Ciclo Evento", "ciclo_evento_id");
        var bValid2 = validarCampoLleno(p2, "Premio", "premio_id");
        var bValid3 = validarCampoLleno(p3, "Stock Total", "ciclo_evento_premio_stock_total");
        var bValid4 = validarCampoLleno(p4, "Estado", "estado_id");
        bValid = bValid1 && bValid2 && bValid3 && bValid4;
        if (bValid) {
            $.ajax({
                url: '/validarCicloEventoPremio',
                type: 'post',
                "_token": "{{ csrf_token() }}",
                async: true,
                data: {
                    'ciclo_evento_premio_id': null,
                    'ciclo_evento_id': p1,
                    'premio_id': p2,
                    'ciclo_evento_premio_stock_total': p3,
                    'estado_id': p4
                },
                beforeSend: function() {
                    $.blockUI({
                        message: '<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'
                    })
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
                        textoMensaje.text("ERROR: " + jqXHR.status + " - " + textStatus + " - " + errorThrown)
                    }
                },
                success: function(j) {
                    if (j !== "") {
                        $("#mensajes").addClass("alert alert-danger");
                        $("#mensajes").removeClass("hide");
                        $("#textoMensaje").text("");
                        for (var h in j) {
                            $("#textoMensaje").append(j[h] + "<br/>")
                        }
                    } else {
                        $("#formCicloEventoPremio").submit()
                    }
                },
                complete: function() {
                    setTimeout($.unblockUI, 1000)
                }
            })
        }
    });
    $(".btn-Log_C").click(function() {
        var fila = $(this).parents("tr");
        var cadena = JSON.stringify(fila.data("id"));
        var obj = eval("(" + cadena + ")");
        var log = obj.log_ciclo_evento_premio;
        var t = $('#log').DataTable();
        t.clear();
        for (var i = 0; i < log.length; i++) {
            t.row.add([log[i].registro_anterior, log[i].registro_nuevo, log[i].tipo_accion, log[i].fecha_ingresa]).draw(false)
        }
        $('#myModal').modal('show')
    });
    $(".btn-C").click(function() {
        var fila = $(this).parents("tr");
        var cadena = JSON.stringify(fila.data("id"));
        var obj = eval("(" + cadena + ")");
        $('#mensajes').removeClass('alert alert-danger');
        $('#mensajes').addClass('hide');
        $('#textoMensaje').text("");
        $.ajax({
            url: "/buscarCicloEventoPremio",
            type: "post",
            _token: "{{ csrf_token() }}",
            async: true,
            data: {
                ciclo_evento_premio_id: obj.ciclo_evento_premio_id
            },
            beforeSend: function() {
                $.blockUI({
                    message: '<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento porfavor </h4>'
                })
            },
            error: function(jqXHR, textStatus, errorThrown) {
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
            success: function(data) {
                if (data != '') {
                    validarCampoLleno(data.ciclo_evento_id, "Ciclo-Evento", "ciclo_evento_id");
                    validarCampoLleno(data.premio_id, "Premio", "premio_id");
                    validarCampoLleno(data.estado_id, "Estado", "estado_id");
                    validarCampoLleno(data.ciclo_evento_premio_stock_total, "Stock Total", "ciclo_evento_premio_stock_total");
                    $("#ciclo_evento_premio_id").val(data.ciclo_evento_premio_id);
                    var p0 = $("#ciclo_evento_premio_id").val();
                    console.log(p0);
                    $("#ciclo_evento_id").val(data.ciclo_evento_id).change();
                    $("#premio_id").val(data.premio_id).change();
                    $("#ciclo_evento_premio_stock_total").val(data.ciclo_evento_premio_stock_total);
                    $("#ciclo_evento_premio_stock_disponible").val(data.ciclo_evento_premio_stock_disponible);
                    $('input:radio[name=estado_id][value=' + data.estado_id + ']').attr('checked', true);
                    $("#div_ciclo_evento_premio_stock_disponible").removeClass("hide");
                    $("#btn_guardar").addClass("hide");
                    $("#btn_actualizar").removeClass("hide");
                    $("#enlace").click()
                }
            },
            complete: function() {
                setTimeout($.unblockUI, 1000)
            }
        })
    });
    $("#btn_actualizar").click(function() {
        var p0 = $("#ciclo_evento_premio_id").val(),
            p1 = $("#ciclo_evento_id").val(),
            p2 = $("#premio_id").val(),
            p3 = $("#ciclo_evento_premio_stock_total").val(),
            p4 = $("input:radio[name=estado_id]:checked").val();
        $('#mensajes').removeClass('alert alert-danger');
        $('#mensajes').addClass('hide');
        $('#textoMensaje').text("");
        var bValid = false;
        var bValid1 = validarCampoLleno(p1, "Ciclo - Evento", "ciclo_evento_id");
        var bValid2 = validarCampoLleno(p2, "Premio", "premio_id");
        var bValid3 = validarCampoLleno(p3, "Stok Total", "ciclo_evento_premio_stock_total");
        var bValid4 = validarCampoLleno(p4, "Estado", "estado_id");
        bValid = bValid1 && bValid2 && bValid3 && bValid4;
        if (bValid) {
            $.ajax({
                url: '/validarCicloEventoPremio',
                type: 'post',
                "_token": "{{ csrf_token() }}",
                async: true,
                data: {
                    'ciclo_evento_premio_id': p0,
                    'ciclo_evento_id': p1,
                    'premio_id': p2,
                    'ciclo_evento_premio_stock_total': p3,
                    'estado_id': p4
                },
                beforeSend: function() {
                    $.blockUI({
                        message: '<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'
                    })
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
                        textoMensaje.text("ERROR: " + jqXHR.status + " - " + textStatus + " - " + errorThrown)
                    }
                },
                success: function(j) {
                    if (j !== "") {
                        $("#mensajes").addClass("alert alert-danger");
                        $("#mensajes").removeClass("hide");
                        $("#textoMensaje").text("");
                        for (var h in j) {
                            $("#textoMensaje").append(j[h] + "<br/>")
                        }
                    } else {
                        $("#formCicloEventoPremio").attr("action", "/actualizarCicloEventoPremio");
                        $("#formCicloEventoPremio").submit()
                    }
                },
                complete: function() {
                    setTimeout($.unblockUI, 1000)
                }
            })
        }
    });
</script>
@endsection
