@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="../plugins/datatables/buttons/buttons.dataTables.min.css">
<link rel="stylesheet" href="../css/ionicons.min.css">
@endsection
@section('htmlheader_title')
Reporte - Premios
@endsection
@section('header')
<h1>
<i class="fa fa-bar-chart"></i> <i class="fa fa-gift"></i>    PREMIOS 
</h1>
<ol class="breadcrumb">
<li><a href="#"><i class="fa fa-bar-chart"></i>Reporte Premios</a></li>
</ol>
@endsection
@section('main-content')
<div class="col-md-12">
<div class="panel panel-body">
<div class="row">
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
<div class="col-sm-12">
<br>
</div>
<div class="col-lg-4 col-xs-4">
<div class="small-box bg-yellow">
<div class="inner">
<h3><label id="total_premio"></label></h3>
<p>Total Premios</p>
</div>
<div class="icon">
<i class="fa fa-gift"></i>
</div>
<a href="" class="small-box-footer"></i></a>
</div>
</div>
<div class="col-lg-4 col-xs-4">
<div class="small-box bg-green">
<div class="inner">
<h3 id="total_disponible"></h3>
<p>Premios Disponibles</p>
</div>
<div class="icon">
<i class="fa fa-check-square"></i>
</div>
<a href="" class="small-box-footer"></i></a>
</div>
</div>
<div class="col-lg-4 col-xs-4">
<div   class="small-box bg-aqua">
<div class="inner">
<h3 id="total_retirado"></h3>
<p>Premios Retirados</p>
</div>
<div class="icon">
<i class="fa fa-star"></i>
</div>
<a href="" class="small-box-footer"></i></a>
</div>
</div>
</div>
</div>
<div class="nav-tabs-custom">
<ul class="nav nav-tabs">
<li  class="active"><a id="enlace" href="#tab_1" data-toggle="tab">Gráfico</a></li>
<li  class=""><a  href="#tab_2" data-toggle="tab">Datos</a></li>
</ul>
<div class="tab-content">
<div class="tab-pane active" id="tab_1">
<form id="formImprimir" class="form-horizontal" method="POST" name="formImprimir" action="/imprimirExcelUsuarios" role="form">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="box-body">
<div class="col-md-12">
<div class="box box-warning">
<div class="box-body">
<div class="chart responsive resize">
<canvas id="chart"></canvas>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="tab-pane" id="tab_2">
<div class="box-body">
<div class="col-sm-12 table-responsive">
<table id="tblPremiosJuego" class="table table-bordered table-striped dataTable" cellspacing="0" >
<thead>
<tr>
<th>NOMBRE</th>
<th>STOCK TOTAL</th>
<th>STOCK DISPONIBLE</th>
<th>STOCK RETIRADO</th>
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
<script src="../plugins/Chart.js/Chart.js"></script>
<script>$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}});$(document).ready(function(){var table=$('#tblPremiosJuego').DataTable({dom:'Bfrtip',buttons:['copy','csv','excel','pdf','print'],"order":[[0,'asc']],"displayLength":15});$("#total_premio").text("0");$("#total_disponible").text("0");$("#total_retirado").text("0");$("#ciclo_evento_id").change()});var ctx=document.getElementById('chart').getContext("2d");var myChart=new Chart(ctx,{type:'bar',data:{labels:[],datasets:[{label:"Total Premios",borderColor:'rgb(255, 128, 0)',pointBorderColor:'rgb(255, 128, 0)',pointBackgroundColor:'rgb(255, 128, 0)',pointHoverBackgroundColor:'rgb(255, 128, 0)',pointHoverBorderColor:'rgb(255, 128, 0)',pointBorderWidth:5,pointHoverRadius:5,pointHoverBorderWidth:1,pointRadius:3,fill:true,backgroundColor:'rgb(255, 128, 0)',borderWidth:2,data:[]},{label:"Premios Disponibles",borderColor:'rgba(0,153,76)',pointBorderColor:'rgba(0,153,76)',pointBackgroundColor:'rgba(0,153,76)',pointHoverBackgroundColor:'rgba(0,153,76)',pointHoverBorderColor:'rgba(0,153,76)',pointBorderWidth:5,pointHoverRadius:5,pointHoverBorderWidth:1,pointRadius:3,fill:true,backgroundColor:'rgba(0,153,76)',borderWidth:2,data:[]},{label:"Premios Retirados",borderColor:'rgba(51,153,255)',pointBorderColor:'rgba(51,153,255)',pointBackgroundColor:'rgba(51,153,255)',pointHoverBackgroundColor:'rgba(51,153,255)',pointHoverBorderColor:'rgba(51,153,255)',pointBorderWidth:5,pointHoverRadius:5,pointHoverBorderWidth:1,pointRadius:3,fill:true,backgroundColor:'rgba(51,153,255)',borderWidth:2,data:[]}]},options:{responsive:true,legend:{position:"top"},scales:{yAxes:[{ticks:{fontColor:"rgba(250,32,68)",fontStyle:"bold",beginAtZero:true,maxTicksLimit:5,padding:20},gridLines:{drawTicks:false,display:false}}],xAxes:[{gridLines:{zeroLineColor:"rgba(255,255,255,0.5)"},ticks:{padding:20,fontColor:"rgba(2,50,114)",fontStyle:"bold"}}]}}});function addData(chart,data,datasetIndex,labels){if(labels.length>0){chart.data.labels=labels}chart.data.datasets[datasetIndex].data=data;chart.update()}$("#ciclo_evento_id").change(function(){var p1=$("#ciclo_evento_id").val();$('#mensajes').removeClass('alert alert-danger');$('#mensajes').addClass('hide');$('#textoMensaje').text("");$.ajax({url:'/buscarPremiosxCicloEvento',type:'post',"_token":"{{ csrf_token() }}",async:true,data:{'ciclo_evento_id':p1},beforeSend:function(){$.blockUI({message:'<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento porfavor </h4>'})},error:function(jqXHR,textStatus,errorThrown){var gnrMensaje=$("#gnrMensaje");toTop();if(jqXHR.status=='401'){gnrMensaje.text(textStatus+" - "+errorThrown+" ,Su sesión ha caducado, porfavor de click en Salir y vuelva a Ingresar.");$('#gnrError').modal('show')}else{$('#mensajes').addClass('alert alert-danger');$('#mensajes').removeClass('hide');var textoMensaje=$("#textoMensaje");textoMensaje.text("ERROR: "+jqXHR.status+" - "+textStatus+" - "+errorThrown)}},success:function(data){var t=$('#tblPremiosJuego').DataTable();t.clear().draw();var nombrepremios=[];var disponiblespremio=[];var retiradospremio=[];var totalpremio=[];var arreglo=[];if(data==""){$("#mensajes").addClass("alert alert-danger");$("#mensajes").removeClass("hide");$("#textoMensaje").text("No se han encontrado premios para el ciclo y evento seleccionados.");$("#total_premio").text("0");$("#total_disponible").text("0");$("#total_retirado").text("0")}else{arreglo=data[0];$("#total_premio").text(data[1]);$("#total_disponible").text(data[2]);$("#total_retirado").text(data[3]);for(var i=0;i<arreglo.length;i++){t.row.add([arreglo[i].premio_nombre,arreglo[i].ciclo_evento_premio_stock_total,arreglo[i].ciclo_evento_premio_stock_disponible,arreglo[i].ciclo_evento_premio_stock_retirado]).draw(false);nombrepremios=nombrepremios.concat(arreglo[i].premio_nombre);totalpremio=totalpremio.concat(arreglo[i].ciclo_evento_premio_stock_total);disponiblespremio=disponiblespremio.concat(arreglo[i].ciclo_evento_premio_stock_disponible);retiradospremio=retiradospremio.concat(arreglo[i].ciclo_evento_premio_stock_retirado)}setTimeout(function(){addData(myChart,totalpremio,0,nombrepremios);addData(myChart,disponiblespremio,1,[]);addData(myChart,retiradospremio,2,[])},1000);$("#enlace").click()}},complete:function(){setTimeout($.unblockUI,1000)}})});</script>
@endsection
