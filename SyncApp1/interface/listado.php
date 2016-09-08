<?php
require_once 'header.php';
require_once './Php/Clases/Connection/Connection.class.php';
require_once 'Php/Clases/Tercero.php';

$tercero = new Tercero();
$comment = NULL;
$nombre  = "";

if($_GET){
    $comment = $_GET['comment'];
    $nombre = $_GET['nombre'];
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
			@media print {
			  #comboVendedor{display:none;}
			}
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
                <div class="panel-heading text-center"><h2>Listado Clientes por vendedor</h2><span id="mess"></span></div>
                <div class="panel-body text-center">
                    <select class="form-control" id="comboVendedor"></select>
					<h2><?php echo $nombre; ?></h2>
                    <hr>
                    <table id="tablaCliente" class="display" cellspacing="0" width="100%">
                        <thead>
                            <tr>								
                                <th>Nombre del Cliente</th>
								<th>Nit</th>
								<th>Dirección</th>
								<th>Telefono</th>
								<th>Ciudad</th>
								<th>Barrio</th>
                                <th>Ruta-Dia</th>
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
                 getvendedorList();
                
                function getdatatable() {
                   
                    var datos = <?php $tercero->terceros($comment) ?>;
                    var tableDatos = "";
                    $.each(datos, function (i, item) {
                        tableDatos += '<tr>' +
                                '<td>' + item.nombres + " " + item.apellidos + " " + item.apellido2 + '</td>' +
								'<td>' + item.nit + '</td>' +
								'<td>' + item.direccion + '</td>' +
								'<td>' + item.telefono + '</td>' +
								'<td>' + item.ciudad + '</td>' +
								'<td>' + item.barrio + '</td>' +
                                '<td>' + item.comentario + '</td>' +
                                '</tr>';

                    });
                    $("#bodytable").html(tableDatos);
                }
                
                function getvendedorList(){
                
                  var datos = <?php $tercero->vendedor() ?>;                  
                    var comboDatos = "<option>Seleccione un vendedor</option>";
                    $.each(datos, function (i, item) {
                        comboDatos+="<option value=\""+item.comentario+"\">"+item.nombres+"</option>";
                    });
                     $("#comboVendedor").html(comboDatos);
                    //   alert($("#comboVendedor").val()+"&nombre="+ $("#comboVendedor option:selected").html());
                    getdatatable();
                    $('#tablaCliente').DataTable();                     
                }
                $("#comboVendedor").on('change',function(){                   
                     window.location.href="listado.php?comment="+$("#comboVendedor").val()+"&nombre="+ $("#comboVendedor option:selected").html();                   
                }); 
               
               //  getdatatable();                
                //$('#tablaCliente').DataTable();
            });
        </script>
    </body>
</html>