//Global variables
var db ;
var shorname ='quarxoDb';
var version = '1.0';
var displayName = 'Quarxodb';
var maxSize = 42000000;


// Populate the database 
function createTables(tx) {
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
    	"new integer)";
	
	var products = "CREATE TABLE IF NOT EXISTS producto("+
		"idproducto INTEGER PRIMARY KEY AUTOINCREMENT,"+
		"codigo text,"+
		"descripcion text,"+
		"nommedida text,"+
		"precioventa real,"+
		"precioespecial1 real,"+
		"precioespecial2 real)";
	
	var pedido ="CREATE TABLE IF NOT EXISTS pedido ("+
		"id INTEGER PRIMARY KEY AUTOINCREMENT,"+
		"idcliente  INTEGER,"+
		"fecha date,"+
		"idvendedor INTEGER,"+
		"version INTEGER,"+
		"valor_pedido real,"+
		"estado int)";
	
	var prod_pedido="CREATE TABLE IF NOT EXISTS producto_pedidos("+
		"id INTEGER PRIMARY KEY AUTOINCREMENT,"+
		"idpedido integer,"+
		"idproducto integer,"+
		"cantidad integer)";



	/*tx.executeSql('DROP TABLE IF EXISTS producto');
	tx.executeSql("DROP TABLE IF EXISTS CLIENT");
	tx.executeSql("DROP TABLE IF EXISTS pedido");
	tx.executeSql("DROP TABLE IF EXISTS producto_pedidos");
	tx.executeSql(products);
	tx.executeSql(client);
	tx.executeSql(pedido);
	tx.executeSql(prod_pedido)*/
	
	//tx.executeSql("INSERT INTO producto(codigo, descripcion, nommedida, precioventa, precioespecial1, precioespecial2) values ('1','LECHE MAGNESIA MK   * 120 ML','UNIDADES',2400.0,0.0,0.0)");

}


function getFile(tx){
	var file = "js/productos.txt";
    $.get(file,function(txt){
    	var lines = txt.split(";");
    	alert("cantidad de rows: "+lines.length);
       for (var i = 0; i < lines.length; i++) {
        	var sql= lines[i]+"";
        	alert(sql);
       		tx.executeSql(sql);
        }        
    }); 	
}

function insetar(){

}

function create(){
	db.transaction(getFile,errorCB, successCB);
	db.transaction(queryDB,errorCB,successCB);
}


    // Transaction error callback
    function errorCB(err) {console.log("Error processing SQL: "+err.message); }
    // Transaction success callback
    function successCB() {console.log("Exito");}
    // Cordova is ready
    function onDeviceReady() {
        db = window.openDatabase(shorname, version, displayName, maxSize);
        db.transaction(createTables, errorCB, successCB);
      
    }


function queryDB(tx) {
        tx.executeSql('SELECT * FROM producto', [], querySuccess, errorCB);
    }

    // Query the success callback
    //
    function querySuccess(tx, results) {
        var len = results.rows.length;
        alert("producto table: " + len + " rows found.");
        var lol = "";
        for (var i=0; i<len; i++){
        	lol +="<li>Row = " + i + " ID = " + results.rows.item(i).idproducto+ " Data =  " + results.rows.item(i).descripcion+"</li>";
        }
        $("#lista").html(lol);
        $( "#lista" ).listview( "refresh" );
    }

