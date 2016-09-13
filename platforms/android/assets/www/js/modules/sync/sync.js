/*
*@Developed by Walther smith franco otero
*twitter: @walthersmith
*@licence: Creative commons
*/
var db ;
var shorname ='quarxoDb';
var version = '1.0';
var displayName = 'Quarxodb';
var maxSize = 42000000;




  
var ServiceAPI="";
if (localStorage.hostData) {
	ServiceAPI = localStorage.hostData;
	 
}

db = window.openDatabase(shorname, version, displayName, maxSize);
function onDeviceReady(){
	db = window.openDatabase(shorname, version, displayName, maxSize);
}


function resartTble(){
	db.transaction(function (tx){
		var pedido ="CREATE TABLE IF NOT EXISTS pedido ("+
		"id INTEGER PRIMARY KEY AUTOINCREMENT,"+
		"idcliente  INTEGER,"+
		"fecha date,"+
		"idvendedor INTEGER,"+
		"descripcion text,"+
		"version INTEGER,"+
		"valor_pedido real,"+
		" estado INTEGER )";
	
	var prod_pedido="CREATE TABLE IF NOT EXISTS producto_pedidos("+
		"id INTEGER PRIMARY KEY AUTOINCREMENT,"+
		"idpedido integer,"+
		"idproducto integer,"+
		"cantidad real,"+
		"valor real )";
		//tx.executeSql("DELETE FROM pedido");
		//tx.executeSql("DELETE FROM producto_pedidos");
		tx.executeSql("DROP TABLE IF EXISTS pedido");
		tx.executeSql("DROP TABLE IF EXISTS producto_pedidos");
		tx.executeSql(pedido);
		tx.executeSql(prod_pedido);
	},Error,function(){
		console.log("tablas creadas y reinicidas de pedidos");
	});
}


function sync_productos(){
	$.ajax({
		url: ServiceAPI+"productos",
		async: true,
		format:"jsonp",
		crossDomain: true
	}).done( function (data) {			
		db.transaction(function (tx){
		$.each( data, function( i, item ){
		  		var sql="INSERT INTO producto (idproducto,codigo, descripcion, nommedida, precioventa, precioespecial1, precioespecial2,cantidad,nombreprove,idregistro) values ("+item.idproducto+",'"+item.codigo+"','"+item.descripcion+"','"+item.nommedida+"',"+item.precioventa+","+item.precioespecial1+","+item.precioespecial2+","+item.cantidad+",'"+item.nombre+"',"+item.idregistro+")";
				//alert(sql);
				tx.executeSql(sql);
			});
		},Error,function(){
			alert("importados los registros  de productos");
		});		
		
	}).fail(function( jqxhr, textStatus, error ) {
		var err = textStatus + ", " + error.message;
		alert( "Error en la sincronización: " + err.message );
	});
}

function sync_Tercero(){
var diasSemana = new Array("DOMINGO","LUNES","MARTES","MIERCOLES","JUEVES","VIERNES","SABADO");
var f=new Date();
var queryform = localStorage.zona+"-";//+diasSemana[f.getDay()];
	 
	$.ajax({
		url: ServiceAPI+"tercero?comment="+queryform,		
		async: true,
		format:"jsonp",
		crossDomain: true
	}).done( function (data) {			 	
		db.transaction(function (tx){			 
		$.each( data, function( i, item ){
				var sql="INSERT INTO CLIENT(id,name,lastname1,lastname2,identification,address,phone,email,tipoprecio,new,comentario,estado,barrio) values("+item.idtercero+",'"+item.nombres+"','"+item.apellidos+"','"+item.apellido2+"','"+item.nit+"','"+item.direccion+"','"+item.telefono+"','"+item.email+"',"+item.tipoprecio+",0,'"+item.comentario+"','S','"+item.barrio+"')";
				 
				tx.executeSql(sql);
			});
		},Error,function(){
			alert("Importados los registros de  Clientes");
		});		
		
	}).fail(function( jqxhr, textStatus, error ) {
		var err = textStatus + ", " + error.message;
		alert( "Error en la sincronización: " + err.message );
	});
}

