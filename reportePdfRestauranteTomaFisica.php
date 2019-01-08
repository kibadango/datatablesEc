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

header('Content-Type: text/html; charset=utf-8');

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

class PDF extends FPDF
{
//Page header
public $header;

    function __construct($header) {
        parent::__construct();
        $this->header=$header;
    }
    function Header()
	{
		//Logo
		 $lc_obj= new clase_MantenimientoArticulos();

		 $lc_idLocal=$_GET['cod_restaurante'];
		 $lc_local=$lc_obj->fn_nombrelocal($lc_idLocal);
		 $lc_nombre_usuario=$_SESSION['sess_nombre'];
		 $lc_fecha=date( "d/m/Y");
		 $lc_tipo=$_GET['TipoTomaFisica'];
		 $lc_nombodega=explode("_",$_GET['DescripcionBodega']);
		$lc_logo=$lc_obj->fn_logo($lc_idLocal);
		$this->Image('../../imagenes/Logos/'.$lc_logo,10,8,33);
		//Arial bold 15
		$this->SetFont('Arial','B',8);
		//Move to the right
		$this->Cell(88);
		//Titulo
        $this->SetFont('Arial','B',18);
		$this->Cell(10,12,'Toma Fisica '.$_GET['TipoTomaFisica'] ,0,0,'C');
        $this->SetFont('Arial','B',8);

		$this->Ln(8);
        $this->Cell(65);
        $this->SetFont('Arial','B',8);
        $this->Cell(15,23,"",0,0,'C');
        $this->Cell(10,7,$lc_local,0,0,'L');

        $this->Ln(4);
        $this->Cell(69);
        $this->SetFont('Arial','',8);
        $this->SetTextColor(0,0,0);
        $this->Cell(15,23,"",0,0,'C');
        $this->Cell(10,7,$this->header.' Articulos',0,0,'L');

        $this->Ln(-12);
		$this->Cell(87);
        $this->SetTextColor(0,0,0);
        $this->SetFont('Arial','B',12);
        $this->Cell(15,23,"",0,0,'C');
        $this->SetFont('Arial','B',8);
		$this->Cell(48);
        $this->Cell(10,7,'Fecha:'.$lc_fecha,0,0,'L');
		$this->Ln(4);

		$this->Cell(150);
        $this->Cell(10,7,'Bodega:'.$_GET['DescripcionBodega'],0,0,'L');
        $this->Ln(5);

        $this->Cell(150);
        $this->Cell(10,4,'Usuario:'.$lc_nombre_usuario,0,0,'L');
        $this->Ln(5);

		/////
		$this->SetFont('Arial','B',6);
        //Cabecera1







		//Line break
		$this->Ln(5);
	}
//comenmt
//Page footer
	function Footer()
	{
		//Position at 1.5 cm from bottom
		$this->SetY(-15);
		//Arial italic 8
		$this->SetFont('Arial','I',6);
		//Page number
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}

//Instanciation of inherited class




//$header = array('BOD.FRIAS', 'BOD.SECOS', 'COCINA', 'LINEA','LINEA/COCINA','PCH.FRIAS','PCH.SECO','PCH.VINOS');
$DATOS=$objArticulos->fn_consultarArticulos($lc_condiciones);
$header=$DATOS['TotalArticulos'];
$pdf=new PDF($header);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',6);




$grupo='';
$i=0;

//var_dump(json_encode($DATOS['Tablerows']));





foreach($DATOS['Tablerows']  as $number => $number_array)
{

    foreach($number_array as $data => $user_data)
    {

        if($data == 1){
            //id
            $arreglo[$i][3]=substr($user_data,0,8);
		}
        if($data == 4){
            //grupo
            if ($grupo!=$user_data) {
                $grupo=$user_data;
                $arreglo[$i][0]=substr($user_data,0,25);
            }else {
                $arreglo[$i][0]='';
            }
            $i++;
        }
        if($data == 2){
        //articulo

            $arreglo[$i][1]=substr(($user_data),0,55);
          //  var_dump(iconv('UTF-8', 'ASCII//TRANSLIT', $arreglo[$i][1]) );
        }

        if($data == 3){
            //unidad
            $arreglo[$i][2]=substr($user_data,0,12);

        }

    }
}



$est=0;
$NextPage=false;
$AuxSeguinteGrupo=false;

		for ($l=0;$l<$i;$l++ ){

					$dist=$pdf->GetY();

            if ($dist>=260)
            {
                $pdf->SetY(29);
                if ($est==0)
                    $est=1;
                else
                    {$est=0 ; $pdf->AddPage();
                        $pdf->SetFont('Times','B',9);
                        $pdf->Cell(98,5,$AuxSeguinteGrupo,'B',0,'L');
                        $pdf->Ln(5);

                    }
            }

            if ($est==0)
            {
                if ($arreglo[$l][0]!=''){

                    $pdf->SetFont('Times','B',9);
                    $pdf->Cell(98,5,$arreglo[$l][0],'B',0,'L');
                    $pdf->Ln(5);

                    $AuxSeguinteGrupo=$arreglo[$l][0];
                }
                $pdf->SetFont('Times','',6);
                $pdf->Cell(12,5,$arreglo[$l][3],0);
                //$pdf->Cell(64,5,iconv('UTF-8', 'ASCII//TRANSLIT', $arreglo[$l][1]) , 0);
                $pdf->Cell(64,5,utf8_decode( $arreglo[$l][1]) , 0);
                $pdf->Cell(10,5,$arreglo[$l][2],0);

            }else{
                $pdf->Cell(100);

                if ($arreglo[$l][0]!=''){

                    $pdf->SetFont('Times','B',9);
                    $pdf->Cell(98,5,$arreglo[$l][0],'B',0,'L');
                    $pdf->Ln(5);

                    $AuxSeguinteGrupo=$arreglo[$l][0];
                    $pdf->Ln(5);
                    $pdf->Cell(100);
                }

                $pdf->SetFont('Times','',6);
                $pdf->Cell(12,5,$arreglo[$l][3],0);
                //$pdf->Cell(64,5,iconv('UTF-8', 'ASCII//TRANSLIT', $arreglo[$l][1]) , 0);
                $pdf->Cell(64,5,utf8_decode( $arreglo[$l][1]) , 0);
                $pdf->Cell(10,5,$arreglo[$l][2],0);
            }
            $pdf->Ln(5);
		}

$pdf->Output();
$lc_obj->fn_liberarecurso();
?>
