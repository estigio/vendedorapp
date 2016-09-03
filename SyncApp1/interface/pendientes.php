<?php
require_once 'header.php';
require_once './Php/Clases/Connection/Connection.class.php';
require_once './Php/Clases/Pendientes.php';


$pendiente = new Pendientes();
 
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
        <link href="css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
        <!-- Bootstrap -->
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
                <div class="panel-heading text-center"><h2>Productos Pendientes  en los pedidos</h2></div>
                <div class="panel-body text-left">
                    <span id="mess"></span>
                    <table id="tablaCliente" class="display" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad Pedida</th>
                                <th>Vendedor</th>  
                                <th>Cliente</th>
                                <th>Fecha del Pedido</th>
                                <th>Numero Pedido</th>
                                <th>Tipo</th>
                                <th>Eliminar de la lista</th>
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
            $(document).on('ready', function () {
                 function getdatatable() {
                    var datos =<?php $pendiente->pendientesList();?>;
                    var tableDatos = "";
                    $.each(datos, function (i, item) {
                        tableDatos += '<tr>' +
                                '<td>' + item.descripcion + '</td>' +
                                '<td>' + item.cantidad_pendiente + '</td>' +
                                '<td>' + item.nombrevenddedor +'</td>'+
                                '<td>' + item.nombrecliente +'</td>'+
                                '<td>' + item.fecha +'</td>'+
                                '<td>' + item.numero +'</td>'+
                                '<td>' + item.tipo +'</td>'+                                
                                '<td> <a href="Php/process/process_Pendientes.php?option=3&id='+item.id+'" class="btn btn-danger" >Eliminar</a></td>'+
                                '</tr>';

                    });
                    $("#bodytable").html(tableDatos);
                }
                getdatatable();
                $('#tablaCliente').DataTable();
                <?php 
                    if($_GET){
                        if($_GET['back']=='ok'){
                            echo ' $("#mess").html("<span class=\"label label-success\">Eliminado</span><hr>");';
                        }else{
                            echo ' $("#mess").html("<span class=\"label label-warning\">Error</span><hr>");';
                        }
                    }
                ?>
            });
        </script>
    </body>
</html>