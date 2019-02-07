<?php

namespace Instaticket\Entidades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Instaticket\Entidades\Estados;

class Premios extends Model {

    protected $table = 'premio';
    protected $primaryKey = 'premio_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'premio_nombre',
        'premio_descripcion',
        'premio_url_imagen',
        'categoria_id',
        'estado_id',
    ];

    public function estado() {
        return $this->belongsTo('Instaticket\Entidades\Estados');
    }

    public function logPremio() {
        return $this->hasMany('Instaticket\Entidades\LogPremios', 'id_tabla');
    }

    public function categoria() {
        return $this->belongsTo('Instaticket\Entidades\Categoria');
    }

    

    //METODOS

    public static function buscarTodosPremios() {
        try {
            $lstPremios = Premios::with('estado', 'logPremio','categoria')->get();
            return $lstPremios;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    
    public static function buscarTodosPremiosActivos() {
        try {
            $lstPremios = Premios::with('estado', 'logPremio','categoria')->where('estado_id', Estados::$estadoActivo)->get();
            return $lstPremios;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    
    public static function buscarFiltrosPremios($ciclo_evento_id) {
        try {
             $valor = \DB::table('ciclo_evento_premio')
                     ->select( \DB::raw('premio.premio_nombre,'
                             . 'ciclo_evento_premio.ciclo_evento_premio_stock_total,'
                             . 'ciclo_evento_premio.ciclo_evento_premio_stock_disponible,'
                             . '(ciclo_evento_premio.ciclo_evento_premio_stock_total - ciclo_evento_premio.ciclo_evento_premio_stock_disponible) as ciclo_evento_premio_stock_retirado,'
                             . 'CONCAT("Desde: ",ciclo.ciclo_fecha_inicio, " Hasta: ",ciclo.ciclo_fecha_fin ," - Tot. Premios: ",ciclo_evento.total_premios) as ciclo,'
                             . 'evento.evento_nombre'))
                     ->join('premio','ciclo_evento_premio.premio_id','=','premio.premio_id')
                     ->join('ciclo_evento','ciclo_evento_premio.ciclo_evento_id','=','ciclo_evento.ciclo_evento_id')
                     ->join('ciclo','ciclo_evento.ciclo_id','=','ciclo.ciclo_id')
                     ->join('evento','ciclo_evento.evento_id','=','evento.evento_id')
                     ->where('ciclo_evento.ciclo_evento_id',$ciclo_evento_id)
                     ->get();
            return $valor;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    
    /*public static function buscarTodosFiltrosPremios($ciclo_id) {
        try {
             $valor = \DB::table('ciclo_premios')
                     ->select( \DB::raw('premios.nombre,'
                             . 'ciclo_premios.stock_total,'
                             . 'ciclo_premios.stock_disponible,'
                             . '(ciclo_premios.stock_total - ciclo_premios.stock_disponible) as stock_retirado'
                             . ',CONCAT("Desde: ",ciclos.fecha_inicio, " Hasta: ",ciclos.fecha_fin ," - Tot. Premios: ",ciclos.total_premios) as ciclo'))
                     ->join('premios','ciclo_premios.premio_id','=','premios.premio_id')
                     ->join('ciclos','ciclo_premios.ciclo_id','=','ciclos.ciclo_id')
                     ->where('ciclo_premios.ciclo_id',$ciclo_id)
                     ->get();
            return $valor;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
*/
}
