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
	
}
    // Query the database
    //
    function queryDB(tx) {
        tx.executeSql('SELECT md.*, p.descripcion FROM metasdiarias md join producto p on p.idproducto = md.idproducto ', [], cargarMetasDiarias, errorCB);
    }	
	
    // Query the success callback
    //
    function cargarMetasDiarias(tx, results) {
        var len = results.rows.length;
        //alert(len);
        var lol = "";
        $( ".selector" ).loader( "show" );
        for (var i=0; i<len; i++){
            var cumplimiento = ((results.rows.item(i).cantidadvendida/results.rows.item(i).cantidadMeta)*100);
          lol+= "<li><h2>"+ results.rows.item(i).descripcion+"</h2> <p>Cantidad Meta: "+results.rows.item(i).cantidadMeta+" | Vendida: "+results.rows.item(i).cantidadvendida+" | cumplimiento: "+cumplimiento.toFixed(2)+"%</p></li>";   
        }
        $("#listProduct").html(lol);
        $("#listProduct").listview("refresh");
        $( ".selector" ).loader( "hide" );
    }

    // Transaction error callback
    function errorCB(err) {
        alert("Error al procesar la información "+err.message);
        console.log(err.message)
    }

    // Transaction success callback
    function successCB() {
        db.transaction(queryDB, errorCB);
    }

    function susses(){
        console.log("exito");
    }