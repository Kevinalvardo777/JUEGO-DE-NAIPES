
function marcarCampoError(valor, mensaje, id){
	 $('#div_' + id).addClass('has-error');
     $('#lbl_' + id).removeClass('hide');
     $('#lbl_' + id).text("Error ! " + mensaje);
}


function descarmarCampoError(){
	 $('div[id^=div_]').removeClass('has-error');
     $('label[id^=lbl_]').addClass('hide');
}

/**
 * 
 * @param {type} valor
 * @param {type} nombreCampo
 * @param {type} id
 * @returns {Boolean}
 */
function validarCampoLleno(valor, nombreCampo, id) {
    if (valor === "" || valor === "0" || valor === undefined || valor === null || valor.length === 0) {
//        $('#mensajes').addClass('alert alert-danger');
//        $('#mensajes').removeClass('hide');
        $('#div_' + id).addClass('has-error');
        $('#lbl_' + id).removeClass('hide');
        $('#lbl_' + id).text("Error ! " + nombreCampo + " no puede estar vacío ");
//        $('#textoMensaje').append("Error ! " + nombreCampo + " no puede estar vacío " + '<br/>');
        return false;
    } else {
        $('#div_' + id).removeClass('has-error');
        $('#lbl_' + id).addClass('hide');
//        $('#mensajes').removeClass('alert alert-danger');
//        $('#mensajes').addClass('hide');
//        $('#textoMensaje').text("");
        return true;
    }
}

/**
 * 
 * @param {type} valor
 * @param {type} mensaje
 * @param {type} id
 * @returns {Boolean}
 */
function validarBoolean(valor, mensaje, id) {
    if (!valor) {
//        $('#mensajes').addClass('alert alert-danger');
//        $('#mensajes').removeClass('hide');
        $('#div_' + id).addClass('has-error');
        $('#lbl_' + id).removeClass('hide');
        $('#lbl_' + id).text("Error ! " + mensaje);
//        $('#textoMensaje').append("Error ! " + mensaje + '<br/>');
        return false;
    } else {
        $('#div_' + id).removeClass('has-error');
        $('#lbl_' + id).addClass('hide');
//        $('#mensajes').removeClass('alert alert-danger');
//        $('#mensajes').addClass('hide');
//        $('#textoMensaje').text("");
        return true;
    }
}


/**
 * 
 * @param {type} valor
 * @param {type} nombreCampo
 * @returns {Boolean}
 */
function validarCampoLlenoModal(valor, nombreCampo, id) {
    if (valor === "" || valor === "0" || valor === undefined || valor.length === 0) {
//        $('#mensajesModal').addClass('alert alert-danger');
//        $('#mensajesModal').removeClass('hide');
        $('#div_' + id).addClass('has-error');
        $('#lbl_' + id).removeClass('hide');
        $('#lbl_' + id).text("Error ! " + nombreCampo);
//        $('#textoMensajeModal').append("Error ! " + nombreCampo + " no puede estar vacío " + '<br/>');
        return false;
    } else {
        $('#div_' + id).removeClass('has-error');
        $('#lbl_' + id).addClass('hide');
//        $('#mensajesModal').removeClass('alert alert-danger');
//        $('#mensajesModal').addClass('hide');
//        $('#textoMensajeModal').text("");
        return true;
    }
}

/**
 * 
 * @param {type} id
 * @param {type} regExp
 * @param {type} message
 * @returns {undefined}
 */
function validarCampoRegExp(id, valor, regExp, message)
{
    var pattern = new RegExp(regExp);
    var valorCodigo = pattern.test(valor);
    if (!valorCodigo) {
        $("#" + id).focus();
        $('#div_' + id).addClass('has-error');
        $('#lbl_' + id).removeClass('hide');
        $('#lbl_' + id).text("Error ! " + message);
        return false;
    } else {
        $('#div_' + id).removeClass('has-error');
        $('#lbl_' + id).addClass('hide');
        $('#lbl_' + id).text("");
        return true;
    }
}