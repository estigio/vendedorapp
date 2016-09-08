<?php

header('Access-Control-Allow-Origin: *');
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);
require 'Rest.inc.php';

/*
 * AUTOR Walther smith franco otero
 * tw:@walther smith
 * link:http://about.me/walthersmith
 */

class API extends REST {

    public $data = "";

    const DB_SERVER = "localhost";
    const DB_USER = "root";
    const DB_PASSWORD = "";
    const DB = "bd_app";

    private $db = NULL;

    function __construct() {
        parent::__construct();
        $this->dbConnect();
    }

    /*
     *  Database connection 
     */

    private function dbConnect() {

        $this->db = new mysqli(self::DB_SERVER, self::DB_USER, self::DB_PASSWORD, self::DB);
        mysqli_set_charset($this->db, "utf8");
    }

    /*
     * Public method for access api.
     * This method dynmically call the method based on the query string
     *
     */

    public function processApi() {
        $func = strtolower(trim(str_replace("/", "", $_REQUEST['rquest'])));
        if ((int) method_exists($this, $func) > 0)
            $this->$func();
        else
            $this->response('', 404);
        // If the method not exist with in this class, response would be "Page not found".
    }

    private function tercero() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $comment = $this->_request['comment'];
        if (!empty($comment)) {
            $sql = $this->db->query("select tr.idtercero, tr.nombres, tr.apellidos, tr.apellido2, TRIM(tr.nit) as nit, tr.direccion, tr.telefono, 
									tr.barcode as email, tr.tipoprecio, tr.comentario ,br.nombarrio  as barrio, mn.nommunicipio as ciudad
									from terceros tr   inner join barrios br  on tr.idbarrio = br.idbarrio inner join municipios mn on br.idmunicipio = mn.idmunicipio
									where tr.cliente= 1 and tr.comentario like '%" . $comment . "%'");
            if ($sql->num_rows > 0) {
                $result = array();
                while ($row = $sql->fetch_assoc()) {
                    $result[] = $row;
                }
                $this->response($this->json($result), 200);
            }
            $this->response('', 204);
        } else {
            $this->response('', 204);
        }
    }

    /*
     * trae los productos que  tiene una cantidad en inventario > 0  el resultado es convertido a json y  para ser sincronizado
     */

    private function productos() {
        // Cross validation if the request method is GET else it will return "Not Acceptable" status
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $this->db->query('SET CHARACTER SET utf8');

        $sql = $this->db->query("SELECT
                              pr.idproducto, pr.codigo, pr.descripcion, m.nommedida, pr.precioventa, pr.precioespecial1, pr.precioespecial2, iv.cantidad,
                              nvpr.nombre, stp.idregistro
                            FROM
                              productos pr
                              INNER JOIN medidas m ON m.idunmedida = pr.idunmedida
                              INNER JOIN inventario iv ON pr.idproducto = iv.idproducto
                              INNER JOIN estproductos stp on stp.idproducto = pr.idproducto 
                              INNER JOIN nivelesprod nvpr on stp.idregistro  = nvpr.idregistro
                            WHERE
                              pr.estado = 1 AND iv.cantidad > 0 AND nvpr.idnivel = 1 ");
        if ($sql->num_rows > 0) {
            $result = array();
            while ($rlt = $sql->fetch_assoc()) {
                $result[] = $rlt;
            }
            // If success everythig is good send header as "OK" and return list of users in JSON format
            $this->response($this->json($result), 200);
        }
        $this->response('', 204); // If no records "No Content" status
    }

    private function prodprecioscant() {
        // Cross validation if the request method is GET else it will return "Not Acceptable" status
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $this->db->query('SET CHARACTER SET utf8');
        $sql = $this->db->query("select * from prodprecioscant");
        if ($sql->num_rows > 0) {
            $result = array();
            while ($rlt = $sql->fetch_assoc()) {
                $result[] = $rlt;
            }
            // If success everythig is good send header as "OK" and return list of users in JSON format
            $this->response($this->json($result), 200);
        }
        $this->response('', 204); // If no records "No Content" status
    }

    private function gerProveedor() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $this->db->query('SET CHARACTER SET utf8');
        $sql = $this->db->query("SELECT idtercero, nombres, trim(nit) as nit, email, comentario from terceros where idclasifterc = 3");
        if ($sql->num_rows > 0) {
            $result = array();
            while ($rlt = $sql->fetch_assoc()) {
                $result[] = $rlt;
            }
            // If success everythig is good send header as "OK" and return list of users in JSON format
            $this->response($this->json($result), 200);
        }
        $this->response('', 204); // If no records "No Content" status
    }

    #functions Data Process

