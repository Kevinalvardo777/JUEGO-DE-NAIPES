@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="../plugins/select2/select2.min.css">
@endsection
@section('htmlheader_title')
Configuración Evento
@endsection
@section('header')
<h1>
    <i class="fa fa-dashboard"></i>   <i class="fa fa-picture-o"></i>   CONFIGURACIÓN EVENTO
</h1>
<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Configuración</a></li>
    <li class="active">Evento</li>
</ol>
@endsection
@section('main-content')
<div class="col-md-12">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li  class="active"><a id="enlace" href="#tab_1" data-toggle="tab">Juego Puerta Ingreso/Actualización</a></li>
            <li ><a href="#tab_2" data-toggle="tab">Juego Carta Ingreso/Actualización</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <form class="form-horizontal" method="post" id="formConfiguracionEvento" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="tipo_juego_id" value="1">
                    <input type="hidden" name="video_id" id="video_id" value="{{$objTipoUrlVideo->parametro_general_valor}}">
                    <input type="hidden" name="sonido_id" id="sonido_id" value="{{$objTipoUrlSonido->parametro_general_valor}}">
                    <input type="hidden" name="configuracion_evento_id" id="configuracion_evento_id" >
                    <button type="button" id="btn_buscarEvento" name="btn_buscarEvento" class="btn btn-warning hide"></button>
                    <div class="box-body">
                        <br/>
                        <div class="col-sm-6">
                            <div class="form-group" id="div_evento_id">
                                <label class="col-lg-2  control-label">Evento</label>
                                <div class="col-lg-10">
                                    <select id="evento_id" name="evento_id" class="form-control select2 " style="width: 100%;" tabindex="-1" aria-hidden="true">
                                        @foreach($lstEventJuegosP as $jp)
                                        @if($jp->evento->estado_id == $estadoActivo)
                                        <option value="{{$jp->evento->evento_id}}">{{$jp->evento->evento_nombre}}</option>
                                        @else
                                        <option disabled="disabled" title="{{$jp->evento->evento_nombre}} , no puede ser utilizada porque se encuentra inactiva." value="{{$jp->evento->evento_id}}">{{$jp->evento->evento_nombre}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    <label  id="lbl_evento_id" class=" control-label hide"></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group hide" id="div_tipo_url_id">
                                <label class="col-lg-2  control-label">Tipo</label>
                                <div class="col-lg-10">
                                    <select id="tipo_url_id" name="tipo_url_id" class="form-control select2 " style="width: 100%;" tabindex="-1" aria-hidden="true">
                                        <option value="0" >Seleccione una opción</option>
                                        @foreach($lstTipoUrl as $p)
                                        <option value="{{$p->tipo_url_id}}">{{$p->tipo_url_nombre}}</option>
                                        @endforeach
                                    </select>
                                    <label  id="lbl_tipo_url_id" class=" control-label hide"></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group" id="div_configuracion_evento_url">
                                <label class="col-lg-2 ">Archivo</label>
                                <div class="col-lg-10">
                                    <input type="file" id="configuracion_evento_url" name="configuracion_evento_url"  minlength="3" maxlength="150" class="form-control">
                                    <label  id="lbl_configuracion_evento_url" class=" control-label hide"></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <button type="button" id="btn_aniadir" name="btn_aniadir" class="btn btn-warning"><i class="fa fa-copy"></i>&nbsp;&nbsp;Añadir</button>
                                </div> 
                            </div> 
                        </div>
                        <div class="box-body">
                            <div class="col-sm-12 table-responsive">
                                <table id="tbl_C" class="table table-bordered table-striped dataTable" cellspacing="0" >
                                    <thead>
                                        <tr>
                                            <th>TIPO CONFIGURACION</th>
                                            <th>URL CONFIGURACION</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                </form>
            </div>
        </div>
        <div class="tab-pane" id="tab_2">
            <form class="form-horizontal" method="post" id="formConfiguracionEventoC" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="tipo_juego_id" value="2">
                <input type="hidden" name="video_id" id="video_id_c" value="{{$objTipoUrlVideo->parametro_general_valor}}">
                <input type="hidden" name="sonido_id" id="sonido_id_c" value="{{$objTipoUrlSonido->parametro_general_valor}}">
                <input type="hidden" name="configuracion_evento_id" id="configuracion_evento_id_c" >
                <button type="button" id="btn_buscarEventoC" name="btn_buscarEventoC" class="btn btn-warning hide"></button>
                <div class="box-body">
                    <br/>
                    <div class="col-sm-6">
                        <div class="form-group" id="div_evento_c_id">
                            <label class="col-lg-2  control-label">Evento</label>
                            <div class="col-lg-10">
                                <select id="evento_id_c" name="evento_id" class="form-control select2 " style="width: 100%;" tabindex="-1" aria-hidden="true">
                                    @foreach($lstEventJuegosC as $p)
                                    @if($p->evento->estado_id == $estadoActivo)
                                    <option value="{{$p->evento->evento_id}}">{{$p->evento->evento_nombre}}</option>
                                    @else
                                    <option disabled="disabled" title="{{$p->evento->evento_nombre}} , no puede ser utilizada porque se encuentra inactiva." value="{{$p->evento->evento_id}}">{{$p->evento->evento_nombre}}</option>
                                    @endif
                                    @endforeach
                                </select>
                                <label  id="lbl_evento_id_c" class=" control-label hide"></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group" id="div_tipo_url_id_c">
                            <label class="col-lg-2  control-label">Tipo</label>
                            <div class="col-lg-10">
                                <select id="tipo_url_id_c" name="tipo_url_id" class="form-control select2 " style="width: 100%;" tabindex="-1" aria-hidden="true">
                                    <option value="0" >Seleccione una opción</option>
                                    @foreach($lstTipoUrlC as $p)
                                    <option value="{{$p->tipo_url_id}}">{{$p->tipo_url_nombre}}</option>
                                    @endforeach
                                </select>
                                <label  id="lbl_tipo_url_id_c" class=" control-label hide"></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group" id="div_configuracion_evento_url_c">
                            <label class="col-lg-2 ">Archivo</label>
                            <div class="col-lg-10">
                                <input type="file" id="configuracion_evento_url_c" name="configuracion_evento_url"  minlength="3" maxlength="150" class="form-control">
                                <label  id="lbl_configuracion_evento_url_c" class=" control-label hide"></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="button" id="btn_aniadirC" name="btn_aniadir_c" class="btn btn-warning"><i class="fa fa-copy"></i>&nbsp;&nbsp;Añadir</button>
                            </div> 
                        </div> 
                    </div>
                    <div class="box-body">
                        <div class="col-sm-12 table-responsive">
                            <table id="tbl_J_C" class="table table-bordered table-striped dataTable" cellspacing="0" >
                                <thead>
                                    <tr>
                                        <th>TIPO CONFIGURACION</th>
                                        <th>URL CONFIGURACION</th>
                                        <th></th>
                                    </tr>
                                </thead>
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
<script src="../plugins/select2/select2.full.min.js"></script>
<script>$.ajaxSetup({headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")}});
$(document).ready(function () {
    $('#tbl_C').DataTable({});
    $('#tbl_J_C').DataTable({});
    $("#btn_buscarEvento").click();
    $("#btn_buscarEventoC").click();
});

var error = false;
var locations = [];

$("#btn_cancelar").click(function () {
    location.reload();
});

function eliminardetalle(valor) {
    $("#configuracion_evento_id").val(valor);
    $.blockUI({message: '<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'});
    $("#formConfiguracionEvento").attr("action", "/eliminarConfiguracionEvento");
    $("#formConfiguracionEvento").submit();
    setTimeout($.unblockUI, 1000);
}

function eliminardetalleC(valor) {
    $("#configuracion_evento_id_c").val(valor);
    $.blockUI({message: '<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'});
    $("#formConfiguracionEventoC").attr("action", "/eliminarConfiguracionEvento");
    $("#formConfiguracionEventoC").submit();
    setTimeout($.unblockUI, 1000);
}

$("#btn_buscarEvento").click(function () {
    var p1 = $("#evento_id").val();
    $('#mensajes').removeClass('alert alert-danger');
    $('#mensajes').addClass('hide');
    $('#textoMensaje').text("");
    var bValid1 = validarCampoLleno(p1, "Evento", "evento_id");
    if (bValid1) {
        $.ajax({url: "/buscarConfiguracionEvento", type: "post", _token: "{{ csrf_token() }}", async: true, data: {evento_id: p1,'juego':1},
            beforeSend: function () {
                $.blockUI({message: '<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'})
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var gnrMensaje = $("#gnrMensaje");
                toTop();
                if (jqXHR.status == "401") {
                    gnrMensaje.text(textStatus + " - " + errorThrown + " ,Su sesión ha caducado, por favor de click en Salir y vuelva a Ingresar.");
                    $("#gnrError").modal("show")
                } else {
                    $("#mensajes").addClass("alert alert-danger");
                    $("#mensajes").removeClass("hide");
                    var textoMensaje = $("#textoMensaje");
                    textoMensaje.text("ERROR: " + jqXHR.status + " - " + textStatus + " - " + errorThrown)
                }
            },
            success: function (data) {
                var t = $('#tbl_C').DataTable();
                t.clear().draw();
                $("#div_tipo_url_id").removeClass("hide");
                $("#div_configuracion_evento_url").removeClass("hide");
                $("#btn_aniadir").removeClass("hide");
                $("#tipo_url_id option").each(function () {
                    if ($(this).val() != "0") {
                        $(this).removeAttr("disabled");
                    }
                });
                locations = [];
                if (data == "") {
                    $("#mensajes").addClass("alert alert-danger");
                    $("#mensajes").removeClass("hide");
                    $("#textoMensaje").text("No se han encontrado resultado de configuraciones para este evento.")
                } else {
                    $('#mensajes').removeClass('alert alert-danger');
                    $('#mensajes').addClass('hide');
                    locations = [];
                    for (var i = 0; i < data.length; i++) {
                        t.row.add([data[i].tipo_url.tipo_url_nombre,
                            data[i].configuracion_evento_url,
                            '<button type="button" class="btn btn-warning btn-sm" onclick=eliminardetalle(' + data[i].configuracion_evento_id + ') title="Eliminar relacion"><i class="fa fa-close"></i></button>'
                        ]).draw(false);
                        locations = locations.concat(data[i]);
                        $("#tipo_url_id option").each(function () {
                            if ($(this).val() == "0") {
                                console.log("es cero ");
                            } else {
                                if (Number($(this).val()) == data[i].tipo_url_id) {
                                    $(this).prop('disabled', 'disabled');
                                }
                            }
                        });
                    }
                    $("#tipo_url_id").change();
                }
            },
            complete: function () {
                setTimeout($.unblockUI, 1000);
            }
        });
    }
});
$("#btn_buscarEventoC").click(function () {
    var p1 = $("#evento_id_c").val();
    $('#mensajes').removeClass('alert alert-danger');
    $('#mensajes').addClass('hide');
    $('#textoMensaje').text("");
    var bValid1 = validarCampoLleno(p1, "Evento", "evento_id");
    if (bValid1) {
        $.ajax({url: "/buscarConfiguracionEvento", type: "post", _token: "{{ csrf_token() }}", async: true, data: {evento_id: p1,'juego':2},
            beforeSend: function () {
                $.blockUI({message: '<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'})
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var gnrMensaje = $("#gnrMensaje");
                toTop();
                if (jqXHR.status == "401") {
                    gnrMensaje.text(textStatus + " - " + errorThrown + " ,Su sesión ha caducado, por favor de click en Salir y vuelva a Ingresar.");
                    $("#gnrError").modal("show")
                } else {
                    $("#mensajes").addClass("alert alert-danger");
                    $("#mensajes").removeClass("hide");
                    var textoMensaje = $("#textoMensaje");
                    textoMensaje.text("ERROR: " + jqXHR.status + " - " + textStatus + " - " + errorThrown)
                }
            },
            success: function (data) {
                var t = $('#tbl_J_C').DataTable();
                t.clear().draw();
                $("#div_tipo_url_id").removeClass("hide");
                $("#div_configuracion_evento_url").removeClass("hide");
                $("#btn_aniadir").removeClass("hide");
                $("#tipo_url_id option").each(function () {
                    if ($(this).val() != "0") {
                        $(this).removeAttr("disabled");
                    }
                });
                locations = [];
                if (data == "") {
                    $("#mensajes").addClass("alert alert-danger");
                    $("#mensajes").removeClass("hide");
                    $("#textoMensaje").text("No se han encontrado resultado de configuraciones para este evento.")
                } else {
                    $('#mensajes').removeClass('alert alert-danger');
                    $('#mensajes').addClass('hide');
                    locations = [];
                    for (var i = 0; i < data.length; i++) {
                        t.row.add([data[i].tipo_url.tipo_url_nombre,
                            data[i].configuracion_evento_url,
                            '<button type="button" class="btn btn-warning btn-sm" onclick=eliminardetalleC(' + data[i].configuracion_evento_id + ') title="Eliminar relacion"><i class="fa fa-close"></i></button>'
                        ]).draw(false);
                        locations = locations.concat(data[i]);
                        $("#tipo_url_id option").each(function () {
                            if ($(this).val() == "0") {
                                console.log("es cero ");
                            } else {
                                if (Number($(this).val()) == data[i].tipo_url_id) {
                                    $(this).prop('disabled', 'disabled');
                                }
                            }
                        });
                    }
                    $("#tipo_url_id").change();
                }
            },
            complete: function () {
                setTimeout($.unblockUI, 1000);
            }
        });
    }
});
$("#evento_id").change(function () {
    $('#mensajes').removeClass('alert alert-danger');
    $('#mensajes').addClass('hide');
    var t = $('#tbl_C').DataTable();
    t.clear().draw();
    $("#div_tipo_url_id").addClass("hide");
    $("#div_configuracion_evento_url").addClass("hide");
    $("#btn_aniadir").addClass("hide");
    $("#btn_buscarEvento").click();
    locations = [];
});
$("#evento_id_c").change(function () {
    $('#mensajes').removeClass('alert alert-danger');
    $('#mensajes').addClass('hide');
    var t = $('#tbl_J_C').DataTable();
    t.clear().draw();
    $("#div_tipo_url_id_c").addClass("hide");
    $("#div_configuracion_evento_url_c").addClass("hide");
    $("#btn_aniadirC").addClass("hide");
    $("#btn_buscarEventoC").click();
    locations = [];
});
$("#tipo_url_id").change(function () {
    var p = $("#tipo_url_id").val();
    if (p != 0) {
        var existe = false;
        var parametro = $("#tipo_url_id").val();
        for (var i = 0; i < locations.length; i++) {
            if (Number(locations[i].tipo_url_id) === Number(parametro)) {
                existe = true;
            }
        }
        if (existe) {
            $('#mensajes').addClass('alert alert-danger');
            $('#mensajes').removeClass('hide');
            var textoMensaje = $("#textoMensaje");
            textoMensaje.text("El tipo ya se encuentra configurado para este evento.");
            $("#btn_aniadir").addClass("hide")
        } else {
            $('#mensajes').removeClass('alert alert-danger');
            $('#mensajes').addClass('hide');
            $("#configuracion_evento_url").change();
            $("#btn_aniadir").removeClass("hide")
        }
    } else {
        $('#mensajes').removeClass('alert alert-danger');
        $('#mensajes').addClass('hide');
        $("#configuracion_evento_url").change();
        $("#btn_aniadir").addClass("hide");
    }
});

$("#tipo_url_id").change(function () {
    var p = $("#tipo_url_id").val();
    if (p != 0) {
        var existe = false;
        var parametro = $("#tipo_url_id").val();
        for (var i = 0; i < locations.length; i++) {
            if (Number(locations[i].tipo_url_id) === Number(parametro)) {
                existe = true;
            }
        }
        if (existe) {
            $('#mensajes').addClass('alert alert-danger');
            $('#mensajes').removeClass('hide');
            var textoMensaje = $("#textoMensaje");
            textoMensaje.text("El tipo ya se encuentra configurado para este evento.");
            $("#btn_aniadir").addClass("hide")
        } else {
            $('#mensajes').removeClass('alert alert-danger');
            $('#mensajes').addClass('hide');
            $("#configuracion_evento_url").change();
            $("#btn_aniadir").removeClass("hide")
        }
    } else {
        $('#mensajes').removeClass('alert alert-danger');
        $('#mensajes').addClass('hide');
        $("#configuracion_evento_url").change();
        $("#btn_aniadir").addClass("hide");
    }
});


$("#configuracion_evento_url_c").change(function () {
    var b = this.files[0];
    error = false;
    $("#div_configuracion_evento_url_c").removeClass("has-error");
    $("#lbl_configuracion_evento_url_c").addClass("hide");
    $("#lbl_configuracion_evento_url_c").text("");
    if (b != null) {
        if (!window.FileReader) {
            existe = false;
            $("#div_configuracion_evento_url_c").addClass("has-error");
            $("#lbl_configuracion_evento_url_c").removeClass("hide");
            $("#lbl_configuracion_evento_url_c").text("Error ! El navegador no soporta la lectura de archivos.");
            return
        }
        var video = $("#video_id_c").val();
        var sonido = $("#sonido_id_c").val();
        var tipourl = $("#tipo_url_id_c").val();
        if (video === tipourl) {
            if (!(/\.(mp4)$/i).test(b.name)) {
                $("#div_configuracion_evento_url_c").addClass("has-error");
                $("#lbl_configuracion_evento_url_c").removeClass("hide");
                $("#lbl_configuracion_evento_url_c").text("Error ! El archivo a adjuntar no es un video con extención mp4.");
                error = true;
            }
        } else {
            if (sonido === tipourl) {
                if (!(/\.(mp3)$/i).test(b.name)) {
                    $("#div_configuracion_evento_url_c").addClass("has-error");
                    $("#lbl_configuracion_evento_url_c").removeClass("hide");
                    $("#lbl_configuracion_evento_url_c").text("Error ! El archivo a adjuntar no es un sonido con extención mp3.");
                    error = true;
                }
            } else {
                if (!(/\.(jpg|png|gif)$/i).test(b.name)) {
                    $("#div_configuracion_evento_url_c").addClass("has-error");
                    $("#lbl_configuracion_evento_url_c").removeClass("hide");
                    $("#lbl_configuracion_evento_url_c").text("Error ! El archivo a adjuntar no es una imagen.");
                    error = true;
                }
            }
        }
    }
});

$("#btn_aniadir").click(function () {
    var p1 = $("#evento_id").val(), p2 = $("#tipo_url_id").val(), p3 = $("#configuracion_evento_url").val();
    $('#mensajes').removeClass('alert alert-danger');
    $('#mensajes').addClass('hide');
    $('#textoMensaje').text("");
    var bValid = false;
    var bValid1 = validarCampoLleno(p1, "Evento", "evento_id");
    var bValid2 = validarCampoLleno(p2, "Tipo", "tipo_url_id");
    var bValid3 = validarCampoLleno(p3, "Archivo", "configuracion_evento_url");
    bValid = bValid1 && bValid2 && bValid3;
    if (error) {
        $('#mensajes').addClass('alert alert-danger');
        $('#mensajes').removeClass('hide');
        var textoMensaje = $("#textoMensaje");
        textoMensaje.text("Escoga un Archivo correcto");
    } else {
        if (!bValid) {
            $('#mensajes').addClass('alert alert-danger');
            $('#mensajes').removeClass('hide');
            var textoMensaje = $("#textoMensaje");
            textoMensaje.text("Escoga un Archivo correcto");
        } else {
            $.blockUI({message: '<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'});
            $("#formConfiguracionEvento").attr("action", "/guardarConfiguracionEvento");
            $("#formConfiguracionEvento").submit();
            setTimeout($.unblockUI, 1000);
        }
    }
});




$("#btn_aniadirC").click(function () {
    var p1 = $("#evento_id_c").val(), p2 = $("#tipo_url_id_c").val(), p3 = $("#configuracion_evento_url_c").val();
    $('#mensajes').removeClass('alert alert-danger');
    $('#mensajes').addClass('hide');
    $('#textoMensaje').text("");
    var bValid = false;
    var bValid1 = validarCampoLleno(p1, "Evento", "evento_id_c");
    var bValid2 = validarCampoLleno(p2, "Tipo", "tipo_url_id_c");
    var bValid3 = validarCampoLleno(p3, "Archivo", "configuracion_evento_url_c");
    bValid = bValid1 && bValid2 && bValid3;
    if (error) {
        $('#mensajes').addClass('alert alert-danger');
        $('#mensajes').removeClass('hide');
        var textoMensaje = $("#textoMensaje");
        textoMensaje.text("Escoga un Archivo correcto");
    } else {
        if (!bValid) {
            $('#mensajes').addClass('alert alert-danger');
            $('#mensajes').removeClass('hide');
            var textoMensaje = $("#textoMensaje");
            textoMensaje.text("Escoga un Archivo correcto");
        } else {
            $.blockUI({message: '<h4><img src="../img/1.gif" /></br> PROCESANDO... Espere un momento por favor </h4>'});
            $("#formConfiguracionEventoC").attr("action", "/guardarConfiguracionEvento");
            $("#formConfiguracionEventoC").submit();
            setTimeout($.unblockUI, 1000);
        }
    }
});
</script>
@endsection
