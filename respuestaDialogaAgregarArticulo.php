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


$lc_condiciones[0] = 'Metodo';

if (isset($_POST["cod_bodega"]) ||
    isset($_POST["Cod_Cadena"]) ||
    isset($_POST["cod_restaurante"]) ||
    isset($_POST["sitio"]) ||
    isset($_POST["Nombre_restaurante"]) ||
    isset($_POST["TipoTomaFisica"])
) {
    $lc_condiciones[0] = 'Metodo';
    $lc_condiciones[0] = $_POST["cod_bodega"];
    $lc_condiciones[1] = $_POST["Cod_Cadena"];
    $lc_condiciones[2] = $_POST["cod_restaurante"];
    $lc_condiciones[3] = $_POST["sitio"];
    $lc_condiciones[4] = $_POST["DescripcionBodega"];
    $lc_condiciones[5] = $_POST["Nombre_restaurante"];
    $lc_condiciones[6] = $_POST["TipoTomaFisica"];

} else {
    $lc_condiciones[0] = '0';
    $lc_condiciones[1] = '0';;
    $lc_condiciones[2] = '0';;
    $lc_condiciones[3] = '0';;
    $lc_condiciones[4] = '0';;
    $lc_condiciones[5] = '';;
    $lc_condiciones[6] = 'Diario';;
}

$objectoRestaurantes = $objArticulos->fn_Articulos_getTodosLosRestaurantes($lc_condiciones);
$Cb_objecto = $objArticulos->fn_consultarGrupoDeArticulos($lc_condiciones);
$objectoBodega = $objArticulos->fn_consultarBodegas($lc_condiciones);
$request = (object)$_POST;
?>

<div style="background-color:white;width:100%">

    <table WIDTH="100%">
        <tr>
            <th align="center" colspan="2">
                <div id="labelToma"><font size="5">Agregar a Toma Fisica <?php echo $lc_condiciones[6]; ?></font></div>
                <div id="labelRestaurante"><font color="#ff4500" size="2">* <?php echo $lc_condiciones[5]; ?> *</font>
                </div>

            </th>
        </tr>
    </table>

    <center>   <font color="green"> Mostrar Seleccionados</font> <input type="checkbox"
                                                             id="tbArticuloRest_checkBoxFiltrarArtSelected"
                                                             value="No">  </center>
    <hr>
    <table WIDTH="100%">
        <tr>
            <th width="50%" align="left">
                GRUPO DE ARTICULOS <BR>
                <?php
                echo '<select STYLE=" WIDTH : 100%" id="tbArticuloRest_selectGrupoArti"  >';
                echo '<option value="0" selected>TODOS</option>';
                foreach ($Cb_objecto['fn_tbAgregarArt_GrupoTodosArticulos'] as $number => $number_array) {
                    echo '<option value="' . $number_array['Cod_GrupoArt'] . '">' . $number_array['Descripcion'] . '</option>';
                }
                echo '</select>';
                ?>
            </th>
            <th width="50%" align="right">
                <center>
                    <button id="Bt_DialogAgragarArt"  type="button">Guardar</button>
                    <button id="Bt_DialogCancelarArt" type="button">Cancelar</button>
                </center>


            </th>
        </tr>
    </table>
    <br>
    <table class="tb_inicio" WIDTH="100%">
        <tr>
            <th width="75%" align="left">

                BUSCAR
                <font size="2" color="white" Buscar : </font></font>
                <input id='tbArticuloRest_Inputbuscar' type="text" name="tbAgregarArtiInputbuscar" class=''>
                <a href="#" id='tbArticuloRest_IconBuscar' border="0"><img src="../../imagenes/BARRA/buscar.png"
                                                                           width="15" height="15" align="top"></a>

            </th>
            <th width="25%" align="right"> Pagina: <select id='tbAgregarArti_tbpaginas' style="width: 40px;"></select>
            </th>
        </tr>
    </table>
    <div style="background-color:white;width:100% ; ">
        <table id="tbArticuloRest" class="compact cell-border hover row-border nowrap">

        </table>
    </div>
</div>