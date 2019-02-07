<?php

namespace Instaticket\Entidades;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Session;

class MigracionInformacionParticipante extends Model
{
    protected $table = 'migracion_informacion_participante';
    protected $primaryKey = 'mig_inf_par_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'evento_id',
        'mig_inf_par_evento',
        'mig_inf_par_localidad',
        'mig_inf_par_numero_boleto',
        'mig_inf_par_juego',
        'mig_inf_par_vendido',
        'mig_inf_par_actualizo',
        'mig_inf_par_cedula',
        'mig_inf_par_email',
        'mig_inf_par_nombre',
        'mig_inf_par_telefono',
        'mig_inf_par_celular',
    ];
    
    //METODOS

    public static function buscarMigracionInformacionParticipantexBoletoyEvento($evento_id,$mig_inf_par_numero_boleto) {
        try {
            $objMigracionInformacionParticipante = MigracionInformacionParticipante::where('evento_id',$evento_id )->where('mig_inf_par_numero_boleto',$mig_inf_par_numero_boleto )->first();
            return $objMigracionInformacionParticipante;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    
    public static function buscarMigracionInformacionParticipantexNumeroBoletoyEvento($evento_id,$mig_inf_par_numero_boleto) {
        try {
            $objMigracionInformacionParticipante = MigracionInformacionParticipante::where('evento_id',$evento_id )->where('mig_inf_par_numero_boleto',$mig_inf_par_numero_boleto )->first();
            return $objMigracionInformacionParticipante;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
}
