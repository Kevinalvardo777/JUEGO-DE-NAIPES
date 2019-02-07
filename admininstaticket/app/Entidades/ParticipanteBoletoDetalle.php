<?php

namespace Instaticket\Entidades;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Session;

class ParticipanteBoletoDetalle extends Model
{
    protected $table = 'participante_boleto_detalle';
    protected $primaryKey = 'participante_boleto_det_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'participante_id',
        'participante_boleto_det_numero_boleto',
    ];

    
    public function participante() {
        return $this->belongsTo('Instaticket\Entidades\Participante','participante_id');
    }



    //METODOS
    public static function buscarDetalleParticipantexNumeroBoleto($participante_boleto_det_numero_boleto) {
        try {
            $objDetalleParticipante = ParticipanteBoletoDetalle::with('participante')->where('participante_boleto_det_numero_boleto',$participante_boleto_det_numero_boleto )->first();
            return $objDetalleParticipante;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    
}
