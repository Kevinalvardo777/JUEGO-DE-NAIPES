<aside class="main-sidebar">
<section class="sidebar">
<div class="logoposicion">
<a href="{{ url('/welcome') }}" class="logo">
<span class="logo-lg"><img src="../img/logo.png" style="width: 80%;"/></span>
</a>
</div>
@if( Auth::user()->tipo_usuario_id == $objUsuarioAdministrador->parametro_general_valor )
<ul class="sidebar-menu">
<li class="treeview">
<a href="#">
<i class="fa fa-suitcase"></i> <span>MANTENIMIENTOS</span>
<i class="fa fa-angle-left pull-right"></i>
</a>
<ul class="treeview-menu">
<li><a href="/categorias"><i class="fa fa-bookmark"></i><span>Categorías</span></a></li>
<li><a href="/ciclos"><i class="fa fa-bookmark"></i><span>Ciclo</span></a></li>
<li><a href="/probabilidades"><i class="fa fa-bookmark"></i><span>Probabilidades</span></a></li>
<li><a href="/eventos"><i class="fa fa-bookmark"></i><span>Evento</span></a></li>
<li><a href="/premios"><i class="fa fa-bookmark"></i><span>Premios</span></a></li>
</ul>
</li>
<li class="treeview">
<a href="#">
<i class="fa fa-dashboard"></i><span>CONFIGURACIÓN</span>
<i class="fa fa-angle-left pull-right"></i>
</a>
<ul class="treeview-menu">
<li><a href="/parametrosgenerales"><i class="fa fa-bookmark"></i><span>Parametros Generales</span></a></li>
<li><a href="/cicloevento"><i class="fa fa-bookmark"></i><span>Ciclo Evento</span></a></li>
<li><a href="/cicloeventopremio"><i class="fa fa-bookmark"></i><span>Ciclo Evento Premio</span></a></li>
<li><a href="/configevento"><i class="fa fa-bookmark"></i><span>Evento</span></a></li>
<li><a href="/incremento"><i class="fa fa-bookmark"></i><span>Incremento Proba. Premio</span></a></li>
<li><a href="/usuarios"><i class="fa fa-bookmark"></i><span>Usuarios</span></a></li>
</ul>
</li>
<li class="treeview">
<a href="#">
<i class="fa fa-bar-chart"></i> <span>REPORTES</span>
<i class="fa fa-angle-left pull-right"></i>
</a>
<ul class="treeview-menu">
<li><a href="/registradorrptpremiosad"><i class="fa fa-bookmark"></i><span>Premios</span></a></li>
<li><a href="/registradorrptganadoresad"><i class="fa fa-bookmark"></i><span>Ganadores</span></a></li>
</ul>
</li>
</ul><!-- /.sidebar-menu -->
@else
<ul class="sidebar-menu">
<li class="treeview">
<li><a href="/registroparticipante"><i class="fa fa-users"></i><span>PARTICIPANTE</span></a></li>
<li><a href="/registropremioparticipante"><i class="fa fa-gift"></i><span>PREMIO</span></a></li>
<li><a href="/registradorrptpremios"><i class="fa fa-bar-chart"></i><span>REPORTE PREMIOS</span></a></li>
<li><a href="/registradorrptganadorjuego"><i class="fa fa-trophy"></i><span>REPORTE GANADORES</span></a></li>
</li>
</ul>
@endif
</section>
</aside>
