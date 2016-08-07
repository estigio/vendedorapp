<?php
require_once 'header.php';
require_once './Php/Clases/Connection/Connection.class.php';
require_once 'Php/Clases/Tercero.php';
require_once './Php/Clases/Metas.php';

$tercero = new Tercero();
$comment = 0;
$metas = new Metas();
$fecha = date("Y-m-d"); 
$fechaFinal =  date("Y-m-d"); 
$nombre  = "";
if($_GET){
    $comment = $_GET['comment'];
    $fecha = $_GET['fecha'];
    $nombre = $_GET['nombre'];
	$fechaFinal = $_GET['fechafinal'];
}
?>

<!DOCTYPE html>
<!--
Copyright (c) 2015 Walther Smith Franco Otero.
All rights reserved.
   Walther Smith Franco Otero  | @walthersmih | http://about.me/walthermsith
-->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Interface Tomador de pedidos</title>
        <!-- Bootstrap -->
        <link href="css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <style>
            body { padding-top: 70px; }
        </style>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->      

    </head>
    <body>
        <?php
        getHeader();
        ?>

        <div class="container">
            <div class="panel panel-default">
                <div class="panel-heading text-center"><h2>Listado Metas por vendedor</h2></div>
                <div class="panel-body text-center">
                    <h2><?php echo $nombre; ?></h2>
                    <select class="form-control" id="comboVendedor"></select>
                     FECHA INICIAL: <input type="date" class="form-control" id="txtFecha">
					 FECHA FINAL: <input type="date" class="form-control" id="txtFechaFinal">
                      <button class="btn btn-info " id="btnfiltrar">Filtrar</button>
                    <hr>
                    <table id="tablaCliente" class="display" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad Meta</th>
                                <th>Cantidad Vendida</th>  
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="bodytable"></tbody>
                    </table>                 
                </div>
            </div>
        </div>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="js/jquery-2.1.0.min.js" type="text/javascript"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.dataTables.min.js" type="text/javascript"></script>

        <script>
            $(document).ready(function () {
                
                
                function getdatatable() {
                    var datos =<?php $metas->MetasList($fecha,$comment,$fechaFinal);?>;
                    var tableDatos = "";
                    $.each(datos, function (i, item) {
                       // alert(item.cantidadvendida +" | "+ item.cantidadMeta);
                        tableDatos += '<tr>' +
                                '<td>' + item.descripcion + '</td>' +
                                '<td>' + item.cantidadMeta + '</td>' +
                                '<td>' + item.cantidadvendida +'</td>';
                                if (parseFloat(item.cantidadvendida) > parseFloat(item.cantidadMeta)) {
                                       tableDatos+='<td><span class="label label-success">O.K.</span>';
                                }else{
                                    tableDatos+='<td><span class="label label-warning">No alcanzado</span>';
                                }
                               tableDatos +=  '</tr>';

                    });
                    $("#bodytable").html(tableDatos);
                }
                
                function getvendedorList(){
                  var datos = <?php $tercero->vendedor() ?>;
                    var comboDatos = "";
                    $.each(datos, function (i, item) {
                        comboDatos+="<option value=\""+item.idtercero+"\">"+item.nombres+"</option>";
                    });
                     $("#comboVendedor").html(comboDatos);
                }
                $("#btnfiltrar").on('click',function(){                   
                     window.location.href="meta_listado.php?comment="+$("#comboVendedor").val()+"&fecha="+$("#txtFecha").val()+"&nombre="+ $("#comboVendedor option:selected").html()+"&fechafinal="+$("#txtFechaFinal").val();                   
                  
                }); 
                getdatatable();
                getvendedorList();
                $('#tablaCliente').DataTable();
            });
        </script>
    </body>
</html>