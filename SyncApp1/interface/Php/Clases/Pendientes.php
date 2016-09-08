<?php
spl_autoload_register(function($clase){
    require_once $clase.'.php'; 
   
});
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
 * Description of Pendientes
 *
 * @author WALTHER SMITH FRANCO OTERO
 */
class Pendientes {

    private $db = null;

    function __construct() {
        
    }
    
    public function deletePendiente($idpendiente){
         $Con = new Connection();
         $this->db = $Con->Connect();
         
         if($this->db->query("delete from pendientes where id = ".$idpendiente)){
             return true;
         }  else {
             return false;
         }
        
    }

    public function pendientesList() {
        $Con = new Connection();
        $this->db = $Con->Connect();
        //$fechaformat = date("Y-m-d",$fecha);
        $SQls = "SELECT  
                  p.id, pr.descripcion, p.cantidad_pendiente, pd.numero, CONCAT(tr.nombres, ' ', tr.nombre2, ' ', tr.apellidos, ' ', tr.apellido2) AS nombrevenddedor, (SELECT
                        CONCAT(trs.nombres, ' ', trs.nombre2, ' ', trs.apellidos, ' ', trs.apellido2) AS nombrevenddedor
                  FROM
                        terceros trs
                  WHERE
                        trs.idtercero = pd.idtercero) AS nombrecliente, pd.fecha, p.tipo
                FROM
                  pendientes p
                  INNER JOIN productos pr ON pr.idproducto = p.id_producto
                  INNER JOIN pedidos pd ON pd.idpedido = p.id_pedido
                  INNER JOIN terceros tr ON tr.idtercero = pd.idvendedor
                  where p.tipo = 'P'
                UNION


                SELECT
                  p.id, pr.descripcion, p.cantidad_pendiente, f.numero, CONCAT(tr.nombres, ' ', tr.nombre2, ' ', tr.apellidos, ' ', tr.apellido2) AS nombrevenddedor, (SELECT
                        CONCAT(trs.nombres, ' ', trs.nombre2, ' ', trs.apellidos, ' ', trs.apellido2) AS nombrevenddedor
                  FROM
                        terceros trs
                  WHERE
                        trs.idtercero = f.idtercero) AS nombrecliente, f.fecha, p.tipo
                FROM
                  pendientes p
                  INNER JOIN productos pr ON pr.idproducto = p.id_producto
                  INNER JOIN facturas f ON f.idfactura = p.id_pedido
                  INNER JOIN terceros tr ON tr.idtercero = f.idvendedor
                  where p.tipo = 'F'
                ";
        $sql = $this->db->query($SQls);
//        echo  $SQls;
        if ($sql->num_rows > 0) {
            $result = array();
            while ($rlt = $sql->fetch_assoc()) {
                $result[] = $rlt;
            }

            echo $this->json($result);
        } else{
                $result = [];
                 echo $this->json($result);
            }
    }

    private function json($data) {

        if (is_array($data)) {
            if (isset($_GET['callback'])) {
                echo $_GET['callback'] . '(' . json_encode($data) . ')';
            } else {
                return json_encode($data);
            }
        }
    }

}