function sync_vendedor(){
	$.ajax({
		url: ServiceAPI+"vendedor",
		async: true,
		format:"jsonp",
		crossDomain: true
	}).done( function (data) {			
		db.transaction(function (tx){
		$.each( data, function( i, item ){
				var sql="INSERT INTO vendedor(id,nombres,nit,email,zona) values("+item.idtercero+",'"+item.nombres+"','"+item.nit.trim()+"','"+item.email+"','"+item.comentario+"')";
				//alert(sql);
				tx.executeSql(sql);
			});
		},Error,function(){	alert("Importados los registros de  vendedores");
		});		
		
	}).fail(function( jqxhr, textStatus, error ) {
		var err = textStatus + ", " + error.message;
		alert( "Error en la sincronización: " + err.message );
	});
}

function sync_transportador(){
	$.ajax({
		url: ServiceAPI+"transportador",
		async: true,
		format:"jsonp",
		crossDomain: true
	}).done( function (data) {			
		db.transaction(function (tx){
		$.each( data, function( i, item ){
				var sql="INSERT INTO transportador(id,nombres,nit) values("+item.idtercero+",'"+item.nombres+"','"+item.nit.trim()+"')";
				//alert(sql);
				tx.executeSql(sql);
			});
		},Error,function(){	alert("Importados los registros de  transportadores");
		});		
		
	}).fail(function( jqxhr, textStatus, error ) {
		var err = textStatus + ", " + error.message;
		alert( "Error en la sincronización: " + err.message );
	});
}

function sync_transportador(){
	$.ajax({
		url: ServiceAPI+"transportador",
		async: true,
		format:"jsonp",
		crossDomain: true
	}).done( function (data) {			
		db.transaction(function (tx){
		$.each( data, function( i, item ){
				var sql="INSERT INTO transportador(id,nombres,nit) values("+item.idtercero+",'"+item.nombres+"','"+item.nit.trim()+"')";
				//alert(sql);
				tx.executeSql(sql);
			});
		},Error,function(){	alert("Importados los registros de  transportadores");
		});		
		
	}).fail(function( jqxhr, textStatus, error ) {
		var err = textStatus + ", " + error.message;
		alert( "Error en la sincronización: " + err.message );
	});
}

function sync_MetasDiarias(fecha){
    console.log(ServiceAPI+"metasDiarias?idvendedor="+localStorage.vendedor);
	$.ajax({
		url: ServiceAPI+"metasDiarias?idvendedor="+localStorage.vendedor+"&fecha="+fecha,
		async: true,
		format:"jsonp",
		crossDomain: true
	}).done( function (data) {			
		db.transaction(function (tx){
                  if(data){
                        $.each( data, function( i, item ){

                    var sql="INSERT INTO metasdiarias (id, idproducto, idvendedor, cantidadMeta, cantidadvendida,  fecha) values("+item.id+","+item.idproducto+", "+item.idvendedor+", "+item.cantidadmeta+", "+item.cantidadvendida+",'"+item.fecha+"');";
                            tx.executeSql(sql);
                        });
                         alert("Importados los registros de  Metas Diarias");
                  }else{
                      alert("No Hay metas Diarias Asignadas");
                     
                  }
		},function(err){
                    console.log("Error en metas: "+err.message);
                },function(){
                 console.log("Importados los registros de  Metas Diarias");
		});		
		
	}).fail(function( jqxhr, textStatus, error ) {
		var err = textStatus + ", " + error.message;
		alert( "Error en la sincronización de metas: " + err.message );
	});
}

function Error(err){
	console.log("Error: "+err.message);
}

function susses(){
	console.log("Exito en la operación");
}

