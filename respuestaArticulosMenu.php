<?php


session_start();
include ("../../seguridades/seguridad_niv2.inc");
////////////////////////////////////////////////////////////////////
///////DESARROLLADO POR: KEVIN IBADANGO  ////////////////////////////
///////DESCRIPCION: Para mostrar el menu  //////////////////////////
///////FECHA CREACION: 14-05-2018///////////////////////////////////

//incluye la clases
include("../../system/conexion/clase_sql.php");
include("../../clases/clase_seguridades.php");

//instancio la clase

$lc_idusuario = $_SESSION['sess_idusuario'];
$lc_accion = $_POST['Pantalla'];
$lc_codtoma = '';

?>
    <table align="center" border="1" >
        <tr>
            <td><?php
                if ($lc_accion == 'respuestaArticulosMenu') {
                    //echo $lc_resultado_todo;
                    echo '<a href="#"  onclick="();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage(' . "'Restaurant'" . ',' . "''" . ',' . "'../../imagenes/BARRA/barra_importar_s.png'" . ',1)" border=0><img name="Restaurant" src="../../imagenes/BARRA/barra_importar.png" border=0></a>';
                }
                ?>
            </td>
        </tr>
    </table>
