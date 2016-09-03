<?php
include_once 'header.php';
?>
<!DOCTYPE html>
<!--
Copyright (c) 2015 Walther Smith Franco Otero.
All rights reserved.
   Walther Smith Franco Otero  | @walthersmih | http://about.me/walthermsith
-->
<html>
    <head>
        <title>Interface PC tomador de pedidos</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="js/jquery-2.1.0.min.js" type="text/javascript"></script>
        <link href="css/normalize.css" rel="stylesheet" type="text/css"/>
        <link href="css/header.css" rel="stylesheet" type="text/css"/>
        <link href="css/body.css" rel="stylesheet" type="text/css"/>
        <script src="js/modernizr-2.7.1.min.js" type="text/javascript"></script>
        <script src="js/modules/sync.js" type="text/javascript"></script>
        <script>
            $(document).on('ready',function(){               
                $("#btnSync").on('click',function(){
                   syncPedidosTablet();
                });
            });
        </script>
    </head>
    <body>
        <div id="container">
           <!--Header-->
            <?php  getHeader(); ?>
           <!--/header-->
            <div id="secSincronizar">
                <h2>Sincronizar pedido con pos</h2>
                <input type="button"  id="btnSync" value="Sincronizar pedido" class='button'/>
                <p id='log'></p>
            </div>
            
            <div id="secMetas">
                
            </div> 
            
        </div>
    </body>
</html>