    private function vendedor() {
        // Cross validation if the request method is GET else it will return "Not Acceptable" status
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $this->db->query('SET CHARACTER SET utf8');

        $sql = $this->db->query("SELECT idtercero, nombres, trim(nit) as nit, email, comentario  
									 from terceros where idclasifterc = 3 ;");
        if ($sql->num_rows > 0) {
            $result = array();
            while ($rlt = $sql->fetch_assoc()) {
                $result[] = $rlt;
            }
            // If success everythig is good send header as "OK" and return list of users in JSON format
            $this->response($this->json($result), 200);
        }
        $this->response('', 204); // If no records "No Content" status
    }

    #functions Data Process

    private function transportador() {
        // Cross validation if the request method is GET else it will return "Not Acceptable" status
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $this->db->query('SET CHARACTER SET utf8');

        $sql = $this->db->query("SELECT idtercero, nombres, nit 
									 from terceros where idclasifterc = 2 ;");
        if ($sql->num_rows > 0) {
            $result = array();
            while ($rlt = $sql->fetch_assoc()) {
                $result[] = $rlt;
            }
            // If success everythig is good send header as "OK" and return list of users in JSON format
            $this->response($this->json($result), 200);
        }
        $this->response('', 204); // If no records "No Content" status
    }

    private function metasDiarias() {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $idvendedor = $this->_request['idvendedor'];
        $fecha = $this->_request['fecha'];
        if (!empty($idvendedor) || !empty($fecha)) {
            $sql = $this->db->query("select id,idproducto,idvendendor as idvendedor,id,cantidadmeta,cantidadvendida,STR_TO_DATE(DATE_FORMAT(fecha,'%Y-%m-%d'),'%Y-%m-%d') as fecha from MetasDiarias
                                    where idvendendor = " . $idvendedor . "  and STR_TO_DATE(DATE_FORMAT(fecha,'%Y-%m-%d'),'%Y-%m-%d')  = STR_TO_DATE(DATE_FORMAT('" . $fecha . "','%Y-%m-%d'),'%Y-%m-%d') ");
            if ($sql->num_rows > 0) {
                $result = array();
                while ($row = $sql->fetch_assoc()) {
                    $result[] = $row;
                }
                $this->response($this->json($result), 200);
            }
            $this->response('', 204);
        } else {
            $this->response('', 204);
        }
    }

    public function guardarClientes() {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $type = json_decode($this->_request['datos']);
        if (!empty($type)) {
            $SQL = "";
            $msg = "";
            foreach ($type as $value) {
                $SQL = "INSERT INTO terceros (nit, digito, nombres, nombre2, apellidos, apellido2, direccion, telefono, telefono2, fax, email, iddepto,
						idmunicipio, tipopersona, cliente, proveedor, empleado, otros, idregimen, manejoica, autoretenedor, fechacumple, mancond, idconcprecio,
						aplicaprom, comentario, idclasifterc, barcode, manejacupocred, cupocred, inactivo, restringirpagos, manejasedes,
						restringirprodcompras, tipoprecio, version, usapuntos) 
						VALUES  ('" . $value->identification . "', 0, upper('" . $value->name . "'), '', upper('" . $value->lastname1 . "'), upper('" . $value->lastname2 . "'), upper('" . $value->address . "'), '" . $value->phone . "', '', '', upper('" . $value->email . "'), 1,
						739, 1, 1, 0, 0, 0, 1, 0, 0, '00000000', 0, NULL, 0, '', 1, '" . $value->identification . "', 0, 0.0, 0, 0, 0, 0, " . $value->tipoprecio . ", 0, 0)";
                if ($sql = $this->db->query($SQL)) {
                    $sql2 = $this->db->query("INSERT INTO clientesporvend (idvendedor, idcliente) 
												  VALUES (" . $value->idvendedor . ",(select max(idtercero) from terceros  where cliente= 1))");
                    $msg = "Clientes Guardados";
                } else {
                    $msg = "Cliente NO Ingresado";
                }
            }
            $success = array('status' => "susses", 'msg' => $msg);
            $this->response($this->json($success), 200);
            $this->response('', 204);
        } else {

            $this->response('', 204);
        }
    }

    public function guardarPedidos() {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $type = json_decode($this->_request['datos']);
        if (!empty($type)) {
            $SQL = "";
            $msg = "";
            //$cantidadregistros = count($type);
            foreach ($type as $value) {
                $SQL = "INSERT INTO app_pedido (idcliente, fecha, idvendedor, descripcion, version, valor_pedido) 
							VALUES ((select idtercero from terceros where nit = '" . $value->identification . "')," . $value->fecha . "," . $value->idvendedor . ",'"
                        . $value->descripcion . "'," . $value->version . "," . $value->valor_pedido . ")";
                if ($sql = $this->db->query($SQL)) {
                    $msg.="hello" . ",";

                    foreach ($value->prod_pedido as $pp) {

                        $SQs = "select max(id) as id from app_pedido where idvendedor = " . $value->idvendedor . "";
                        $sql = $this->db->query($SQs);
                        $idpedido = 0;
                        if ($sql->num_rows > 0) {
                            while ($rlts1 = $sql->fetch_assoc()) {
                                $idpedido = $rlts1['id'];
                            }
                        }
                        //  $msg.=$SQs . ",";


                        $SQ = "INSERT INTO app_producto_pedido (idpedido, idproducto, cantidad, valor)  VALUES (" . $idpedido . "," . $pp->idproducto . "," . $pp->cantidad . ", " . $pp->valor . ")";
                        if ($sq = $this->db->query($SQ)) {
                            //  $msg.="-bien" . $SQ . ",";
                        } else {
                            //   $msg.="-Err 2:" . $SQ . ",";
                        }
                    } //fin for 						
                } else {
                    $msg.="-Err" . $SQL . ",";
                }
            }
            //guardarPedidoss();
            $success = array('status' => 'sussed', "msg" => $msg);
            $this->response($this->json($success), 200);
            $this->response('', 204);
        } else {
            $this->response('', 204);
        }
    }

    public function importPedido() {
        echo '<div class="collapse" id="Pedidos">
                <div class="well">';
        $SQLs = "select * from app_pedido";
        $sql = $this->db->query($SQLs);
        if ($sql->num_rows > 0) {
            while ($rlt = $sql->fetch_assoc()) {
                $SQLinsert = "INSERT INTO pedidos (numero, idtercero, fecha, plazo, detalle, idalmacen, idpago, idvendedor, valdescuentos,
				valretenciones, valimpuesto, subtotal, valortotal, estado, fechacrea, hora, fechaact, horaact, cambio, impresa, fechavenc,
				 documento, idusuario, idconcprecio, idfactura, idarqueo, recibeefec, venta, idsedeterc, idusuarioanul, fechaanul, horaanul,
				 pedidosiniva, retefuente, reteiva, reteica, valimpoconsumo, valiva, valimpcree) 
				VALUES ((select max(p.numero) from pedidos p)+1, " . $rlt['idcliente'] . ",'" . str_replace("-", "", $rlt['fecha']) . "', 0, '"
                        . $rlt['descripcion'] . "', 1, 1, " . $rlt['idvendedor'] . ", 0.0,
				 0.0, 0.0, " . $rlt['valor_pedido'] . ", " . $rlt['valor_pedido'] . ", 0, '" . str_replace("-", "", $rlt['fecha']) . "', '00:00:00', '00000000', '00:00:00', 0.0, 0, '00000000',
				'', 1, NULL, NULL, 1, 0.0, 0, NULL, 0, '00000000', '00:00:00',
				 0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0)";

                if ($sqinser = $this->db->query($SQLinsert)) { // Insertamos el Pedido
                    $sqlId = "select max(idpedido) as  id from pedidos p where idvendedor = " . $rlt['idvendedor'];
                    $sqls = $this->db->query($sqlId);
                    $idpedido = 0;
                    if ($sqls->num_rows > 0) {
                        while ($rlt2 = $sqls->fetch_assoc()) {
                            $idpedido = $rlt2['id'];
                        }
                        echo $idpedido;
                        echo " | ";
                        $SQLUpdate = "Update  app_pedido set idpos = " . $idpedido . " where id= " . $rlt["id"];
                        $this->db->query($SQLUpdate);
                    }
                }
            }
        }
        echo '<br>';
        echo "terminado el proceso de importación de la tabla app_pedidos a la tabla pedidos";
        echo '</div></div>';
    }

    private function app_productos() {
        echo '<div class="collapse" id="productoPedidos">
                <div class="well">';
        $SQL = "select * from app_producto_pedido";
        if ($sql = $this->db->query($SQL)) {
            if ($sql->num_rows > 0) {
                while ($rlt = $sql->fetch_assoc()) {
                    //consultamos los datos del pedido
                    $sqls = "select * from app_pedido where  id =" . $rlt["idpedido"];
                    //echo $sqls;                  
                    $idpos = 0;
					$fecha="";
                    if ($sqlapp = $this->db->query($sqls)) {
                        if ($sqlapp->num_rows > 0) {
                            while ($rltd = $sqlapp->fetch_assoc()) {
                                $idpos = $rltd['idpos'];
								$fecha = $rltd['fecha'];
                            }
                        }
                    }

                    //traemos la cantidad del  disponble de un produco  filtrado por el id
                    $sqlrs = "select cantidad from inventario where idproducto = " . $rlt['idproducto'];
                    $cantidad = 0;
                    if ($sqlr = $this->db->query($sqlrs)) {
                        if ($rtlsd = $sqlr->fetch_assoc()) {
                            $cantidad = $rtlsd['cantidad'];
                        }
                    }

                    if ($cantidad >= $rlt['cantidad']) {
                        //Si la cantidad del inventario es mayor a la cantidad pedida  ingresamos el registro en la tabla detalle pedido
                        $SQ = "INSERT INTO detpedidos (idpedido, idproducto, cantidad, valorprod, 
                                    descuento, porcdesc, porciva, ivaprod, costoprod, detalle, base, despachado) 
                                    VALUES (" . $idpos . ", " . $rlt['idproducto'] . ", " . $rlt['cantidad'] . ", " . $rlt['valor'] . ", 
                                    0.0, 0.0, (select iv.porcentaje from productos p inner join iva iv on p.codiva = iv.codiva WHERE p.idproducto =" . $rlt['idproducto'] . "),
                                     ((" . $rlt['valor'] . "-(" . $rlt['valor'] . "/(1+(select iv.porcentaje from productos p inner join iva iv on p.codiva = iv.codiva WHERE p.idproducto = " . $rlt['idproducto'] . ")/100)))*" . $rlt['cantidad'] . "),
                                    (select costo from productos where idproducto = " . $rlt['idproducto'] . "), NULL, 
                                     ((" . $rlt['valor'] . "/(1+(select iv.porcentaje from productos p inner join iva iv on p.codiva = iv.codiva WHERE p.idproducto = " . $rlt['idproducto'] . ")/100))*" . $rlt['cantidad'] . "), 1)";

                        if ($sqldep = $this->db->query($SQ)) {
							echo '<br>';
                            echo 'EXITO!! detalle pedido';
                            echo '<br>';
							//$cantidad,$idproducto,$idpedido,$fecha
							$this->app_actualizarInventario( $rlt['cantidad'],$rlt['idproducto'],$idpos,$fecha);
                        }
                    } else {
                        //Si la cantidad  en inventario es mejor a la canridad pedida se ingresa el registro en la tabla  de pendientes
                        $sqlpendi = "Insert into pendientes (id_pedido,id_producto, cantidad_pendiente, tipo) values (" . $idpos . "," . $rlt['idproducto'] . "," . ($rlt['cantidad']) . ",'P')";
                        if ($sqlpen = $this->db->query($sqlpendi)) {
                            echo 'Exito!! se guardo la entrada en la tabla pendientes';
                            echo '<br>';
                        }
                    }
                }
            }
        }
        echo 'Terminado el proceso de improtacion de el detalle pedido entr las tablas de app_detalle_pedido , detpedidos y pendientes  si hay faltantes  en el inventario ';
        echo '<br>';
        echo '</div>
                </div>';
    }

    //Funciones adicionales en la sincronizacion del pedido
    private function app_actualizarInventario($cantidad,$idproducto,$idpedido,$fecha){
       // echo '<div class="collapse" id="inventario">
         //       <div class="well">';
   
                                $SQL = "UPDATE inventario set cantidad = (cantidad-" . $cantidad . "), cantpedida = (cantpedida+" . $cantidad . ") where idproducto = " . $idproducto;
                                // echo $SQL;
                                if ($this->db->query($SQL)) {
                                    echo 'Actualizando  el inventario para el pedido: ' . $idpedido;
                                    echo '<br>';
									
									$Sqlkard = "INSERT INTO kardex  ( numdocumento, tipodoc, idproducto, idalmacen, detallemov, fechamov, cantidad, costo, tipomov, saldocant, ultcosto, saldoant, costoant, fechakardex, idsincronizacion, serialescruzados)
                                            VALUES ((select numero from  pedidos where idpedido = " . $idpedido . "), concat('PEDIDO ID ',(select numero from  pedidos where idpedido = " . $idpedido . ")) , "
                                        . $idproducto . ", 1, concat( 'PEDIDO ',(select numero from  pedidos where idpedido = " . $idpedido . "),' DE ','" . str_replace('-', '', $fecha) . "'), '" . str_replace('-', '', $fecha) . "', " . $cantidad . ", (select costo from productos where idproducto = " . $idproducto . ") ,
                                            '-',(select cantidad from inventario where idproducto = " . $idproducto . "),  (select costo from productos where idproducto = " . $idproducto . "),
                                            ((select cantidad from inventario where idproducto = " . $idproducto . ")+" . $cantidad . "),  (select costo from productos where idproducto = " . $idproducto . "), '" . str_replace('-', '', $fecha) . "', NULL, 0);";
                                // echo $Sqlkard;
                                // echo "<br>";
                                if ($this->db->query($Sqlkard)) { //se comprueba si la consulta ejecuto correctamente
                                    
                                    echo 'Actualizando  el kardex para el  el pedido : ' . $idpedido;
                                    echo "<br>";
                                } else {
                                    echo $this->db->error;
                                }
                             }
         
        
        echo 'Terminado la Actualizacion del inventario y kardex';
        echo '<br><hr>';
       // echo '<hr></div></div>';
		
		
    }

  /*  private function app_actualizar_kardex() {
        $sqls = "select * from app_pedido";
        if ($sqlres = $this->db->query($sqls)) {
            if ($sqlres->num_rows > 0) {
                while ($rtls = $sqlres->fetch_assoc()) { //recorremos los resultados de la tabla app_pedido                    
                    //ahora buscamos los  los detalles de los pedidos 
                    $sql = "select  * from detpedidos where idpedido = " . $rtls['idpos'] . ' and idproducto not in (select id_producto from pendientes where id_pedido = ' . $rtls['idpos'] . ' and  tipo = \'P\' )';
                    //echo $sql;
                    if ($sqlResult = $this->db->query($sql)) {
                        if ($sqlResult->num_rows > 0) {
                            while ($rlt = $sqlResult->fetch_assoc()) {
                                //Actualizamos el kardex
                                $Sqlkard = "INSERT INTO kardex  ( numdocumento, tipodoc, idproducto, idalmacen, detallemov, fechamov, cantidad, costo, tipomov, saldocant, ultcosto, saldoant, costoant, fechakardex, idsincronizacion, serialescruzados)
                                            VALUES ((select numero from  pedidos where idpedido = " . $rtls['idpos'] . "), concat('PEDIDO ID ',(select numero from  pedidos where idpedido = " . $rtls['idpos'] . ")) , "
                                        . $rlt['idproducto'] . ", 1, concat( 'PEDIDO ',(select numero from  pedidos where idpedido = " . $rtls['idpos'] . "),' DE ','" . str_replace('-', '', $rtls['fecha']) . "'), '" . str_replace('-', '', $rtls['fecha']) . "', " . $rlt['cantidad'] . ", (select costo from productos where idproducto = " . $rlt['idproducto'] . ") ,
                                            '-',(select cantidad from inventario where idproducto = " . $rlt['idproducto'] . "),  (select costo from productos where idproducto = " . $rlt['idproducto'] . "),
                                            ((select cantidad from inventario where idproducto = " . $rlt['idproducto'] . ")+" . $rlt['cantidad'] . "),  (select costo from productos where idproducto = " . $rlt['idproducto'] . "), '" . str_replace('-', '', $rtls['fecha']) . "', NULL, 0);";
                                // echo $Sqlkard;
                                // echo "<br>";
                                if ($this->db->query($Sqlkard)) { //se comprueba si la consulta ejecuto correctamente
                                    echo "<br>";
                                    echo 'Actualizando  el kardex para el  el pedido : ' . $rtls['idpos'];
                                    echo "<br>";
                                } else {
                                    echo $this->db->error;
                                }
                            }
                        }
                    } else {
                        echo $this->db->error;
                    }
                }
            }
        } else {
            echo $this->db->error;
        }
        echo 'Terminado  el registro de el kardex';
        echo '<br>';
        echo '<hr>';
    }
*/
    
	private function app_valImpuesto() {
        $sqls = "select * from app_pedido";
        if ($sqlres = $this->db->query($sqls)) {
            if ($sqlres->num_rows > 0) {
                while ($rtls = $sqlres->fetch_assoc()) { //recorremos los resultados de la tabla app_pedido                    
                    //ahora buscamos los  los detalles de los pedidos 
                    $SQL = "update pedidos  set valimpuesto = (select sum(dd.ivaprod) from detpedidos dd where dd.idpedido = " . $rtls['idpos'] . ")
                                            where idpedido =" . $rtls['idpos'];
                    if ($sqlupdate = $this->db->query($SQL)) { //se comprueba si la consulta ejecuto correctamente
                        echo 'Actualizando  el ValImpuesto para el pedido' . $rtls['idpos'];
                        echo '<br>';
                    }
                }
            }
        }

        echo 'Terminado la Actualizacion del Valimpuesto';
        echo '<br>';
        echo '<hr>';
    }

    private function app_actualizarValorTotal() {
        $sqls = "select * from app_pedido";
        if ($sqlres = $this->db->query($sqls)) {
            if ($sqlres->num_rows > 0) {
                while ($rtls = $sqlres->fetch_assoc()) { //recorremos los resultados de la tabla app_pedido                    
                    //ahora buscamos los  los detalles de los pedidos 
                    $SQL = "update pedidos  set valortotal = (select  sum((valorprod*cantidad))  from detpedidos dd where dd.idpedido = " . $rtls['idpos'] . ") where idpedido = " . $rtls['idpos'];
                    //$SQL = "update pedidos  set valortotal = (select  sum((valorprod*cantidad)+dd.ivaprod)  from detpedidos dd where dd.idpedido = ". $rtls['idpos'] .") where idpedido = " . $rtls['idpos'];
                    //echo $SQL;
                    echo "<br>";
                    if ($sqlupdate = $this->db->query($SQL)) { //se comprueba si la consulta ejecuto correctamente
                        echo 'Actualizando  el valor total para  el pedido' . $rtls['idpos'];
                        echo '<br>';
                    } else {
                        echo 'Error al actualizar el valor total' . $rtls['idpos'];
                        echo '<br>';
                        echo $this->db->error;
                    }
                }
            }
        }
        echo 'Terminado la Actualizacion del valorTotal';
        echo '<br>';
        echo '<hr>';
    }

    private function app_subtotal() {
        $sqls = "select * from app_pedido";
        if ($sqlres = $this->db->query($sqls)) {
            if ($sqlres->num_rows > 0) {
                while ($rtls = $sqlres->fetch_assoc()) { //recorremos los resultados de la tabla app_pedido                    
                    //ahora buscamos los  los detalles de los pedidos 
                    $SQL = "select (valortotal- valimpuesto) as valor  from pedidos where idpedido = " . $rtls['idpos'];


                    // $SQL = "update pedidos SET subtotal = (select (valortotal- valimpuesto) from pedidos where idpedido = ".$rtls['idpos'].") WHERE idpedido = " . $rtls['idpos'];
                    //echo $SQL;
                    if ($sqlupdate = $this->db->query($SQL)) { //se comprueba si la consulta ejecuto correctamente
                        if ($sqlupdate->num_rows > 0) {
                            while ($rtls2 = $sqlupdate->fetch_assoc()) {

                                $SQL2 = "update pedidos SET subtotal =" . $rtls2['valor'] . " WHERE idpedido = " . $rtls['idpos'];

                                if ($sqlUpdate2 = $this->db->query($SQL2)) {
                                    echo 'Actualizando  el  Subtotal para el pedido' . $rtls['idpos'];
                                    echo '<br>';
                                    //echo $SQL2;
                                }
                            }
                        }
                    } else {
                        echo 'Error al actualizar el subtotal' . $rtls['idpos'];
                        echo '<br>';
                        echo mysql_error($sqlupdate);
                    }
                }
            }
        }

        echo 'Terminado la Actualizacion del Subtotal';
        echo '<br>';
        echo '<hr>';
    }

    private function procesoSync() {
        $sqls = "select * from app_pedido";
        if ($sqlres = $this->db->query($sqls)) {
            if ($sqlres->num_rows > 0) {
                echo'<h2>Log de la operacion</h2>';
                echo '<a class="btn btn-primary" data-toggle="collapse" href="#Pedidos" aria-expanded="false" aria-controls="collapseExample">Pedidos</a> | ';
                echo '<a class="btn btn-primary" data-toggle="collapse" href="#productoPedidos" aria-expanded="false" aria-controls="collapseExample">Productos</a> | ';
                echo '<a class="btn btn-primary" data-toggle="collapse" href="#valores" aria-expanded="false" aria-controls="collapseExample">valores</a>';
                echo '<hr>';

                $this->importPedido(); // se importan los pedidos de la tabla app_pedidos  a la tabla pedidos
                $this->app_productos(); // se importan los productos de la tabla app_detelle_pedido a detpedidos
                //$this->app_actualizarInventario(); // Actualiza el Inventario de los productos 
                echo '<div class="collapse" id="valores"> <div class="well">';
              //  $this->app_actualizar_kardex(); // Actualiza el Kardex
                $this->app_actualizarValorTotal(); // Actualiza el valor total
                $this->app_valImpuesto(); // Actualiza el valimpuesto       
                $this->app_subtotal(); //Actualiza el Subtotal;        
                echo '</div> <div>';
                //$this->limpiarTablasincronizacion(); // se eliminan las tablas temporales
            } else {
                echo '<h2>No Hay Pedidos Para Procesar</h2>';
            }
        }
    }

    /*
     * *************************************************
     *   Funciones para la sincronizacion de facturas
     * *************************************************
     */

    private function procesoSyncFactura() {
        $sqls = "select * from app_pedido";
        if ($sqlres = $this->db->query($sqls)) {
            if ($sqlres->num_rows > 0) {
                echo'<h2>Log de la operacion</h2>';
                echo '<a class="btn btn-primary" data-toggle="collapse" href="#Pedidos" aria-expanded="false" aria-controls="collapseExample">Facturas</a> | ';
                echo '<a class="btn btn-primary" data-toggle="collapse" href="#productoPedidos" aria-expanded="false" aria-controls="collapseExample">Productos</a> | ';
                //echo '<a class="btn btn-primary" data-toggle="collapse" href="#inventario" aria-expanded="false" aria-controls="collapseExample">inventario</a> | ';
                echo '<a class="btn btn-primary" data-toggle="collapse" href="#valores" aria-expanded="false" aria-controls="collapseExample">valores</a>';
                echo '<hr>';

                $this->importPedidoFactura(); // se importan los pedidos de la tabla app_pedidos  a la tabla pedidos
                $this->app_productosFactura(); // se importan los productos de la tabla app_detelle_pedido a detpedidos
                //$this->app_actualizarInventario_factura(); // Actualiza el Inventario de los productos 
                echo '<div class="collapse" id="valores"> <div class="well">';
               // $this->app_actualizar_kardex_factura(); // Actualiza el Kardex
                $this->app_actualizarValorTotal_factura(); // Actualiza el valor total
                $this->app_valImpuesto_factura(); // Actualiza el valimpuesto       
                $this->app_subtotal_factura(); //Actualiza el Subtotal;  
                echo '</div> <div>';
                //$this->limpiarTablasincronizacion(); // se eliminan las tablas temporales
            } else {
                echo '<h2>No Hay Facturas Para Procesar</h2>';
            }
        }
    }

    public function importPedidoFactura() {
        echo '<div class="collapse" id="Pedidos">
                <div class="well">';
        $SQLs = "select * from app_pedido";
        $sql = $this->db->query($SQLs);
        if ($sql->num_rows > 0) {
            while ($rlt = $sql->fetch_assoc()) {
                $SQLinsert = "INSERT INTO facturas (numero, idtercero, fecha, plazo, detalle, idalmacen, idpago, idvendedor, valdescuentos, 
                                valretenciones, valimpuesto, subtotal, valortotal, estado, fechacrea, hora, 
                                cambio, impresa, fechavenc, documento, idusuario, idconcprecio, idarqueo, nombrepc, idresolucion,
                                 recibeefec, otrosimpuestos, idsedeterc, idcomprobantecont, idusuarioanul, fechaanul, horaanul,
                                 valredondeo, motivoanulacion, idsincronizacion, idsincanulacion, version, retefuente, reteiva,
                                 reteica, entregada, fechaentrega, horaentrega, valimpoconsumo, valiva, idpuntotercero, valimpcree,
                                 descuentodespuesiva, idvehiculo, facturadaotraemp) 
                                VALUES ((select max(f.numero) from facturas f)+1, " . $rlt['idcliente'] . ",'" . str_replace("-", "", $rlt['fecha']) . "', 0, '" . $rlt['descripcion'] . "', 1, 1, " . $rlt['idvendedor'] . ", 0.0,
                                0.0, 0.0, " . $rlt['valor_pedido'] . ", " . $rlt['valor_pedido'] . ", 0, '" . str_replace("-", "", $rlt['fecha']) . "', '00:00:00',
                                0.0, 0, '" . str_replace("-", "", $rlt['fecha']) . "','', 1, NULL, 1, NULL, 1, 
                                DEFAULT, DEFAULT, NULL, NULL, NULL, NULL, NULL, 
                                DEFAULT, NULL, NULL, NULL, DEFAULT, DEFAULT, DEFAULT, 
                                DEFAULT, DEFAULT, NULL, NULL, DEFAULT, DEFAULT, NULL, DEFAULT, 
                                DEFAULT, NULL, DEFAULT);";

                if ($sqinser = $this->db->query($SQLinsert)) { // Insertamos el Pedido
                    $sqlId = "select max(idfactura) as  id from facturas  where idvendedor = " . $rlt['idvendedor'];
                    $sqls = $this->db->query($sqlId);
                    $idpedido = 0;
                    if ($sqls->num_rows > 0) {
                        while ($rlt2 = $sqls->fetch_assoc()) {
                            $idpedido = $rlt2['id'];
                        }
                        echo $idpedido;
                        echo " | ";
                        $SQLUpdate = "Update  app_pedido set idpos = " . $idpedido . " where id= " . $rlt["id"];
                        $this->db->query($SQLUpdate);
                    }
                } else {
                    echo 'Error!!  Factura' . $this->db->error;
                    echo '<br>';
                }
            }
        }
        echo '<br>';
        echo "terminado el proceso de importación de la tabla app_pedidos a la tabla Facturas";
        echo '<br>';
        echo '<hr></div></div>';
    }

    private function app_productosFactura() {
        echo '<div class="collapse" id="productoPedidos">
                <div class="well">';
        $SQL = "select * from app_producto_pedido";
        if ($sql = $this->db->query($SQL)) {
            if ($sql->num_rows > 0) {
                while ($rlt = $sql->fetch_assoc()) {
                    //consultamos los datos del pedido
                    $sqls = "select * from app_pedido where  id =" . $rlt["idpedido"];
                    //echo $sqls;
                    //echo '<br><br>';
                    $idpos = 0;
					$fecha="";
                    if ($sqlapp = $this->db->query($sqls)) {
                        if ($sqlapp->num_rows > 0) {
                            while ($rltd = $sqlapp->fetch_assoc()) {
                                $idpos = $rltd['idpos'];
								$fecha = $rltd['fecha'];
                            }
                        }
                    }

                    //traemos la cantidad del  disponble de un produco  filtrado por el id
                    $sqlrs = "select cantidad from inventario where idproducto = " . $rlt['idproducto'];
                    $cantidad = 0;
					
                    if ($sqlr = $this->db->query($sqlrs)) {
                        if ($rtlsd = $sqlr->fetch_assoc()) {
                            $cantidad = $rtlsd['cantidad'];
							
                        }
                    }

                    if ($cantidad >= $rlt['cantidad']) {
                        //Si la cantidad del inventario es mayor a la cantidad pedida  ingresamos el registro en la tabla detalle pedido
                        $SQ = "INSERT INTO detfacturas (idfactura, idproducto, cantidad, valorprod,
                                descuento, porcdesc, porciva, ivaprod, costoprod, detalle, base, 
                               otrosimpuestos, tmpidcomp, idmenu, numorden, idvendedor, desc_manual, desc_promo, desc_puntos, coment, numsilla) 
                               VALUES (" . $idpos . ", " . $rlt['idproducto'] . ", " . $rlt['cantidad'] . ", " . $rlt['valor'] . ", 
                               0.0, 0.0, (select iv.porcentaje from productos p inner join iva iv on p.codiva = iv.codiva WHERE p.idproducto =" . $rlt['idproducto'] . "), ((" . $rlt['valor'] . "-(" . $rlt['valor'] . "/(1+(select iv.porcentaje from productos p inner join iva iv on p.codiva = iv.codiva WHERE p.idproducto = " . $rlt['idproducto'] . ")/100)))*" . $rlt['cantidad'] . "), (select costo from productos where idproducto = " . $rlt['idproducto'] . "), NULL, ((" . $rlt['valor'] . "/(1+(select iv.porcentaje from productos p inner join iva iv on p.codiva = iv.codiva WHERE p.idproducto = " . $rlt['idproducto'] . ")/100))*" . $rlt['cantidad'] . "), 
                               0.0, NULL, NULL, NULL, NULL, 0.0, 0.0, 0.0, NULL, NULL)";

                        if ($sqldep = $this->db->query($SQ)) {
							echo '<br>';
                            echo 'EXITO!! detalle Factura';
                            echo '<br>';
							//$cantidad,$idproducto,$idpedido,$fecha
								$this->app_actualizarInventario_factura($rlt['cantidad'],$rlt['idproducto'],$idpos,$fecha);
                        } else {
                            echo 'Error!! detalle Factura' . $this->db->error;
                            echo '<br>';
                        }
                    } else {
                        //Si la cantidad  en inventario es mejor a la canridad pedida se ingresa el registro en la tabla  de pendientes
                        $sqlpendi = "Insert into pendientes (id_pedido,id_producto, cantidad_pendiente, tipo) values (" . $idpos . "," . $rlt['idproducto'] . "," . ($rlt['cantidad']) . ", 'F')";
                        if ($sqlpen = $this->db->query($sqlpendi)) {
                            echo 'Exito!! se guardo la entrada en la tabla pendientes';
                            echo '<br>';
                        }
                    }
                }
            }
        }
        echo 'Terminado el proceso de improtacion de el detalle pedido entr las tablas de app_detalle_pedido , detfactura y pendientes  si hay faltantes  en el inventario ';
        echo '<br>';
        echo '<hr></div></div>';
    }

