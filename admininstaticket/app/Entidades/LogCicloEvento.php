<?php

namespace Instaticket\Entidades;

use Illuminate\Database\Eloquent\Model;

class LogCicloEvento extends Model
{
    protected $table = 'log_ciclo_evento'; 
    
    protected $primaryKey = 'log_ciclo_evento_id';
    
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
    
    
    public function cicloEvento() {
        return $this->belongsTo('Instaticket\Entidades\CicloEvento','ciclo_evento_id');
    }
}
