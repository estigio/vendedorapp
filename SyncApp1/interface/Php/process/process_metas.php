<?php
 include_once '../Clases/Connection/Connection.class.php';
 include_once '../Clases/Metas.php';
 
 $metas = new Metas();
/* 
******************************************************
 * Copyright (C) 2015    WALTHER SMITH FRANCO OTERO <WFRANCOOTERO@GMAIL.COM> |  http://about.me/walthersmith
 * 
 * This file is part of tomador de pedidos API and mobil app.
 * 
 * tomador de pedidos API and mobil app can not be copied and/or distributed without the express
 * permission of WALTHER SMITH FRANCO OTERO
 ******************************************************
 */

if ($_GET) {
    
    switch ($_GET['op']) {
        case '1':
		//echo 'hola';
		//echo $metas->SaveMeta($_GET['vendedor'], $_GET['producto'], $_GET['cantidad'],$_GET['fecha'],$_GET['fechafinal']);
            if($metas->SaveMeta($_GET['vendedor'], $_GET['producto'], $_GET['cantidad'],$_GET['fecha'],$_GET['fechafinal'])){
                echo '<span class="label label-success">Se ha guardado la informacion</span>';
            }  else {
                echo '<span class="label label-warning">Error al Guardar la información</span>';
            }
            break;
        case '2':
            if ($metas->app_ActualizarMetas($_GET['fecha'])) {
                 echo '<span class="label label-success">Se ha actualizado la información</span>';
            }  else {
                echo '<span class="label label-warning">Error al actualizar la información</span>';
            }
            break;
    }
    
    
   
    
}
