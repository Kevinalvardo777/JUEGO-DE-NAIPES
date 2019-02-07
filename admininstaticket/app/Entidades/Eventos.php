<?php

namespace Instaticket\Entidades;

use Illuminate\Database\Eloquent\Model;
use Instaticket\Entidades\Estados;

class Eventos extends Model {

    protected $table = 'evento';
    protected $primaryKey = 'evento_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'evento_nombre',
        'evento_descripcion',
        'evento_total_jugadas',
        'evento_url_imagen',
        'estado_id',
    ];

    public function estado() {
        return $this->belongsTo('Instaticket\Entidades\Estados');
    }
    
    public function logEventos() {
        return $this->hasMany('Instaticket\Entidades\LogEventos', 'id_tabla');
    }

     public function evento() {
        return $this->belongsTo('Instaticket\Entidades\Eventos');
    }
   

    //METODOS
   

    public static function buscarTodosEventos() {
        try {
            $lstEventos = Eventos::with('estado', 'logEventos')->get();
            return $lstEventos;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    
    //METODOS

    public static function buscarTodosEventosActivos() {
        try {
            $lstEventos = Eventos::with('estado', 'logEventos')->where('estado_id', Estados::$estadoActivo )->get();
            return $lstEventos;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }

}
