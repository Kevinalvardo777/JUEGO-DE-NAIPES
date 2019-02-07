<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title> Admin Instaticket - @yield('htmlheader_title', 'Admin') </title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name=csrf-token content="{{ csrf_token() }}" />
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<link href="{{ asset('/css/bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
@yield('css')
<link href="{{ asset('/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/css/skins/skin-red.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/plugins/iCheck/square/yellow.css') }}" rel="stylesheet" type="text/css" />
</head>
<body class="skin-red sidebar-mini">
<div class="wrapper">
@include('layouts.partials.mainheader')
@include('layouts.partials.sidebar')
<div class="content-wrapper">
<section class="content-header">
@yield('header')
</section>
<br/>
<section class="content">
<div class="row">
<div class="col-md-12">
@if(Session::has('message'))
<div style="background-color: #ffcc33 !important;border-color: #ffcc33 ;color: #525049 !important;" class="alert alert-success">
<button type=button class=close data-dismiss=alert>&times;</button>
<label>{{Session::get('message')}}</label>
</div>
<p><p/>
@endif
@if(Session::has('messageError'))
<div class="alert alert-danger">
<button type=button class=close data-dismiss=alert>&times;</button>
<label>{{Session::get('messageError')}}</label>
</div>
<p><p/>
@endif
@if (count($errors) > 0)
<div class="alert alert-danger">
<button type="button" class="close" data-dismiss="alert">&times;</button>
@foreach ($errors->all() as $error)
<p>{{ $error }}</p>
@endforeach
</div>
@endif
<p><p/>
<div id=mensajes name=mensajes class="hide">
<label id=textoMensaje name=textoMensaje></label>
</div>
</div>
@yield('main-content')
</div>
</section>
</div>
@include('layouts.partials.footer')
</div>
<div id="gnrError" class="modal fade">
<div class="modal-dialog" role=document>
<div class="modal-content">
<div class="modal-header">
<h4 class="modal-title">Mensaje del Sistema</h4>
</div>
<div class=modal-body>
<p id=gnrMensaje></p>
</div>
<div class=modal-footer>
<a class="btn btn-primary" href="{{ url('/logout') }}">
<i class="fa fa-sign-out"></i> Salir
</a>
</div>
</div>
</div>
</div> 
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="myModal">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<h4 class="modal-title">LOG</h4>
</div>
<div class="modal-body">
<div class="table-responsive">
<table id="log" class="table table-bordered table-striped dataTable" cellspacing="0" >
<thead>
<tr>
<th>R. ANTERIOR</th>
<th>R. NUEVO</th>
<th>ACCIÓN</th>
<th>FECHA</th>
</tr>
</thead>
</table>
</div>
</div>
</div>
</div>
</div><!-- ./wrapper -->
<script src="{{ asset('/js/ValidadorDatos.js') }}"></script>
<script src="{{ asset('/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
<script src="{{ asset('/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/app.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/jquery.blockUI.js') }}"></script>
@yield('script')
<script>function toTop() {window.scrollTo(0, 0);}$("#cancelar").click(function () {location.reload();});</script>
</body> 
</html>
