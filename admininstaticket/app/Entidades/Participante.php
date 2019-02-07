<?php

namespace Instaticket\Entidades;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Session;

class Participante extends Model
{
    protected $table = 'participante';
    protected $primaryKey = 'participiante_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'participante_cedula',
        'participante_nombre',
        'participante_email',
        'participante_telefono',
        'participante_celular'
    ];

    
    public function logParticipante() {
        return $this->hasMany('Instaticket\Entidades\LogParticipante', 'id_tabla');
    }
    
    public function participanteBoletoDetalle() {
        return $this->hasMany('Instaticket\Entidades\ParticipanteBoletoDetalle', 'participante_id');
    }



    //METODOS

    public static function buscarTodosParticipantes() {
        try {
            $lstParticipante = Participante::with('logParticipante','participanteBoletoDetalle')->get();
            return $lstParticipante;
            
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    
    public static function buscarParticipantexCedula($participante_cedula) {
        try {
            $lstParticipante = Participante::with('logParticipante','participanteBoletoDetalle')->where('participante_cedula',$participante_cedula )->first();
            return $lstParticipante;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
}
