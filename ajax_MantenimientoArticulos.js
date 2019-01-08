
var IdrestauranteSession;
var IduserSession;
var tbCadena;
var $dialogAgregarArticulo;
var $dialogContainer;
var tbRestaurante;
var tbBodega ;
var tbArticuloRest;
var tbTipoInventario ;
var selected = [];
var msgCounter=0;
var tbArticuloRest_ArrayArticulosSelected = [];
var tbArticuloRest_ArrayGruposSelected = [];
$.datepicker.regional['es'] = {
    closeText: 'Cerrar',
    prevText: '< Ant',
    nextText: 'Sig >',
    currentText: 'Hoy',
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'Mi&eacute;rcoles', 'Jueves', 'Viernes', 'S&aacute;bado'],
    dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
    weekHeader: 'Sm',
    dateFormat: 'dd/mm/yy',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''
};
$.datepicker.setDefaults($.datepicker.regional['es']);
//$.ajaxSetup({cache: false});

$(document).ready(function () {


    $("#cod_restaurante").val('0');
    IdrestauranteSession=$("#IdrestauranteSession").val();
    IduserSession=$("#IduserSession").val();


    //html en pagina index, dialog cargando...
    $dialogContainer = $('#dialogPrincipalCargando');
    $dialogContainer.dialog({
        width: 425,
        autoOpen: false,
        draggable: false,
        resizable: false
    });
    $progressbarPrincipalCargando = $("#progressbarPrincipalCargando").progressbar({
        value: false
    });

    //fn_menu('respuestaArticulosMenu','', 'ArticulosMenu');
    fn_Restaurante_respuestaTablas();
});

