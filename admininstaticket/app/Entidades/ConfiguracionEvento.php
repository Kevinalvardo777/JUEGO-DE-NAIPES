<?php

namespace Instaticket\Entidades;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionEvento extends Model
{
   protected $table = 'configuracion_evento';
    protected $primaryKey = 'configuracion_evento_id';
    public $timestamps = false;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'evento_id',
        'tipo_url_id',
        'configuracion_evento_url',
        'tipo_juego_id',
    ];

    
    public function tipoUrl() {
        return $this->belongsTo('Instaticket\Entidades\TipoUrl');
    }
    
    public function evento() {
        return $this->belongsTo('Instaticket\Entidades\Eventos');
    }

    //METODOS
    public static function buscarConfiguracionEventoxEvento($evento_id,$juego) {
        try {
            $lstConfoguracionEventoxEvento = ConfiguracionEvento::with('tipoUrl','evento')
                    ->where('tipo_juego_id', $juego)
                    ->where('evento_id', $evento_id)->get();
            return $lstConfoguracionEventoxEvento;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
   
}
