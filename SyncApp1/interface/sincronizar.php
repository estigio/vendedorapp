<?php
require_once 'header.php';
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
        <title>Interface Tomador de Pedidos</title>
        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/jquery-ui.css" rel="stylesheet" type="text/css"/>
        <link href="css/jquery-ui.structure.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/jquery-ui.theme.min.css" rel="stylesheet" type="text/css"/>
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
                <div class="panel-heading"><h2>Sincronizar pedidos</h2></div>
                <div class="panel-body text-center">
                    <p>Despues de Sincronizar la tablet con el servidor  hacer click en el siguiente boton</p>
                    <button  id="btnSync" class="btn btn-primary">Sincronizar Como Pedido Comercial</button>
                    <button  id="btnfactura" class="btn btn-primary">Sincronizar Factura Comercial</button>                  
                    <hr>
                    <button  id="btnNuevatablet" class="btn btn-primary">Sincronizar Nueva Tablet</button>
                    <hr>
                    <p id='log'></p>
                </div>
            </div>
        </div>


        <div id="Process" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content text-center" style="padding:10px;">
                    <h2>Procesando</h2>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="500" aria-valuemin="0" aria-valuemax="100" style="width: 100%">  
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="js/jquery-2.1.0.min.js" type="text/javascript"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery-ui.js" type="text/javascript"></script>
        <script src="js/modules/sync.js" type="text/javascript"></script>
        <script>

            $(document).on('ready', function () {
          
        $("#btnSync").on('click', function () {
                    exeShow();
                    syncPedidosTablet();
                });

                $("#btnfactura").on('click', function () {
                     exeShow();
                    syncPedidosFacturaTablet();
                });


                $("#btnMetas").on('click', function () {
                    if ($("#datepicker").val().length > 5) {
                        $.ajax({
                            url: "Php/process/process_metas.php?op=2&fecha=" + $("#datepicker").val(),
                            type: 'post',
                            async: true,
                            format: "jsonp",
                            crossDomain: true
                        }).done(function (data) {
                            $("#log").html(data);
                        }).fail(function (jqxhr, textStatus, error) {
                            var err = textStatus + ", " + error.code;
                            alert("Request Failed: " + err);
                        });
                    } else {
                        alert("seleccione una fecha para sincronizar las metas ");
                    }
                });

                $("#btnNuevatablet").on('click', function () {
                    nuevatablet();
                });

                $.datepicker.setDefaults($.datepicker.regional[ "es" ]);
                $("#datepicker").datepicker({
                    dateFormat: "yy-mm-dd",
                    inline: true,
                    firstDay: 1,
                    defaultDate: "+2m",
                    appendText: "(yyyy-mm-dd)",
                    altField: "#actualDate"
                });
            });
        </script>
    </body>
</html>