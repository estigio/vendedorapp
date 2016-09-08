<?php

spl_autoload_register(function($clase) {
    require_once $clase . '.php';
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
 * Description of Metas
 *
 * @author WALTHER SMITH FRANCO OTERO
 */
class Metas {

    private $db = NULL;

    function __construct() {
        
    }

    function SaveMeta($idvendedor, $idproducto, $candtidadMeta,$fecha,$fechaFinal) {
        $con = new Connection();
        $this->db = $con->Connect();
		$chk = false;		
		$SQL = "";
		for( $i =0; $i<$this->dias_transcurridos($fecha,$fechaFinal)+1 ; $i++){
			//date('Y-m-d', strtotime($Date. ' + 2 days'))
			//$SQL = " insert into MetasDiarias(idproducto,idvendendor,cantidadMeta,cantidadvendida,fecha) values(" . $idproducto . "," . $idvendedor . "," . $candtidadMeta . ",0,'".$fecha."')";
			$SQL = " insert into MetasDiarias(idproducto,idvendendor,cantidadMeta,cantidadvendida,fecha) values(" . $idproducto . "," . $idvendedor . "," . $candtidadMeta . ",0,'".$this->addDayswithdate($fecha,$i)."')";
			
			if ($this->db->query($SQL)) {
				//echo $SQL;
				$chk=  true;
			} else {
				echo $this->db->error;
				$chk =  false;
			}
		
		}
		
		//echo  $this->dias_transcurridos($fecha,$fechaFinal);
        return $chk;
        
    }
	
	function addDayswithdate($date,$days){

    $date = strtotime("+".$days." days", strtotime($date));
    return  date("Y-m-d", $date);

}

function dias_transcurridos($fecha_i,$fecha_f)
{
	$dias	= (strtotime($fecha_i)-strtotime($fecha_f))/86400;
	$dias 	= abs($dias); $dias = floor($dias);		
	return $dias;
}

    public function MetasList($fecha, $comment,$fechaFinal) {
        $Con = new Connection();
        $this->db = $Con->Connect();
        //$fechaformat = date("Y-m-d",$fecha);
        $SQls = "select md.id, p.descripcion, CONCAT(tr.nombres, ' ', tr.nombre2,' ',tr.apellidos,' ',tr.apellido2)  as nombre, 
                            sum(md.cantidadMeta) as cantidadMeta, sum(md.cantidadvendida) as cantidadvendida, DATE(md.fecha) as fecha
                             from MetasDiarias md inner join productos p on  p.idproducto = md.idproducto 
                             inner join terceros tr on tr.idtercero = md.idvendendor
                            where  DATE(fecha) between '" . $fecha . "' and '".$fechaFinal."' and tr.idtercero = " . $comment." group by p.idproducto ";
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

    /*
     * Funcion para  para actualizar la cantidad  vendida de producto  para las metas
     */

    public function app_ActualizarMetas($fecha) {
        $Con = new Connection();
        $this->db = $Con->Connect();
        $sqls = "select * from app_pedido";
        if ($sqlres = $this->db->query($sqls)) {
            if ($sqlres->num_rows > 0) {
				
                while ($rtls = $sqlres->fetch_assoc()) { //recorremos los resultados de la tabla app_pedido                    
                    //ahora buscamos los  los detalles de los pedidos 
                    $sql = "select  * from detpedidos where idpedido = " . $rtls['idpos'] . ' and idproducto not in (select id_producto from pendientes )';
                    // echo $sql;
					
                    if ($sqlResult = $this->db->query($sql)) {
                        if ($sqlResult->num_rows > 0) {
                            while ($rlt = $sqlResult->fetch_assoc()) {
                                //Actualizamos el inventarios
                                $SQL = "UPDATE MetasDiarias set cantidadvendida = " . $rlt['cantidad'] .
                                        "   where idvendendor = " . $rtls['idvendedor'] . " and fecha = '" . $fecha . "'";
                                 //echo $SQL;
                                if ($this->db->query($SQL)) {
                                    //echo 'Actualizando Metas para el pedido' . $rtls['idpos'];
                                    //echo '<br>';
                                } else {
                                    echo $this->db->error;
                                }
                            }
                        }
                    }
                }
            }
            
            return true;
        }else{
            return false;
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