function fn_Restaurante_respuestaTablas(){
    $.get("respuestaArticulos.php", function (dataHtml) {
        //html
        $("#respuestaPantallaBody").html(dataHtml);
        $("#MsgAlerta").html('');

        $('#cod_bodega').val('0');
        $('#Cod_Cadena').val('0');
        $('#cod_restaurante').val('0');
        $('#sitio').val('0');
        $('#DescripcionBodega').val('0');
        $('#Nombre_restaurante').val('0');
        $('#TipoTomaFisica').val('Diario');

        $('#tbArticuloRest_FiltroGrupoArticulo').val('0');
        $('#tbTomaFisicaRest_FiltroGrupoArticulo').val('0');

        $("#tbTomaFisicaRest_FiltrarPorArtSelected").val('NO');
        $("#tbArticuloRest_FiltrarPorArtSelected").val('NO');

        // instanciar
        $dialogAgregarArticulo = $('#dialogAgregarArticulo');
        $dialogAgregarArticulo.dialog({
            autoOpen: false,
            modal: true,
            draggable: false,
            resizable: false,
            width: 600
        });

        fn_tbRestaurante_ini();
        //Todos los Articulos del Restaurante segun la toma
        fn_tbTomaFisicaRest_ini();
        //Toma Fisica del Restaurante

        $("#respuestaPantallaBody").on('click' , '#tbRestaurante_IconBuscar', function(){
            fn_iniciar();
            $('#cod_bodega').val('0');
            $('#Cod_Cadena').val('0');
            $('#cod_restaurante').val('0');
            $('#sitio').val('0');
            $('#DescripcionBodega').val('0');
            $('#Nombre_restaurante').val('0');
            $('#TipoTomaFisica').val('Diario');

            $('#tbArticuloRest_FiltroGrupoArticulo').val('0');
            $('#tbTomaFisicaRest_FiltroGrupoArticulo').val('0');

            $("#tbTomaFisicaRest_FiltrarPorArtSelected").val('NO');
            $("#tbArticuloRest_FiltrarPorArtSelected").val('NO');

            tbArticuloRest_ArrayArticulosSelected = [];
            tbArticuloRest_ArrayGruposSelected = [];
            fn_llenardinamicTomaFisica();

            tbRestaurante.search($('#tbRestaurante_Inputbuscar').val()).draw();
        });       // respuestaPantallaBody es un contenedor dibujado en index
        $("#respuestaPantallaBody").on('keyup' , '#tbRestaurante_Inputbuscar', function(event){

            // ´presiona enter
            if (event.keyCode == 13) {
                fn_iniciar();
                $('#cod_bodega').val('0');
                $('#Cod_Cadena').val('0');
                $('#cod_restaurante').val('0');
                $('#sitio').val('0');
                $('#DescripcionBodega').val('0');
                $('#Nombre_restaurante').val('0');
                $('#TipoTomaFisica').val('Diario');

                $('#tbArticuloRest_FiltroGrupoArticulo').val('0');
                $('#tbTomaFisicaRest_FiltroGrupoArticulo').val('0');

                $("#tbTomaFisicaRest_FiltrarPorArtSelected").val('NO');
                $("#tbArticuloRest_FiltrarPorArtSelected").val('NO');

                tbArticuloRest_ArrayArticulosSelected = [];
                tbArticuloRest_ArrayGruposSelected = [];
                fn_llenardinamicTomaFisica();
                tbRestaurante.search(this.value).draw();
            }else if(this.value =="") {
                fn_iniciar();
                $('#cod_bodega').val('0');
                $('#Cod_Cadena').val('0');
                $('#cod_restaurante').val('0');
                $('#sitio').val('0');
                $('#DescripcionBodega').val('0');
                $('#Nombre_restaurante').val('0');
                $('#TipoTomaFisica').val('Diario');

                $('#tbArticuloRest_FiltroGrupoArticulo').val('0');
                $('#tbTomaFisicaRest_FiltroGrupoArticulo').val('0');

                $("#tbTomaFisicaRest_FiltrarPorArtSelected").val('NO');
                $("#tbArticuloRest_FiltrarPorArtSelected").val('NO');

                tbArticuloRest_ArrayArticulosSelected = [];
                tbArticuloRest_ArrayGruposSelected = [];
                fn_llenardinamicTomaFisica();
                tbRestaurante.search(this.value).draw();
            }
        });       // respuestaPantallaBody es un contenedor dibujado en index


        $("#dinamicTomaFisica").on('click' , '#tbTipoTomaFisica_IconBuscar', function(){
            $("#tbTomaFisicaRest_selectGrupoArti").val($("#tbTomaFisicaRest_selectGrupoArti option:first").val());
            $("#tbTomaFisicaRest_FiltroGrupoArticulo").val('0');
            $("#tbTomaFisicaRest_FiltrarPorArtSelected").val('NO');
            $('#tbTomaFisicaRest_checkBoxFiltrarArtSelected').prop('checked', false);
            tbTipoTomaFisica.search($('#tbTomaFisicaRest_FiltroGrupoArticulo').val()).draw();
        });       // respuestaPantallaBody es un contenedor dibujado en index
        $("#dinamicTomaFisica").on('keyup' , '#tbTomaFisicaRest_Inputbuscar', function(event){

            // ´presiona enter
            if (event.keyCode == 13) {
                $("#tbTomaFisicaRest_selectGrupoArti").val($("#tbTomaFisicaRest_selectGrupoArti option:first").val());
                $("#tbTomaFisicaRest_FiltroGrupoArticulo").val('0');
                $("#tbTomaFisicaRest_FiltrarPorArtSelected").val('NO');
                $('#tbTomaFisicaRest_checkBoxFiltrarArtSelected').prop('checked', false);
                tbTipoTomaFisica.search(this.value).draw();
            }else if(this.value =="") {
                $("#tbTomaFisicaRest_selectGrupoArti").val($("#tbTomaFisicaRest_selectGrupoArti option:first").val());
                $("#tbTomaFisicaRest_FiltroGrupoArticulo").val('0');
                $("#tbTomaFisicaRest_FiltrarPorArtSelected").val('NO');
                $('#tbTomaFisicaRest_checkBoxFiltrarArtSelected').prop('checked', false);
                tbTipoTomaFisica.search(this.value).draw();
            }
        });       // respuestaPantallaBody es un contenedor dibujado en index
        $("#dinamicTomaFisica").on('change', '#tbTomaFisicaRest_selectGrupoArti', function(){
            $("#tbTomaFisicaRest_FiltroGrupoArticulo").val(this.value);
            $("#tbTomaFisicaRest_FiltrarPorArtSelected").val('NO');
            $('#tbTomaFisicaRest_checkBoxFiltrarArtSelected').prop('checked', false);
            $("#tbTomaFisicaRest_Inputbuscar").val('');
            tbTipoTomaFisica.search('').draw();
        });       // respuestaPantallaBody es un contenedor dibujado en index
        $("#dinamicTomaFisica").on('change', '#tbTomaFisicaRest_checkBoxFiltrarArtSelected', function(){
            if(tbRestaurante.rows('.selected').data().length ==0){
                ErrorMsg('Seleccione un restaurante.');
            }else{

                $("#tbTomaFisicaRest_FiltroGrupoArticulo").val('0');  // filtra los articulos por cod_grupo de articulo en la tabla tbTomaFisicaRest 0 defecto
                if ($(this).is(':checked')) {
                    $("#tbTomaFisicaRest_FiltrarPorArtSelected").val('SI');
                    $("#tbTomaFisicaRest_selectGrupoArti").val($("#tbTomaFisicaRest_selectGrupoArti option:first").val());
                    $("#tbTomaFisicaRest_FiltroGrupoArticulo").val('0');
                }else{
                    $("#tbTomaFisicaRest_FiltrarPorArtSelected").val('NO');
                    $("#tbTomaFisicaRest_selectGrupoArti").val($("#tbTomaFisicaRest_selectGrupoArti option:first").val());
                    $("#tbTomaFisicaRest_FiltroGrupoArticulo").val('0');
                }

                tbTipoTomaFisica.search('').draw();
            }



        });       // respuestaPantallaBody es un contenedor dibujado en index


        $("#dinamicTomaFisica").on('click' , '#IconExcel', function(){
            if(tbRestaurante.rows('.selected').data().length ==0){
                ErrorMsg('Seleccione un restaurante.');
            }else{
                newwindow = window.open("reporteExcelRestauranteTomaFisica.php?cod_restaurante=" + $('#cod_restaurante').val()+ "&Nombre_restaurante=" + $('#Nombre_restaurante').val() + "&cod_bodega=" +   $("#cod_bodega").val() + "&Cod_Cadena=" +$("#Cod_Cadena").val() + "&sitio=" +  $("#sitio").val()+"&DescripcionBodega=" +  $("#DescripcionBodega").val()+"&TipoTomaFisica=" +$("#TipoTomaFisica").val(), "Toma_Fisica", "scrollbars=1,left=100,resizable=1");

            }

         });       // respuestaPantallaBody es un contenedor dibujado en index
        $("#dinamicTomaFisica").on('click' , '#IconImpirmir', function(){
            if(tbRestaurante.rows('.selected').data().length ==0){
                ErrorMsg('Seleccione un restaurante.');
            }else{
                newwindow = window.open("reportePdfRestauranteTomaFisica.php?cod_restaurante=" + $('#cod_restaurante').val()+ "&Nombre_restaurante=" + $('#Nombre_restaurante').val() + "&cod_bodega=" +   $("#cod_bodega").val() + "&Cod_Cadena=" +$("#Cod_Cadena").val() + "&sitio=" +  $("#sitio").val()+"&DescripcionBodega=" +  $("#DescripcionBodega").val()+"&TipoTomaFisica=" +$("#TipoTomaFisica").val(), "Toma_Fisica", "scrollbars=1,left=100,resizable=1");
            }
        });


        $("#dialogAgregarArticulo").on('click' , '#tbArticuloRest_IconBuscar', function(){
            $("#tbArticuloRest_selectGrupoArti").val($("#tbArticuloRest_selectGrupoArti option:first").val());
            $("#tbArticuloRest_FiltroGrupoArticulo").val('0');
            $("#tbArticuloRest_FiltrarPorArtSelected").val('NO');
            $('#tbArticuloRest_checkBoxFiltrarArtSelected').prop('checked', false);
            tbArticuloRest.search($('#tbTomaFisicaRest_FiltroGrupoArticulo').val()).draw();
        });       // respuestaPantallaBody es un contenedor dibujado en index
        $("#dialogAgregarArticulo").on('keyup' , '#tbArticuloRest_Inputbuscar', function(event){

            // ´presiona enter
            if (event.keyCode == 13) {
                $("#tbArticuloRest_selectGrupoArti").val($("#tbArticuloRest_selectGrupoArti option:first").val());
                $("#tbArticuloRest_FiltroGrupoArticulo").val('0');// filtrar  articulos por cod_grupo de articulo
                $("#tbArticuloRest_FiltrarPorArtSelected").val('NO');//Deseas filtrar los articulos que fueron seleccionados ? SI - NO
                $('#tbArticuloRest_checkBoxFiltrarArtSelected').prop('checked', false); // sin check
                tbArticuloRest.search(this.value).draw();
            }else if(this.value =="") {
                $("#tbArticuloRest_selectGrupoArti").val($("#tbArticuloRest_selectGrupoArti option:first").val());
                $("#tbArticuloRest_FiltroGrupoArticulo").val('0');
                $("#tbArticuloRest_FiltrarPorArtSelected").val('NO');
                $('#tbArticuloRest_checkBoxFiltrarArtSelected').prop('checked', false);
                tbArticuloRest.search(this.value).draw();
            }
        });       // respuestaPantallaBody es un contenedor dibujado en index
        $("#dialogAgregarArticulo").on('change', '#tbArticuloRest_selectGrupoArti', function(){
            $("#tbArticuloRest_FiltroGrupoArticulo").val(this.value);
            $("#tbArticuloRest_FiltrarPorArtSelected").val('NO');
            $('#tbArticuloRest_checkBoxFiltrarArtSelected').prop('checked', false);
            $("#tbArticuloRest_Inputbuscar").val('');
            tbArticuloRest.search('').draw();
        });       // respuestaPantallaBody es un contenedor dibujado en index
        $("#dialogAgregarArticulo").on('change', '#tbArticuloRest_checkBoxFiltrarArtSelected', function(){

            if(tbRestaurante.rows('.selected').data().length ==0){
                ErrorMsg('Seleccione un restaurante.');
            }else{
                $("#tbTomaFisicaRest_FiltroGrupoArticulo").val('0');  // filtra los articulos por cod_grupo de articulo en la tabla tbTomaFisicaRest 0 defecto
                if ($(this).is(':checked')) {
                    $("#tbArticuloRest_FiltrarPorArtSelected").val('SI');
                    $("#tbArticuloRest_selectGrupoArti").val($("#tbArticuloRest_selectGrupoArti option:first").val());
                    $("#tbArticuloRest_FiltroGrupoArticulo").val('0');
                }else{
                    $("#tbArticuloRest_FiltrarPorArtSelected").val('NO');
                    $("#tbArticuloRest_selectGrupoArti").val($("#tbArticuloRest_selectGrupoArti option:first").val());
                    $("#tbArticuloRest_FiltroGrupoArticulo").val('0');
                }

                tbArticuloRest.search('').draw();
            }


        });       // respuestaPantallaBody es un contenedor dibujado en index


        $("#respuestaPantallaBody").on('change', 'input[name=checkboxTipoInventario]', function(){
            fn_iniciar();
            $("#TipoTomaFisica").val(this.value);
            $("#tbTomaFisicaRest_FiltroGrupoArticulo").val('0');// filtra los articulos por cod_grupo de articulo en la tabla tbTomaFisicaRest
            $("#tbArticuloRest_FiltroGrupoArticulo").val('0');// filtra los articulos por cod_grupo de articulo en la tabla tbArticuloRest
            tbArticuloRest_ArrayArticulosSelected = [];
            tbArticuloRest_ArrayGruposSelected  = [];
            fn_llenardinamicTomaFisica();
        });       // respuestaPantallaBody es un contenedor dibujado en index
        $("#respuestaPantallaBody").on('click' , '#IconAgregarArticulos', function(){

            if(tbRestaurante.rows('.selected').data().length ==0){
                ErrorMsg('Seleccione un restaurante.');
            }else{
                $dialogAgregarArticulo.dialog('destroy');
                $dialogAgregarArticulo.dialog({
                    autoOpen: false,
                    modal: true,
                    draggable: false,
                    resizable: false,
                    width: 575
                });
                //$("#dinamic").html('');
                var lc_par = new Object;
                lc_par['cod_restaurante'] =  $('#cod_restaurante').val();
                lc_par['Nombre_restaurante'] =    $('#Nombre_restaurante').val();
                lc_par['cod_bodega'] = $("#cod_bodega").val();
                lc_par['Cod_Cadena'] = $("#Cod_Cadena").val();
                lc_par['sitio'] =     $("#sitio").val();
                lc_par['DescripcionBodega'] =    $("#DescripcionBodega").val();
                lc_par['TipoTomaFisica'] = $("#TipoTomaFisica").val();
                $.ajax({
                    async: true,
                    type: "POST",
                    dataType: "html",
                    contentType: "application/x-www-form-urlencoded",
                    url: "respuestaDialogaAgregarArticulo.php",
                    data: lc_par,
                    success: function (data) {
                        $("#MsgAlerta").html('');
                        $("#DinamicArticuloContenido").html(data);
                        fn_tbArticuloRest_ini();
                    }, error: function (xhr, ajaxOptions, thrownError) {
                        alert(thrownError + " " + ajaxOptions + " " + thrownError);
                    }
                });



                $dialogAgregarArticulo.dialog( "open" );
            }


        });       // respuestaPantallaBody es un contenedor dibujado en index

        $("#dialogAgregarArticulo").on('click' , '#Bt_DialogAgragarArt', function(){
            fn_iniciar();
            var lc_par = new Object;
            lc_par['metodo'] = 'fn_ArtiPorRestaurante_AgregarArtTomaFisca';
            lc_par['cod_bodega'] = $("#cod_bodega").val();
            lc_par['Cod_Cadena'] = $("#Cod_Cadena").val();
            lc_par['cod_restaurante'] = $("#cod_restaurante").val();
            lc_par['sitio'] = $("#sitio").val();
            lc_par['TipoTomaFisica'] = $("#TipoTomaFisica").val();
            lc_par['tbArticuloRest_ArrayArticulosSelected'] =  tbArticuloRest_ArrayArticulosSelected; // Array de Articulos selecionados

            lc_par['Nombre_restaurante']  = $("#Nombre_restaurante").val();
            lc_par['IduserSession']  = $("#IduserSession").val();
            lc_par['IdrestauranteSession']= $("#IdrestauranteSession").val();

            $.ajax({
                async: false,
                type: "POST",
                dataType: "text",
                contentType: "application/x-www-form-urlencoded",
                url: "ControladorArticulos.php",
                data: lc_par,
                success: function (result) {
                    $("#tbTomaFisicaRest_FiltroGrupoArticulo").val('0');
                    fn_llenardinamicTomaFisica();
                    $dialogAgregarArticulo.dialog('close');
                    ExitoMsg('Se han agregado los articulos a toma fisica '+$("#TipoTomaFisica").val()+ ' con exito.');
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(thrownError + " " + ajaxOptions + " " + thrownError);
                }
            });
        });
        $("#dialogAgregarArticulo").on('click' , '#Bt_DialogCancelarArt', function(){
            $dialogAgregarArticulo.dialog( "close" );
        });





});
}
function fn_tbRestaurante_ini(){
    tbRestaurante = $('#tablaRestaurante').DataTable( {
        destroy: true,
        processing: true,
        paging: true,
        select : true,
        scrollY: "415",
        pageLength: 100,
        serverSide: true,
        order: [[1, "asc" ]],
        columns: [
            { "title":"Cod_Art_Bodega"      , "class": "dt-body-left "},
            // { "title":'<a  title="Seleccionar Todos"></a> <input type="checkbox" id ="tbAgregarArti_SelectTodosGrupos" name="tbAgregarArti_SelectTodosGrupos" class = "tbAgregarArti_SelectTodosGrupos" value="0">' , "class": "dt-body-center "},
            { "title":'Restaurante'         , "class": "ColumnaCheck dt-body-left "},
            { "title":"Cod_Bodega"          , "class": "ColumnaCod_Articulo dt-body-center" },
            { "title":"cod_cadena"          , "class": "ColumnaNombreArt dt-body-left" },
            { "title":"sitio"               , "class": "ColumnaNombreArt dt-body-left" },
            { "title":"Descripcion"         , "class": "ColumnaNombreArt dt-body-left" }
        ],
        dom: '<"top">rt<"bottom"><"clear">',
        bDeferRender: true,
        columnDefs: [
            {
                targets: [ 0 ], // columna (ojo comienza con 0,1,2)
                //className: "dt-body-center" ,// centrar info
                "visible": false,
                searchable: false
            },{
                targets: [ 2 ], // columna (ojo comienza con 0,1,2)
                orderable: false,
                searchable: false,
                "visible": false
            },{
                targets: [3],
                orderable: false,
                "visible": false
            },{
                targets: [4],
                "visible": false
            },{
                targets: [5],
                "visible": false
            }
        ],
        drawCallback: function( settings ) {
            var info = tbRestaurante.page.info();
            var i;
            $("#cb_paginasRestaurante option").remove();
            var $thisPaginasTipoProveedor = $('#cb_paginasRestaurante');
            var  paginasInventario = '';
            for (i = 0; i <= info.pages-1; i++) {
                if(parseInt(info.page) ==i){
                    paginasInventario+='<option  value="' + i + '" selected>' + (i+1) + '</option>'
                }else{
                    paginasInventario+='<option value="' + i + '">' + (i+1) + '</option>'
                }
            }
            $thisPaginasTipoProveedor.append(paginasInventario);
            $thisPaginasTipoProveedor.unbind();
            $thisPaginasTipoProveedor.change(function() {
                var page=parseInt($(this).val());
                tbRestaurante.page(page).draw( 'page' );
            });
            // cada vez que se invoca  o se dibuja la tabla , se ejecuta el siguinte processos
        },
        language: {url: '../../js/DatatablesSpanish.json'},
        ajax: {
            type: "POST",
            contentType: "application/x-www-form-urlencoded",
            dataType: "json",
            url: "ControladorArticulos.php",
            data:function ( d ) {
                d.metodo = "fn_Articulos_Restaurantes_Ini";
                d.IduserSession = $("#IduserSession").val();
                d.Cod_Cadena = $("#Cod_Cadena").val();}
        }
    });
    tbRestaurante.on( 'select', function ( e, dt, type, indexes ) {
        fn_iniciar();
        //Borar Datos de las siguientes variables
        $("#tbTomaFisicaRest_FiltroGrupoArticulo").val('0');// filtra los articulos por cod_grupo de articulo en la tabla tbTomaFisicaRest 0 defecto
        $("#tbArticuloRest_FiltroGrupoArticulo").val('0');// filtra los articulos por cod_grupo de articulo en la tabla tbArticuloRest 0 defecto
        tbArticuloRest_ArrayArticulosSelected = [];
        tbArticuloRest_ArrayGruposSelected  = [];

        var rowData = tbRestaurante.rows( indexes ).data().toArray();

        var cod_restaurante=rowData[0][0];
        var NombreRestaurante=rowData[0][1];
        var cod_bodega=rowData[0][2];
        var cod_cadena=rowData[0][3];
        var sitio=rowData[0][4];
        var nombreBodega=rowData[0][5];

        // IMPORTANTE ---- Indicadores
        $('#cod_restaurante').val(cod_restaurante);
        $('#Nombre_restaurante').val(NombreRestaurante);
        $("#cod_bodega").val(cod_bodega);
        $("#Cod_Cadena").val(cod_cadena);
        $("#sitio").val(sitio);
        $("#DescripcionBodega").val(nombreBodega);

        fn_llenardinamicTomaFisica();

    });
    tbRestaurante.on( 'user-select', function ( e, dt, type, cell, originalEvent ) {
        fn_iniciar();
        var row = dt.row( cell.index().row ).node();
        if ( $(row).hasClass('selected') ) {

            $('#cod_bodega').val('0');
            $('#Cod_Cadena').val('0');
            $('#cod_restaurante').val('0');
            $('#sitio').val('0');
            $('#DescripcionBodega').val('0');
            $('#Nombre_restaurante').val('0');
            $('#TipoTomaFisica').val('Diario');

            $('#tbArticuloRest_FiltroGrupoArticulo').val('0');
            $('#tbTomaFisicaRest_FiltroGrupoArticulo').val('0');

            $("#tbTomaFisicaRest_FiltrarPorArtSelected").val('NO');
            $("#tbArticuloRest_FiltrarPorArtSelected").val('NO');

            tbArticuloRest_ArrayArticulosSelected = [];
            tbArticuloRest_ArrayGruposSelected = [];

            fn_llenardinamicTomaFisica();

        }
    } );
}
function fn_tbTomaFisicaRest_ini(){
    tbTipoTomaFisica = $('#tablaTipoTomaFisica ').DataTable( {
        processing: true,
        paging: true,
        scrollY: "255",
        pageLength: 100,
        serverSide: true,
        rowGroup: {
            dataSrc: 4
        },
        order: [[4, "asc" ],[2, "asc" ]],
        columns: [
            { "title":"Cod_Art_Bodega" , "class": "ColumnaCheck dt-body-left "},
            { "title":"Codigo", "class": "ColumnaCod_Articulo dt-body-center" },
            { "title":"Nombre", "class": "ColumnaNombreArt dt-body-left" },
            { "title":"Toma", "class": "ColumnaTipo dt-body-center" },
            { "title":"grupo" , "class": "dt-body-center "},
            { "title":"Editar" , "class": "ColumnaEditar dt-body-center "},
            { "title":"Guardar" , "class": "ColumnaGaurdar dt-body-center "}
        ],
        dom: '<"top">rt<"bottom"><"clear">',
        bDeferRender: true,
        columnDefs: [
            {
                targets: [ 0 ], // columna (ojo comienza con 0,1,2)
                //className: "dt-body-center" ,// centrar info
                "visible": false,
                searchable: false
            },
            {
                targets: [ 4 ], // columna (ojo comienza con 0,1,2)
                //className: "dt-body-center" ,// centrar info
                "visible": false,
                searchable: false
            }],
        language: {url: '../../js/DatatablesSpanish.json'},
        rowCallback: function( row, data ) {

            if ( $.inArray(data[0], tbArticuloRest_ArrayArticulosSelected) !== -1 ) {
                $(row).addClass('greenadd');
            }
        },
        drawCallback: function( settings ) {
            var info = tbTipoTomaFisica.page.info();
            var i;
            $("#labelTotalArticulos").html('<font color="blue" >Total : ' +info.recordsDisplay+ ' Articulos</font>');
            $("#cb_paginasTipoTomaFisica option").remove();
            var $thisPaginasTipoProveedor = $('#cb_paginasTipoTomaFisica');
            var  paginasInventario = '';
            for (i = 0; i <= info.pages-1; i++) {
                if(parseInt(info.page) ==i){
                    paginasInventario+='<option  value="' + i + '" selected>' + (i+1) + '</option>'
                }else{
                    paginasInventario+='<option value="' + i + '">' + (i+1) + '</option>'
                }
            }
            $thisPaginasTipoProveedor.append(paginasInventario);
            $thisPaginasTipoProveedor.unbind();
            $thisPaginasTipoProveedor.change(function() {
                var page=parseInt($(this).val());
                tbTipoTomaFisica.page(page).draw( 'page' );
            });
            // cada vez que se invoca  o se dibuja la tabla , se ejecuta el siguinte processos
        },
        ajax: {
            type: "POST",
            contentType: "application/x-www-form-urlencoded",
            dataType: "json",
            url: "ControladorArticulos.php",
            data:function ( d ) {
                d.metodo = "fn_tbTipoTomaFisica_ini";
                d.IduserSession = $("#IduserSession").val();
                d.cod_bodega = $("#cod_bodega").val();
                d.Cod_Cadena = $("#Cod_Cadena").val();
                d.cod_restaurante = $("#cod_restaurante").val();
                d.sitio = $("#sitio").val();
                d.DescripcionBodega = $("#DescripcionBodega").val();
                d.Nombre_restaurante = $("#Nombre_restaurante").val();
                d.TipoTomaFisica = $("#TipoTomaFisica").val();

                d.tbTomaFisicaRest_Cod_Grupo_art = $("#tbTomaFisicaRest_FiltroGrupoArticulo").val();// filtrar  articulos por cod_grupo de articulo
                d.tbTomaFisicaRest_FiltrarPorArtSelected =$("#tbTomaFisicaRest_FiltrarPorArtSelected").val(); //Deseas filtrar los articulos que fueron seleccionados ? SI - NO
                d.tbArticuloRest_ArrayArticulosSelected =  tbArticuloRest_ArrayArticulosSelected; // Array de Articulos selecionados

            }
        }
    });
    tbTipoTomaFisica.on( 'click', '.tbTipoTomaFisica_IconEditar', function () {
        var $this = $(this);
        var $fila = $this.closest("tr");
        $fila.find("select.selectTipoInv").removeAttr('disabled');
        $fila.addClass('selected');
    });
    tbTipoTomaFisica.on( 'click', '.tbTipoTomaFisica_IconGuardar', function () {
        fn_iniciar();
        var $this = $(this);
        var row = $this.closest('tr');
        var $thisRow = $(row);

        var rowDataArray = tbTipoTomaFisica.rows( $thisRow ).data().toArray();
        var cod_Art_bodega = rowDataArray[0][0];
        var cod_Articulo = rowDataArray[0][1];
        var NombreArt = rowDataArray[0][2];
        var selectTomaFisica = rowDataArray[0][3];
        // obtenemos el id del elemento
        var id = $(selectTomaFisica).prop('id');

        var lc_par = new Object;
        lc_par['metodo'] =  'fn_Gaurdar';
        lc_par['cod_bodega'] =$("#cod_bodega").val();
        lc_par['cod_restaurante'] =$("#cod_restaurante").val();
        lc_par['Cod_Cadena'] =$("#Cod_Cadena").val();
        lc_par['sitio'] =$("#sitio").val();
        lc_par['TipoTomaFisica'] =$('#'+id).val();// obtemos el valo seleccionado por el usuario
        lc_par['TipoTomaFisicaOriginal'] =$("#TipoTomaFisica").val();// obtemos el valo seleccionado por el usuario

        lc_par['cod_Art_bodega'] =cod_Art_bodega;
        lc_par['cod_Articulo'] =cod_Articulo;
        lc_par['Nombre_restaurante']  = $("#Nombre_restaurante").val();

        lc_par['IduserSession']  = $("#IduserSession").val();
        lc_par['IdrestauranteSession']= $("#IdrestauranteSession").val();

        $.ajax({
            async: true,
            type: "POST",
            dataType: "html",
            contentType: "application/x-www-form-urlencoded",
            url: "ControladorArticulos.php",
            data: lc_par,
            success: function (data) {
                ExitoMsg('Se ha agregado el articulo '+NombreArt+' a toma fisica '+lc_par['TipoTomaFisica']+' con exito.');
                tbTipoTomaFisica.draw();
                fn_terminar();
            }, error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + " " + ajaxOptions + " " + thrownError);
            }
        });

    });
    fn_terminar();
}
function fn_llenardinamicTomaFisica(){
    var lc_par = new Object;
    lc_par['cod_restaurante'] =  $('#cod_restaurante').val();
    lc_par['Nombre_restaurante'] =    $('#Nombre_restaurante').val();
    lc_par['cod_bodega'] = $("#cod_bodega").val();
    lc_par['Cod_Cadena'] = $("#Cod_Cadena").val();
    lc_par['sitio'] =     $("#sitio").val();
    lc_par['DescripcionBodega'] =    $("#DescripcionBodega").val();
    lc_par['TipoTomaFisica'] = $("#TipoTomaFisica").val();
    $.ajax({
        async: true,
        type: "POST",
        dataType: "html",
        contentType: "application/x-www-form-urlencoded",
        url: "respuestaArtculosTomaFisica.php",
        data: lc_par,
        success: function (data) {
            $("#dinamicTomaFisica").html(data);

            $('#tbArticuloRest_FiltroGrupoArticulo').val('0');
            $('#tbTomaFisicaRest_FiltroGrupoArticulo').val('0');

            $("#tbTomaFisicaRest_FiltrarPorArtSelected").val('NO');
            $("#tbArticuloRest_FiltrarPorArtSelected").val('NO');

            fn_tbTomaFisicaRest_ini();
        }, error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError + " " + ajaxOptions + " " + thrownError);
        }
    });
}
function fn_tbArticuloRest_ini(){
    tbArticuloRest = $('#tbArticuloRest').DataTable( {
        destroy: true,
        processing: true,
        paging: true,
        scrollY: "220",
        pageLength: 100,
        rowGroup: {
            dataSrc : 6,
            startRender: function ( rows, group ) {
                var cod_grupo =rows.data().pluck(7).unique();
                var index = $.inArray(cod_grupo[0], tbArticuloRest_ArrayGruposSelected);
                if ( index === -1 ) {
                    return $('<tr/>')
                    //. append( '<td  align="right"><font size="1" color="white">Grupo</font></td>' )
                        . append( '<td colspan="6" align="right"><input type="checkbox" id ="checkboxGrupoSelected" name="'+group+'" class = "tbArticuloRest_checkboxGrupoSelected" value="'+cod_grupo[0]+'">'+group+'</td>' )
                } else {
                    return $('<tr/>')
                    //. append( '<td  align="right"><font size="1" color="white">Grupo</font></td>' )
                        . append( '<td colspan="6" align="right"><input type="checkbox" id ="checkboxGrupoSelected" name="'+group+'" class = "tbArticuloRest_checkboxGrupoSelected" checked value="'+cod_grupo[0]+'">'+group+'</td>' )
                }
            }

        },//api buscar internet como usar
        serverSide: true,
        order: [[6, "asc" ],[3, "asc" ]],
        columns: [
            { "title":"Cod_Art_Bodega" , "class": "dt-body-left "},
            // { "title":'<a  title="Seleccionar Todos"></a> <input type="checkbox" id ="tbAgregarArti_SelectTodosGrupos" name="tbAgregarArti_SelectTodosGrupos" class = "tbAgregarArti_SelectTodosGrupos" value="0">' , "class": "dt-body-center "},
            { "title":'*'              , "class": "ColumnaCheck dt-body-center "},
            { "title":"Codigo"         , "class": "ColumnaCod_Articulo dt-body-center" },
            { "title":"Nombre"         , "class": "ColumnaNombreArt dt-body-left" },
            { "title":" + "            , "class": "ColumnaEliminar dt-body-center" },
            { "title":"Si - NO "       , "class": "ColumnaGrupo dt-body-center" },
            { "title":"Grupo"          , "class": "ColumnaGrupo dt-body-center" },
            { "title":"cod_Grupo"      , "class": "ColumnaCod_Grupo dt-body-center" }
        ],
        dom: '<"top">rt<"bottom"><"clear">',
        bDeferRender: true,
        rowCallback: function( row, data ) {

            if ( $.inArray(data[0], tbArticuloRest_ArrayArticulosSelected) !== -1 ) {
                $(row).addClass('greenadd');
            }
        },
        drawCallback: function( settings ) {
            var info = tbArticuloRest.page.info();
            var i;
            $("#tbAgregarArti_tbpaginas option").remove();
            var $thisPaginasTipoProveedor = $('#tbAgregarArti_tbpaginas');
            var  paginasInventario = '';
            for (i = 0; i <= info.pages-1; i++) {
                if(parseInt(info.page) ==i){
                    paginasInventario+='<option  value="' + i + '" selected>' + (i+1) + '</option>'
                }else{
                    paginasInventario+='<option value="' + i + '">' + (i+1) + '</option>'
                }
            }
            $thisPaginasTipoProveedor.append(paginasInventario);
            $thisPaginasTipoProveedor.unbind();
            $thisPaginasTipoProveedor.change(function() {
                var page=parseInt($(this).val());
                tbArticuloRest.page(page).draw( 'page' );
            });
            // cada vez que se invoca  o se dibuja la tabla , se ejecuta el siguinte processos
        },
        columnDefs: [
            {
                targets: [ 0 ], // columna (ojo comienza con 0,1,2)
                //className: "dt-body-center" ,// centrar info
                "visible": false,
                searchable: false
            },{
                targets: [ 1 ], // columna (ojo comienza con 0,1,2)
                orderable: false,
                searchable: false
            },{
                targets: [4],
                orderable: false,
                "visible": false
            },{
                targets: [5],
                "visible": false
            },{
                targets: [6],
                "visible": false
            },{
                targets: [7],
                "visible": false
            }
        ],
        language: {url: '../../js/DatatablesSpanish.json'},
        ajax: {
            type: "POST",
            contentType: "application/x-www-form-urlencoded",
            dataType: "json",
            url: "ControladorArticulos.php",
            data:function ( d ) {
                d.metodo = "fn_tbArticuloRest_ini";
                d.IduserSession = $("#IduserSession").val();

                d.cod_bodega = $("#cod_bodega").val();
                d.Cod_Cadena = $("#Cod_Cadena").val();
                d.cod_restaurante = $("#cod_restaurante").val();
                d.sitio = $("#sitio").val();
                d.DescripcionBodega = $("#DescripcionBodega").val();
                d.Nombre_restaurante = $("#Nombre_restaurante").val();
                d.TipoTomaFisica = $("#TipoTomaFisica").val();

                d.tbArticuloRest_Cod_Grupo_art = $("#tbArticuloRest_FiltroGrupoArticulo").val();// filtrar  articulos por cod_grupo de articulo
                d.tbArticuloRest_FiltrarPorArtSelected =$("#tbArticuloRest_FiltrarPorArtSelected").val(); //Deseas filtrar los articulos que fueron seleccionados ? SI - NO
                d.tbArticuloRest_ArrayArticulosSelected =  tbArticuloRest_ArrayArticulosSelected; // Array de Articulos selecionados
            }
        }
    });
    tbArticuloRest.on( 'change', 'input.tbArticuloRest_checkboxArtSelected', function () {
        var $this = $(this);
        var row = $this.closest('tr');
        var $thisRow = $(row);

        var rowDataArray = tbArticuloRest.rows( $thisRow ).data().toArray();
        var Cod_Art_Bodega=rowDataArray[0][0];

        if ($this.is(':checked')){
            $thisRow.addClass("greenadd");
        }else{
            $thisRow.removeClass('greenadd');
            var cod_Grupo =rowDataArray[0][7];

            var index = $.inArray(cod_Grupo, tbArticuloRest_ArrayGruposSelected);
            tbArticuloRest_ArrayGruposSelected.splice( index, 1 );

        }

        var index = $.inArray(Cod_Art_Bodega, tbArticuloRest_ArrayArticulosSelected);
        if ( index === -1 ) {
            tbArticuloRest_ArrayArticulosSelected.push(Cod_Art_Bodega);
        } else {
            tbArticuloRest_ArrayArticulosSelected.splice( index, 1 );
        }
    });
    //permite selecionar todos los articulos segun el grupo seleccionado Declarado en la parte rowGroup al instanciar la tabla
    tbArticuloRest.on( 'change', 'input.tbArticuloRest_checkboxGrupoSelected', function () {
        var $this = $(this);
        var row = $this.closest('tr');
        var $thisRow = $(row);
        var index = $.inArray($this.val(), tbArticuloRest_ArrayGruposSelected);
        if ( index === -1 ) {
            tbArticuloRest_ArrayGruposSelected.push($this.val());
        } else {
            tbArticuloRest_ArrayGruposSelected.splice( index, 1 );
        }
        var lc_par1 = new Object;
        lc_par1['metodo'] = 'getTodosArticulosConCod_Grupo';
        lc_par1['cod_bodega'] = $("#cod_bodega").val();
        lc_par1['cod_restaurante'] = $("#cod_restaurante").val();
        lc_par1['Cod_Cadena'] = $("#Cod_Cadena").val();
        lc_par1['sitio'] = $("#sitio").val();
        lc_par1['Cod_Grupo_art'] = $this.val();
        lc_par1['TipoTomaFisica'] = $("#TipoTomaFisica").val();
        $.ajax({
            async: true,
            type: "POST",
            dataType: "json",
            contentType: "application/x-www-form-urlencoded",
            url: "ControladorArticulos.php",
            data: lc_par1,
            success: function (CodBodega) {
                if ($this.is(':checked')){
                    $.each(CodBodega, function(i, item) {
                        //busca si elemento existe en array y su posicion   | -1 no existe
                        var index = $.inArray(item.Cod_Art_Bodega, tbArticuloRest_ArrayArticulosSelected);
                        if ( index === -1 ) {
                            tbArticuloRest_ArrayArticulosSelected.push(item.Cod_Art_Bodega );
                        }
                    });
                }else{
                    $.each(CodBodega, function(i, item) {
                        //busca si elemento existe en array y su posicion
                        var index = $.inArray(item.Cod_Art_Bodega, tbArticuloRest_ArrayArticulosSelected);
                        tbArticuloRest_ArrayArticulosSelected.splice( index, 1 );
                    });
                }

                tbArticuloRest.draw();

            }, error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + " " + ajaxOptions + " " + thrownError);
            }
        });

    });
}
function boo(){
    var lc_par = new Object;
    lc_par['cod_restaurante'] =  $('#cod_restaurante').val();
    lc_par['Nombre_restaurante'] =    $('#Nombre_restaurante').val();
    lc_par['cod_bodega'] = $("#cod_bodega").val();
    lc_par['Cod_Cadena'] = $("#Cod_Cadena").val();
    lc_par['sitio'] =     $("#sitio").val();
    lc_par['DescripcionBodega'] =    $("#DescripcionBodega").val();
    lc_par['TipoTomaFisica'] = $("#TipoTomaFisica").val();
    $.ajax({
        async: true,
        type: "POST",
        dataType: "html",
        contentType: "application/x-www-form-urlencoded",
        url: "respuestaArticulos.php",
        data: lc_par,
        success: function (data) {
            $("#respuestaPantallaBody").html(data);
            $("#MsgAlerta").html('');
            $("#tbTomaFisicaRest_FiltroGrupoArticulo").val('0');// filtra los articulos por cod_grupo de articulo en la tabla tbTomaFisicaRest 0 defecto
            $("#tbArticuloRest_FiltroGrupoArticulo").val('0');// filtra los articulos por cod_grupo de articulo en la tabla tbArticuloRest 0 defecto
            fn_tbArticuloRest_ini();
            fn_tbTomaFisicaRest_ini();
        }, error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError + " " + ajaxOptions + " " + thrownError);
        }
    });
}
function fn_menu(lc_accion, lc_rest, lc_pantalla) {


    var lc_par = new Object;
    //Insertar datos al objecto para enviar por Ajax
    lc_par['Pantalla'] = lc_accion;
    lc_par['restaurante'] = lc_rest;
    lc_par['toma'] = lc_pantalla;
    $.ajax({
        async: false,
        type: "POST",
        dataType: "text",
        contentType: "application/x-www-form-urlencoded",
        url: "respuestaArticulosMenu.php",
        data: lc_par,
        success: function (datos) {
            $("#respuestaMenu").html(datos);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError + " " + ajaxOptions + " " + thrownError);
        }
    });
}
function ErrorMsg(ErrorText) {
    msgCounter++;
    var IdErrorMsg = 'IdErrorMsg'+msgCounter;
    var txt = " <center>\n" +
        "                    <div id = "+IdErrorMsg+" class='Error'    align='left'>\n" +
        "                        <span class='closebtn' onclick=\"this.parentElement.style.display='none';\">&times;</span>\n" +
        "                        <strong>Alerta!</strong>\n" + ErrorText +
        "                    </div>\n" +
        "        </center><br>";
    $('#MsgAlerta').append(txt);

    setInterval(function() {
        $('#'+IdErrorMsg).hide();
    }, 5000)

    return txt;

}
function ExitoMsg(ErrorText) {
    msgCounter++;
    var IdErrorMsg = 'ExitoMsg'+msgCounter;
    var txt = " <center>\n" +
        "                    <div id = "+IdErrorMsg+" class='Exito'    align='left'>\n" +
        "                        <span class='closebtn' onclick=\"this.parentElement.style.display='none';\">&times;</span>\n" +
        "                        <strong>Alerta!</strong>\n" + ErrorText +
        "                    </div>\n" +
        "        </center><br>";
    $('#MsgAlerta').append(txt);

    setInterval(function() {
        $('#'+IdErrorMsg).hide();
    }, 5000)

    return txt;

}
function ErrorMsgOrdenPedidos(ErrorText) {
    msgCounter++;
    var IdErrorMsg = 'IdErrorMsg'+msgCounter;
    var txt = " <center>\n" +
        "                    <div id = "+IdErrorMsg+" class='Error'    align='left'>\n" +
        "                        <span class='closebtn' onclick=\"this.parentElement.style.display='none';\">&times;</span>\n" +
        "                        <strong>Error!</strong>\n" + ErrorText +
        "                    </div>\n" +
        "        </center>";
    $('#messageAlert').append(txt);

    setInterval(function() {
        $('#'+IdErrorMsg).hide();
    }, 5000)

    return txt;

}
function ExitoMsgOrdenPedidos(ErrorText) {
    msgCounter++;
    var IdErrorMsg = 'ExitoMsg'+msgCounter;
    var txt = " <center>\n" +
        "                    <div id = "+IdErrorMsg+" class='Exito'    align='left'>\n" +
        "                        <span class='closebtn' onclick=\"this.parentElement.style.display='none';\">&times;</span>\n" +
        "                        <strong>Aviso!</strong>\n" + ErrorText +
        "                    </div>\n" +
        "        </center>";
    $('#messageAlert').append(txt);

    setInterval(function() {
        $('#'+IdErrorMsg).hide();
    }, 5000)

    return txt;

}

function fn_iniciar() {
    //mostrar el div
    var value = 4;
    var testObj = document.getElementById( "darkBack" );
    testObj.style.display = 'block';
    testObj.style.height = document.getElementsByTagName('body')[0].scrollHeight + 5;
    testObj.style.opacity = value/10;
    testObj.style.filter = 'alpha(opacity=' + value*10 + ')';

    var testObj = document.getElementById( "whiteBackWait" );
    testObj.style.display = 'block';
}
function fn_terminar()
{
    document.getElementById('darkBack').style.display='none';
    document.getElementById('whiteBackWait').style.display='none';
}