    //Funciones adicionales en la sincronizacion del pedido
    private function app_actualizarInventario_factura($cantidad,$idproducto,$idpedido,$fecha) {
                $SQL = "UPDATE inventario set cantidad = (cantidad-" . $cantidad . "), cantpedida = (cantpedida+" . $cantidad . ") where idproducto = " . $idproducto;
                                // echo $SQL;
                                if ($this->db->query($SQL)) {
                                    echo 'Actualizando  el inventario para la factura: ' . $idpedido;
                                    echo '<br>';
									
									    //Actualizamos el kardex
										$Sqlkard = "INSERT INTO kardex  ( numdocumento, tipodoc, idproducto, idalmacen, detallemov, fechamov, cantidad, costo, tipomov, saldocant, ultcosto, saldoant, costoant, fechakardex, idsincronizacion, serialescruzados)
													VALUES ((select numero from  facturas where idfactura = " . $idpedido . "), concat('FACTURA ID ',(select numero from  facturas where idfactura = " . $idpedido . ")) , "
												. $idproducto . ", 1, concat( 'FACTURA ',(select numero from  facturas where idfactura = " . $idpedido . "),' DE ','" . str_replace('-', '', $fecha) . "'), '" . str_replace('-', '', $fecha) . "', " . $cantidad . ", (select costo from productos where idproducto = " . $idproducto . ") ,
													'-',(select cantidad from inventario where idproducto = " . $idproducto . "),  (select costo from productos where idproducto = " . $idproducto . "),
													((select cantidad from inventario where idproducto = " . $idproducto . ")+" . $cantidad . "),  (select costo from productos where idproducto = " . $idproducto . "), '" . str_replace('-', '', $fecha) . "', NULL, 0);";
										//echo $Sqlkard;
										//echo "<br>";
										if ($this->db->query($Sqlkard)) { //se comprueba si la consulta ejecuto correctamente
											echo 'Actualizando  el kardex para la factura: ' . $idpedido;
											echo '<br>';
										} else {
											echo "Error!!! al actualizar el kardex " . $this->db->error;
											echo '<br>';
										}
									
                                }
    
        echo 'Terminado la Actualizacion del inventario y Kardex';
        echo '<br><hr>';
       // echo '<hr></div></div>';
    }

