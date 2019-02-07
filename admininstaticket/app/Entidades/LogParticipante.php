<?php

namespace Instaticket\Entidades;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Session;

class LogParticipante extends Model
{
     protected $table = 'log_participante'; 
    
    protected $primaryKey = 'log_participante_id';
    
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
    
    
    public function participante() {
        return $this->belongsTo('Instaticket\Entidades\Participante','participante_id');
    }
}
