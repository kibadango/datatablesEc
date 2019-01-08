<?php
session_start();
/**
 * Created by PhpStorm.
 * User: kevin.ibadango
 * hello World
 * Date: 16/08/2018
 * Time: 15:3655
 */

$lc_codrestaurante = $_SESSION['sess_restaurante'];
$lc_condiciones[0] = $lc_codrestaurante;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>GERENTE | CUENTAS BANCARIAS </title>
    <meta http-equiv="Content-Type" content="text/html"/>
    <meta charset="UTF-8" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css"  href="../../css/dataTables.select.min.css">
    <link rel="stylesheet" type="text/css"  href="../../css/dataTables.buttons.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/dataTables.jquery.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/dataTables.CustomRowGroup.min.css">
    <link rel="stylesheet" href="../../css/jquery-ui.css">
    <script src="../../js/dataTables.jquery.1.12.4.js"></script>
    <style>



        body {
            background-image: url("../../imagenes/fondo1.jpg"); 
        }
        .button1 {
            background-color: #4CAF50;
            color: white;
        }


        .button2 {
            background-color: #008CBA;
            color: white;
        }


        label {
            color:#2e6e9e;
            text-align: center;
            font-family: "Trebuchet MS";
            font-size: 12px; 
        }
        .ColumnaRestaurante{
            width: 100%;
        }
        .ColumnaBodega{
            width: 100%;
        }
        .ColumnaCheck{
            width: 5%;
        }
        .ColumnaCod_Articulo{
            width: 10%;
        }

        .selectColorOrange { border:5px solid red; }

        .ColumnaNombreArt{
            width: 55%;
        }
        .ColumnaTipo{
            width: 15%;
        }
        .ColumnaGaurdar{
            width: 5%;
        }


        .same {
            background-color: #eee6ff !important;
            color: black !important;
            font-family: "Trebuchet MS" !important;
            font-size: 12px !important; 
        }

        .greenadd {
            background-color: #449d44 !important;
            color: white !important;
            font-family: "Trebuchet MS" !important;
            font-size: 12px !important; 
        }

        div.left{
            float:left;
        }
        div.center{
            text-align: center;
            font-family: "Trebuchet MS";
            font-size: 12px; 
        display: inline-block;
        }
        select {
            width: 150px;
        }

        div.floatright{
            float:right;
        }
        div {
            font-family: "Trebuchet MS";
            font-size: 12px; 

        }
        a {
            font-family: "Trebuchet MS";
            font-size: 12px; 
        display: inline-block;
        }
        input{
            font-family: "Trebuchet MS";
            font-size: 12px; 
        display: inline-block;
        }

        .smallInput {
            text-align: center;
            line-height:13px;
            height:12px;
            padding: 0px;
        }

        .tb_inicio {
            background-image: url("../../imagenes/grid-blue-pg.gif");
            color:white;
            padding: 5px;
            text-align: center;
            font-family: "Trebuchet MS";
            font-size: 12px; 


        }
        .tb_cab{  
        color:#2e6e9e;
            background-image: url("../../imagenes/grid-blue-ln.gif");
            text-align: center;
            font-family: "Trebuchet MS";
            font-size: 10px; 
        }  
         .closebtn {
             margin-left: 15px;
             color: white;
             font-weight: bold;
             float: right;
             font-size: 22px;
             line-height: 20px;
             cursor: pointer;
             transition: 0.3s;
         }
        .closebtn:hover {
            color: black;
        }
        .Error {
            padding:12px;
            width: 80%;
            background-color:  #f44336;
            color: white;
            font-family: "Trebuchet MS";
            font-size: 14px; 

        }
        .Exito {
            padding:12px;
            width: 80%;
            background-color:  #4CAF50;
            color: white;
            font-family: "Trebuchet MS";
            font-size: 14px; 

        }

        .closebtn {
            margin-left: 15px;
            color: white;
            font-weight: bold;
            float: right;
            font-size: 22px;
            line-height: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        ::-ms-clear {
            display: none;
        }
        .red {
            background-color: #f44336 !important;
        }
        .green {
            background-color: #449d44 !important;
        }
        .descripcion{
            text-align: left;
            color:#2e6e9e;
            font-family: "Trebuchet MS";
            font-size: 10px; 
        }
        .redbutton {
            display: inline-block;
            border-radius: 4px;
            background-color: #ff0000;
            border: none;
            color: #FFFFFF;
            text-align: center;
            font-size: 10px;
            padding: 3px;
            width: 40px;
            transition: all 0.5s;
            cursor: pointer;
            margin: 5px;
        }
        .redbutton span {
            cursor: pointer;
            display: inline-block;
            position: relative;
            transition: 0.5s;
        }
        .redbutton span:after {
            content: '\00bb';
            position: absolute;
            opacity: 0;
            top: 0;
            right: -20px;
            transition: 0.5s;
        }
        .redbutton:hover span {
            padding-right: 10px;
        }
        .redbutton:hover span:after {
            opacity: 1;
            right: 0;
        }
        .greenbutton {
                    border-radius: 4px;
            background-color: #449d44;
            border: none;
            color: #FFFFFF;
            text-align: center;
            font-size: 15px;
            padding: 0px;
            width: 20px;
            transition: all 0.5s;
            cursor: pointer;
        }
        .greenbutton span {
            cursor: pointer;
            display: inline-block;
            position: relative;
            transition: 0.5s;
        }
        .greenbutton span:after {
            content: '\00bb';
            position: absolute;
            opacity: 0;
            top: 0;
            right: -20px;
            transition: 0.5s;
        }
        .greenbutton:hover span {
            padding-right: 10px;
        }
        .greenbutton:hover span:after {
            opacity: 1;
            right: 0;
        }
        table.dataTable {
            width: 100%;
            margin: 0 auto;
            clear: both;
            border-collapse: separate;
            border-spacing: 0;
            background-image: url("../../imagenes/grid-blue-ln.gif");
        }
        table.dataTable thead .sorting {
            background-image: url("../../imagenes/DatablesIcons/sort_both.png"); }
        table.dataTable thead .sorting_asc {
            background-image: url("../../imagenes/DatablesIcons/sort_asc.png"); }
        table.dataTable thead .sorting_desc {
            background-image: url("../../imagenes/DatablesIcons/sort_desc.png"); }
        table.dataTable thead .sorting_asc_disabled {
            background-image: url("../../imagenes/DatablesIcons/sort_asc_disabled.png"); }
        table.dataTable thead .sorting_desc_disabled {
            background-image: url("../../imagenes/DatablesIcons/sort_desc_disabled.png"); }
        table.dataTable thead th,table.dataTable tfoot th {
            text-align: center;
            color:#2e6e9e;
            font-family: "Trebuchet MS";
            font-size: 12px; 
        }
        table.dataTable thead th,table.dataTable thead td {
            text-align: center;
            color:#2e6e9e;
            font-family: "Trebuchet MS";
            font-size: 12px; 

        }
        table.dataTable.compact thead th,
        table.dataTable.compact thead td {
            padding: 1px 1px 1px 1px; }
        table.dataTable.compact tfoot th,
        table.dataTable.compact tfoot td {
            padding: 1px; }
        table.dataTable.compact tbody th,
        table.dataTable.compact tbody td {
            padding: 1px; }


        div.dataTables_wrapper div.dataTables_processing {
            z-index: 1000;!important;
            text-align: center!important;
            color:black!important;
            font-size: 15px!important;
        }



        table.dataTable tbody tr.selected {
            background-image: url("../../imagenes/grid-activo.gif");
        }

        table.highlight tbody tr:nth-child(even):hover td{
            background-color:#ccdcff  !important;
        }

        table.highlight tbody tr:nth-child(odd):hover td {
            background-color:#ccdcff !important;
        }


        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            color: white !important;
            border: 0px solid #2e6e9e;
            background-color: #f7f495;
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #2e6e9e), color-stop(100%,#2e6e9e));
            /* Chrome,Safari4+ */
            background: -webkit-linear-gradient(top, #2e6e9e 0%, #2e6e9e 100%);
            /* Chrome10+,Safari5.1+ */
            background: -moz-linear-gradient(top, #2e6e9e 0%, #2e6e9e 100%);
            /* FF3.6+ */
            background: -ms-linear-gradient(top, #2e6e9e 0%, #2e6e9e 100%);
            /* IE10+ */
            background: -o-linear-gradient(top, #2e6e9e 0%, #2e6e9e 100%);
            /* Opera 11.10+ */
            background: linear-gradient(to bottom, #2e6e9e 0%,#2e6e9e 100%);
            /* W3C */ }


        table.dataTable tbody th,table.dataTable tbody td {
            padding: 100px 100px; }
        table.dataTable.row-border tbody th, table.dataTable.row-border tbody td, table.dataTable.display tbody th, table.dataTable.display tbody td {
            border-top: 1px solid darkgrey; }
        table.dataTable.row-border tbody tr:first-child th,
        table.dataTable.row-border tbody tr:first-child td, table.dataTable.display tbody tr:first-child th,
        table.dataTable.display tbody tr:first-child td {
            border-top: 1px solid darkgrey; }
        table.dataTable.cell-border tbody th, table.dataTable.cell-border tbody td {
            border-top: 1px solid darkgrey;
            border-right: 1px solid darkgrey; }
        table.dataTable.cell-border tbody tr th:first-child,
        table.dataTable.cell-border tbody tr td:first-child {
            border-left: 1px solid darkgrey; }
    </style>
    <style type="text/css" media="screen">
        .black_overlay{
            display: none;
            position: absolute;
            top: 0%;
            left: 0%;
            width: 100%;
            height: 100%;
            background-color: black;
            z-index:1001;
            -moz-opacity: 0.8;
            opacity:.80;
            filter: alpha(opacity=50);}

        .white_content {
            display: none;
            position: absolute;
            top: 35%;
            left:32%;
            width:35%;
            height:35%;
            padding:16px;
            border: 7px outset #96c0e0;
            background-color: white;
            background-color: black;
            background: #ffffff url('../../imagenes/procesando.gif') no-repeat fixed center;
            /*background: url('../../imagenes/loading.gif') no-repeat fixed center;*/
            z-index:1002;
            overflow: auto;}
    </style>
    <!-- FIN DE LA LLAMADA DE ARCHIVOS DE AJAX -->
</head>
<body>

<input type="hidden" id="IdrestauranteSession" name="IdrestauranteSession"   value="<?php echo $_SESSION['sess_restaurante'] ?>">
<input type="hidden" id="IduserSession" name="IduserSession"  value="<?php echo $_SESSION['sess_idusuario'] ?>">


<input type="hidden" id="cod_bodega"            name="cod_restaurante"    value="0">
<input type="hidden" id="Cod_Cadena"            name="Cod_Cadena"         value="0">
<input type="hidden" id="cod_restaurante"       name="cod_restaurante"    value="0">
<input type="hidden" id="sitio"                 name="sitio"              value="0">
<input type="hidden" id="DescripcionBodega"     name="DescripcionBodega"  value="0">
<input type="hidden" id="Nombre_restaurante"    name="Nombre_restaurante" value="0">
<input type="hidden" id="TipoTomaFisica"        name="TipoTomaFisica"     value="Diario">



<input type="hidden" id="tbArticuloRest_FiltroGrupoArticulo" name="tbArticuloRest_FiltroGrupoArticulo"  value="0">
<input type="hidden" id="tbArticuloRest_FiltrarPorArtSelected" name="tbArticuloRest_FiltrarPorArtSelected"  value="NO">


<input type="hidden" id="tbTomaFisicaRest_FiltroGrupoArticulo"   name="tbTomaFisicaRest_FiltroGrupoArticulo"    value="0">
<input type="hidden" id="tbTomaFisicaRest_FiltrarPorArtSelected" name="tbTomaFisicaRest_FiltrarPorArtSelected"  value="NO">

<br>

<div id="HeaderMsg" align="center" style="  font-family: 'Trebuchet MS';  font-size: 13px; color:white;"> Administraci&oacute;n de Art&iacute;culo por Restaurante
</div>

<div id="respuestaMenu">

</div>

<br>

<div id="respuestaPantallaBody" style="margin: 0 auto;width: 100%;">

</div>

<br>
<div id="MsgAlerta" style="margin: 0 auto;width: 100%;">
</div>



<div id="dialogPrincipalCargando">
    <div>
        <h2 style ="text-align: center;"><div id="idCargarPrincipalHeader"></div></h2>
        <div   align="center">
            <div id="cargarPregunta" align="center"></div>

            <table style="width: 60%">
                <tr>
                    <th style="width: 30%" align="right">
                        <div id = "idCargarPrincipalicon1">

                        </div>
                    </th>
                    <th style="width: 70%" align="left">
                        <div id = "idCargarPrincipalFecha"></div>
                    </th>
                </tr>
                <tr>
                    <th style="width: 30%" align="right" >
                        <div id = "idCargarPrincipalicon2">

                        </div>
                    </th>
                    <th style="width: 70%" align="left">
                        <div id = "idCargarPrincipalTipo"></div>
                    </th>

                </tr>
            </table>
        </div>
        <div id = "idCargarPrincipalPregunta" style="width: 100%" align="center"></div>
        <hr>
        <div id="progressbarPrincipalCargando"></div>
        <div id="EspereText">Por favor, espere mientras se procesa sus datos.....
        </div>
    </div>
</div>
<div id="darkBack" class="black_overlay"></div>
<div id="whiteBackWait" class="white_content"></div>

<script language="javascript1.2" src="../../js/dataTables.jquerydt.min.js"></script>
<script language="javascript1.2" src="../../js/dataTables.select.min.js"></script>
<script language="javascript1.2" src="../../js/dataTables.buttons.min.js"></script>
<script language="javascript1.2" src="../../js/datables.date.order.js"></script>
<script language="javascript1.2" src="../../js/dataTables.rowGroup.min.js"></script>
<script language="javascript1.1"  src="../../js/configuracion.js"></script>
<script src="../../js/jquery-ui.1.12.1.js"></script>
<script src="../../js/ajax_MantenimientoArticulos.js"></script>

</body>
</html>
