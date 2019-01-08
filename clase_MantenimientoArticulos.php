<?php
/**
 * Created by PhpStorm.
 * User: kevin.ibadango
 * Date: 17/9/2018
 * Time: 10:33
 */

include_once("../../seguridades/seguridad.inc");



class clase_MantenimientoArticulos extends sql
{

    public function fn_tbArticuloRest_ini($lc_condiciones)
    {
        $FiltroSoloArtSelecionado='';
        //deseas filtrar solo los articulos seleccionados?

        if($lc_condiciones[10] =='0'){
            $Cod_Grupo_art = '';
        }else{
            $Cod_Grupo_art = " and grup.Cod_GrupoArt ='$lc_condiciones[10]'";
        }



        if($lc_condiciones[13]=='SI'){
            if($lc_condiciones[12]==''){
                $FiltroSoloArtSelecionado = '   and ArtBodega.Cod_Art_Bodega in (0)';
            }else{
                $FiltroSoloArtSelecionado = '   and ArtBodega.Cod_Art_Bodega in ( '.implode(', ', $lc_condiciones[12]).')';
            }

        }else{
            $FiltroSoloArtSelecionado = '';
        }

        $stack='';
        if($lc_condiciones[11] =='Diario'){
            $TipoTomaFisica = "and   ((ArtBodega.tomafisica is null OR 	ArtBodega.tomafisica  ='Mensual') OR  (ArtBodega.tomafisica ='Semanal'))";
        }elseif ($lc_condiciones[11] =='Semanal'){
            $TipoTomaFisica = "and   ((ArtBodega.tomafisica is null OR 	ArtBodega.tomafisica  ='Mensual') OR   (ArtBodega.tomafisica ='Diario'))";
        }elseif ($lc_condiciones[11] =='Mensual'){
            $TipoTomaFisica = "and   ((ArtBodega.tomafisica ='Semanal') OR   (ArtBodega.tomafisica ='Diario'))";
        }

        //ejemplo
        //db => alias de la tabla ejemplo  tb.Descripcion (en este ejemplo no utilizo un alias) (requerido )
        //dt => orden de columnas al desplegar en datatables (requerido )
        //field => nombre de campo  = Descripcion (requerido )
        //as  => Alias de la Column  ejemplo select desripcion as desp from ... (No requerido )
        //Fecha_Recepcion1  => solo debe adjuntar este campo a las fechas para que el orden by funccione. (No requerido ) para este ejemplo en js esta instanciado   order: [[ 1, "desc" ]] (order by columna fecha)
        // Declaracion de campos
        $columns = array(
            array( 'db' => "ArtBodega.Cod_Art_Bodega",          'dt' => 0  , 'field' =>'Cod_Art_Bodega',     'as'=>'Cod_Art_Bodega'),
            array( 'db' => "''",                                'dt' => 1  , 'field' =>'Checkbox',           'as'=>'Checkbox',           'formatter' => 'fn_tbArticuloRest_ini_AgregarCheckbox'),
            array( 'db' => "RTRIM(Arti.Cod_Articulo)",           'dt' => 2  , 'field' =>'Cod_Articulo',       'as'=>'Cod_Articulo'),
            array( 'db' => "RTRIM(Arti.Nombre)",                'dt' => 3  , 'field' =>'Nombre',             'as'=>'Nombre'),
            array( 'db' => "''",                                'dt' => 4  , 'field' =>'Agregar',            'as'=>'Agregar'            , 'formatter' => 'fn_tbArticuloRest_ini_AgregarIconAgregar'),
            array( 'db' => "'No'",                              'dt' => 5  , 'field' =>'AgregarCssVerde',    'as'=>'AgregarCssVerde'),
            array( 'db' => "RTRIM(grup.Descripcion)",           'dt' => 6  , 'field' =>'Descripcion',        'as'=>'Descripcion'),
            array( 'db' => "grup.Cod_GrupoArt",                 'dt' => 7  , 'field' =>'Cod_GrupoArt',       'as'=>'Cod_GrupoArt')
        );
        //$condiciones[0] = $request->metodo;
        //$condiciones[1] =$request->IduserSession;
        //$condiciones[2] =$_POST;
        //$condiciones[3] =$request->cod_bodega;
        //$condiciones[4] =$request->cod_restaurante;
        //$condiciones[5] =$request->Cod_Cadena;
        //$condiciones[6] =$request->cod_restaurante;
        //$condiciones[7] =$request->sitio;
        //$condiciones[8] =$request->DescripcionBodega;
        //$condiciones[9] =$request->Nombre_restaurante;
        //$condiciones[10] =$request->Cod_Grupo_art;
        //$condiciones[11] =$request->TipoTomaFisica;

        //declaracion de tabla principal  ejemplo $table = 'users' o una subconsulta (SELECT * from temp ) as tbTemporal
        $table = 'ArticulosCadena';
        //Permite realizar un join a la tabla principal  ($table)  , si realiza un inner join debe declar el campo con su respectivo alias en el array $columns.....  ejemplo 'db' => 'temp.Descripcion'
        $joinQuery = " FROM {$table}  ARTCadena
                    inner join Articulos Arti on ARTCadena.Cod_Articulo = Arti.Cod_Articulo
                    inner join Costos_Articulos  CostArt on CostArt.Cod_Articulo = ARTCadena.Cod_Articulo 
                                    and  ARTCadena.Cod_Cadena = CostArt.Cod_Cadena 
                                    and CostArt.Sitio = '$lc_condiciones[7]' 
                                    and CostArt.Estado= 1
                    inner join Restaurante Rest on Rest.Cod_Cadena = ARTCadena.Cod_Cadena and rest.Cod_Restaurante = '$lc_condiciones[6]'
                    inner join ArticuloBodega ArtBodega on ArtBodega.Cod_Articulo = Arti.Cod_Articulo 
                                    and  Cod_Bodega = '$lc_condiciones[3]' 
                                    and ArtBodega.Estado = 1
                    inner join GruposArt grup on Arti.Cod_GrupoArt=grup.Cod_GrupoArt 
                    inner join Bodegas bod on bod.Cod_Bodega=ArtBodega.Cod_Bodega
                ";

        //clave Primaria de la tabla principal
        $primaryKey = 'ArtBodega.Cod_Art_Bodega';
        $extraWhere = "
        	ARTCadena.Cod_Cadena = $lc_condiciones[5]   
           $TipoTomaFisica
           $Cod_Grupo_art 
           $FiltroSoloArtSelecionado
        ";
        //$extraWhere = "Cod_Pedido = 'A001O0000000'";
        //$groupBy = 'CP.Cod_Pedido, PR.Cod_Proveedor, PR.Nombre';
        $having = '';
        $groupBy = '';
        $distinct  = 'true';
        $returna=$this->ExecuteQuerys( $lc_condiciones[2], null, $table, $primaryKey, $columns ,$joinQuery,$extraWhere,$groupBy,$having,$distinct,$lc_condiciones);
        return  json_encode($returna);
    }
    public function fn_tbTipoTomaFisica_ini($lc_condiciones)
    {


        $FiltroSoloArtSelecionado='';
        //deseas filtrar solo los articulos seleccionados


        if($lc_condiciones[13]=='SI'){
            if($lc_condiciones[12]==''){
                $FiltroSoloArtSelecionado = '   and ArtBodega.Cod_Art_Bodega in (0)';
            }else{
                $FiltroSoloArtSelecionado = '   and ArtBodega.Cod_Art_Bodega in ( '.implode(', ', $lc_condiciones[12]).')';
            }

        }else{
            $FiltroSoloArtSelecionado = '';
        }




        if($lc_condiciones[10] =='0'){
            $Cod_Grupo_art = '';
        }else{
            $Cod_Grupo_art = " and grup.Cod_GrupoArt ='$lc_condiciones[10]'";
        }

        $stack='';
        if($lc_condiciones[11] =='Diario'){
            $TipoTomaFisica = "and   (ArtBodega.tomafisica ='Diario')";
        }elseif ($lc_condiciones[11] =='Semanal'){
            $TipoTomaFisica = "and   (ArtBodega.tomafisica ='Semanal')";
        }elseif ($lc_condiciones[11] =='Mensual'){
            $TipoTomaFisica = "and   (ArtBodega.tomafisica is null OR 	ArtBodega.tomafisica  ='Mensual') ";
        }


        //ejemplo
        //db => alias de la tabla ejemplo  tb.Descripcion (en este ejemplo no utilizo un alias) (requerido )
        //dt => orden de columnas al desplegar en datatables (requerido )
        //field => nombre de campo  = Descripcion (requerido )
        //as  => Alias de la Column  ejemplo select desripcion as desp from ... (No requerido )
        //Fecha_Recepcion1  => solo debe adjuntar este campo a las fechas para que el orden by funccione. (No requerido ) para este ejemplo en js esta instanciado   order: [[ 1, "desc" ]] (order by columna fecha)
        // Declaracion de campos
        $columns = array(
            array( 'db' => "ArtBodega.Cod_Art_Bodega",                  'dt' => 0  , 'field' =>'Cod_Art_Bodega',      'as'=>'Cod_Art_Bodega'),
            array( 'db' => "rtrim(Arti.Cod_Articulo)",                  'dt' => 1  , 'field' =>'Cod_Articulo',        'as'=>'Cod_Articulo'),
            array( 'db' => " rtrim(Arti.Nombre)",                       'dt' => 2  , 'field' =>'Nombre',              'as'=>'Nombre'),
            array( 'db' => "isnull(ArtBodega.tomafisica, 'Mensual' )",  'dt' => 3  , 'field' =>'tomafisica',          'as'=>'tomafisica','formatter' => 'fn_tbTipoTomaFisica_ini_AgregarSelect'),
            array( 'db' => "grup.Descripcion",                          'dt' => 4  , 'field' =>'Descripcion',         'as'=>'Descripcion'),
            array( 'db' => "''",                                        'dt' => 5  , 'field' =>'Editar',               'as'=>'Editar', 'formatter' => 'fn_tbTipoTomaFisica_ini_Editar'),
            array( 'db' => "''",                                        'dt' => 6  , 'field' =>'Guardar',            'as'=>'Guardar', 'formatter' => 'fn_tbTipoTomaFisica_ini_Guardar')
        );


        //$condiciones[0] = $request->metodo;
        //$condiciones[1] =$request->IduserSession;
        //$condiciones[2] =$_POST;
        //$condiciones[3] =$request->cod_bodega;
        //$condiciones[4] =$request->cod_restaurante;
        //$condiciones[5] =$request->Cod_Cadena;
        //$condiciones[6] =$request->cod_restaurante;
        //$condiciones[7] =$request->sitio;
        //$condiciones[8] =$request->DescripcionBodega;
        //$condiciones[9] =$request->Nombre_restaurante;
        //$condiciones[10] =$request->Cod_Grupo_art;
        //$condiciones[11] =$request->TipoTomaFisica;


        //declaracion de tabla principal  ejemplo $table = 'users' o una subconsulta (SELECT * from temp ) as tbTemporal
        $table = 'ArticulosCadena';
        //Permite realizar un join a la tabla principal  ($table)  , si realiza un inner join debe declar el campo con su respectivo alias en el array $columns.....  ejemplo 'db' => 'temp.Descripcion'
        $joinQuery = " FROM {$table}  ARTCadena
                    inner join Articulos Arti on ARTCadena.Cod_Articulo = Arti.Cod_Articulo
                    inner join Costos_Articulos  CostArt on CostArt.Cod_Articulo = ARTCadena.Cod_Articulo 
                                    and  ARTCadena.Cod_Cadena = CostArt.Cod_Cadena 
                                    and CostArt.Sitio = '$lc_condiciones[7]' 
                                    and CostArt.Estado= 1
                    inner join Restaurante Rest on Rest.Cod_Cadena = ARTCadena.Cod_Cadena and rest.Cod_Restaurante = '$lc_condiciones[6]'
                    inner join ArticuloBodega ArtBodega on ArtBodega.Cod_Articulo = Arti.Cod_Articulo 
                                    and  Cod_Bodega = '$lc_condiciones[3]'
                                    and ArtBodega.Estado = 1
                    inner join GruposArt grup on Arti.Cod_GrupoArt=grup.Cod_GrupoArt 
                    inner join Bodegas bod on bod.Cod_Bodega=ArtBodega.Cod_Bodega
                ";

        //clave Primaria de la tabla principal
        $primaryKey = 'ArtBodega.Cod_Art_Bodega';
        $extraWhere = "
        	ARTCadena.Cod_Cadena = $lc_condiciones[5]   
           $TipoTomaFisica
           $Cod_Grupo_art
           $FiltroSoloArtSelecionado
        ";
        //$extraWhere = "Cod_Pedido = 'A001O0000000'";
        //$groupBy = 'CP.Cod_Pedido, PR.Cod_Proveedor, PR.Nombre';
        $having = '';
        $groupBy = '';
        $distinct  = 'true';
        $returna=$this->ExecuteQuerys( $lc_condiciones[2], null, $table, $primaryKey, $columns ,$joinQuery,$extraWhere,$groupBy,$having,$distinct,$lc_condiciones);
        return  json_encode($returna);
    }

