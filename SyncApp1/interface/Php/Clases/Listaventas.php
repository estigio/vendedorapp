<?php
spl_autoload_register(function($clase){
  require_once $clase.'.php'; 

});
/*
 * *****************************************************
 * Copyright (C) 2015    Carlos Ortiz <Carlosortiz0923@GMAIL.COM> |  
 * 
 * This file is part of tomador de pedidos API and mobil app.
 * 
 * tomador de pedidos API and mobil app can not be copied and/or distributed without the express
 * permission of Carlos Ortiz
 * *****************************************************
 */

/**
 * Description of Listaventas
 *
 * @author Carlos Ortiz
 */
class Listaventas {

  private $db = null;

  function __construct() {

  }

  public function VentaList($proveedor,$lineas,$producto,$vendedor,$fini,$ffin) {
    $Con = new Connection();
    $this->db = $Con->Connect();
        //$fechaformat = date("Y-m-d",$fecha);

    if($proveedor!=0){$s_prov=" and prop.idregistro=".$proveedor;}else{$s_prov="";}
    if($lineas!=0){$s_line=" and nlp.idregistro=".$lineas;}else{$s_line="";}
    if($producto!=0){$s_prod=" and pr.idproducto=".$producto;}else{$s_prod="";}
    if($vendedor!=0){$s_vend=" and f.idvendedor=".$vendedor;}else{$s_vend="";}
    if($fini!=0&&$ffin!=0){
      $s_fecha=" and (f.fecha>=".$fini." and f.fecha<=".$ffin.")";
    }else if($fini!=0){
      $s_fecha=" and  f.fecha>=".$fini;
    }else if($ffin!=0){
      $s_fecha=" and f.fecha<=".$ffin;
    }else{
      $s_fecha="";
    }
    $SQls = "SELECT 'f' as origen,f.numero as Fact_num,f.idvendedor as Fact_idvendedor ,f.fecha as Fact_Fecha, f.valortotal as Fact_ValorTotal,
    v.idtercero as Vendedor_id, v.nit as Vendedor_Nit, v.nombres as Vendedor_Nombres,  v.apellidos as Vendedor_Apellidos,
    fp.idproducto as Fact_Producto_Id, pr.codigo as Producto_Codigo, pr. descripcion as Producto_Descripcion,fp.valorprod as Fact_Producto_valor,
    prop.codigo as Proveedor_codigo, prop.nombre as Proveedor_Nombre,
    nlp.codigo as Nivel_codigo, nlp.nombre as Nivel_nombre

    from facturas f 
    JOIN terceros as v on f.idvendedor =v.idtercero ".$s_vend."
    JOIN detfacturas as fp on f.idfactura = fp.idfactura
    JOIN productos as pr on pr.idproducto=fp.idproducto ".$s_prod."
    JOIN estproductos as epl on epl.idproducto= pr.idproducto and epl.idnivel=2
    JOIN nivelesprod as nlp on nlp.idregistro  =epl.idregistro ".$s_line."
    JOIN estproductos as eppr on eppr.idproducto= pr.idproducto and eppr.idnivel=1
    JOIN nivelesprod as prop on prop.idregistro  =eppr.idregistro ".$s_prov."
    where f.estado <>1 ".$s_fecha." limit 1000

    union


    SELECT 'p' as origen,f.numero as Fact_num,f.idvendedor as Fact_idvendedor ,f.fecha as Fact_Fecha, f.valortotal as Fact_ValorTotal,
    v.idtercero as Vendedor_id, v.nit as Vendedor_Nit, v.nombres as Vendedor_Nombres,  v.apellidos as Vendedor_Apellidos,
    fp.idproducto as Fact_Producto_Id, pr.codigo as Producto_Codigo, pr. descripcion as Producto_Descripcion,fp.valorprod as Fact_Producto_valor,
    prop.codigo as Proveedor_codigo, prop.nombre as Proveedor_Nombre,
    nlp.codigo as Nivel_codigo, nlp.nombre as Nivel_nombre

    from pedidos f 
    JOIN terceros as v on f.idvendedor =v.idtercero ".$s_vend."
    JOIN detpedidos as fp on f.idpedido = fp.idpedido
    JOIN productos as pr on pr.idproducto=fp.idproducto ".$s_prod."
    JOIN estproductos as epl on epl.idproducto= pr.idproducto and epl.idnivel=2
    JOIN nivelesprod as nlp on nlp.idregistro  =epl.idregistro ".$s_line."
    JOIN estproductos as eppr on eppr.idproducto= pr.idproducto and eppr.idnivel=1
    JOIN nivelesprod as prop on prop.idregistro  =eppr.idregistro ".$s_prov."
    where f.estado <>1 ".$s_fecha." limit 1000
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
