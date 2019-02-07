<?php

namespace Instaticket\Entidades;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Session;

class CicloEventoPremio extends Model
{
    protected $table = 'ciclo_evento_premio';
    protected $primaryKey = 'ciclo_evento_premio_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ciclo_evento_id',
        'premio_id',
        'ciclo_evento_premio_stock_total',
        'ciclo_evento_premio_stock_disponible',
        'ciclo_evento_premio_stock_virtual',
        'estado_id',
    ];
    
    public function cicloEvento() {
        return $this->belongsTo('Instaticket\Entidades\CicloEvento');
    }

    public function premio() {
        return $this->belongsTo('Instaticket\Entidades\Premios');
    }

    public function logCicloEventoPremio() {
        return $this->hasMany('Instaticket\Entidades\LogCicloEventoPremio', 'id_tabla');
    }
   
     public function estado() {
        return $this->belongsTo('Instaticket\Entidades\Estados');
    }
   

    //METODOS

    public static function buscarTodosCiclosEventosPremios() {
        try {
            $lstCiclosEventoPremios = CicloEventoPremio::with('cicloEvento','premio','logCicloEventoPremio','estado','cicloEvento.ciclo','cicloEvento.evento')->get();
            return $lstCiclosEventoPremios;
            
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    
    public static function buscarTotalCicloEventoPremiosxCicloEvento($ciclo_evento_id) {
        try {
             $valor = \DB::table('ciclo_evento_premio')
                     ->select(\DB::raw('COALESCE(sum(ciclo_evento_premio.ciclo_evento_premio_stock_total),0) as total_premios'))
                     ->join('ciclo_evento','ciclo_evento.ciclo_evento_id','=','ciclo_evento_premio.ciclo_evento_id')
                     ->where('ciclo_evento.ciclo_evento_id', $ciclo_evento_id)
                     ->first();
            return $valor;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    
    public static function buscarDisponibleCicloEventoPremiosxCicloEvento($ciclo_evento_id) {
        try {
             $valor = \DB::table('ciclo_evento_premio')
                     ->select(\DB::raw('COALESCE(sum(ciclo_evento_premio.ciclo_evento_premio_stock_disponible),0) as total_disponible'))
                     ->join('ciclo_evento','ciclo_evento.ciclo_evento_id','=','ciclo_evento_premio.ciclo_evento_id')
                     ->where('ciclo_evento.ciclo_evento_id', $ciclo_evento_id)
                     ->first();
            return $valor;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    
    public static function buscarRetiradosCicloEventoPremiosxCicloEvento($ciclo_evento_id) {
        try {
             $valor = \DB::table('ciclo_evento_premio')
                     ->select(\DB::raw('COALESCE(sum(ciclo_evento_premio.ciclo_evento_premio_stock_total),0) - COALESCE(sum(ciclo_evento_premio.ciclo_evento_premio_stock_disponible),0) as total_retirado'))
                     ->join('ciclo_evento','ciclo_evento.ciclo_evento_id','=','ciclo_evento_premio.ciclo_evento_id')
                     ->where('ciclo_evento.ciclo_evento_id', $ciclo_evento_id)
                     ->first();
            return $valor;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    
}
