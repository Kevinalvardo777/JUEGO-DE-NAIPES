<?php

namespace Instaticket\Entidades;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Session;

class LogCicloEventoPremio extends Model
{
    protected $table = 'log_ciclo_evento_premio'; 
    
    protected $primaryKey = 'log_ciclo_evento_premio_id';
    
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
    
    
    public function cicloEventoPremio() {
        return $this->belongsTo('Instaticket\Entidades\CicloEventoPremio','ciclo_evento_premio_id');
    }
}
