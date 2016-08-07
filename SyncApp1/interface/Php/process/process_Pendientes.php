<?php
include_once '../Clases/Pendientes.php';
include_once '../Clases/Connection/Connection.class.php';
$pendiente = new Pendientes();
/*
 * *****************************************************
 * Copyright (C) 2015    WALTHER SMITH FRANCO OTERO <WFRANCOOTERO@GMAIL.COM> |  http://about.me/walthersmith
 * 
 * This file is part of tomador de pedidos API and mobil app.
 * 
 * tomador de pedidos API and mobil app can not be copied and/or distributed without the express
 * permission of WALTHER SMITH FRANCO OTERO
 * *****************************************************
 */

/**
 * Description of process_Pendientes
 *
 * @author WALTHER SMITH FRANCO OTERO
 */
if($_GET){
    
    switch ($_GET['option']) {
        case '3':
            $pendiente->deletePendiente($_GET['id']);
            header("location:../../pendientes.php?back=ok");
            break;

        default:
            break;
    }
}