function createDatabase(){
	 var db = window.openDatabase(shorname, version, displayName, maxSize);
	db.transaction(function (tx){
		    var client = "CREATE TABLE IF NOT EXISTS CLIENT("+
    	"id INTEGER PRIMARY KEY AUTOINCREMENT,"+
    	"name text,"+
    	"lastname1 text,"+
    	"lastname2 text,"+
    	"identification text,"+
    	"address text,"+
    	"phone text,"+
    	"email text,"+
    	"tipoprecio integer,"+    
    	"new integer,"+
    	"comentario text,"+
        "estado text,"+
		"barrio text)";
	
	var products = "CREATE TABLE IF NOT EXISTS producto("+
		"idproducto INTEGER PRIMARY KEY AUTOINCREMENT,"+
		"codigo text,"+
		"descripcion text,"+
		"nommedida text,"+
		"precioventa real,"+
		"precioespecial1 real,"+
		"precioespecial2 real,"+
                "cantidad real,"+
		"nombreprove Text,"+
		"idregistro INTEGER)";
	
	var pedido ="CREATE TABLE IF NOT EXISTS pedido ("+
		"id INTEGER PRIMARY KEY AUTOINCREMENT,"+
		"idcliente  INTEGER,"+
		"fecha date,"+
		"idvendedor INTEGER,"+
		"descripcion text,"+
		"version INTEGER,"+
		"valor_pedido real ,"+
		" estado INTEGER )";
	
	var prod_pedido="CREATE TABLE IF NOT EXISTS producto_pedidos("+
		"id INTEGER PRIMARY KEY AUTOINCREMENT,"+
		"idpedido integer,"+
		"idproducto integer,"+
		"cantidad real,"+
		"valor real )";

	var vendedor = "CREATE TABLE IF NOT EXISTS vendedor("+
			"id INTEGER PRIMARY KEY AUTOINCREMENT, "+
			"nombres text,"+
			"nit text,"+
			"email text,"+
			"zona text)";
	
	var  prodPreciosCant = "CREATE TABLE prodprecioscant ("+
			"id INTEGER PRIMARY KEY AUTOINCREMENT,"+
			"idproducto INTEGER, "+
			"cantidad real, "+
			"precio real)";

	var transportador = "CREATE TABLE IF NOT EXISTS transportador("+
				"id INTEGER PRIMARY KEY AUTOINCREMENT, "+
				"nombres text,"+
				"nit text);";
        
        var metasDiarias = "CREATE TABLE IF NOT EXISTS metasdiarias ("+
                "id INTEGER PRIMARY KEY AUTOINCREMENT,"+
                "idproducto  integer,"+
                "idvendedor integer,"+
                "cantidadMeta real,"+
                "cantidadvendida real,"+
                "fecha text);";

	tx.executeSql('DROP TABLE IF EXISTS producto');
	tx.executeSql("DROP TABLE IF EXISTS CLIENT");
	tx.executeSql("DROP TABLE IF EXISTS vendedor");
	tx.executeSql("DROP TABLE IF EXISTS prodprecioscant");
	tx.executeSql("DROP TABLE IF EXISTS transportador");
        tx.executeSql("DROP TABLE IF EXISTS metasdiarias");
	tx.executeSql(products);
	tx.executeSql(client);
	tx.executeSql(pedido);
	tx.executeSql(prod_pedido);
	tx.executeSql(vendedor);
	tx.executeSql(transportador);
	tx.executeSql(prodPreciosCant);
        tx.executeSql(metasDiarias);

			
	},function(err){
            console.log("Error: "+err.message);
        },function(){
		console.log("tablas creadas y reinicidas");
	});
}
function reset(){
	 var db = window.openDatabase(shorname, version, displayName, maxSize);
	db.transaction(function (tx){
		    var client = "CREATE TABLE IF NOT EXISTS CLIENT("+
    	"id INTEGER PRIMARY KEY AUTOINCREMENT,"+
    	"name text,"+
    	"lastname1 text,"+
    	"lastname2 text,"+
    	"identification text,"+
    	"address text,"+
    	"phone text,"+
    	"email text,"+
    	"tipoprecio integer,"+    
    	"new integer,"+
    	"comentario text,"+
        "estado text,"+
		"barrio text)";
	
	var products = "CREATE TABLE IF NOT EXISTS producto("+
		"idproducto INTEGER PRIMARY KEY AUTOINCREMENT,"+
		"codigo text,"+
		"descripcion text,"+
		"nommedida text,"+
		"precioventa real,"+
		"precioespecial1 real,"+
		"precioespecial2 real,"+
        "cantidad real,"+
		"nombreprove Text,"+
		"idregistro INTEGER)";
	
	var pedido ="CREATE TABLE IF NOT EXISTS pedido ("+
		"id INTEGER PRIMARY KEY AUTOINCREMENT,"+
		"idcliente  INTEGER,"+
		"fecha date,"+
		"idvendedor INTEGER,"+
		"descripcion text,"+
		"version INTEGER,"+
		"valor_pedido real,"+
		" estado INTEGER )";
	
	var prod_pedido="CREATE TABLE IF NOT EXISTS producto_pedidos("+
		"id INTEGER PRIMARY KEY AUTOINCREMENT,"+
		"idpedido integer,"+
		"idproducto integer,"+
		"cantidad real,"+
		"valor real)";

	var vendedor = "CREATE TABLE IF NOT EXISTS vendedor("+
			"id INTEGER PRIMARY KEY AUTOINCREMENT, "+
			"nombres text,"+
			"nit text,"+
			"email text,"+
			"zona text)";

	var transportador = "CREATE TABLE IF NOT EXISTS transportador("+
				"id INTEGER PRIMARY KEY AUTOINCREMENT, "+
				"nombres text,"+
				"nit text);";
         
        var metasDiarias = "CREATE TABLE IF NOT EXISTS metasdiarias ("+
                "id INTEGER PRIMARY KEY AUTOINCREMENT,"+
                "idproducto  integer,"+
                "idvendedor integer,"+
                "cantidadMeta real,"+
                "cantidadvendida real,"+
                "fecha TEXT);";

	tx.executeSql('DROP TABLE IF EXISTS producto');
	tx.executeSql("DROP TABLE IF EXISTS CLIENT");
	tx.executeSql("DROP TABLE IF EXISTS vendedor");
	tx.executeSql("DROP TABLE IF EXISTS pedido");
	tx.executeSql("DROP TABLE IF EXISTS producto_pedidos");
	tx.executeSql("DROP TABLE IF EXISTS transportador");
        tx.executeSql("DROP TABLE IF EXISTS metasdiarias");
        
	tx.executeSql(products);
	tx.executeSql(client);
	tx.executeSql(pedido);
	tx.executeSql(prod_pedido);
	tx.executeSql(vendedor);
	tx.executeSql(transportador);
        tx.executeSql(metasDiarias);
			
	},Error,function(){
		alert("tablas creadas y reinicidas");
	});
}

