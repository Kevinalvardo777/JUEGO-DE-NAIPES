<?php

namespace Instaticket\Entidades;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Session;

class Ciclo extends Model
{
    protected $table = 'ciclo';
    protected $primaryKey = 'ciclo_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ciclo_nombre',
        'ciclo_descripcion',
        'ciclo_fecha_inicio',
        'ciclo_fecha_fin',
    ];

    
    public function logCiclo() {
        return $this->hasMany('Instaticket\Entidades\LogCiclo', 'id_tabla');
    }

   
   /* public function cicloPremio() {
        return $this->hasMany('Instaticket\Entidades\CicloPremios');
    }*/


    //METODOS

    public static function buscarTodosCiclos() {
        try {
            $lstCiclos = Ciclo::with('logCiclo')->get();
            return $lstCiclos;
            
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    
    
    /*public static function buscarTotalPremios() {
        try {
             $valor = \DB::table('ciclos')
                     ->select(\DB::raw('COALESCE(sum(total_premios),0) as total_premios'))
                     ->where('estado_id', 1)
                     ->first();
            return $valor;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }*/
}
