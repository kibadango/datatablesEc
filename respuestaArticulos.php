
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


$lc_condiciones[0]='Metodo';

if( isset($_POST["cod_bodega"]) ||
    isset($_POST["Cod_Cadena"]) ||
    isset($_POST["cod_restaurante"]) ||
    isset($_POST["sitio"]) ||
    isset($_POST["Nombre_restaurante"]) ||
    isset($_POST["TipoTomaFisica"])
 ){
    $lc_condiciones[0]='Metodo';
    $lc_condiciones[0]=$_POST["cod_bodega"];
    $lc_condiciones[1]=$_POST["Cod_Cadena"];
    $lc_condiciones[2]=$_POST["cod_restaurante"];
    $lc_condiciones[3]=$_POST["sitio"];
    $lc_condiciones[4]=$_POST["DescripcionBodega"];
    $lc_condiciones[5]=$_POST["Nombre_restaurante"];
    $lc_condiciones[6]=$_POST["TipoTomaFisica"];

}else{
    $lc_condiciones[0]='0';
    $lc_condiciones[1]='0';;
    $lc_condiciones[2]='0';;
    $lc_condiciones[3]='0';;
    $lc_condiciones[4]='0';;
    $lc_condiciones[5]='';;
    $lc_condiciones[6]='Diario';;
}

$objectoRestaurantes=$objArticulos->fn_Articulos_getTodosLosRestaurantes($lc_condiciones);
$Cb_objecto=$objArticulos->fn_consultarGrupoDeArticulos($lc_condiciones);
$objectoBodega=$objArticulos->fn_consultarBodegas($lc_condiciones);
$request = (object)$_POST;
?>