function deleteOrder(id_order){
		db.transaction(function (tx){
			var sql= "DELETE FROM producto_pedidos WHERE idpedido = "+ id_order;
			tx.executeSql(sql);
		},Error,function(){
			db.transaction(function (tx){
				var sq = "DELETE FROM pedido where id = "+id_order;
				tx.executeSql(sq);
			}, Error,function(){
				console.log("El pedido se ha borrado exitosamente");
			});
		});
	
}

function send_Client(){
	var data =  new Array();
	db.transaction(function (tx){
		tx.executeSql("select * from client where new = 1",[],function (tx, results){
			var len = results.rows.length;
			  for (var i=0; i<len; i++){
			  		data[i] = {'id':results.rows.item(i).id,'name':results.rows.item(i).name,'lastname1':results.rows.item(i).lastname1,'lastname2':results.rows.item(i).lastname2,'identification':results.rows.item(i).identification,'address':results.rows.item(i).address, 'phone':results.rows.item(i).phone,'email':results.rows.item(i).email,'tipoprecio':results.rows.item(i).tipoprecio,"idvendedor":results.rows.item(i).idvendedor};

			  }
			 // alert(JSON.stringify(data));
			 //alert("tamaño del array de clientes: "+data.length);
			  if (data.length >0) {
			  	$.ajax({
			  		url: ServiceAPI+"guardarClientes",
			  		type: 'post',
			  		data: {"datos":JSON.stringify(data)},
			  		async: true,
					format:"jsonp",
				 	crossDomain: true		  		
			  	}).done( function (data) {
			  		if(data.msg == "Clientes Guardados"){
			  			alert(data.msg);
			  			updateClient();
			  			formatjson_pedido();
			  		}else{
			  			alert(data.msg);
			  		}
			  		

			  	}).fail(function( jqxhr, textStatus, error ) {
					var err = textStatus + ", " + error.code;
					alert( "Request Failed: " + err );
				});
			  }else{
			  	alert("No hay Clientes para sincronizar.");
			  	formatjson_pedido();
			  }	

		},function(err){
			console.log(err.message);
		});
	},function(err){
		alert("Error al sinronizar los clientes : "+err.message);
		formatjson_pedidoClientesAntiguos();
	},function(){
		//formatjson_pedido();
	}); 
}

