@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="../plugins/datatables/buttons/buttons.dataTables.min.css">
<link rel="stylesheet" href="../css/ionicons.min.css">
@endsection
@section('htmlheader_title')
Reporte - Ganadores
@endsection
@section('header')
<h1>
<i class="fa fa-trophy"></i>    GANADORES 
</h1>
<ol class="breadcrumb">
<li><a href="#"><i class="fa fa-trophy"></i> Reporte Ganadores</a></li>
</ol>
@endsection
@section('main-content')
<div class="col-md-12">
<div class="nav-tabs-custom">
<ul class="nav nav-tabs">
<li  class="active"><a id="enlace" href="#tab_1" data-toggle="tab">Datos</a></li>
</ul>
<div class="tab-content">
<div class="tab- active" id="tab_1">
<div class="box-body">
<div class="col-sm-12">
<div class="form-group" id="div_ciclo_evento_id">
<label  class="col-lg-2">Ciclo - Evento</label>
<div class="col-lg-10">
<select id="ciclo_evento_id" name="ciclo_evento_id" class="form-control select2" style="width: 100%;" tabindex="-1" aria-hidden="true">
@foreach($lstCicloEvento as $lstCiclosEventoAc)
<option value="{{$lstCiclosEventoAc->ciclo_evento_id}}"> CICLO: {{$lstCiclosEventoAc->ciclo->ciclo_nombre}} - FECHA CICLO: {{$lstCiclosEventoAc->ciclo->ciclo_fecha_inicio}}/{{$lstCiclosEventoAc->ciclo->ciclo_fecha_fin}} - EVENTO: {{$lstCiclosEventoAc->evento->evento_nombre}} </option>
@endforeach
</select>
</div>
<label  id="lbl_ciclo_evento_id" class=" control-label hide"></label>
</div>
</div>
<div class="col-sm-12"><br></div>
<div class="col-sm-12 table-responsive">
<table id="tblGanadoresJuego" class="table table-bordered table-striped dataTable" cellspacing="0" >
<thead>
<tr>
<th>PREMIO</th>
<th>PARTICIPANTE</th>
<th>CÉDULA</th>
<th>EMAIL</th>
<th>CELULAR</th>
<th>TELÉFONO</th>
<th># BOLETO</th>
<th>FECHA/HORA</th>
</tr>
</thead>
<tbody>
</tbody>
</table>
</div>
</div>
</form>
</div>
</div>
</div>
</div>
@endsection
@section('script')
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="../plugins/datatables/buttons/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables/buttons/buttons.flash.min.js"></script>
<script src="../plugins/datatables/buttons/jszip.min.js"></script>
<script src="../plugins/datatables/buttons/pdfmake.min.js"></script>
<script src="../plugins/datatables/buttons/vfs_fonts.js"></script>
<script src="../plugins/datatables/buttons/vfs_fonts.js"></script>
<script src="../plugins/datatables/buttons/buttons.html5.min.js"></script>
<script src="../plugins/datatables/buttons/buttons.print.min.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });
    $(document).ready(function() {
        var table = $('#tblGanadoresJuego').DataTable({
            "columnDefs": [{
                "visible": false,
                "targets": 0
            }],
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', {
                extend: 'pdf',
                text: 'Pdf',
                orientation: 'landscape'
            }, 'print'],
            "order": [
                [0, 'asc']
            ],
            "displayLength": 20,
            "responsive": true,
            "drawCallback": function(settings) {
                var api = this.api();
                var rows = api.rows({
                    page: 'current'
                }).nodes();
                var last = null;
                api.column(0, {
                    page: 'current'
                }).data().each(function(group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before('<tr class="group"><td colspan="7">' + group + '</td></tr>');
                        last = group;
                    }
                });
            }
        });
        $('#tblGanadoresJuego tbody').on('click', 'tr.group', function() {
            var currentOrder = table.order()[0];
            if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                table.order([2, 'desc']).draw();
            } else {
                table.order([2, 'asc']).draw();
            }
        });
        $("#ciclo_evento_id").change();
    });
    $("#ciclo_evento_id").change(function() {
        var p1 = $("#ciclo_evento_id").val();
        $('#mensajes').removeClass('alert alert-danger');
        $('#mensajes').addClass('hide');
        $('#textoMensaje').text("");
        $.ajax({
            url: '/buscarGanadoresxCicloEvento',
            type: 'post',
            "_token": "{{ csrf_token() }}",
            async: true,
            data: {
                'ciclo_evento_id': p1
            },
            beforeSend: function() {
                $.blockUI({
                    message: '<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento porfavor </h4>'
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                var gnrMensaje = $("#gnrMensaje");
                toTop();
                if (jqXHR.status == '401') {
                    gnrMensaje.text(textStatus + " - " + errorThrown + " ,Su sesión ha caducado, porfavor de click en Salir y vuelva a Ingresar.");
                    $('#gnrError').modal('show');
                } else {
                    $('#mensajes').addClass('alert alert-danger');
                    $('#mensajes').removeClass('hide');
                    var textoMensaje = $("#textoMensaje");
                    textoMensaje.text("ERROR: " + jqXHR.status + " - " + textStatus + " - " + errorThrown);
                }
            },
            success: function(data) {
                var t = $('#tblGanadoresJuego').DataTable();
                t.clear().draw();
                if (data == "") {
                    $("#mensajes").addClass("alert alert-danger");
                    $("#mensajes").removeClass("hide");
                    $("#textoMensaje").text("No se han encontrado ganadores de premios para el ciclo y evento seleccionados.");
                } else {
                    for (var i = 0; i < data.length; i++) {
                        t.row.add([data[i].premio_nombre, 
                            data[i].participante, 
                            data[i].participante_cedula, 
                            data[i].participante_email, 
                            data[i].participante_celular, 
                            data[i].participante_telefono, 
                            data[i].participante_boleto_det_numero_boleto, 
                            data[i].fecha_entrega_premio
                        ]).draw(false);
                    }
                }
            },
            complete: function() {
                setTimeout($.unblockUI, 1000);
            }
        });
    });
</script>
@endsection