<table align="center"  width="85%">
    <tbody >
    <tr>
        <td width="30%">

            <div style="background-color:white;width:100%" id="PantallaTomaFisica">
                <table bgcolor=white style="margin: 0 auto;width: 100%;background-color: white; ">
                    <tr>
                        <th class="tb_inicio" colspan="2">Tipo de Inventario</th>
                    </tr>
                </table>

                <table WIDTH="100%">
                    <tr>
                        <th width="50%" align="left">


                            <table  width="100%" >
                                <tr align="center">

                                    <td>
                                        <label>
                                            <input type="radio" name="checkboxTipoInventario"  class="tipoTomafisica"  value="Diario" id="rdbTipo_0" checked>
                                            Diario
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input type="radio" name="checkboxTipoInventario"  class="tipoTomafisica"  value="Semanal" id="rdbTipo_1">
                                            Semanal
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input type="radio" name="checkboxTipoInventario"  class="tipoTomafisica"  value="Mensual" id="rdbTipo_2">
                                            Mensual
                                        </label>
                                    </td>
                                </tr>
                            </table>

                        </th>
                    </tr>
                </table>
            </div>
            <br>
            <div style="background-color:white;width:100%">
                <table class="tb_inicio"  WIDTH="100%">
                    <tr>
                        <th width="72%" align="left">

                            BUSCAR
                            <font size="2" color="white" Buscar : </font></font>
                            <input id='tbRestaurante_Inputbuscar'type="text" name="tbRestaurante_Inputbuscar" class = 'TipoInventario'>
                            <a href="#" id = 'tbRestaurante_IconBuscar' border="0"><img src="../../imagenes/BARRA/buscar.png" width="15" height="15" align="top"></a>

                        </th>
                        <th width="28%" align="right">      Pagina: <select id = 'cb_paginasRestaurante' style="width: 40px;"></select></th>
                    </tr>
                </table>

                <table id="tablaRestaurante" class="compact cell-border hover row-border nowrap highlight">

                </table>
            </div>
        </td>
        <td width="5%">
            <div align="center">
                <a  id = "Icon" href="javascript: void(0)"    border="0" style="vertical-align:middle"><img  src="../../imagenes/BARRA/green-left.png"  border="0" height="30" width="30"></a>
                <br><br> <font color="white" size="2">Toma Fisica</font>
            </div>

        </td>
        <td width="75%">
            <div style="background-color:white;width:100%" >
                <div id="dinamicTomaFisica">
                        <table bgcolor=white style="margin: 0 auto;width: 100%;background-color: white; ">
                            <tr>
                                <th class="tb_inicio" colspan="2">Tipo de Inventario</th>
                            </tr>
                        </table>
                        <table WIDTH="100%">
                            <tr>
                                <th align="center" colspan="2">
                                    <div id="labelToma"><font size="5">Toma Fisica <?php  echo $lc_condiciones[6] ;?></font></div>
                                    <div id="labelRestaurante"><font color="#ff4500" size="2">* <?php  echo $lc_condiciones[5] ;?> *</font> </div>
                                    <div id="labelfiltroNuevos" ><font color="green">Mostrar Nuevos Articulos</font> <input type="checkbox"  id="tbTomaFisicaRest_checkBoxFiltrarArtSelected" value="No"> </div>

                                </th>
                            </tr>
                        </table>
                        <table WIDTH="100%">
                            <tr>
                                <th align="center" colspan="2">
                                  <a href="#" onMouseOut="MM_swapImgRestore()" id="IconExcel" onMouseOver="MM_swapImage('excel', '', '../../imagenes/BARRA/excel_ds.png', 1)"  border=0><img name="excel" src="../../imagenes/BARRA/excel_ac.png" border=0></a>
                                    <a href="#" onMouseOut="MM_swapImgRestore()" id="IconImpirmir" onMouseOver="MM_swapImage('pdf', '', '../../imagenes/BARRA/barra_imprimir_s.png', 1)"  border=0><img name="pdf" src="../../imagenes/BARRA/barra_imprimir.png" border=0></a>
                                </th>
                            </tr>
                        </table>
                        <table WIDTH="100%">
                            <tr>
                                <th align="left">
                                    <div  style="visibility: hidden">

                                    </div>
                                </th>
                                <th align="left">
                                    <div  style="visibility: hidden">

                                    </div>
                                </th>
                                <th align="right">
                                    <a  id = "IconAgregarArticulos" href="javascript: void(0)"  class="" name=""  border="0" style="vertical-align:middle"><img  src="../../imagenes/BARRA/mas.png"  border="0" height="25" width="25"></a>
                                    <font color="black" size="2">Agregar Articulo</font>
                                </th>

                            </tr>
                            <tr >
                                <th  width="25%" align="left">
                                    <div id="labelTotalArticulos"></div>
                                </th>
                                <th  align="left" width="40%">
                                    GRUPO DE ARTICULOS<br>
                                    <select  STYLE="width: 100% " id="tbTomaFisicaRest_selectGrupoArti">
                                        <option value="0" selected>TODOS</option>
                                    </select>
                                </th>
                                <th  align="left"  width="35%">
                                    BODEGA<br>
                                    <select STYLE="width: 100% ">
                                        <option value="0" selected>TODOS</option>
                                    </select>
                                </th>

                            </tr>
                        </table>
                        <table class="tb_inicio"  WIDTH="100%">
                            <tr>
                                <th width="80%" align="left">

                                    BUSCAR
                                    <font size="2" color="white" Buscar : </font></font>
                                    <input id='tbTomaFisicaRest_Inputbuscar'type="text" name="tbTomaFisicaRest_Inputbuscar" class = 'TipoInventario'>
                                    <a href="#" id = 'tbTipoTomaFisica_IconBuscar' border="0"><img src="../../imagenes/BARRA/buscar.png" width="15" height="15" align="top"></a>

                                </th>
                                <th width="20%" align="right">      Pagina: <select id = 'cb_paginasTipoTomaFisica' style="width: 40px;"></select></th>
                            </tr>
                        </table>
                        <table id="tablaTipoTomaFisica" class="compact cell-border hover row-border nowrap highlight">
                        </table>

                </div>
            </div>
        </td>
    </tr>
    </tbody>
</table>

<div id="dialogAgregarArticulo">
    <div id="DinamicArticuloContenido" style="  height: 430px;"">
    </div>
</div>










