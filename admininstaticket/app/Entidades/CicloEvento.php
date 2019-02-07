<?php

namespace Instaticket\Entidades;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Session;
use Instaticket\Entidades\Estados;

class CicloEvento extends Model
{
    protected $table = 'ciclo_evento';
    protected $primaryKey = 'ciclo_evento_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ciclo_id',
        'evento_id',
        'estado_id',
        'total_premios',
        'juego_puerta',
        'juego_carta',
		'turnos',
    ];
    
    public function ciclo() {
        return $this->belongsTo('Instaticket\Entidades\Ciclo');
    }

    public function evento() {
        return $this->belongsTo('Instaticket\Entidades\Eventos');
    }
    
    public function estado() {
        return $this->belongsTo('Instaticket\Entidades\Estados');
    }

    
    public function logCicloEvento() {
        return $this->hasMany('Instaticket\Entidades\LogCicloEvento', 'id_tabla');
    }
   
   

    //METODOS

    public static function buscarTodosCiclosEventos() {
        try {
            $lstCiclosEventos = CicloEvento::with('ciclo','evento','estado','logCicloEvento')->get();
            return $lstCiclosEventos;
            
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    
    public static function buscarTodosCiclosEventosPuerta() {
        try {
            $lstCiclosEventosPuertas = CicloEvento::with('ciclo','evento','estado','logCicloEvento')
                    ->where('juego_puerta','=','S')
                    ->get();
            return $lstCiclosEventosPuertas;
            
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    public static function buscarTodosCiclosEventosCarta() {
        try {
            $lstCiclosEventosPuertas = CicloEvento::with('ciclo','evento','estado','logCicloEvento')
                    ->where('juego_carta','=','S')
                    ->get();
            return $lstCiclosEventosPuertas;
            
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    
    public static function buscarTodosCiclosEventosActivos() {
        try {
            $lstCiclosEventos = CicloEvento::with('ciclo','evento','estado','logCicloEvento')->where('estado_id', Estados::$estadoActivo)->get();
            return $lstCiclosEventos;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
}