    function fn_consultarArticulos($lc_condiciones) {

        $Grupos='';
        $totalArticulos=0;
        if($lc_condiciones[6]=='Mensual'){
            $lc_condiciones[6]=" (ArtBodega.tomafisica is null OR 	ArtBodega.tomafisica  ='Mensual')";
        }else{
            $lc_condiciones[6]=" ArtBodega.tomafisica ='$lc_condiciones[6]'";
        }
        $Tablerows=array();

        $lc_query = "
        set dateformat dmy;SELECT  distinct ArtBodega.Cod_Art_Bodega AS Cod_Art_Bodega, rtrim(Arti.Cod_Articulo) AS Cod_Articulo,  rtrim(Arti.Nombre) AS Nombre, isnull(ArtBodega.tomafisica, 'Mensual' ) AS tomafisica, grup.Descripcion AS Descripcion,grup.Cod_GrupoArt 
			  FROM ArticulosCadena  ARTCadena
                    inner join Articulos Arti on ARTCadena.Cod_Articulo = Arti.Cod_Articulo
                    inner join Costos_Articulos  CostArt on CostArt.Cod_Articulo = ARTCadena.Cod_Articulo 
                                    and  ARTCadena.Cod_Cadena = CostArt.Cod_Cadena 
                                    and CostArt.Sitio = '$lc_condiciones[3]' 
                                    and CostArt.Estado= 1
                    inner join Restaurante Rest on Rest.Cod_Cadena = ARTCadena.Cod_Cadena and rest.Cod_Restaurante = '$lc_condiciones[2]'
                    inner join ArticuloBodega ArtBodega on ArtBodega.Cod_Articulo = Arti.Cod_Articulo 
                                    and  Cod_Bodega = '$lc_condiciones[0]'
                                    and ArtBodega.Estado = 1
                    inner join GruposArt grup on Arti.Cod_GrupoArt=grup.Cod_GrupoArt 
                    inner join Bodegas bod on bod.Cod_Bodega=ArtBodega.Cod_Bodega
                
			 
			  WHERE 
        	ARTCadena.Cod_Cadena = $lc_condiciones[1]  
           and  $lc_condiciones[6]
			 ORDER BY grup.Descripcion ASC,  rtrim(Arti.Nombre) ASC
        ";
//--Cod_GrupoArt

        $result = $this->fn_ejecutarquery($lc_query);
        $auxGrupo= '';
        while ($row = $this->fn_leerarreglo()) {
            $stack =  array(
                $row['Cod_Art_Bodega'],
                $row['Cod_Articulo'],
                utf8_encode($row['Nombre']),
                utf8_encode($row['tomafisica']),
                $row['Descripcion'],
                $row['Cod_GrupoArt']);

            if($row["Descripcion"] != $auxGrupo){
                $Grupos[] = array('Descripcion'=> $row["Descripcion"],
                    'Cod_GrupoArt'=> $row["Cod_GrupoArt"] );
                $auxGrupo=$row["Descripcion"];
            }

            array_push($Tablerows,$stack);
            $totalArticulos++;
        }

        $lc_regs['Tablerows']= $Tablerows;
        $lc_regs['Grupos']= $Grupos;
        $lc_regs['TotalArticulos']= $totalArticulos;
        //$lc_regs['condicionCalculoInventario']= $condicionCalculoInventario;

        //return json_encode($lc_regs);
        return $lc_regs;
    }


