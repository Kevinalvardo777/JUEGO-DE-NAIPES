<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title> Admin Instaticket - Login </title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/css/skins/skin-red.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/plugins/iCheck/square/yellow.css') }}" rel="stylesheet" type="text/css" />
</head>
<body class="hold-transition">
    <img src="../img/LOGOINSTATICKET.png" class="responsive resize" width="20%" style="margin-left: 50px;margin-top: 50px;"></img>
<div class="container">
@yield('content')
</div>
<script src="{{ asset('/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
<script src="{{ asset('/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
<script>$(function(){$('input').iCheck({checkboxClass:'icheckbox_square-yellow',radioClass:'iradio_square-yellow',increaseArea:'20%'})});</script>
</body>
</html>