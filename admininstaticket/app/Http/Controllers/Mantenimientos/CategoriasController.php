<?php
namespace Instaticket\Http\Controllers\Mantenimientos;
use Instaticket\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Instaticket\Entidades\Categoria;
use Instaticket\Entidades\Estados;
use Instaticket\Entidades\LogCategorias;
use Illuminate\Support\Facades\Log;
class CategoriasController extends Controller {
    /**
     * AUTOR: INNOVASYSTEM ECUADOR S.A
     * DESCRIPCION: MÉTODO REALIZA CARGAR PANTALLA: CATEGORIAS
     * @return type
     */
    public function index() {
        try {
            Log::info('Ingreso Interfaz CATEGORIAS, con usuario: ' . Auth::user()->usuario_username);
            $lstCategorias = Categoria::buscarTodasCategorias();
            return view('mantenimientos.categoria', compact('lstCategorias'));
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Categorias/index' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect('welcome');
        }
    }
    public function validarCategoria(Request $request) {
        try {
            $lstError = '';
            $array = array();
            $messages = [
                'nombre.required' => 'El campo nombre es requerido.',
                'nombre.max' => 'El campo nombre debe tener un tamaño de 45 caracteres.',
                'nombre.unique' => 'Nombre ' . $request->nombre . ' ya se encuentra registrado.',
                'descripcion.required' => 'El campo descripción es requerido.',
                'descripcion.max' => 'El campo descripción debe tener un tamaño de 250 caracteres.',
                'porcentaje_probabilidad.required' => 'El campo ´porcentaje probabilidad es requerido.',
                'porcentaje_probabilidad.max' => 'El campo porcentaje probabilidad debe tener un tamaño de 11 caracteres.',
                'porcentaje_probabilidad.unique' => 'Porcentaje Probabilidad ' . $request->porcentaje_probabilidad . ' ya se encuentra registrado.',
                'estado_id.required' => 'El campo estado es requerido.',
                'estado_id.max' => 'El campo estado debe tener un tamaño de 11 caracteres.',
                'min.required' => 'El campo mínimo es requerido.',
                'min.max' => 'El campo mínimo debe tener un tamaño de 2 caracteres.',
                'max.required' => 'El campo máximo es requerido.',
                'max.max' => 'El campo máximo debe tener un tamaño de 3 caracteres.',
            ];
            if (!empty($request->valor)) {
                $validator = Validator::make($request->all(), [
                            'nombre' => 'max:45|required|unique:categoria,categoria_nombre,' . $request->valor . ',categoria_id',
                            'descripcion' => 'max:250|required|',
                            'porcentaje_probabilidad' => 'required|unique:categoria,categoria_porcentaje_probabilidad,' . $request->valor . ',categoria_id',
                            'estado_id' => 'required|',
                            'min' => 'max:2|required|',
                            'max' => 'max:3|required|',
                                ], $messages);
            } else {
                $validator = Validator::make($request->all(), [
                            'nombre' => 'max:45|required|unique:categoria,categoria_nombre',
                            'descripcion' => 'max:250|required|',
                            'porcentaje_probabilidad' => 'required|unique:categoria,categoria_porcentaje_probabilidad',
                            'estado_id' => 'required|',
                            'min' => 'max:2|required|',
                            'max' => 'max:3|required|',
                                ], $messages);
            }
            if ($validator->fails()) {
                $lstError = $validator->errors();
            } else {
                $ld_Activo = Categoria::where('estado_id', Estados::$estadoActivo)->count();
                if ($ld_Activo <= 6 && $request->estado_id == Estados::$estadoInactivo) {
                    $msj = array("No se permite inactivar, debe tener 6 categorías activas.");
                    array_push($array, $msj);
                }
                if (($request->porcentaje_probabilidad < 1) || ($request->porcentaje_probabilidad > 100)) {
                    $msj = array("El número de la probabilidad debe ser entre 1 y 100%.");
                    array_push($array, $msj);
                }
                if ($request->min <= 0 || $request->min > 100) {
                    $msj = array("El número del mínimo debe ser entre 1 y 100");
                    array_push($array, $msj);
                }
                if ($request->max <= 0 || $request->max > 100) {
                    $msj = array("El número del máximo debe ser entre 1 y 100");
                    array_push($array, $msj);
                }
                if ($request->min >= $request->max) {
                    $msj = array("El número del mínimo no puede ser mayor o igual que el número del máximo.");
                    array_push($array, $msj);
                }
                if ($request->max <= $request->min) {
                    $msj = array("El número del máximo no puede ser menor o igual que el número del mínimo.");
                    array_push($array, $msj);
                }
                if ($request->porcentaje_probabilidad < $request->min || $request->porcentaje_probabilidad > $request->max) {
                    $msj = array("La probabilidad debe estar entre el rango de mínimo y máximo.");
                    array_push($array, $msj);
                }
                $cat_Activo = Categoria::orderBy('categoria_porcentaje_probabilidad', 'asc')->get();
                if (count($cat_Activo) > 0) {
                    if (!empty($request->valor)) {
                        $ar = array();
                        $min = array();
                        $max = array();
                        for ($p = 0; $p < count($cat_Activo); $p++) {
                            $obj = $cat_Activo[$p]->categoria_porcentaje_probabilidad;
                            $obj1 = $cat_Activo[$p]->categoria_rango_minimo;
                            $obj2 = $cat_Activo[$p]->categoria_rango_maximo;
                            array_push($ar, $obj);
                            array_push($min, $obj1);
                            array_push($max, $obj2);
                        }
                        $objPro = Categoria::find($request->valor);
                        $clave = array_search($objPro->categoria_porcentaje_probabilidad, $ar, true);
                        $indice1 = $clave;
                        if ($clave > 0) {
                            $pro_Anterior = $ar[$indice1 - 1];
                            $max_Anterior = $max[$indice1 - 1];
                            if ($request->porcentaje_probabilidad <= $pro_Anterior) {
                                $msj = array("El número de la probabilidad debe ser mayor a " . $pro_Anterior);
                                array_push($array, $msj);
                            }
                            if ($request->min <= $max_Anterior) {
                                $msj = array("El número del mínimo debe ser mayor a " . $max_Anterior);
                                array_push($array, $msj);
                            }
                        }
                        if ($clave < (count($cat_Activo) - 1)) {
                            $pro_Siguiente = $ar[$indice1 + 1];
                            $min_Siguiente = $min[$indice1 + 1];
                            if ($request->porcentaje_probabilidad >= $pro_Siguiente) {
                                $msj = array("El número de la probabilidad debe ser menor a " . $pro_Siguiente);
                                array_push($array, $msj);
                            }
                            if ($request->max >= $min_Siguiente) {
                                $msj = array("El número del máximo debe ser menor a " . $min_Siguiente);
                                array_push($array, $msj);
                            }
                        }
                    } else {
                        $ld_categorias = Categoria::all()->count();
                        if ($ld_categorias == 7) {
                            $msj = array("No se permite ingresar más de 6 categorías.");
                            array_push($array, $msj);
                        }
                        $ar = array();
                        $min = array();
                        $max = array();
                        for ($p = 0; $p < count($cat_Activo); $p++) {
                            $obj = $cat_Activo[$p]->categoria_porcentaje_probabilidad;
                            $obj1 = $cat_Activo[$p]->categoria_rango_minimo;
                            $obj2 = $cat_Activo[$p]->categoria_rango_maximo;
                            array_push($ar, $obj);
                            array_push($min, $obj1);
                            array_push($max, $obj2);
                        }
                        $count = count($cat_Activo);
                        $pro_Anterior = $ar[$count - 1];
                        $max_Anterior = $max[$count - 1];
                        if ($request->porcentaje_probabilidad <= $pro_Anterior) {
                            $msj = array("El número de la probabilidad debe ser mayor a " . $pro_Anterior);
                            array_push($array, $msj);
                        }
                        if ($request->min <= $max_Anterior) {
                            $msj = array("El número del mínimo debe ser mayor a " . $max_Anterior);
                            array_push($array, $msj);
                        }
                    }
                }
                if (count($array) > 0) {
                    $lstError = $array;
                }
            }
            return $lstError;
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Categorias/validarCategoria' . $ex->getMessage());
            return array($ex->getMessage());
        }
    }
    public function guardarCategoria(Request $request) {
        try {
            $obj = new Categoria();
            \DB::transaction(function () use ($request, $obj) {
                $obj->categoria_nombre = strtoupper($request->categoria_nombre);
                $obj->categoria_porcentaje_probabilidad = $request->categoria_porcentaje_probabilidad;
                $obj->categoria_descripcion = $request->categoria_descripcion;
                $obj->estado_id = $request->estado_id;
                $obj->categoria_rango_minimo = $request->categoria_rango_minimo;
                $obj->categoria_rango_maximo = $request->categoria_rango_maximo;
                $obj->save();
                $objC = Categoria::with('estado')->find($obj->categoria_id);
                $var = 'CATEGORIA_ID: ' . $objC->categoria_id .
                        ' |NOMBRE: ' . strtoupper($objC->categoria_nombre) .
                        ' |DESCRIPCIÓN: ' . $objC->categoria_descripcion .
                        ' |PORCENTAJE PROBABILIDAD: ' . $objC->categoria_porcentaje_probabilidad .
                        ' |MÍNIMO: ' . $objC->categoria_rango_minimo .
                        ' |MÁXIMO: ' . $objC->categoria_rango_maximo .
                        ' |ESTADO: ' . $objC->estado_id . '-' . $objC->estado->estado_nombre;

                $log = new LogCategorias();
                $log->registro_nuevo = $var;
                $log->id_tabla = $obj->categoria_id;
                $log->tipo_accion = 'I';
                $log->save();
            });
            Session::flash('message', 'CATEGORÍA: ' . strtoupper($obj->categoria_nombre) . ' ha sido registrado con éxito.');
            return redirect()->back();
        } catch (\Exception $ex) {
            \DB::rollback();
            Log::error('CONTROLLER Categorias/guardarCategoria' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    public function buscarCategoria(Request $request) {
        try {
            if ($request->valor != null) {
                $obj = Categoria::find($request->valor);
                if (!empty($obj)) {
                    return $obj;
                }
            }
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Categorias/buscarCategoria' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    public function actualizarCategoria(Request $request) {
        try {
            if ($request->valor != null) {
                $obj = Categoria::with('estado')->find($request->valor);
                if (!empty($obj)) {
                    $var = 'CATEGORIA_ID: ' . $obj->categoria_id .
                            ' |NOMBRE: ' . strtoupper($obj->categoria_nombre) .
                            ' |DESCRIPCIÓN: ' . $obj->categoria_descripcion .
                            ' |PORCEMTAE PROBABILIDAD: ' . $obj->categoria_porcentaje_probabilidad .
                            ' |MÍNIMO: ' . $obj->categoria_rango_minimo .
                            ' |MÁXIMO: ' . $obj->categoria_rango_maximo .
                            ' |ESTADO: ' . $obj->estado_id . '-' . $obj->estado->estado_nombre;

                    \DB::transaction(function () use ($request, $obj, $var) {
                        $obj->categoria_nombre = strtoupper( $request->categoria_nombre );
                        $obj->categoria_porcentaje_probabilidad = $request->categoria_porcentaje_probabilidad;
                        $obj->categoria_descripcion = $request->categoria_descripcion;
                        $obj->estado_id = $request->estado_id;
                        $obj->categoria_rango_minimo = $request->categoria_rango_minimo;
                        $obj->categoria_rango_maximo = $request->categoria_rango_maximo;
                        $obj->save();
                        $objC = Categoria::with('estado')->find($obj->categoria_id);
                        $var2 = 'CATEGORIA_ID: ' . $objC->categoria_id .
                                ' |NOMBRE: ' . strtoupper($objC->categoria_nombre) .
                                ' |DESCRIPCIÓN: ' . $objC->categoria_descripcion .
                                ' |PORCENTAJE PROBABILIDAD: ' . $objC->categoria_porcentaje_probabilidad .
                                ' |MÍNIMO: ' . $objC->categoria_rango_minimo .
                                ' |MÁXIMO: ' . $objC->categoria_rango_maximo .
                                ' |ESTADO: ' . $objC->estado_id . '-' . $objC->estado->estado_nombre;

                        $log = new LogCategorias();
                        $log->registro_anterior = $var;
                        $log->registro_nuevo = $var2;
                        $log->id_tabla = $obj->categoria_id;
                        $log->tipo_accion = 'A';
                        $log->save();
                    });
                    Session::flash('message', 'CATEGORÍA: ' . strtoupper($request->categoria_nombre) . ' ha sido actualizada con éxito.');
                } else {
                    Session::flash('messageError', "No se ha encontrado la categoría a actualizar.");
                }
            } else {
                Session::flash('messageError', "No se ha seleccionado categoría.");
            }
            return redirect()->back();
        } catch (\Exception $ex) {
            \DB::rollback();
            Log::error('CONTROLLER Categorias/actualizarCategoria' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
}
