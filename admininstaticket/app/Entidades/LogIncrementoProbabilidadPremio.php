<?php

namespace Instaticket\Entidades;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Session;

class LogIncrementoProbabilidadPremio extends Model
{
    protected $table = 'log_incremento_probabilidad_premio'; 
    
    protected $primaryKey = 'log_inc_pro_pre_id';
    
    public $timestamps = false;
    
    public static $save = 'I';
    public static $update = 'A';
    public static $delete = 'E';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'registro_anterior',
        'registro_nuevo',
        'tipo_accion',
        'fecha_ingresa',
        'id_tabla',
    ];
    
    
    public function incrementoProbabilidadPremio() {
        return $this->belongsTo('Instaticket\Entidades\IncrementoProbabilidadPremio','inc_pro_pre_id');
    }
}