function updateClient(){

	db.transaction(function (tx){
		tx.executeSql("update client set new =0 where new = 1");
	},Error,function(){
		console.log("contactos Modificados");
	});
}



function formatjson_pedidoClientesAntiguos(){
	var data =  new Array();
	sessionStorage.data= "";
	sessionStorage.data2 = "";
	db.transaction(function (tx){
		tx.executeSql("select  p.*, c.identification from pedido p join CLIENT c on c.id = p.idcliente and c.new != 1",[],
			function (tx, results){
			var len = results.rows.length;
			  for (var i=0; i<len; i++){
			  	//alert(results.rows.item(i).identification);
			  	data[i] = {'id':results.rows.item(i).id,'idcliente':results.rows.item(i).idcliente,'fecha':results.rows.item(i).fecha.replace("-","").replace("-","").split(" ")[0],
			  	'idvendedor':localStorage.vendedor,'descripcion':results.rows.item(i).descripcion,'version':results.rows.item(i).version,'valor_pedido':results.rows.item(i).valor_pedido,
			  	 'estado':results.rows.item(i).estado,'identification':results.rows.item(i).identification,'prod_pedido':"[]"};
			  }
			   //alert(JSON.stringify(data));
			  localStorage.data = JSON.stringify(data);
			  alert(localStorage.data+"Hola");
			  $.each( data, function( i, item ){			  	
			  		var sql = "select pp.* from producto_pedidos pp join pedido p on p.id = pp.idpedido join producto pr on pr.idproducto = pp.idproducto  "+
						  "where  p.id = "+item.id;
						  //alert(sql);
					tx.executeSql(sql,[],function (tx, result){
 					var ln = result.rows.length;
 					var data2 =  new Array();
					for(var j =0; j < ln; j++){	 						
 						data2[j] = {'id':result.rows.item(j).id,'idpedido':result.rows.item(j).idpedido,'idproducto':result.rows.item(j).idproducto,'cantidad':result.rows.item(j).cantidad,'valor':result.rows.item(j).valor};
 					}
 					item.prod_pedido = data2;
 					localStorage.data = JSON.stringify(data);
			  		//alert(JSON.stringify(data));
			  		//$("#deviceProperties").html(localStorage.data);	
 					},Error);
			  });	
		},function(err){
			console.log(err.message);
		});
	},function(err){
			console.log(err.message);
		},function() {
		if(localStorage.data.replace("[]","").length>0){
			send_pedidos();
		}else{
			alert("No se encontraron pedidos para sincronizar.");
		}
		 
	}); 
		
}




