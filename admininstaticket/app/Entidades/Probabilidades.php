<?php

namespace Instaticket\Entidades;

use Illuminate\Database\Eloquent\Model;

class Probabilidades extends Model {

    protected $table = 'probabilidades';
    protected $primaryKey = 'probabilidad_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'probabilidad_porcentaje',
        'probabilidad_hora_inicio',
        'probabilidad_hora_fin',
        'estado_id'
    ];

    public function estado() {
        return $this->belongsTo('Instaticket\Entidades\Estados');
    }

    public function logProbabilidades() {
        return $this->hasMany('Instaticket\Entidades\LogProbabilidades', 'id_tabla');
    }

    public function categorias() {
        return $this->hasMany('Instaticket\Entidades\Categoria');
    }

    //METODOS

    public static function buscarTodasProbabilidades() {
        try {
            $lst = Probabilidades::with('estado', 'logProbabilidades')->get();
            return $lst;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }

}