    private function app_actualizarValorTotal_factura() {
        $sqls = "select * from app_pedido";
        if ($sqlres = $this->db->query($sqls)) {
            if ($sqlres->num_rows > 0) {
                while ($rtls = $sqlres->fetch_assoc()) { //recorremos los resultados de la tabla app_pedido                    
                    //ahora buscamos los  los detalles de los pedidos 
                    $SQL = "update facturas  set valortotal = (select  sum((valorprod*cantidad))  from detfacturas dd where dd.idfactura = " . $rtls['idpos'] . ") where idfactura = " . $rtls['idpos'];
                    //$SQL = "update pedidos  set valortotal = (select  sum((valorprod*cantidad)+dd.ivaprod)  from detpedidos dd where dd.idpedido = ". $rtls['idpos'] .") where idpedido = " . $rtls['idpos'];
                    //echo $SQL;
                    echo "<br>";
                    if ($sqlupdate = $this->db->query($SQL)) { //se comprueba si la consulta ejecuto correctamente
                        echo 'Actualizando  el valor total para  la factura' . $rtls['idpos'];
                        echo '<br>';
                    } else {
                        echo 'Error al actualizar el valor total' . $rtls['idpos'];
                        echo '<br>';
                        echo mysql_error($sqlupdate);
                    }
                }
            }
        }
        echo 'Terminado la Actualizacion del valorTotal de factura';
        echo '<br>';
        echo '<hr>';
    }