//Envia la informacion reacolectada sobre los pedidos
function formatjson_pedido(transportador){
	var data =  new Array();
	sessionStorage.data= "";
	sessionStorage.data2 = "";
	db.transaction(function (tx){
		tx.executeSql("select p.*, c.identification from pedido p join CLIENT c on c.id = p.idcliente  where p.estado = 1 ",[],
			function (tx, results){
			var len = results.rows.length;
			  for (var i=0; i<len; i++){
			  	 var now = new Date(results.rows.item(i).fecha); 
                var day = ("0" + now.getDate()).slice(-2);
                var month = ("0" + (now.getMonth() + 1)).slice(-2);
                var year = now.getFullYear();
			  		//alert(results.rows.item(i).identification);
			  	data[i] = {'id':results.rows.item(i).id,'idcliente':results.rows.item(i).idcliente,'fecha':year+month+day,
			  	'idvendedor':localStorage.vendedor,'descripcion':results.rows.item(i).descripcion+ " | transportador: "+transportador,'version':results.rows.item(i).version,'valor_pedido':results.rows.item(i).valor_pedido,
			  	 'estado':results.rows.item(i).estado,'identification':results.rows.item(i).identification,'prod_pedido':"[]"};
			  }
			   //alert(JSON.stringify(data));
			  localStorage.data = JSON.stringify(data);
			 // alert(localStorage.data+"Hola");

			  $.each( data, function( i, item ){			  	
			  		var sql = "select pp.* from producto_pedidos pp join pedido p on p.id = pp.idpedido join producto pr on pr.idproducto = pp.idproducto  "+
						  "where  p.id = "+item.id;
						  console.log(sql);
					tx.executeSql(sql,[],function (tx, result){
 					var ln = result.rows.length;
 					var data2 =  new Array();
					for(var j =0; j < ln; j++){	 						
 						data2[j] = {'id':result.rows.item(j).id,'idpedido':result.rows.item(j).idpedido,'idproducto':result.rows.item(j).idproducto,'cantidad':result.rows.item(j).cantidad,'valor':result.rows.item(j).valor};
 					}
 					item.prod_pedido = data2;
 					localStorage.data = JSON.stringify(data);
			  		//console.log(JSON.stringify(data));
			  		//alert(localStorage.data);	
			  		
 					},function(err){
						console.log(err.message);
					});
			  });	

		},function(err){
			console.log(err.message);
		});
	},Error,function() {
		console.log(localStorage.data);
		if(localStorage.data.replace("[]","").length > 0){
			send_pedidos();
		}else{
			alert("No se encontraron pedidos para sincronizar.");
		}
		 
	}); 
		
}

function send_pedidos(){
	//formatjson_pedido();
	console.log(localStorage.data);
	$.ajax({
			url: ServiceAPI+"guardarPedidos",
  			type: 'post',
		  	data: {"datos":localStorage.data},
		  	async: true,
			format:"jsonp",
			crossDomain: true		  		
		  	}).done( function (data) {
		  	// alert(data.msg);
		  		localStorage.data="";
		  		if ((data.msg.split(",").length-1)>0) {
			  		for(var i =0; i < data.msg.split(",").length-1; i++){
			  			//alert(data.msg.split(",")[i]);
			  			//deleteOrder(data.msg.split(",")[i]);
			  		}
			  		alert("Se han Sincronizado : "+(data.msg.split(",").length-1)+"  Pedidos.");
			  	}else{
			  		alert("Se han Sincronizado : "+(data.msg.split(",").length-1)+"  Pedidos.");
			  	}

			  	//verificate(data);
		  		
		  		//resartTble();
		  	}).fail(function( jqxhr, textStatus, error ) {
				var err = textStatus + ", " + error.code;
				localStorage.data="";
				alert( "Error en la sincronización: " + err );
			});
}