    public function fn_tbArticuloRest_ini_AgregarCheckbox($d, &$row,$lc_condiciones){
        //$condiciones[0] = $request->metodo;
        //$condiciones[1] =$request->IduserSession;
        //$condiciones[2] =$_POST;
        //$condiciones[3] =$request->cod_bodega;
        //$condiciones[4] =$request->cod_restaurante;
        //$condiciones[5] =$request->Cod_Cadena;
        //$condiciones[6] =$request->cod_restaurante;
        //$condiciones[7] =$request->sitio;
        //$condiciones[8] =$request->DescripcionBodega;
        //$condiciones[9] =$request->Nombre_restaurante;
        //$condiciones[10] =$request->Cod_Grupo_art;
        //$condiciones[11] =$request->TipoTomaFisica;
        //$condiciones[12] =$request->tbAgregarArticulosSelected;
        //$lc_condiciones[12] =='' la primera vez no envia un array que contiene los selecionados
        if($lc_condiciones[12] ==''){
            return '<input type="checkbox" name="checkboxArtSelected" class = "tbArticuloRest_checkboxArtSelected" value="0">';
        }else{
            //valida si valor del dato existe en el array enviado de los art. selccionados
            if (in_array($row[0], $lc_condiciones[12])) {
                //si existe
                //¡AgregarCssVerde! Si para que en el lado del cliente sepa agregar estilo
                return '<input type="checkbox" name="checkboxArtSelected" class = "tbArticuloRest_checkboxArtSelected" value="0" checked>';
            }else{
                //no existe
                //¡AgregarCssVerde! Se mantiene en No
                return '<input type="checkbox" name="checkboxArtSelected" class = "tbArticuloRest_checkboxArtSelected" value="0">';
            }
        }

    }
    public function fn_tbArticuloRest_ini_AgregarIconAgregar($d, $row,$lc_condiciones){
        //$condiciones[0] = $request->metodo;
        //$condiciones[1] =$request->IduserSession;
        //$condiciones[2] =$_POST;
        //$condiciones[3] =$request->cod_bodega;
        //$condiciones[4] =$request->cod_restaurante;
        //$condiciones[5] =$request->Cod_Cadena;
        //$condiciones[6] =$request->cod_restaurante;
        //$condiciones[7] =$request->sitio;
        //$condiciones[8] =$request->DescripcionBodega;
        //$condiciones[9] =$request->Nombre_restaurante;
        //$condiciones[10] =$request->Cod_Grupo_art;
        //$condiciones[11] =$request->TipoTomaFisica;
        //$condiciones[12] =$request->tbAgregarArticulosSelected;
        return '<a  href="javascript: void(0)" tabindex="-1" class = "tbArticuloRest_IconAgregarArt" border="0" style="vertical-align:middle"><img   src="../../imagenes/greenCircle.png" border="0" width="15" height="15"></a>';
    }

    public function fn_tbTipoTomaFisica_ini_Editar($d, $row,$lc_condiciones){
        //$condiciones[0] = $request->metodo;
        //$condiciones[1] =$request->IduserSession;
        //$condiciones[2] =$_POST;
        //$condiciones[3] =$request->cod_bodega;
        //$condiciones[4] =$request->cod_restaurante;
        //$condiciones[5] =$request->Cod_Cadena;
        //$condiciones[6] =$request->cod_restaurante;
        //$condiciones[7] =$request->sitio;
        //$condiciones[8] =$request->DescripcionBodega;
        //$condiciones[9] =$request->Nombre_restaurante;
        //$condiciones[10] =$request->Cod_Grupo_art;
        //$condiciones[11] =$request->TipoTomaFisica;
        //$condiciones[12] =$request->tbAgregarArticulosSelected;
        return '<a  href="javascript: void(0)" tabindex="-1" class = "tbTipoTomaFisica_IconEditar" border="0" style="vertical-align:middle"><img  src="../../imagenes/editar.png" border="0" width="15" height="15"></a>';
    }
    public function fn_tbTipoTomaFisica_ini_Guardar($d, $row,$lc_condiciones){
        //$condiciones[0] = $request->metodo;
        //$condiciones[1] =$request->IduserSession;
        //$condiciones[2] =$_POST;
        //$condiciones[3] =$request->cod_bodega;
        //$condiciones[4] =$request->cod_restaurante;
        //$condiciones[5] =$request->Cod_Cadena;
        //$condiciones[6] =$request->cod_restaurante;
        //$condiciones[7] =$request->sitio;
        //$condiciones[8] =$request->DescripcionBodega;
        //$condiciones[9] =$request->Nombre_restaurante;
        //$condiciones[10] =$request->Cod_Grupo_art;
        //$condiciones[11] =$request->TipoTomaFisica;
        //$condiciones[12] =$request->tbAgregarArticulosSelected;
        return '<a  href="javascript: void(0)" tabindex="-1" class = "tbTipoTomaFisica_IconGuardar" border="0" style="vertical-align:middle"><img  src="../../imagenes/BARRA/guardar.png" border="0" width="15" height="15"></a>';
    }
    public function fn_Gaurdar($lc_condiciones)
    {
        $lc_query = "set dateformat dmy;
        update ArticuloBodega
                    set TomaFisica = '$lc_condiciones[7]'
                    from 
                    ArticulosCadena  ARTCadena
                    inner join Articulos Arti on ARTCadena.Cod_Articulo = Arti.Cod_Articulo
                    inner join Costos_Articulos  CostArt on CostArt.Cod_Articulo = ARTCadena.Cod_Articulo 
                                    and  ARTCadena.Cod_Cadena = CostArt.Cod_Cadena 
                                    and CostArt.Sitio = '$lc_condiciones[6]' 
                                    and CostArt.Estado= 1
                    inner join Restaurante Rest on Rest.Cod_Cadena = ARTCadena.Cod_Cadena and rest.Cod_Restaurante = '$lc_condiciones[4]'
                    inner join ArticuloBodega ArtBodega on ArtBodega.Cod_Articulo = Arti.Cod_Articulo 
                                    and  Cod_Bodega = '$lc_condiciones[3]' 
                                    and ArtBodega.Estado = 1
                    inner join GruposArt grup on Arti.Cod_GrupoArt=grup.Cod_GrupoArt 
                    inner join Bodegas bod on bod.Cod_Bodega=ArtBodega.Cod_Bodega
                    WHERE 
                    ARTCadena.Cod_Cadena =$lc_condiciones[5]  
                       and Cod_Art_Bodega ='$lc_condiciones[8]'
                    ";
        $result = $this->fn_ejecutarquery($lc_query);

        $lc_query = "set dateformat dmy; 
           insert into AuditTrans (Cod_Audit,Cod_Restaurante,Cod_Usuario,Fecha_Audit,Modulo,Descripcion,Accion) values (newid(),$lc_condiciones[2],$lc_condiciones[1],getdate(),'Art. Por Restaurante','Se modificado toma fisica  $lc_condiciones[11] del restaurante $lc_condiciones[10]','MODIFICAR')
      ";
        $result = $this->fn_ejecutarquery($lc_query);



        return  $result;
    }

