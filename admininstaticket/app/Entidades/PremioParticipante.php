<?php

namespace Instaticket\Entidades;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Session;

class PremioParticipante extends Model
{
    protected $table = 'premio_participante';
    protected $primaryKey = 'premio_participante_id';
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'premio_id',
        'participante_id',
        'estado_id',
        'ciclo_evento_premio_id',
        'inc_pro_pre_id',
        'participante_boleto_det_id',
        'codigo_premio',
        'fecha_gano_premio',
        'fecha_entrega_premio'
    ];

    public function premio() {
        return $this->belongsTo('Instaticket\Entidades\Premios');
    }
    
    public function participante() {
        return $this->belongsTo('Instaticket\Entidades\Participante');
    }
    
    public function estado() {
        return $this->belongsTo('Instaticket\Entidades\Estados');
    }
    
    public function cicloEventoPremio() {
        return $this->belongsTo('Instaticket\Entidades\CicloEventoPremio');
    }
    
    public function incrementoProbabilidadPremio() {
        return $this->belongsTo('Instaticket\Entidades\IncrementoProbabilidadPremio','inc_pro_pre_id');
    }
    
    public function participanteBoletoDetalle() {
        return $this->belongsTo('Instaticket\Entidades\ParticipanteBoletoDetalle','participante_boleto_det_id');
    }
    
    //METODOS

    public static function buscarPremioParticipantexCodigoPremio($codigo_premio) {
        try {
            $objPremioParticipante = PremioParticipante::with('premio','participante','estado','cicloEventoPremio','cicloEventoPremio.cicloEvento','cicloEventoPremio.cicloEvento.evento','cicloEventoPremio.cicloEvento.ciclo','incrementoProbabilidadPremio','participanteBoletoDetalle')->where('codigo_premio',$codigo_premio)->first();
            return $objPremioParticipante;
            
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    
    public static function buscarPremioParticipantexCicloEvento($ciclo_evento_id) {
        try {
             $valor = \DB::table('premio_participante')
                     ->select( \DB::raw('premio.premio_nombre,'
                             . 'participante.participante_nombre as participante,'
                             . 'participante.participante_cedula,'
                             . 'participante.participante_email,'
                             . 'participante.participante_celular,'
                             . 'participante.participante_telefono,'
                             . 'participante_boleto_detalle.participante_boleto_det_numero_boleto,'
                             . 'premio_participante.fecha_entrega_premio'))
                     ->join('premio','premio.premio_id','=','premio_participante.premio_id')
                     ->join('participante','participante.participiante_id','=','premio_participante.participante_id')
                     ->join('participante_boleto_detalle','participante_boleto_detalle.participante_boleto_det_id','=','premio_participante.participante_boleto_det_id')
                     ->join('ciclo_evento_premio','ciclo_evento_premio.ciclo_evento_premio_id','=','premio_participante.ciclo_evento_premio_id')
                     ->join('ciclo_evento','ciclo_evento.ciclo_evento_id','=','ciclo_evento_premio.ciclo_evento_id')
                     ->where('ciclo_evento.ciclo_evento_id',$ciclo_evento_id)
                     ->get();
            return $valor;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
}