    private function app_valImpuesto_factura() {
        $sqls = "select * from app_pedido";
        if ($sqlres = $this->db->query($sqls)) {
            if ($sqlres->num_rows > 0) {
                while ($rtls = $sqlres->fetch_assoc()) { //recorremos los resultados de la tabla app_pedido                    
                    //ahora buscamos los  los detalles de los pedidos 
                    $SQL = "update facturas  set valimpuesto = (select sum(dd.ivaprod) from detfacturas dd where dd.idfactura = " . $rtls['idpos'] . ") , valiva = (select sum(dd.ivaprod) from detfacturas dd where dd.idfactura = " . $rtls['idpos'] . ") 
                                            where idfactura =" . $rtls['idpos'];
                    if ($sqlupdate = $this->db->query($SQL)) { //se comprueba si la consulta ejecuto correctamente
                        echo 'Actualizando  el ValImpuesto para la Factura' . $rtls['idpos'];
                        echo '<br>';
                    }
                }
            }
        }

        echo 'Terminado la Actualizacion del Valimpuesto para la Factura';
        echo '<br>';
        echo '<hr>';
    }

    private function app_subtotal_factura() {
        $sqls = "select * from app_pedido";
        if ($sqlres = $this->db->query($sqls)) {
            if ($sqlres->num_rows > 0) {
                while ($rtls = $sqlres->fetch_assoc()) { //recorremos los resultados de la tabla app_pedido                    
                    //ahora buscamos los  los detalles de los pedidos 
                    $SQL = "select (valortotal- valimpuesto) as valor  from facturas where idfactura = " . $rtls['idpos'];
                    // $SQL = "update pedidos SET subtotal = (select (valortotal- valimpuesto) from facturas where idpedido = ".$rtls['idpos'].") WHERE idpedido = " . $rtls['idpos'];
                    //echo $SQL;
                    if ($sqlupdate = $this->db->query($SQL)) { //se comprueba si la consulta ejecuto correctamente
                        if ($sqlupdate->num_rows > 0) {
                            while ($rtls2 = $sqlupdate->fetch_assoc()) {
                                $SQL2 = "update facturas SET subtotal =" . $rtls2['valor'] . " WHERE idfactura = " . $rtls['idpos'];

                                if ($sqlUpdate2 = $this->db->query($SQL2)) {
                                    echo 'Actualizando  el  Subtotal para la Factura' . $rtls['idpos'];
                                    echo '<br>';
                                    //echo $SQL2;
                                }
                            }
                        }
                    } else {
                        echo 'Error al actualizar el subtotal' . $rtls['idpos'];
                        echo '<br>';
                        echo mysql_error($sqlupdate);
                    }
                }
            }
        }

        echo 'Terminado la Actualizacion del Subtotal de la Factura';
        echo '<br>';
        echo '<hr>';
    }

