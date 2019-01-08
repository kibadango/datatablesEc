<?php

session_start();
 include ("../../seguridades/seguridad_niv2.inc");


/**
 * Created by PhpStorm.
 * User: kevin.ibadango
 *  Date: 14/08/2018
 * Time: 15:36
 */

////////////////////////////////////////////////////////////////////
///////DESARROLLADO POR: Milton Aguilera////////////////////////////
///////DESCRIPCION: Reporte pdf para articulos segun tipo toma /////
///////TABLAS INVOLUCRADAS:TomaFisica,DetalleFisica,SubrecetaToma //
///////SubrecetaToma, Costos_Historicos ////////////////////////////
///////FECHA CREACION: 22-06-2009///////////////////////////////////
///////FECHA ULTIMA MODIFICACION: //////////////////////////////////
///////USUARIO QUE MODIFICO: ///////////////////////////////////////
///////DECRIPCION ULTIMO CAMBIO: ///////////////////////////////////
////////////////////////////////////////////////////////////////////
//incluye la clase
include("../../system/conexion/clase_sql.php");
include("../../clases/clase_seguridades.php");
include_once("../../clases/clase_MantenimientoArticulos.php");
include('../../system/pdf/fpdf.php');

$lc_seguridades = new seguridades();

$objArticulos = new clase_MantenimientoArticulos();


$lc_condiciones[0]='Metodo';

if( isset($_GET["cod_bodega"]) ||
    isset($_GET["Cod_Cadena"]) ||
    isset($_GET["cod_restaurante"]) ||
    isset($_GET["sitio"]) ||
    isset($_GET["Nombre_restaurante"]) ||
    isset($_GET["TipoTomaFisica"])
){
    $lc_condiciones[0]='Metodo';
    $lc_condiciones[0]=$_GET["cod_bodega"];
    $lc_condiciones[1]=$_GET["Cod_Cadena"];
    $lc_condiciones[2]=$_GET["cod_restaurante"];
    $lc_condiciones[3]=$_GET["sitio"];
    $lc_condiciones[4]=$_GET["DescripcionBodega"];
    $lc_condiciones[5]=$_GET["Nombre_restaurante"];
    $lc_condiciones[6]=$_GET["TipoTomaFisica"];

}else{
    $lc_condiciones[0]='0';
    $lc_condiciones[1]='0';;
    $lc_condiciones[2]='0';;
    $lc_condiciones[3]='0';;
    $lc_condiciones[4]='0';;
    $lc_condiciones[5]='';;
    $lc_condiciones[6]='Diario';;
}
$lc_nombre_usuario=$_SESSION['sess_nombre'];
$lc_idLocal=$_GET['cod_restaurante'];
$lc_local=$objArticulos->fn_nombrelocal($lc_idLocal);
$lc_logo=$objArticulos->fn_logo($lc_idLocal);

$DATOS=$objArticulos->fn_consultarArticulos($lc_condiciones);

$grupo='ALL';
$i=0;


foreach($DATOS['Tablerows']  as $number => $number_array) {

        $stack[] =  array(
            $number_array[4],
            $number_array[1],
            $number_array[2]);

}


header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=TomaFisica_'.$_GET["TipoTomaFisica"].'_'.date( "d-m-Y").'.xls');
header('Pragma: no-cache');
header('Expires: 0');

?>



 <!--==================================================CABECERA REPORTE====================================================================-->
    <table align="center" width="70%" border="0">
    	<tr>
            <!--<td width="21%" align="center" rowspan="4"><img src= <?php echo "../../imagenes/Logos/$lc_logo"?> width="140" height="78"></td>-->
            <td width="50%" rowspan="4">
                <div align="center">
                    <p class="titulo_cadena"><strong>Toma Fisica <?php echo $_GET["TipoTomaFisica"]?></strong><br/> </p>

                </div>
            </td>
            
        </tr>
        <tr>
            <td class="titulo_parametro"><strong>Usuario: </strong><?php echo utf8_encode($lc_nombre_usuario)?></td>
        </tr>
        <tr>
            <td class="titulo_parametro"><strong>Local: </strong><?php echo utf8_encode($lc_local)?></td>
        </tr>
        <tr>
            <td class="titulo_parametro"><strong>Fecha: </strong><?php echo date( "d/m/Y");?> </td>
        </tr>
        <tr>
            <td></td>
            <td class="titulo_parametro" ><strong>Bodega: </strong><?php echo $_GET["DescripcionBodega"]; ?> </td>
        </tr>
    </table>
    
   


<!--==============================================CUERPO REPORTE========================================================-->
    <div id="tabla" class="tabla_detalle" style="padding-top:20px; padding-left:15px; padding-bottom:20px; font-size:14px;">
    	<table id="tablaReporte" border='1' align='left' cellspacing='4' class='tabla' >
            <?php
          echo "<tr class='tabla_cabecera'>".
            "<td style='text-align:left; '><strong>Cod. Art</strong></td>".
                            "<td style='text-align:left; '><strong>Descripcion</strong></td>".
                            "<td style='text-align:left;'><strong>Grupo</strong></td>";
                 echo "</tr>";


foreach ($stack as $grupo){

  echo "<tr class='tabla_detalle'>";

                        echo "<td align='left'>".$grupo[1]."</td>";
                         echo "<td align='left'>".$grupo[2]."</td>";
     echo "<td align='left'>".$grupo[0]."</td>";

                        echo "</tr>";




}
                

            ?>
             
        </table> 
    </div>

