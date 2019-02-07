<?php

namespace Instaticket\Entidades;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Session;
use Instaticket\Entidades\Estados;

class IncrementoProbabilidadPremio extends Model
{
     protected $table = 'incremento_probabilidad_premio';
    protected $primaryKey = 'inc_pro_pre_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inc_pro_pre_nombre',
        'inc_pro_pre_descripcion',
        'inc_pro_pre_url_imagen',
        'inc_pro_pre_porcentaje_probabilidad',
        'estado_id',
        'inc_pro_pre_cantidad',
        'inc_pro_pre_cantidad_disponible',
        'inc_pro_pre_cantidad_virtual',
        'inc_pro_pre_fecha_incremento',
        'inc_pro_pre_hora_inicio_incremento',
        'inc_pro_pre_hora_fin_incremento',
        'evento_id',
        'categoria_id',
        'premio_id',
        'ciclo_evento_premio_id',
    ];
    
    public function categoria() {
        return $this->belongsTo('Instaticket\Entidades\Categoria');
    }

    public function evento() {
        return $this->belongsTo('Instaticket\Entidades\Eventos');
    }
    
    public function estado() {
        return $this->belongsTo('Instaticket\Entidades\Estados');
    }
    
     public function premio() {
        return $this->belongsTo('Instaticket\Entidades\Premios');
    }
    
     public function cicloEventoPremio() {
        return $this->belongsTo('Instaticket\Entidades\CicloEventoPremio');
    }

    
    public function logIncrementoProbabilidadPremio() {
        return $this->hasMany('Instaticket\Entidades\LogIncrementoProbabilidadPremio', 'id_tabla');
    }
   
    //METODOS

    public static function buscarTodosIncrementoProbabilidadPremio() {
        try {
            $lst = IncrementoProbabilidadPremio::with('estado', 'logIncrementoProbabilidadPremio', 'categoria', 'cicloEventoPremio','cicloEventoPremio.cicloEvento','cicloEventoPremio.cicloEvento.ciclo','premio','evento')->get();
            return $lst;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
}