    public function fn_tbTipoTomaFisica_ini_AgregarSelect($d, $row,$lc_condiciones){
        //$condiciones[0] = $request->metodo;
        //$condiciones[1] =$request->IduserSession;
        //$condiciones[2] =$_POST;
        //$condiciones[3] =$request->cod_bodega;
        //$condiciones[4] =$request->cod_restaurante;
        //$condiciones[5] =$request->Cod_Cadena;
        //$condiciones[6] =$request->cod_restaurante;
        //$condiciones[7] =$request->sitio;
        //$condiciones[8] =$request->DescripcionBodega;
        //$condiciones[9] =$request->Nombre_restaurante;
        //$condiciones[10] =$request->Cod_Grupo_art;
        //$condiciones[11] =$request->TipoTomaFisica;
        //$condiciones[12] =$request->tbAgregarArticulosSelected;

        $selectAcum = '<select name="selectTipoInv" id="idTipoInv'.$row[0].'" class ="selectTipoInv" disabled style="width: 100%;"> ';

        if($row[3] =='Diario'){
            $selectAcum.= '<option value="Diario" selected>'.$row[3].'</option>';
        }else{
            $selectAcum.= '<option value="Diario">Diario</option>';
        }

        if($row[3] =='Semanal'){
            $selectAcum.= '<option value="Semanal" selected>'.$row[3].'</option>';
        }else{
            $selectAcum.= '<option value="Semanal">Semanal</option>';
        }

        if($row[3] =='Mensual'){
            $selectAcum.= '<option value="Mensual" selected>'.$row[3].'</option>';
        }else{
            $selectAcum.= '<option value="Mensual">Mensual</option>';
        }

        $selectAcum.='</select>';

        return $selectAcum;
    }
    public function fn_tbTipoTomaFisica_ini_AgregarIconAgregar($d, $row,$lc_condiciones){
        //$condiciones[0] = $request->metodo;
        //$condiciones[1] =$request->IduserSession;
        //$condiciones[2] =$_POST;
        //$condiciones[3] =$request->cod_bodega;
        //$condiciones[4] =$request->cod_restaurante;
        //$condiciones[5] =$request->Cod_Cadena;
        //$condiciones[6] =$request->cod_restaurante;
        //$condiciones[7] =$request->sitio;
        //$condiciones[8] =$request->DescripcionBodega;
        //$condiciones[9] =$request->Nombre_restaurante;
        //$condiciones[10] =$request->Cod_Grupo_art;
        //$condiciones[11] =$request->TipoTomaFisica;
        //$condiciones[12] =$request->tbAgregarArticulosSelected;
        return '<a  href="javascript: void(0)" tabindex="-1" class = "tbArticuloRest_IconAgregarArt" border="0" style="vertical-align:middle"><img  src="../../imagenes/BARRA/cancel.png" border="0" width="15" height="15"></a>';
    }
    public function fn_consultarGrupoDeArticulos($lc_condiciones) {
        $lc_regs['fn_consultarBodegas']= $this->fn_consultarBodegas($lc_condiciones);
        $lc_regs['fn_tbAgregarArt_GrupoTodosArticulos']= $this->fn_tbAgregarArt_GrupoTodosArticulos($lc_condiciones);
        $lc_regs['fn_tbTipoTomaFisica_GrupoTomaArticulos']= $this->fn_tbTipoTomaFisica_GrupoTomaArticulos($lc_condiciones);
        return $lc_regs;
    }
    public function fn_consultarBodegas($lc_condiciones){
        //restuarante
        //$lc_condiciones[0]=$_POST["cod_bodega"];
        //$lc_condiciones[1]=$_POST["Cod_Cadena"];
        //$lc_condiciones[2]=$_POST["cod_restaurante"];
        //$lc_condiciones[3]=$_POST["sitio"];
        //$lc_condiciones[4]=$_POST["DescripcionBodega"];
        //$lc_condiciones[5]=$_POST["Nombre_restaurante"];
        //$lc_condiciones[6]=$_POST["TipoTomaFisica"];
        $stack='';
        $lc_query = "set dateformat dmy; select Cod_Bodega,Descripcion,tipo from Bodegas where Cod_Restaurante = $lc_condiciones[2]";
        $result = $this->fn_ejecutarquery($lc_query);
        while ($row = $this->fn_leerarreglo()) {
            $stack[] = array(
                "Cod_Bodega" => utf8_encode($row['Cod_Bodega']),
                "Descripcion" => utf8_encode($row['Descripcion']),
                "tipo" => utf8_encode($row['tipo']),
            );
        }
        return $stack;
    }
    public function fn_tbAgregarArt_GrupoTodosArticulos($lc_condiciones) {
        //tipo inventario  (Mensual, diario, semanal)
        //$lc_condiciones[0]=$_POST["cod_bodega"];
        //$lc_condiciones[1]=$_POST["Cod_Cadena"];
        //$lc_condiciones[2]=$_POST["cod_restaurante"];
        //$lc_condiciones[3]=$_POST["sitio"];
        //$lc_condiciones[4]=$_POST["DescripcionBodega"];
        //$lc_condiciones[5]=$_POST["Nombre_restaurante"];
        //$lc_condiciones[6]=$_POST["TipoTomaFisica"];


        $stack='';
        if($lc_condiciones[6] =='Diario'){
            $TipoTomaFisica = "and   ((ArtBodega.tomafisica is null OR 	ArtBodega.tomafisica  ='Mensual') OR  (ArtBodega.tomafisica ='Semanal'))";
        }elseif ($lc_condiciones[6] =='Semanal'){
            $TipoTomaFisica = "and   ((ArtBodega.tomafisica is null OR 	ArtBodega.tomafisica  ='Mensual') OR   (ArtBodega.tomafisica ='Diario'))";
        }elseif ($lc_condiciones[6] =='Mensual'){
            $TipoTomaFisica = "and   ((ArtBodega.tomafisica ='Semanal') OR   (ArtBodega.tomafisica ='Diario'))";
        }


        $lc_query = "set dateformat dmy;
                    select distinct  grup.Cod_GrupoArt,grup.Descripcion
                    from ArticulosCadena ARTCadena
                    inner join Articulos Arti on ARTCadena.Cod_Articulo = Arti.Cod_Articulo
                    inner join Costos_Articulos  CostArt on CostArt.Cod_Articulo = ARTCadena.Cod_Articulo 
                                    and  ARTCadena.Cod_Cadena = CostArt.Cod_Cadena 
                                    and CostArt.Sitio = '$lc_condiciones[3]' 
                                    and CostArt.Estado= 1
                    inner join Restaurante Rest on Rest.Cod_Cadena = ARTCadena.Cod_Cadena and rest.Cod_Restaurante = '$lc_condiciones[2]'
                    inner join ArticuloBodega ArtBodega on ArtBodega.Cod_Articulo = Arti.Cod_Articulo 
                                    and  Cod_Bodega = '$lc_condiciones[0]'
                                    and ArtBodega.Estado = 1
                    inner join GruposArt grup on Arti.Cod_GrupoArt=grup.Cod_GrupoArt 
                    inner join Bodegas bod on bod.Cod_Bodega=ArtBodega.Cod_Bodega
                    where ARTCadena.Cod_Cadena = '$lc_condiciones[1]'
                    $TipoTomaFisica
                    ORDER BY grup.Cod_GrupoArt  ASC";


        $result = $this->fn_ejecutarquery($lc_query);
        while ($row = $this->fn_leerarreglo()) {
            $stack[] = array(
                "Cod_GrupoArt" => utf8_encode($row['Cod_GrupoArt']),
                "Descripcion" => utf8_encode($row['Descripcion'])
            );
        }

        return $stack;
    }
    public function fn_tbTipoTomaFisica_GrupoTomaArticulos($lc_condiciones) {
        //tipo inventario  (Mensual, diario, semanal)
        //$lc_condiciones[0]=$_POST["cod_bodega"];
        //$lc_condiciones[1]=$_POST["Cod_Cadena"];
        //$lc_condiciones[2]=$_POST["cod_restaurante"];
        //$lc_condiciones[3]=$_POST["sitio"];
        //$lc_condiciones[4]=$_POST["DescripcionBodega"];
        //$lc_condiciones[5]=$_POST["Nombre_restaurante"];
        //$lc_condiciones[6]=$_POST["TipoTomaFisica"];
        $stack='';
        if($lc_condiciones[6] =='Diario'){
            $TipoTomaFisica = "and   (ArtBodega.tomafisica ='Diario')";
        }elseif ($lc_condiciones[6] =='Semanal'){
            $TipoTomaFisica = "and   (ArtBodega.tomafisica ='Semanal')";
        }elseif ($lc_condiciones[6] =='Mensual'){
            $TipoTomaFisica = "and   (ArtBodega.tomafisica is null OR 	ArtBodega.tomafisica  ='Mensual') ";
        }

        $lc_query = "set dateformat dmy;
                    select distinct  grup.Cod_GrupoArt,grup.Descripcion
                    from ArticulosCadena ARTCadena
                    inner join Articulos Arti on ARTCadena.Cod_Articulo = Arti.Cod_Articulo
                    inner join Costos_Articulos  CostArt on CostArt.Cod_Articulo = ARTCadena.Cod_Articulo 
                                    and  ARTCadena.Cod_Cadena = CostArt.Cod_Cadena 
                                    and CostArt.Sitio = '$lc_condiciones[3]' 
                                    and CostArt.Estado= 1
                    inner join Restaurante Rest on Rest.Cod_Cadena = ARTCadena.Cod_Cadena and rest.Cod_Restaurante = '$lc_condiciones[2]'
                    inner join ArticuloBodega ArtBodega on ArtBodega.Cod_Articulo = Arti.Cod_Articulo 
                                    and  Cod_Bodega = '$lc_condiciones[0]'
                                    and ArtBodega.Estado = 1
                    inner join GruposArt grup on Arti.Cod_GrupoArt=grup.Cod_GrupoArt 
                    inner join Bodegas bod on bod.Cod_Bodega=ArtBodega.Cod_Bodega
                    where ARTCadena.Cod_Cadena = '$lc_condiciones[1] '  
                    $TipoTomaFisica
                    ORDER BY grup.Cod_GrupoArt  ASC";


        $result = $this->fn_ejecutarquery($lc_query);
        while ($row = $this->fn_leerarreglo()) {
            $stack[] = array(
                "Cod_GrupoArt" => utf8_encode($row['Cod_GrupoArt']),
                "Descripcion" => utf8_encode($row['Descripcion'])
            );
        }

        return $stack;
    }
    public function fn_Articulos_getTodosLosRestaurantes($lc_condiciones)
    {
        $lc_query = "set dateformat dmy;
        SELECT  distinct r.Cod_Restaurante AS Cod_Restaurante, '' +r.Cod_Tienda +' - '+ R.Descripcion  AS Cod_Tienda, Cod_Bodega AS Cod_Bodega, cast(Filtro_Articulos as int) AS cod_cadena, sitio AS sitio, Bg.Descripcion AS Descripcion
			  FROM Cadena as C
                        inner join Restaurante R on C.Cod_Cadena = R.Cod_Cadena
                        inner join UserRestaurante US on US.Cod_Restaurante = R.Cod_Restaurante
                        inner join Bodegas Bg on bg.Cod_Restaurante = r.Cod_Restaurante
			 
			  WHERE Cod_Usuario = 8623  and bg.tipo = 'Principal' 
			  ";
        $result = $this->fn_ejecutarquery($lc_query);
        while ($row = $this->fn_leerarreglo()) {
            $stack[] = array(
                "Cod_Restaurante" => utf8_encode($row['Cod_Restaurante']),
                "Cod_Tienda" => utf8_encode($row['Cod_Tienda']),
                "Cod_Bodega" => utf8_encode($row['Cod_Bodega']),
                "cod_cadena" => utf8_encode($row['cod_cadena']),
                "sitio" => utf8_encode($row['sitio']),
                "Descripcion" => utf8_encode($row['Descripcion'])
            );
        }
        return $stack;
    }
    public function fn_ArtiPorRestaurante_AgregarArtTomaFisca($lc_condiciones)
    {
        $FiltroSoloArtSelecionado='';
        //deseas filtrar solo los articulos seleccionados?

       // $condiciones[3] =$request->cod_bodega;
        // $condiciones[4] =$request->cod_restaurante;
        //$condiciones[5] =$request->Cod_Cadena;
        //$condiciones[6] =$request->sitio;
        //$condiciones[7] =$request->TipoTomaFisica;


        $FiltroSoloArtSelecionado = '   and Cod_Art_Bodega in ( '.implode(', ', $lc_condiciones[8]).')';
        $lc_query = "set dateformat dmy;
                    update ArticuloBodega
                    set TomaFisica = '$lc_condiciones[7]'
                    from 
                    ArticulosCadena  ARTCadena
                    inner join Articulos Arti on ARTCadena.Cod_Articulo = Arti.Cod_Articulo
                    inner join Costos_Articulos  CostArt on CostArt.Cod_Articulo = ARTCadena.Cod_Articulo 
                                    and  ARTCadena.Cod_Cadena = CostArt.Cod_Cadena 
                                    and CostArt.Sitio = '$lc_condiciones[6]' 
                                    and CostArt.Estado= 1
                    inner join Restaurante Rest on Rest.Cod_Cadena = ARTCadena.Cod_Cadena and rest.Cod_Restaurante = '$lc_condiciones[4]'
                    inner join ArticuloBodega ArtBodega on ArtBodega.Cod_Articulo = Arti.Cod_Articulo 
                                    and  Cod_Bodega = '$lc_condiciones[3]' 
                                    and ArtBodega.Estado = 1
                    inner join GruposArt grup on Arti.Cod_GrupoArt=grup.Cod_GrupoArt 
                    inner join Bodegas bod on bod.Cod_Bodega=ArtBodega.Cod_Bodega
                    WHERE 
                    ARTCadena.Cod_Cadena =$lc_condiciones[5]  
                    $FiltroSoloArtSelecionado ";
        $result = $this->fn_ejecutarquery($lc_query);


        $lc_query = "set dateformat dmy; 
           insert into AuditTrans (Cod_Audit,Cod_Restaurante,Cod_Usuario,Fecha_Audit,Modulo,Descripcion,Accion) values (newid(),$lc_condiciones[2],$lc_condiciones[1],getdate(),'Art. Por Restaurante','Se agregado articulos a la toma fisica  $lc_condiciones[7] del restaurante $lc_condiciones[9]','AGREGAR')
      ";
        $result = $this->fn_ejecutarquery($lc_query);




        return  $result;
    }
    public function getTodosArticulosConCod_Grupo($lc_condiciones)
    {
        $FiltroSoloArtSelecionado='';
        //deseas filtrar solo los articulos seleccionados?


        if($lc_condiciones[8] =='Diario'){
            $TipoTomaFisica = "and   ((ArtBodega.tomafisica is null OR 	ArtBodega.tomafisica  ='Mensual') OR  (ArtBodega.tomafisica ='Semanal'))";
        }elseif ($lc_condiciones[8] =='Semanal'){
            $TipoTomaFisica = "and   ((ArtBodega.tomafisica is null OR 	ArtBodega.tomafisica  ='Mensual') OR   (ArtBodega.tomafisica ='Diario'))";
        }elseif ($lc_condiciones[8] =='Mensual'){
            $TipoTomaFisica = "and   ((ArtBodega.tomafisica ='Semanal') OR   (ArtBodega.tomafisica ='Diario'))";
        }

        $stack='';
        $lc_query = "set dateformat dmy;
                 SELECT  distinct ArtBodega.Cod_Art_Bodega AS Cod_Art_Bodega
			  FROM ArticulosCadena  ARTCadena
                    inner join Articulos Arti on ARTCadena.Cod_Articulo = Arti.Cod_Articulo
                    inner join Costos_Articulos  CostArt on CostArt.Cod_Articulo = ARTCadena.Cod_Articulo 
                                    and  ARTCadena.Cod_Cadena = CostArt.Cod_Cadena 
                                    and CostArt.Sitio = '$lc_condiciones[6]' 
                                    and CostArt.Estado= 1
                    inner join Restaurante Rest on Rest.Cod_Cadena = ARTCadena.Cod_Cadena and rest.Cod_Restaurante = '$lc_condiciones[4]'
                    inner join ArticuloBodega ArtBodega on ArtBodega.Cod_Articulo = Arti.Cod_Articulo 
                                    and  Cod_Bodega = '$lc_condiciones[3]' 
                                    and ArtBodega.Estado = 1
                    inner join GruposArt grup on Arti.Cod_GrupoArt=grup.Cod_GrupoArt 
                    inner join Bodegas bod on bod.Cod_Bodega=ArtBodega.Cod_Bodega
                
			 
			  WHERE 
        	ARTCadena.Cod_Cadena = $lc_condiciones[5]  
           $TipoTomaFisica
		   and grup.Cod_GrupoArt = '$lc_condiciones[7]'
";


        $result = $this->fn_ejecutarquery($lc_query);
        while ($row = $this->fn_leerarreglo()) {
            $stack[] = array(
                "Cod_Art_Bodega" => utf8_encode($row['Cod_Art_Bodega']) );
        }


        return  json_encode($stack);
    }
    public function fn_Articulos_getGrupoTodosArticulos($lc_condiciones)
    {

        return $this->fn_consultarArticulos('');
    }
    public function fn_Articulos_getTipoInventarioTomaFisica()
    {
        $result = null;
        $lc_query = "set dateformat dmy;
                    SELECT distinct  grup.Cod_GrupoArt as Cod_GrupoArt ,grup.Descripcion as Descripcion
                    FROM ArticuloBodega  artbod
                            inner join Bodegas bod on bod.Cod_Bodega=artbod.Cod_Bodega
                            inner join ArticulosCadena artcad on artbod.Cod_Articulo=artcad.Cod_Articulo 
                            inner join Articulos art on artbod.Cod_Articulo=art.Cod_Articulo
                            INNER JOIN Costos_Articulos CosArt ON CosArt.Cod_Articulo= art.Cod_Articulo AND CosArt.Cod_Cadena = 8 AND CosArt.Sitio = 'PUIO'
                            inner join GruposArt grup on art.Cod_GrupoArt=grup.Cod_GrupoArt 
                    WHERE 
                    artbod.Estado=1 and 
                    artcad.Cod_Cadena=8 and       
                    artbod.Cod_Bodega=527 and 
                    ((artbod.tomafisica is null OR 	artbod.tomafisica  ='Mensual') OR  (artbod.tomafisica =' Semanal') OR  (artbod.tomafisica ='Diario'))
                    ORDER BY grup.Cod_GrupoArt  ASC";

        $result = $this->fn_ejecutarquery($lc_query);
        while ($row = $this->fn_leerarreglo()) {

            $this->lc_regs[] = array(
                "Cod_GrupoArt" => $row['Cod_GrupoArt'],
                "Descripcion" => $row['Descripcion']
            );
        }

        return json_encode($this->lc_regs);
    }
    public function fn_Articulos_getCadena($lc_condiciones)
    {

        //ejemplo
        //db => alias de la tabla ejemplo  tb.Descripcion (en este ejemplo no utilizo un alias) (requerido )
        //dt => orden de columnas al desplegar en datatables (requerido )
        //field => nombre de campo  = Descripcion (requerido )
        //as  => Alias de la Column  ejemplo select desripcion as desp from ... (No requerido )
        //Fecha_Recepcion1  => solo debe adjuntar este campo a las fechas para que el orden by funccione. (No requerido ) para este ejemplo en js esta instanciado   order: [[ 1, "desc" ]] (order by columna fecha)
        // Declaracion de campos
        $columns = array(
            array( 'db' => "c.Cod_Cadena",             'dt' => 0  , 'field' =>'Cod_Cadena',      'as'=>'Cod_Cadena'),
            array( 'db' => "c.Descripcion ",           'dt' => 1  , 'field' =>'Descripcion',     'as'=>'Descripcion')
        );


        //declaracion de tabla principal  ejemplo $table = 'users' o una subconsulta (SELECT * from temp ) as tbTemporal
        $table = 'Cadena';
        //Permite realizar un join a la tabla principal  ($table)  , si realiza un inner join debe declar el campo con su respectivo alias en el array $columns.....  ejemplo 'db' => 'temp.Descripcion'
        $joinQuery = " FROM {$table} as C
                        inner join Restaurante R on C.Cod_Cadena = R.Cod_Cadena
                        inner join UserRestaurante US on US.Cod_Restaurante = R.Cod_Restaurante";

        //clave Primaria de la tabla principal
        $primaryKey = 'r.Cod_Cadena';
        $extraWhere = "Cod_Usuario = $lc_condiciones[1]";
        //$extraWhere = "Cod_Pedido = 'A001O0000000'";
        //$groupBy = 'CP.Cod_Pedido, PR.Cod_Proveedor, PR.Nombre';
        $having = '';
        $groupBy = '';
        $distinct  = 'true';
        $returna=$this->ExecuteQuerys( $lc_condiciones[2], null, $table, $primaryKey, $columns ,$joinQuery,$extraWhere,$groupBy,$having,$distinct,$lc_condiciones);
        return  json_encode($returna);
    }
    public function fn_Articulos_Restaurantes_Ini($lc_condiciones)
    {
       /* if($lc_condiciones[3]=='0' ){
            $IdCadena = '';
        }else{
            $IdCadena = 'and c.Cod_Cadena = '.$lc_condiciones[3].' ' ;
        }*/
        //ejemplo
        //db => alias de la tabla ejemplo  tb.Descripcion (en este ejemplo no utilizo un alias) (requerido )
        //dt => orden de columnas al desplegar en datatables (requerido )
        //field => nombre de campo  = Descripcion (requerido )
        //as  => Alias de la Column  ejemplo select desripcion as desp from ... (No requerido )
        //Fecha_Recepcion1  => solo debe adjuntar este campo a las fechas para que el orden by funccione. (No requerido ) para este ejemplo en js esta instanciado   order: [[ 1, "desc" ]] (order by columna fecha)
        // Declaracion de campos
        $columns = array(
            array( 'db' => "r.Cod_Restaurante",                         'dt' => 0  , 'field' =>'Cod_Restaurante',      'as'=>'Cod_Restaurante'),
            array( 'db' => "'( ' +r.Cod_Tienda +' ) '+ R.Descripcion ", 'dt' => 1  , 'field' =>'Cod_Tienda',          'as'=>'Cod_Tienda'),
            array( 'db' => "Cod_Bodega",                                'dt' => 2  , 'field' =>'Cod_Bodega',           'as'=>'Cod_Bodega'),
            array( 'db' => "cast(Filtro_Articulos as int)",             'dt' => 3  , 'field' =>'cod_cadena',           'as'=>'cod_cadena'),
            array( 'db' => "sitio",                                     'dt' => 4  , 'field' =>'sitio',                'as'=>'sitio'),
            array( 'db' => "Bg.Descripcion",                            'dt' => 5  , 'field' =>'Descripcion',          'as'=>'Descripcion')
        );


        //declaracion de tabla principal  ejemplo $table = 'users' o una subconsulta (SELECT * from temp ) as tbTemporal
        $table = 'Cadena';
        //Permite realizar un join a la tabla principal  ($table)  , si realiza un inner join debe declar el campo con su respectivo alias en el array $columns.....  ejemplo 'db' => 'temp.Descripcion'
        $joinQuery = " FROM {$table} as C
                        inner join Restaurante R on C.Cod_Cadena = R.Cod_Cadena
                        inner join UserRestaurante US on US.Cod_Restaurante = R.Cod_Restaurante
                        inner join Bodegas Bg on bg.Cod_Restaurante = r.Cod_Restaurante";

        //clave Primaria de la tabla principal
        $primaryKey = 'r.Cod_Restaurante';
        $extraWhere = "Cod_Usuario = $lc_condiciones[1]  and bg.tipo = 'Principal' ";
        //$extraWhere = "Cod_Pedido = 'A001O0000000'";
        //$groupBy = 'CP.Cod_Pedido, PR.Cod_Proveedor, PR.Nombre';
        $having = '';
        $groupBy = '';
        $distinct  = 'true';
        $returna=$this->ExecuteQuerys( $lc_condiciones[2], null, $table, $primaryKey, $columns ,$joinQuery,$extraWhere,$groupBy,$having,$distinct,$lc_condiciones);
        return  json_encode($returna);
    }
    public function fn_Articulos_getBodegas($lc_condiciones)
    {

        //ejemplo
        //db => alias de la tabla ejemplo  tb.Descripcion (en este ejemplo no utilizo un alias) (requerido )
        //dt => orden de columnas al desplegar en datatables (requerido )
        //field => nombre de campo  = Descripcion (requerido )
        //as  => Alias de la Column  ejemplo select desripcion as desp from ... (No requerido )
        //Fecha_Recepcion1  => solo debe adjuntar este campo a las fechas para que el orden by funccione. (No requerido ) para este ejemplo en js esta instanciado   order: [[ 1, "desc" ]] (order by columna fecha)
        // Declaracion de campos
        $columns = array(
            array( 'db' => "Cod_Bodega",                         'dt' => 0  , 'field' =>'Cod_Bodega',          'as'=>'Cod_Bodega'),
            array( 'db' => "cast(Filtro_Articulos as int) ",     'dt' => 1  , 'field' =>'Cod_Cadena',          'as'=>'Cod_Cadena'),
            array( 'db' => "res.Cod_Restaurante",                'dt' => 2  , 'field' =>'Cod_Restaurante',     'as'=>'Cod_Restaurante'),
            array( 'db' => "res.sitio",                          'dt' => 3  , 'field' =>'sitio',               'as'=>'sitio'),
            array( 'db' => "Bg.Descripcion +' ( '+Tipo+' ) '",   'dt' => 4  , 'field' =>'DescripcionBodega',   'as'=>'DescripcionBodega')
        );


        //declaracion de tabla principal  ejemplo $table = 'users' o una subconsulta (SELECT * from temp ) as tbTemporal
        $table = 'bodegas';
        //Permite realizar un join a la tabla principal  ($table)  , si realiza un inner join debe declar el campo con su respectivo alias en el array $columns.....  ejemplo 'db' => 'temp.Descripcion'
        $joinQuery = " FROM {$table}   Bg
inner join  Restaurante res on Bg.Cod_Restaurante = res.Cod_Restaurante";

        //clave Primaria de la tabla principal
        $primaryKey = 'Cod_Bodega';
        $extraWhere = "  res.Cod_Restaurante = $lc_condiciones[3]";
        //$extraWhere = "Cod_Pedido = 'A001O0000000'";
        //$groupBy = 'CP.Cod_Pedido, PR.Cod_Proveedor, PR.Nombre';
        $having = '';
        $groupBy = '';
        $distinct  = 'true';
        $returna=$this->ExecuteQuerys( $lc_condiciones[2], null, $table, $primaryKey, $columns ,$joinQuery,$extraWhere,$groupBy,$having,$distinct,$lc_condiciones);
        return  json_encode($returna);
    }
    public function fn_Articulos_getPermisosRestaurante()
    {
        $result = null;
        $lc_query = "
                   set dateformat dmy;
                   SELECT  r.Cod_Restaurante AS Cod_Restaurante, '( ' +r.Cod_Tienda +' ) '+ R.Descripcion  AS Descripcion
                      FROM Cadena as C
                                inner join Restaurante R on C.Cod_Cadena = R.Cod_Cadena
                                inner join UserRestaurante US on US.Cod_Restaurante = R.Cod_Restaurante
                     
                      WHERE Cod_Usuario = 7072";

        $result = $this->fn_ejecutarquery($lc_query);
        while ($row = $this->fn_leerarreglo()) {

            $this->lc_regs[] = array(
                "Cod_Restaurante" => $row['Cod_Restaurante'],
                "Descripcion" => $row['Descripcion']
            );
        }

        return json_encode($this->lc_regs);
    }
    function ExecuteQuerys ( $request, $sql_details, $table, $primaryKey, $columns, $joinQuery = NULL, $extraWhere = '', $groupBy = '', $having = '',$distinct,$lc_condiciones)

