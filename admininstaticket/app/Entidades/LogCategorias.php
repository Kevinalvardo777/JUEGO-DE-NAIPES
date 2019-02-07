<?php


namespace Instaticket\Entidades;

use Illuminate\Database\Eloquent\Model;

class LogCategorias extends Model
{
    protected $table = 'log_categoria'; 
    
    protected $primaryKey = 'log_categoria_id';
    
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
    
    
    public function categorias() {
        return $this->belongsTo('Instaticket\Entidades\Categoria','categoria_id');
    }
    
}
