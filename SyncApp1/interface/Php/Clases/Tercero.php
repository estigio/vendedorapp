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
 * Description of Tercero
 *
 * @author WALTHER SMITH FRANCO OTERO
 */
class Tercero  {   
   
    private $db=NULL;
    
    function __construct() {
       
    }
    
    
    public function terceros( $zona) { 
        if($zona != NULL){
            $zona= "'".$zona."-%'";
        }else{
            $zona = "NULL";
        }
        $Con = new Connection();
        $this->db = $Con->Connect();
      $csql = "select tr.idtercero, tr.nombres, tr.apellidos, tr.apellido2, TRIM(tr.nit) as nit, tr.direccion, tr.telefono, 
                tr.barcode as email, tr.tipoprecio, tr.comentario ,br.nombarrio  as barrio, mn.nommunicipio as ciudad
                from terceros tr   inner join barrios br  on tr.idbarrio = br.idbarrio inner join municipios mn on br.idmunicipio = mn.idmunicipio 
                where tr.cliente= 1 and tr.comentario like ifnull(".$zona.",tr.comentario)";
//      echo $csql;
            $sql = $this->db->query($csql);
            
            //echo $this->db->error;
            if ($sql->num_rows > 0) {
                $result = [];
                while ($row = $sql->fetch_assoc()) {
                    $result[] = $row;
                }
                echo $this->json($result);
            }else{
                $result = [];
                 echo $this->json($result);
            }
        
    }
    
        public function vendedor() {
        // Cross validation if the request method is GET else it will return "Not Acceptable" status
        $Con = new Connection();
        $this->db = $Con->Connect();
        $this->db->query('SET CHARACTER SET utf8');
        $sql = $this->db->query("SELECT idtercero, nombres, trim(nit) as nit, email, comentario  
				from terceros where idclasifterc = 3  and char_length(trim(comentario)) > 0;");
        if ($sql->num_rows > 0) {
            $result = array();
            while ($rlt = $sql->fetch_assoc()) {
                $result[] = $rlt;
            }
            // If success everythig is good send header as "OK" and return list of users in JSON format
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