    private function app_actualizar_kardex_factura() {
        $sqls = "select * from app_pedido";
        if ($sqlres = $this->db->query($sqls)) {
            if ($sqlres->num_rows > 0) {
                while ($rtls = $sqlres->fetch_assoc()) { //recorremos los resultados de la tabla app_pedido                    
                    //ahora buscamos los  los detalles de los pedidos 
                    $sql = "select  * from detfacturas where idfactura = " . $rtls['idpos'] . ' and idproducto not in (select id_producto from pendientes  where id_pedido = ' . $rtls['idpos'] . ' and  tipo = \'F\' )';
                    if ($sqlResult = $this->db->query($sql)) {
                        if ($sqlResult->num_rows > 0) {
                            while ($rlt = $sqlResult->fetch_assoc()) {
                                //Actualizamos el kardex
                                $Sqlkard = "INSERT INTO kardex  ( numdocumento, tipodoc, idproducto, idalmacen, detallemov, fechamov, cantidad, costo, tipomov, saldocant, ultcosto, saldoant, costoant, fechakardex, idsincronizacion, serialescruzados)
                                            VALUES ((select numero from  facturas where idfactura = " . $rtls['idpos'] . "), concat('FACTURA ID ',(select numero from  facturas where idfactura = " . $rtls['idpos'] . ")) , "
                                        . $rlt['idproducto'] . ", 1, concat( 'FACTURA ',(select numero from  facturas where idfactura = " . $rtls['idpos'] . "),' DE ','" . str_replace('-', '', $rtls['fecha']) . "'), '" . str_replace('-', '', $rtls['fecha']) . "', " . $rlt['cantidad'] . ", (select costo from productos where idproducto = " . $rlt['idproducto'] . ") ,
                                            '-',(select cantidad from inventario where idproducto = " . $rlt['idproducto'] . "),  (select costo from productos where idproducto = " . $rlt['idproducto'] . "),
                                            ((select cantidad from inventario where idproducto = " . $rlt['idproducto'] . ")+" . $rlt['cantidad'] . "),  (select costo from productos where idproducto = " . $rlt['idproducto'] . "), '" . str_replace('-', '', $rtls['fecha']) . "', NULL, 0);";
                                //echo $Sqlkard;
                                //echo "<br>";
                                if ($this->db->query($Sqlkard)) { //se comprueba si la consulta ejecuto correctamente
                                    echo 'Actualizando  el kardex para la factura: ' . $rtls['idpos'];
                                    echo '<br>';
                                } else {
                                    echo "Error!!! al actualizar el kardex " . $this->db->error;
                                    echo '<br>';
                                }
                            }
                        }
                    } else {
                        echo "Error!!! consulta detFacturas " . $this->db->error;
                        echo '<br>';
                    }
                }
            }
        }
        echo 'Terminado  el registro de el kardex';
        echo '<br>';
        echo '<hr>';
    }

    /*
     * ***************************************************
     * FIN de  los metodos de sincronizacion de Factura
     * ***************************************************
     */

    private function actualizarMetas() {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $type = json_decode($this->_request['datos']);
        if (!empty($type)) {
            $SQL = "";
            $msg = "";
            foreach ($type as $value) {
                $SQL = "update MetasDiarias set cantidadvendida = " . $value->cantidadvendida . " where id = " . $value->id;
                if ($sql = $this->db->query($SQL)) {
                    $msg = "meta Actualizada";
                } else {
                    $msg = "meta no guardada" . $this->db->error;
                }
            }
            $success = array('status' => "susses", 'msg' => $msg);
            $this->response($this->json($success), 200);
            $this->response('', 204);
        } else {

            $this->response('', 204);
        }
    }

    private function limpiarTablasincronizacion() {
        $SQL = "delete from app_pedido";
        $SQl_detalle = "delete from  app_producto_pedido";
        if ($this->db->query($SQL)) {
            if ($this->db->query($SQl_detalle)) {
                echo 'Tablas temporales borradas satisfactoriamente ';
                echo '<br>';
            }
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

#initialice Library
$api = new API;
$api->processApi();
?>