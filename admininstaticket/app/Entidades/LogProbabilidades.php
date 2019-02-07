<?php


namespace Instaticket\Entidades;

use Illuminate\Database\Eloquent\Model;

class LogProbabilidades extends Model
{
    protected $table = 'log_probabilidades'; 
    
    protected $primaryKey = 'log_probabilidad_id';
    
    public $timestamps = false;
    
    public static $save = 'INGRESO';
    public static $update = 'ACTUALIZACIÓN';
    public static $delete = 'ELIMINACIÓN';
    
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
    
    
    public function probabilidades() {
        return $this->belongsTo('Instaticket\Entidades\Probabilidadad','probabilidades_id');
    }
    
    
}
