/*
*this file will  be used for  manage the product information 
*@Developed by Walther smith franco otero
*twitter: @walthersmith
*/

//Global variables
var db ;
var shorname ='quarxoDb';
var version = '1.0';
var displayName = 'Quarxodb';
var maxSize = 42000000;
 
function onDeviceReady () {
    
    db = window.openDatabase(shorname, version, displayName, maxSize);
    successCB();
    successCBRuta();
	
}
    // Query the database
    //
    function queryDB(tx) {
        var sql = "SELECT * FROM CLIENT WHERE comentario LIKE '%" + diaFormatoFiltro() + "%'";
        //alert(sql);
        tx.executeSql(sql, [], cargarVisistas, errorCB);
    }	
	
    // Query the success callback
    //
    function cargarVisistas(tx, results) {
        var len = results.rows.length;
        localStorage.cantidadClientes = len;
        //alert(len);
        var lol = "";
        var nv= 0; //no visitado
        var vi = 0; // visitado;
        var SP = 0;
        var CP = 0;
        $( ".selector" ).loader( "show" );
        for (var i=0; i<len; i++){
            if(results.rows.item(i).estado==="S"){
                nv++;
            }
            if(results.rows.item(i).estado==="SP" || results.rows.item(i).estado==="CP"){
                vi++;
            }
            
            if(results.rows.item(i).estado==="SP"){
                SP++;
            }
            if(results.rows.item(i).estado==="CP"){
                CP++;
            }
        }        
        lol+="Cantidad de clientes: "+len+"<br>";
        lol+="Cantidad de clientes (Sin Pedido): "+SP+"<br>";
        lol+="Cantidad de clientes (Con Pedido): "+CP+"<br>";
        lol+="Cantidad de Total clientes Visitados: "+vi+"<br>";
        lol+="Cantidad de clientes NO visitados: "+nv+"<br>";
        var ps= ((vi/len)*100);        
        lol+="Porcentaje: "+ps.toFixed(2)+"%<br>";
        $("#desarrollo").html(lol);
       
    }

    // Transaction error callback
    function errorCB(err) {
        console.log("Error al procesar la información "+err.message);
     
    }

    // Transaction success callback
    function successCB() {
        db.transaction(queryDB, errorCB);
    }

    function susses(){
        console.log("exito");
    }



//efectividad den la ruta
     function queryDBRuta(tx) {
        var sql = "SELECT * FROM pedido where estado = 1 ";
        //alert(sql);
        tx.executeSql(sql, [], cargarefectividad,
                      function (err) {
        console.log("Error al procesar la información "+err.message);
        console.log(err.message);
    });
    }	
	
    // Query the success callback
    //
    function cargarefectividad(tx, results) {
        var len = results.rows.length;      
        //alert(len);
        var lol = "";
        var nv= 0; //no visitado
        var vi = 0; // visitado;
              
        lol+="Cantidad de Pedidos posibles: "+ localStorage.cantidadClientes+"<br>";
        lol+="Cantidad de pedidos Realizados: "+len+"<br>";       
        var ps= ((len/localStorage.cantidadClientes)*100);        
        lol+="Porcentaje pedidos realizados: "+ps.toFixed(2)+"%<br>";
        $("#efectividad").html(lol);       
        
    }
 

    // Transaction success callback
    function successCBRuta() {
        db.transaction(queryDBRuta, function (err) {
        console.log("Error al procesar la información "+err.message);
        console.log(err.message);
    });
    }




function diaFormatoFiltro() {
    var diasSemana = new Array("DOMINGO", "LUNES", "MARTES", "MIERCOLES", "JUEVES", "VIERNES", "SABADO");
    var f = new Date();
    var queryform = localStorage.zona + "-" + diasSemana[f.getDay()];
    return queryform;
}