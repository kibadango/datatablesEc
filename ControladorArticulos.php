<?php
/**
 * Created by PhpStorm.
 * User: kevin.ibadango
 * Date: 31/08/2018
 * Time: 15:3655
 */



session_start();
include_once("../../seguridades/seguridad.inc");
//nacional GerenteNacional_18
//include_once("../../system/conexion/clase_sql_nacional.php");
//nacional sql SqlGerente_18
include_once("../../system/conexion/clase_sql.php");
include_once("../../clases/clase_seguridades.php");
include_once("../../clases/clase_MantenimientoArticulos.php");


$lc_seguridades = new seguridades();
$objArticulos = new clase_MantenimientoArticulos();
$request = (object)$_POST;

$condiciones = null;
if ($request->metodo === "fn_Articulos_Restaurantes_Ini") {
    $condiciones[0] = $request->metodo;
    $condiciones[1] =$request->IduserSession;
    $condiciones[2] =$_POST;
    $condiciones[3] =$request->Cod_Cadena;
    $retorna = $objArticulos->fn_Articulos_Restaurantes_Ini($condiciones);

    print $retorna;
}else if ($request->metodo === "fn_Articulos_getBodegas") {
    $condiciones[0] = $request->metodo;
    $condiciones[1] =$request->IduserSession;
    $condiciones[2] =$_POST;
    $condiciones[3] =$request->Cod_Restaurante;
    $retorna = $objArticulos->fn_Articulos_getBodegas($condiciones);

    print $retorna;
}else if ($request->metodo === "fn_Articulos_getCadena") {
    $condiciones[0] = $request->metodo;
    $condiciones[1] =$request->IduserSession;
    $condiciones[2] =$_POST;
    $retorna = $objArticulos->fn_Articulos_getCadena($condiciones);

    print $retorna;
}else if ($request->metodo === "fn_tbArticuloRest_ini") {
    $condiciones[0] = $request->metodo;
    $condiciones[1] =$request->IduserSession;
    $condiciones[2] =$_POST;
    $condiciones[3] =$request->cod_bodega;
    $condiciones[4] =$request->cod_restaurante;
    $condiciones[5] =$request->Cod_Cadena;
    $condiciones[6] =$request->cod_restaurante;
    $condiciones[7] =$request->sitio;
    $condiciones[8] =$request->DescripcionBodega;
    $condiciones[9] =$request->Nombre_restaurante;
    $condiciones[10] =$request->tbArticuloRest_Cod_Grupo_art; //Deseas filtrar los articulos por grupo de articulo
    $condiciones[11] =$request->TipoTomaFisica;
    if(isset($request->tbArticuloRest_ArrayArticulosSelected)){
        $condiciones[12] =$request->tbArticuloRest_ArrayArticulosSelected;// Array de Articulos selecionados
    }else{
        $condiciones[12] = "";
    }
    $condiciones[13] =$request->tbArticuloRest_FiltrarPorArtSelected;//Deseas filtrar los articulos que fueron seleccionados ? SI - NO

    $retorna = $objArticulos->fn_tbArticuloRest_ini($condiciones);

    print $retorna;
}else if ($request->metodo === "fn_tbTipoTomaFisica_ini") {
    $condiciones[0] = $request->metodo;
    if(isset($request->IduserSession)){
        $condiciones[1] =$request->IduserSession;
    }else{
        $condiciones[1] =$_SESSION['sess_idusuario'];
    }
    $condiciones[2] =$_POST;
    $condiciones[3] =$request->cod_bodega;
    $condiciones[4] =$request->cod_restaurante;
    $condiciones[5] =$request->Cod_Cadena;
    $condiciones[6] =$request->cod_restaurante;
    $condiciones[7] =$request->sitio;
    $condiciones[8] =$request->DescripcionBodega;
    $condiciones[9] =$request->Nombre_restaurante;
    $condiciones[10] =$request->tbTomaFisicaRest_Cod_Grupo_art;
    $condiciones[11] =$request->TipoTomaFisica;
    if(isset($request->tbArticuloRest_ArrayArticulosSelected)){
        $condiciones[12] =$request->tbArticuloRest_ArrayArticulosSelected;// Array de Articulos selecionados
    }else{
        $condiciones[12] = "";
    }
    $condiciones[13] =$request->tbTomaFisicaRest_FiltrarPorArtSelected;//Deseas filtrar los articulos que fueron seleccionados ? SI - NO

    $retorna = $objArticulos->fn_tbTipoTomaFisica_ini($condiciones);

    print $retorna;
}else if ($request->metodo === "getTodosArticulosConCod_Grupo") {
    $condiciones[0] = $request->metodo;
    //$condiciones[1] =$request->IduserSession;
    //$condiciones[2] =$_POST;
    $condiciones[3] =$request->cod_bodega;
    $condiciones[4] =$request->cod_restaurante;
    $condiciones[5] =$request->Cod_Cadena;
    $condiciones[6] =$request->sitio;
    $condiciones[7] =$request->Cod_Grupo_art;
    $condiciones[8] =$request->TipoTomaFisica;


    $retorna = $objArticulos->getTodosArticulosConCod_Grupo($condiciones);

    print $retorna;
}else if ($request->metodo === "fn_ArtiPorRestaurante_AgregarArtTomaFisca") {
    $condiciones[0] = $request->metodo;
    if(isset($request->IduserSession)){
        $condiciones[1] =$request->IduserSession;
    }else{
        $condiciones[1] =$_SESSION['sess_idusuario'];
    }
    if(isset($request->IdrestauranteSession)){
        $condiciones[2] =$request->IdrestauranteSession;
    }else{
        $condiciones[2] =$_SESSION['sess_restaurante'];
    }

    //$condiciones[1] =$request->IduserSession;
    //$condiciones[2] =$_POST;
    $condiciones[3] =$request->cod_bodega;
    $condiciones[4] =$request->cod_restaurante;
    $condiciones[5] =$request->Cod_Cadena;
    $condiciones[6] =$request->sitio;
    $condiciones[7] =$request->TipoTomaFisica;
    $condiciones[8] =$request->tbArticuloRest_ArrayArticulosSelected;
    $condiciones[9] =$request->Nombre_restaurante;

    $retorna = $objArticulos->fn_ArtiPorRestaurante_AgregarArtTomaFisca($condiciones);

    print $retorna;
}else if ($request->metodo === "fn_Gaurdar") {
    $condiciones[0] = $request->metodo;

    if(isset($request->IduserSession)){
        $condiciones[1] =$request->IduserSession;
    }else{
        $condiciones[1] =$_SESSION['sess_idusuario'];
    }
    if(isset($request->IdrestauranteSession)){
        $condiciones[2] =$request->IdrestauranteSession;
    }else{
        $condiciones[2] =$_SESSION['sess_restaurante'];
    }
    $condiciones[3] =$request->cod_bodega;
    $condiciones[4] =$request->cod_restaurante;
    $condiciones[5] =$request->Cod_Cadena;
    $condiciones[6] =$request->sitio;
    $condiciones[7] =$request->TipoTomaFisica;
    $condiciones[8] =$request->cod_Art_bodega;
    $condiciones[9] =$request->cod_Articulo;
    $condiciones[10] =$request->Nombre_restaurante;
    $condiciones[11] =$request->TipoTomaFisicaOriginal;



    $retorna = $objArticulos->fn_Gaurdar($condiciones);

    print $retorna;
}else{
    print 'error';
}
