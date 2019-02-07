<header class="main-header">
<nav class="navbar navbar-static-top" role="navigation">
<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
<span class="sr-only">{{ trans('adminlte_lang::message.togglenav') }}</span>
</a>
<div class="navbar-custom-menu">
<ul class="nav navbar-nav">
<li class="dropdown user user-menu">
<a href="#" class="dropdown-toggle" data-toggle="dropdown">
<span class="hidden-xs"><i class="fa fa-user-circle"></i>&nbsp;&nbsp;{{Auth::user()->usuario_username}}</span>
</a>
</li>
<li>
<a href="/logout" >
<i class="fa fa-sign-out"></i>
<span class="hidden-xs">Cerrar Sesión</span>
</a>
</li>
</ul>
</div>
</nav>
</header>
