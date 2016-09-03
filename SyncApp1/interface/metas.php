<?php
require_once 'header.php';
require_once './Php/Clases/Connection/Connection.class.php';
require_once './Php/Clases/Tercero.php';
require_once './Php/Clases/Metas.php';
require_once './Php/Clases/Producto.php';

$metas = new Metas();
$tercero = new Tercero();
$productos = new Producto();
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
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/jquery-ui.css" rel="stylesheet" type="text/css"/>
        <link href="css/jquery-ui.structure.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/jquery-ui.theme.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/chosen.min.css" rel="stylesheet" type="text/css"/>
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
                <div class="panel-heading text-center"><h2>Metas Diarias</h2></div>
                <div class="panel-body text-left">
                    <span id="mess"></span>
                    <form class="form" >

                        <div class="form-group">
                            <label for="exampleInputEmail1">Producto</label>
                            <select id="selProducto"  class="chosen-select">                                
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Vendedor</label>
                            <select id="selVendedor" class="form-control">                                
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="txtCantidad">Cantidad Meta</label>
                            <input type="text" class="form-control" id="txtCantidad" placeholder="Cantidad">
                        </div>   
                        <div class="form-group">
                            <label for="txtCantidad">Fecha Inicial</label>             
                            <input type="text" id="datepicker" class="form-control">   
                        </div>
						 <div class="form-group">
                            <label for="txtCantidad">Fecha Final</label>             
                            <input type="text" id="datepickerfinal" class="form-control">   
                        </div>
                        <button type="button" id="btnAsignar" class="btn btn-primary">Asignar</button>
                    </form>
                </div>                   
            </div>
        </div>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-2.1.0.min.js" type="text/javascript"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-ui.js" type="text/javascript"></script>
    <script src="js/chosen.jquery.min.js" type="text/javascript"></script>
    <script src="js/chosen.proto.min.js" type="text/javascript"></script>
    <script>
        $(document).on('ready', function () {
            
          
            
            function getvendedorList() {
                var datos = <?php $tercero->vendedor() ?>;
                var comboDatos = "";
                $.each(datos, function (i, item) {
                    comboDatos += "<option value=\"" + item.idtercero + "\">" + item.nombres + "</option>";
                });
                $("#selVendedor").html(comboDatos);
                 $(".chosen-select").chosen();
            }

            function getProductos() {
                var datos = <?php $productos->productosList(); ?>;
                var comboDatos = "";
                $.each(datos, function (i, item) {
                    comboDatos += "<option value=\"" + item.idproducto + "\">" + item.descripcion + "</option>";
                });
                $("#selProducto").html(comboDatos);
            }
            getProductos();
            getvendedorList();

            function SaveMeta(producto, vendedor, cantidad, fecha, fechaFinal) {
                $.ajax({
                    url: "Php/process/process_metas.php?op=1&producto=" + producto + "&vendedor=" + vendedor + "&cantidad=" + cantidad + "&fecha=" + fecha+"&fechafinal="+fechaFinal,
                    async: true,
                    format: "jsonp",
                    crossDomain: true
                }).done(function (data) {
                    //alert(data);
                    $("#mess").html(data + "<hr>");
                }).fail(function (jqxhr, textStatus, error) {
                    var err = textStatus + ", " + error.message;
                    alert("Error: " + err.message);
                });
            }

            $("#btnAsignar").on("click", function () {
                var producto = $("#selProducto").val();
                var vendedor = $("#selVendedor").val();
                var cantidad = $("#txtCantidad").val();
                var fecha = $("#datepicker").val();
				var fechaFinal =  $("#datepickerfinal").val();
                if ($("#datepicker").val().length > 5 || $("#datepickerfinal").val().length > 5) {
                    SaveMeta(producto, vendedor, cantidad, fecha, fechaFinal);
                } else {
                    alert("seleccione una fecha para sincronizar las metas ");
                }
            });

            $.datepicker.setDefaults($.datepicker.regional[ "es" ]);
            $("#datepicker").datepicker({
                dateFormat: "yy-mm-dd",
                inline: true,
                firstDay: 1,              
                appendText: "(yyyy-mm-dd)",
                altField: "#actualDate"
            });
			$("#datepickerfinal").datepicker({
                dateFormat: "yy-mm-dd",
                inline: true,
                firstDay: 1,              
                appendText: "(yyyy-mm-dd)",
                altField: "#actualDate"
            });

        });



    </script>
</body>
</html>