    {

        if($distinct=='true'){
            $distinct='distinct';
        }else {
            $distinct='';
        }

        $bindings = array();
        $data= array();
        $whereAllSql = '';
        // Build the SQL query string from the request
        $limit = self::limit( $request, $columns );

        $order = self::order( $request, $columns, $joinQuery );


        $where = self::filter( $request, $columns, $bindings );
        // IF Extra where set then set and prepare query
        if($extraWhere) $extraWhere = ($where) ? ' AND '.$extraWhere : ' WHERE '.$extraWhere;
        $groupBy = ($groupBy) ? ' GROUP BY '.$groupBy .' ' : '';
        $having = ($having) ? ' HAVING '.$having .' ' : '';
        // Main query to actually get the data
        if($joinQuery){
            $col = self::pluck($columns, 'db', $joinQuery);
            $query =  "set dateformat dmy;SELECT  $distinct ".implode(", ", $col)."
			 $joinQuery
			 $where
			 $extraWhere
			 $groupBy
             $having
			 $order
			 $limit";


            $recordsTotal =  "set dateformat dmy;SELECT  $distinct count(*) as recordsTotal ".
                $joinQuery. " ".
                $where." ".
                $extraWhere." ".
                $groupBy." ".
                $having." ";

            //$recordsFiltered =  "set dateformat dmy;SELECT count(*) as recordsFiltered ".

            $recordsFiltered =  " SELECT $distinct $primaryKey".
                $joinQuery. " ".
                $where." ".
                $extraWhere." ".
                $groupBy." ".
                $having." ".
                "order by ". $primaryKey." ".
                $limit." ";

        }
        else{

            $query =  "set dateformat dmy;SELECT $distinct  ".implode(", ", self::pluck($columns, 'db'))."
			 FROM $table
			 $where
			 $extraWhere
			 $groupBy
             $having
			 $order
			 $limit";

            $recordsTotal =  "set dateformat dmy;SELECT $distinct count(*) as recordsTotal ".
                $joinQuery. "".
                $where."".
                $extraWhere."".
                $groupBy."".
                $having."";

            $recordsFiltered =  " SELECT  $distinct $primaryKey".
                $joinQuery. "".
                $where."".
                $extraWhere."".
                $groupBy."".
                $having." ".
                "order by ". $primaryKey." ".
                $limit."";

        }

        $this->fn_ejecutarquery($query);
        while ($row = $this->fn_leerarreglo()) {
            $data[] = $row;
        }



        $this->fn_ejecutarquery($recordsTotal);
        while ($row = $this->fn_leerarreglo()) {
            $recordsTotal =$row['recordsTotal'];
        }


        // Total data set length

        $lcquery = "SELECT $distinct count(*) as recordsFiltered from ( $recordsFiltered)  as tt";

        $this->fn_ejecutarquery($lcquery);
        while ($row = $this->fn_leerarreglo()) {

            $recordsFiltered =$row['recordsFiltered'];
        }



        while ($row = $this->fn_leerarreglo()) {
            $data[] = $row;
        }
        return array(
            "draw"            => isset ( $request['draw'] ) ? intval( $request['draw'] ) : 0,
            "recordsTotal"    => intval( $recordsFiltered  ),
            "recordsFiltered" => intval(  $recordsTotal),
            "data"            => self::data_output( $columns, $data ,$joinQuery,$lc_condiciones)

        );
    }
    function data_output ( $columns, $data, $isJoin = false,$lc_condiciones )
    {
        $out = array();
        for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
            $row = array();
            for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
                $column = $columns[$j];
                // Is there a formatter?
                if ( isset( $column['formatter'] ) ) {
                    if(isset($column['as'])){
                        $row[ $column['dt'] ] = ($isJoin) ? $this->$column['formatter']( utf8_encode(rtrim($data[$i][ $column['as'] ])), $data[$i],$lc_condiciones) : $this->$column['formatter']( utf8_encode(rtrim($data[$i][ $column['db'] ])), $data[$i],$lc_condiciones );
                    }else{
                        $row[ $column['dt'] ] = ($isJoin) ? utf8_encode(rtrim($this->$column['formatter']( $data[$i][ $column['field'] ])), $data[$i],$lc_condiciones) : utf8_encode(rtrim($this->$column['formatter']( $data[$i][ $column['db'] ])), $data[$i],$lc_condiciones );
                    }
                }
                else {
                    if(isset($column['as'])){
                        $row[ $column['dt'] ] = ($isJoin) ? utf8_encode(rtrim($data[$i][ $columns[$j]['as'] ])) : utf8_encode(rtrim($data[$i][ $columns[$j]['db'] ]));
                    }else{
                        $row[ $column['dt'] ] = ($isJoin) ? utf8_encode(rtrim($data[$i][ $columns[$j]['field'] ])) : utf8_encode(rtrim($data[$i][ $columns[$j]['db'] ]));
                    }
                }
            }

