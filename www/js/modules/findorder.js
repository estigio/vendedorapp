/*
*@Developed by Walther smith franco otero
*@twitter: @walthersmith
*@licenced: Creative commons
*/

//Global variables
var db ;
var shorname ='quarxoDb';
var version = '1.0';
var displayName = 'Quarxodb';
var maxSize = 42000000;

/*$(function  () {
    db = window.openDatabase(shorname, version, displayName, maxSize);
    getPedidos();
});
 */
function onDeviceReady(){
	db = window.openDatabase(shorname, version, displayName, maxSize);
	getPedidos();
}



//Funcion consultar pedidos
function getPedidos(){
	db.transaction(getPedidosSQL, Error);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           

}

function getPedidosSQL(tx){
	var sql="select (c.name ||' '||c.lastname1 ||' '|| c.lastname2) as nombre, p.fecha, p.estado, p.idcliente, p.valor_pedido, p.id"+
			" from client c join pedido p on c.id = p.idcliente"+
            " where p.estado = 1";
		 
	tx.executeSql(sql, [], cargarPedidos, Error);
}


function getporcentajeRuta(){
    var cantidadClientes =0;
    db.transaction(function(tx){
        var SQL = "SELECT count(*) as cantidad FROM CLIENT WHERE comentario LIKE '%" + diaFormatoFiltro() + "%'";
        tx.executeSql(SQL,[],function(tx,results){
            if(results.rows.length>0){
                for (var i = 0; i < results.rows.length ; i++) {
                    cantidadClientes = results.rows.item(i).cantidad;
                }
                var cantidadVendida = 0;
              cantidadVendida =  $("#cantidadPedidos").html();
              $("#progressContador").html(cantidadVendida.replace("Pedidos: ","")+"/"+cantidadClientes+"<br><progress max=\""+cantidadClientes+"\"  value=\""+cantidadVendida.replace("Pedidos: ","")+"\"></progress>");
            }
        },function(err){
            console.log("Error al ejecutar la consulta "+err.message)
        });
    },function(err){
        console.log("error  en la transaccion de ger porcentaje ruta")
    });
}

function cargarPedidos (tx, results) {
		var len = results.rows.length;
		 
        var lol = "";
        var pop = "";
        var total = 0;

        if (len <= 0) {
        	lol = '<li><h2>No hay registros de pedidos</h2></li>';
				    $("#listaPedidos").html(lol);
        			$("#listaPedidos").listview("refresh");
        } else{
			
        	 for (var i=0; i<len; i++){
        	lol += '<li><a href="findOrderDetails.html?id_pedido='+results.rows.item(i).id+'" rel="external" data-ajax="false"><h2>Cliente: '+results.rows.item(i).nombre+'</h2> <p> <strong>Total: </strong>'+formatNumber(results.rows.item(i).valor_pedido,"$")+' | Fecha pedido: '+convertDate(results.rows.item(i).fecha)+'</p></a></li>';
       		   total+=results.rows.item(i).valor_pedido;
            }
            var men = "<h2 id=\"cantidadPedidos\">Pedidos: "+len+"</h2> <h2> Valor Pedidos: "+formatNumber(total)+"</h2>";

            $("#totalInfo").html(men);
	        $("#listaPedidos").html(lol);
	        $("#listaPedidos").listview("refresh"); 
                getporcentajeRuta();
        }			   
			
}


function Error(err){
	alert("Error en la operación: "+err.message+" Code: #"+err.code);
	// Handle errors here
    var we_think_this_error_is_fatal = true;
    if (we_think_this_error_is_fatal) return true;
    return false;
}

function convertDate(inputFormat) {
  var d = new Date(inputFormat);
  return [d.getDate(), d.getMonth()+1, d.getFullYear()].join('/');
}


function formatNumber(num,prefix){
    num = Math.round(parseFloat(num)*Math.pow(10,2))/Math.pow(10,2)
    prefix = prefix || '';
    num += '';
    var splitStr = num.split('.');
    var splitLeft = splitStr[0];
    var splitRight = splitStr.length > 1 ? '.' + splitStr[1] : '.00';
    splitRight = splitRight + '00';
    splitRight = splitRight.substr(0,3);
    var regx = /(\d+)(\d{3})/;
    while (regx.test(splitLeft)) {
        splitLeft = splitLeft.replace(regx, '$1' + ',' + '$2');
    }
    return prefix + splitLeft + splitRight;
}

function diaFormatoFiltro() {
    var diasSemana = new Array("DOMINGO", "LUNES", "MARTES", "MIERCOLES", "JUEVES", "VIERNES", "SABADO");
    var f = new Date();
    var queryform = localStorage.zona + "-" + diasSemana[f.getDay()];
    return queryform;
}

