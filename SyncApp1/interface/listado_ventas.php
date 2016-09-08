<?php
require_once 'header.php';
require_once './Php/Clases/Connection/Connection.class.php';
require_once './Php/Clases/Listaventas.php';
require_once './Php/Clases/Tercero.php';
require_once './Php/Clases/Producto.php';
require_once './Php/Clases/Proveedores.php';


$tercero = new Tercero();
$productos = new Producto();
$proveedores = new Proveedores();
$proveedor =$lineas=$producto=$vendedor=0;
date_default_timezone_set('America/Bogota');
$fini=$ffin=date('Ymd');

$ventas = new Listaventas();
$fecha = date("Y-m-d"); 
$fechaFinal =  date("Y-m-d"); 
$nombre  = "";
if($_GET){
	$proveedor = $_GET['proveedor'];
	$lineas = $_GET['lineas'];
	
	$producto = $_GET['producto'];
	$vendedor = $_GET['vendedor'];
	$fini = $_GET['fini'];
	$ffin = $_GET['ffin'];
}
//echo $ventas->VentaList('','','','');
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
	<link href="css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
	<!-- Bootstrap -->
	<link href="css/jquery-ui.css" rel="stylesheet" type="text/css"/>
	<link href="css/jquery-ui.structure.min.css" rel="stylesheet" type="text/css"/>
	<link href="css/jquery-ui.theme.min.css" rel="stylesheet" type="text/css"/>
	<link href="css/bootstrap.min.css" rel="stylesheet">
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
      	<div class="panel panel-default">
      		<div class="panel-heading text-center"><h2>Informe de Ventas</h2></div>
      		<div class="panel-body text-center">
      			<div class="form-group">
      				PROVEEDOR:<select class="form-control" id="comboProveedores"></select>

      				LINEAS:<select class="form-control" id="comboLineas"></select>

      				PRODUCTO:<select class="form-control" id="comboProducto"></select>

      				VENDEDOR:<select class="form-control" id="comboVendedor"></select>
      			</div>
      			<div class="form-group">
      				<label for="txtCantidad">Fecha Inicial</label>             
      				<input type="text" id="fini" name="fini" class="form-control" value="<?php echo $fini; ?>">   
      			</div>
      			<div class="form-group">
      				<label for="txtCantidad">Fecha Final</label>             
      				<input type="text" id="ffin" name="ffin" class="form-control" value="<?php echo $ffin; ?>">   
      			</div>
      			<button class="btn btn-info " id="btnfiltrar">Filtrar</button>
      			<hr>
      			<table id="tablaCliente" class="display" cellspacing="0" width="100%">
      				<thead>
      					<tr>
      						<th># Factura</th>
      						<th>Fecha</th>
      						<th>Proveedor</th>
      						<th>Lineas</th>
      						<th>Cod Producto</th>  
      						<th>Producto</th>  
      						<th>Fact Prod Valor</th>  
      						<th>Vendedor</th>
      						<th>Valor</th>
      						<th>Tipo</th>
      					</tr>
      				</thead>
      				<tbody id="bodytable"></tbody>
      			</table>                 
      		</div>
      	</div>
      </div>
      <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
      <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
      <script src="js/jquery-2.1.0.min.js" type="text/javascript"></script>
      <!-- Include all compiled plugins (below), or include individual files as needed -->
      <script src="js/jquery-ui.js" type="text/javascript"></script>
      <script src="js/bootstrap.min.js"></script>
      <script src="js/jquery.dataTables.min.js" type="text/javascript"></script>

      <script>
      	$(document).ready(function () {

      		getvendedorList();  
      		getproductoList();  
      		getproveedorList();  
      		getlineasList();  
      		function getdatatable() {
      			var datos =<?php $ventas->VentaList($proveedor,$lineas,$producto,$vendedor,$fini,$ffin);?>;
      			var tableDatos = "";
      			$.each(datos, function (i, item) {
                       // alert(item.cantidadvendida +" | "+ item.cantidadMeta);
                       tableDatos += '<tr>' +
                       '<td>'+item.Fact_num+'</td>' +
                       '<td>'+item.Fact_Fecha+'</td>' +
                       '<td>'+item.Proveedor_Nombre+'</td>' +
                       '<td>'+item.Nivel_nombre+'</td>' +
                       '<td>'+item.Producto_Codigo+'</td>' +
                       '<td>'+item.Producto_Descripcion+'</td>' +
                       '<td>'+item.Fact_Producto_valor+'</td>' +
                       '<td>'+item.Vendedor_Nombres+'</td>' +
                       '<td>'+item.Fact_ValorTotal+'</td>' +
                       '<td>'+item.origen+'</td>';

                       tableDatos +=  '</tr>';

                   });
      			$("#bodytable").html(tableDatos);
      		}

      		function getvendedorList(){

      			var datos = <?php $tercero->vendedor() ?>;
      			var comboDatos = "<option value='0'>Seleccione...</option>";
      			$.each(datos, function (i, item) {
      				if(item.idtercero==<?php echo$vendedor ?>){
      					comboDatos+="<option selected value=\""+item.idtercero+"\">"+item.nombres+"</option>";
      				}else{
      					comboDatos+="<option value=\""+item.idtercero+"\">"+item.nombres+"</option>";

      				}

      			});
      			$("#comboVendedor").html(comboDatos);
      		}
      		function getproductoList(){

      			var datos = <?php $productos->productosList() ?>;
      			var comboDatos = "<option value='0'>Seleccione...</option>";
      			$.each(datos, function (i, item) {
      				if(item.idproducto==<?php echo$producto ?>){
      					comboDatos+="<option selected value=\""+item.idproducto+"\">"+item.descripcion+"</option>";
      				}else{
      					comboDatos+="<option value=\""+item.idproducto+"\">"+item.descripcion+"</option>";
      				}
      			});
      			$("#comboProducto").html(comboDatos);
      		}
      		function getproveedorList(){

      			var datos = <?php $proveedores->proveedoresList() ?>;
      			var comboDatos = "<option value='0'>Seleccione...</option>";
      			$.each(datos, function (i, item) {
      				if(item.idregistro==<?php echo$proveedor ?>){
      					comboDatos+="<option selected value=\""+item.idregistro+"\">"+item.nombre+"</option>";
      				}else{
      					comboDatos+="<option value=\""+item.idregistro+"\">"+item.nombre+"</option>";
      				}
      			});
      			$("#comboProveedores").html(comboDatos);
      		}
      		function getlineasList(){

      			var datos = <?php $proveedores->lineasList() ?>;
      			var comboDatos = "<option value='0'>Seleccione...</option>";
      			$.each(datos, function (i, item) {
      				if(item.idregistro==<?php echo$lineas ?>){
      					comboDatos+="<option selected value=\""+item.idregistro+"\">"+item.nombre+"</option>";
      				}else{
      					comboDatos+="<option value=\""+item.idregistro+"\">"+item.nombre+"</option>";
      				}
      			});
      			$("#comboLineas").html(comboDatos);
      		}
      		$("#btnfiltrar").on('click',function(){                   
      			window.location.href="listado_ventas.php?vendedor="+$("#comboVendedor").val()+"&proveedor="+$("#comboProveedores").val()+"&producto="+ $("#comboProducto").val()+"&lineas="+$("#comboLineas").val()+"&fini="+$("#fini").val()+"&ffin="+$("#ffin").val();                   

      		}); 
      		getdatatable();
      		$('#tablaCliente').DataTable();
      		$.datepicker.setDefaults($.datepicker.regional[ "es" ]);
      		$("#fini").datepicker({
      			dateFormat: "yymmdd",
      			changeMonth: true,
      			changeYear: true,
      			inline: true,
      			firstDay: 1,              
      			appendText: "(yyyymmdd)",
      			altField: "#actualDate",
      			onClose: function( selectedDate ) {
      				$( "#ffin" ).datepicker( "option", "minDate", selectedDate );
      			}
      		});
      		$("#ffin").datepicker({
      			dateFormat: "yymmdd",
      			changeMonth: true,
      			changeYear: true,
      			inline: true,
      			firstDay: 1,              
      			appendText: "(yyyymmdd)",
      			altField: "#actualDate",
      			onClose: function( selectedDate ) {
      				$( "#fini" ).datepicker( "option", "maxDate", selectedDate );
      			}
      		});
      	});
</script>
</body>
</html>