            $out[] = $row;
        }
        return $out;
    }
    static function limit ( $request, $columns )
    {
        $limit = '';
        if ( isset($request['start']) && $request['length'] != -1 ) {
            $limit = "OFFSET ".intval($request['start'])." ROWS FETCH NEXT ".intval($request['length'])."  ROWS ONLY";
        }
        return $limit;
    }
    static function order ( $request, $columns,$isJoin = false )
    {

        $order = '';
        if ( isset($request['order']) && count($request['order']) ) {
            $orderBy = array();
            $dtColumns = self::pluck( $columns, 'dt' );


            for ( $i=0, $ien=count($request['order']) ; $i<$ien ; $i++ ) {

                // Convert the column index into the column data property
                $columnIdx = intval($request['order'][$i]['column']);

                $requestColumn = $request['columns'][$columnIdx];
                $columnIdx = array_search( $requestColumn['data'], $dtColumns );
                $column = $columns[ $columnIdx ];
                if ( $requestColumn['orderable'] == 'true' ) {

                    $dir = $request['order'][$i]['dir'] === 'asc' ?
                        'ASC' :
                        'DESC';
                    if(isset($column['FechaFormat'])){
                        $orderBy[] = ($isJoin) ? $column['FechaFormat'].' '.$dir : ''.$column['FechaFormat'].' '.$dir;
                    }else {
                        $orderBy[] = ($isJoin) ? $column['db'].' '.$dir : ''.$column['db'].' '.$dir;
                    }
                }
            }
            $order = 'ORDER BY '.implode(', ', $orderBy);
        }
        return $order;
    }
    static function filter ( $request, $columns, &$bindings , $isJoin = false )
    {
        $globalSearch = array();
        $columnSearch = array();
        $dtColumns = self::pluck( $columns, 'dt' );
        if ( isset($request['search']) && $request['search']['value'] != '' ) {
            $str = $request['search']['value'];
            for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search( $requestColumn['data'], $dtColumns );
                $column = $columns[ $columnIdx ];
                if ( $requestColumn['searchable'] == 'true' ) {
                    //$binding = self::bind( $bindings, '%'.$str.'%', PDO::PARAM_STR );
                    $globalSearch[] = ($isJoin) ? "".$column['db']." LIKE '%".$str."%'": "".$column['db']." LIKE '%".$str."%'";
                }
            }
        }
        // Individual column filtering

        if ( isset( $request['columns'] ) ) {
            for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];
                $str = $requestColumn['search']['value'];
                if ($requestColumn['searchable'] == 'true' &&
                    $str != '') {
                    //$binding = self::bind( $bindings, '%'.$str.'%', PDO::PARAM_STR );
                    //$columnSearch[] = "" . $column['db'] . " LIKE '%" . $str . "%'";

                    $columnSearch[] = ($isJoin) ? "".$column['db']." LIKE '%".$str."%'": "".$column['db']." LIKE '%".$str."%'";

                }
            }
        }
        // Combine the filters into a single string
        $where = '';
        if ( count( $globalSearch ) ) {
            $where = '('.implode(' OR ', $globalSearch).')';
        }
        if ( count( $columnSearch ) ) {
            $where = $where === '' ?
                implode(' AND ', $columnSearch) :
                $where .' AND '. implode(' AND ', $columnSearch);
        }
        if ( $where !== '' ) {
            $where = 'WHERE '.$where;
        }
        return $where;
    }
    static function pluck ( $a, $prop, $isJoin = false )
    {
        $out = array();
        for ( $i=0, $len=count($a) ; $i<$len ; $i++ ) {
            $out[] = ($isJoin && isset($a[$i]['as'])) ? $a[$i][$prop]. ' AS '.$a[$i]['as'] : $a[$i][$prop];
        }
        return $out;
    }
    function fn_User($lc_restaurante)
    {
        $lc_query = "select Descripcion from Users where Cod_Usuario ='$lc_restaurante'";
        if($lc_datos=$this->fn_ejecutarquery($lc_query))
        {
            $lc_row = $this->fn_leerobjeto();
            $lc_numreg = $this->fn_numregistro();
            if ( $lc_numreg > 0)
            {
                return $lc_row->Descripcion;
            }
        }
    }
    function fn_nombrelocal($lc_restaurante)
    {
        $lc_query = "select c.Descripcion as Descripcion, c.Cod_Tienda from Restaurante c where  c.Cod_Restaurante='$lc_restaurante'";
        if($lc_datos=$this->fn_ejecutarquery($lc_query))
        {
            $lc_row = $this->fn_leerobjeto();
            $lc_numreg = $this->fn_numregistro();
            if ( $lc_numreg > 0)
            {
                return $lc_row->Cod_Tienda . " _ ". $lc_row->Descripcion;
            }
        }
    }
    function fn_logo($lc_restaurante) {
        $lc_query = "select Logo from Restaurante res,Cadena cad where res.Cod_Cadena=cad.Cod_Cadena and  Cod_Restaurante=$lc_restaurante";
        if ($lc_datos = $this->fn_ejecutarquery($lc_query)) {
            $lc_row = $this->fn_leerobjeto();
            $lc_res = $this->fn_numregistro();
            if ($lc_res > 0) {
                return $lc_row->Logo;
            } else {
                return 0;
            }
        }
    }




}