function verificate( data ){
	if ((data.msg.split(",").length-1)>0) {

		for(var i =0; i < data.msg.split("|").length-1; i++){

			var idpedido = data.msg.split("|")[i].split("-")[0].replace("ídp*","");
			var idproductos = data.msg.split("|")[i].split("-")[1].replace("(","").replace(")","");
			alert( data.msg.split("|")[i]);
			alert("idpedido = "+idpedido);
			alert("idpedido = "+idproductos);
			for (var i = 0; i  < idproductos.split(",").length-1; i++) {
				db.transaction(function (tx){
					var estado = "";
					var strs = idproductos.split(",")[i];
					if(strs.indexOf('pe') !=-1){
						estado = "pe";
					}else if(strs.idproductos.split(",")[i].indexOf('E') !=-1) {
						estado= "E";
					}else{
						estado = "s";
					}

					var sql = "update  producto_pedidos set estado = '"+ estado +"' where idpedido = "+idpedido;
					alert(sql);
				});
			  		
		}
	}
		//alert("Se han Sincronizado : "+(data.msg.split("|").length-1)+"  Pedidos.");
	}else{
		//alert("Se han Sincronizado : "+(data.msg.split("|").length-1)+"  Pedidos.");
	}


	}





/*function send_producto_pedidos(){
	var data =  new Array();
	db.transaction(function (tx){
		tx.executeSql("select * from producto_pedidos ",[],function (tx, results){
			var len = results.rows.length;
			  for (var i=0; i<len; i++){
			  		data[i] = {'id':results.rows.item(i).id,'idpedido':results.rows.item(i).idpedido,'fechaidproducto':results.rows.item(i).idproducto,'cantidad':results.rows.item(i).cantidad};
			  }
			  //alert(JSON.stringify(data));
			  $.ajax({
			  		url: ServiceAPI+"guardarClientes",
			  		type: 'post',
			  		data: {"datos":JSON.stringify(data)},
			  		success: function (data) {
			  			alert("Datos Guardados");
			  		}
			  	}).fail(function( jqxhr, textStatus, error ) {
					var err = textStatus + ", " + error.message;
					alert( "Request Failed: " + err );
				});
		},Error);
	},Error); 

}*/





function send_MetasDiarias(){
	var data =  new Array();
	db.transaction(function (tx){
		tx.executeSql("select * from metasdiarias",[],function (tx, results){
			var len = results.rows.length;
			  for (var i=0; i<len; i++){
			  		data[i] ={'id':results.rows.item(i).id,'idproducto':results.rows.item(i).idproducto,'idvendedor':results.rows.idvendedor,'cantidadMeta':results.rows.item(i).cantidadMeta,'cantidadvendida':results.rows.item(i).cantidadvendida,'fecha':results.rows.item(i).fecha}
			  }
			  console.log(JSON.stringify(data));
			 //alert("tamaño del array de clientes: "+data.length);
			  if (data.length >0) {
			  	$.ajax({
			  		url: ServiceAPI+"actualizarMetas",
			  		type: 'post',
			  		data: {"datos":JSON.stringify(data)},
			  		async: true,
					format:"jsonp",
				 	crossDomain: true		  		
			  	}).done( function (data) {
			  		alert("metas sincronizadas");
			  	}).fail(function( jqxhr, textStatus, error ) {
					var err = textStatus + ", " + error.code;
					alert( "Request Failed: " + err );
				});
			  }else{
			  	console.log("No hay MetasDiarias para sincronizar.");			  
			  }	

		},function(err){
			console.log(err.message);
		});
	},function(err){
		alert("Error al sinronizar las Metas Diarias : "+err.message);
		
	},function(){
		console.log("consulta metas con Exito");
	}); 
}