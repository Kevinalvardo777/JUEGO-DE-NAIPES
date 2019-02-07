<?php

namespace Instaticket\Entidades;

use Illuminate\Database\Eloquent\Model;
use Instaticket\Entidades\Estados;
use Illuminate\Support\Facades\Session;

class Categoria extends Model {

    protected $table = 'categoria';
    protected $primaryKey = 'categoria_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'categoria_nombre',
        'categoria_descripcion',
        'categoria_porcentaje_probabilidad',
        'categoria_rango_minimo',
        'categoria_rango_maximo',
        'estado_id',
    ];

    public function estado() {
        return $this->belongsTo('Instaticket\Entidades\Estados');
    }

    public function logCategoria() {
        return $this->hasMany('Instaticket\Entidades\LogCategorias', 'id_tabla');
    }

   

    //METODOS

    public static function buscarTodasCategorias() {
        try {
            $lstCategorias = Categoria::with('estado', 'logCategoria')->get();
            return $lstCategorias;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    
    //METODOS

    public static function buscarTodasCategoriasActivas() {
        try {
            $lstCategorias = Categoria::with('estado', 'logCategoria')->where('estado_id', Estados::$estadoActivo )->get();
            return $lstCategorias;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }

}
