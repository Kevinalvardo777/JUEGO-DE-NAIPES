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
@if($lstGanadoresJuego)
@foreach($lstGanadoresJuego as $lstG)
<tr>
<td>{{$lstG->premio_nombre}}</td>
<td>{{$lstG->participante}}</td>
<td>{{$lstG->participante_cedula}}</td>
<td>{{$lstG->participante_email}}</td>
<td>{{$lstG->participante_celular}}</td>
<td>{{$lstG->participante_telefono}}</td>
<td>{{$lstG->participante_boleto_det_numero_boleto}}</td>
<td>{{$lstG->fecha_entrega_premio}}</td>
</tr>
@endforeach
@endif
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
    });
</script>
@endsection
