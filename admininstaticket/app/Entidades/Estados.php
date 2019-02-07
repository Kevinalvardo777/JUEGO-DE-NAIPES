<?php


namespace Instaticket\Entidades;

use Illuminate\Database\Eloquent\Model;

class Estados extends Model
{
    
     protected $table = 'estado'; 
    
    protected $primaryKey = 'estado_id';
    
    public $timestamps = false;
    
    public static $estadoActivo = 1;
    public static $estadoInactivo = 2;
    public static $estadoNoLeido = 6;
    public static $estadoLeido = 5;
    public static $estadoEntregado = 5;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'estado_nombre','estado_descripcion'
    ];
    
    
   
    public function categorias() {
        return $this->hasMany('Instaticket\Entidades\Categoria');
    
    }
    
    
    public function usuario() {
        return $this->hasMany('Instaticket\Entidades\Usuario');
    }
    
}
