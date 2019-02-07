<?php

namespace Instaticket\Entidades;

use Illuminate\Database\Eloquent\Model;

class TipoUrl extends Model
{
    protected $table = 'tipo_url';
    protected $primaryKey = 'tipo_url_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo_url_nombre',
        'tipo_url_descripcion'
    ];

    

    //METODOS
    
        public static function buscarTipoUrl($tipo) {
        try {
            $lst = TipoUrl::where('tipo_juego_id','=',$tipo)->get();
            return $lst;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }

    public static function buscarTodosTipoUrl() {
        try {
            $lst = TipoUrl::get();
            return $lst;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    
}
