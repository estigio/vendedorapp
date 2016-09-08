/*
*this file will  used for  manage the product information 
*@Developed by Walther smith franco otero
*twitter: @walthersmith
*/

//Global variables
var db ;
var shorname ='quarxoDb';
var version = '1.0';
var displayName = 'Quarxodb';
var maxSize = 42000000;



function onDeviceReady(){
	db = window.openDatabase(shorname, version, displayName, maxSize);
}

function crearPedido(){	
	
	db.transaction(pedido,Error,success);
}

function pedido(tx) {
		var sql="insert into pedido (idcliente,fecha,idvendedor,version,valor_pedido)"+
				"values(null,(DATETIME('now')),0,0,0)";
		tx.executeSql(sql);
	}

function Error(err){
	console.log("Error al realizar la Acción: "+err.message);
}

function success(){
	console.log("La acción se ha realizado satisfactoriamente.");
	 window.location.href="addOrder.html";
}

