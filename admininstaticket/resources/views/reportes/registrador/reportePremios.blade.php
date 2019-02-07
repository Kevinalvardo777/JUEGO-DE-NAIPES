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
<i class="fa fa-bar-chart"></i>    PREMIOS 
</h1>
<ol class="breadcrumb">
<li><a href="#"><i class="fa fa-bar-chart"></i> Reporte Premios</a></li>
</ol>
@endsection
@section('main-content')
<div class="col-md-12">
<div class="panel panel-body">
<div class="row">
<div class="col-lg-4 col-xs-4">
<div class="small-box bg-yellow">
<div class="inner">
<h3>{{ $totalPremios->total_premios}}</h3>
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
<h3>{{$totalDisponible->total_disponible}}</h3>
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
<h3>{{$totalRetirado->total_retirado}}</h3>
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
<li  class=""><a id="enlace" href="#tab_2" data-toggle="tab">Datos</a></li>
</ul>
<div class="tab-content">
<div class="tab-pane active" id="tab_1">
<form id="formImprimir" class="form-horizontal" method="POST" name="formImprimir" action="/imprimirExcelUsuarios" role="form">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="box-body">
<div class="col-md-12">
<div class="box box-warning">
<div class="box-body">
<div class="chart">
<canvas id="barChart" ></canvas>
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
<th>CICLO</th>
<th>NOMBRE</th>
<th>STOCK TOTAL</th>
<th>STOCK DISPONIBLE</th>
<th>STOCK RETIRADO</th>
</tr>
</thead>
<tbody>
@if($lstPremiosJuego)
@foreach($lstPremiosJuego as $lstP)
<tr>
<td>{{$lstP->ciclo}}</td>
<td>{{$lstP->premio_nombre}}</td>
<td>{{$lstP->ciclo_evento_premio_stock_total}}</td>
<td>{{$lstP->ciclo_evento_premio_stock_disponible}}</td>
<td>{{$lstP->ciclo_evento_premio_stock_retirado}}</td>
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
<script src="../plugins/Chart.js/Chart.js"></script>
<script>$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}});var ctx=document.getElementById('barChart').getContext("2d");var myChart=new Chart(ctx,{type:'bar',data:{labels:[],datasets:[{label:"Total Premios",borderColor:'rgb(255, 128, 0)',pointBorderColor:'rgb(255, 128, 0)',pointBackgroundColor:'rgb(255, 128, 0)',pointHoverBackgroundColor:'rgb(255, 128, 0)',pointHoverBorderColor:'rgb(255, 128, 0)',pointBorderWidth:5,pointHoverRadius:5,pointHoverBorderWidth:1,pointRadius:3,fill:true,backgroundColor:'rgb(255, 128, 0)',borderWidth:2,data:[]},{label:"Premios Disponibles",borderColor:'rgba(0,153,76)',pointBorderColor:'rgba(0,153,76)',pointBackgroundColor:'rgba(0,153,76)',pointHoverBackgroundColor:'rgba(0,153,76)',pointHoverBorderColor:'rgba(0,153,76)',pointBorderWidth:5,pointHoverRadius:5,pointHoverBorderWidth:1,pointRadius:3,fill:true,backgroundColor:'rgba(0,153,76)',borderWidth:2,data:[]},{label:"Premios Retirados",borderColor:'rgba(51,153,255)',pointBorderColor:'rgba(51,153,255)',pointBackgroundColor:'rgba(51,153,255)',pointHoverBackgroundColor:'rgba(51,153,255)',pointHoverBorderColor:'rgba(51,153,255)',pointBorderWidth:5,pointHoverRadius:5,pointHoverBorderWidth:1,pointRadius:3,fill:true,backgroundColor:'rgba(51,153,255)',borderWidth:2,data:[]}]},options:{responsive:true,legend:{position:"top"},scales:{yAxes:[{ticks:{fontColor:"rgba(250,32,68)",fontStyle:"bold",beginAtZero:true,maxTicksLimit:5,padding:20},gridLines:{drawTicks:false,display:false}}],xAxes:[{gridLines:{zeroLineColor:"rgba(255,255,255,0.5)"},ticks:{padding:20,fontColor:"rgba(2,50,114)",fontStyle:"bold"}}]}}});function addData(chart,data,datasetIndex,labels){if(labels.length>0){chart.data.labels=labels}chart.data.datasets[datasetIndex].data=data;chart.update()}$(document).ready(function(){var table=$('#tblPremiosJuego').DataTable({"columnDefs":[{"visible":false,"targets":0}],dom:'Bfrtip',buttons:['copy','csv','excel','pdf','print'],"order":[[0,'asc']],"displayLength":10,"drawCallback":function(settings){var api=this.api();var rows=api.rows({page:'current'}).nodes();var last=null;api.column(0,{page:'current'}).data().each(function(group,i){if(last!==group){$(rows).eq(i).before('<tr class="group"><td colspan="5">'+group+'</td></tr>');last=group}})}});$('#tblPremiosJuego tbody').on('click','tr.group',function(){var currentOrder=table.order()[0];if(currentOrder[0]===2&&currentOrder[1]==='asc'){table.order([2,'desc']).draw()}else{table.order([2,'asc']).draw()}});var premios={!!json_encode($lstPremiosJuego)!!};var nombrepremios=[];var disponiblespremio=[];var retiradospremio=[];var totalpremio=[];for(var i=0;i<premios.length;i++){nombrepremios=nombrepremios.concat(premios[i].premio_nombre);totalpremio=totalpremio.concat(premios[i].ciclo_evento_premio_stock_total);disponiblespremio=disponiblespremio.concat(premios[i].ciclo_evento_premio_stock_disponible);retiradospremio=retiradospremio.concat(premios[i].ciclo_evento_premio_stock_retirado)}setTimeout(function(){addData(myChart,totalpremio,0,nombrepremios);addData(myChart,disponiblespremio,1,[]);addData(myChart,retiradospremio,2,[])},1000)});</script>
@endsection
