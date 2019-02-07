<?php


namespace Instaticket\Entidades;

use Illuminate\Database\Eloquent\Model;

class TipoUsuarios extends Model
{
     protected $table = 'tipo_usuario';
    protected $primaryKey = 'tipo_usuario_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    public static $tipoUsuarioAdmin = 1;
    public static $tipoUsuarioTelevisor = 3;
    public static $tipoUsuarioRegistrador = 2;
    
    protected $fillable = [
        'tipo_usuario_nombre',
        'tipo_usuario_descripcion',
    ];
    
   
    
    
    
     //METODOS

    public static function buscarTodosTipoUsuarios() {
        try {
            $lstTipoUsuarios = TipoUsuarios::get();
            return $lstTipoUsuarios;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
}
