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
	successCBProveedor();
}
    // Query the database
    //
    function queryDB(tx) {
        tx.executeSql('SELECT * FROM producto', [], cargarClientes, errorCB);
    }
	
	 function queryDBfiltrado(tx) {
        tx.executeSql('SELECT * FROM producto where idregistro= '+localStorage.idregistro, [], cargarClientes, errorCB);
    }
	
	function successCBfiltrado() {
        db.transaction(queryDBfiltrado, errorCB);
    }
    // Query the success callback
    //
    function cargarClientes(tx, results) {
        var len = results.rows.length;
        //alert(len);
        var lol = "";
        $( ".selector" ).loader( "show" );
        for (var i=0; i<len; i++){
            lol+= "<li><h2>"+ results.rows.item(i).descripcion+"</h2> <p>1 "+results.rows.item(i).nommedida+" | $"+results.rows.item(i).precioventa+" | Cantidad inventario: "+results.rows.item(i).cantidad+"</p></li>";        }
        $("#listProduct").html(lol);
        $("#listProduct").listview("refresh");
        $( ".selector" ).loader( "hide" );
    }

    // Transaction error callback
    function errorCB(err) {
        alert("Error al procesar la información ");
        console.log(err.message)
    }

    // Transaction success callback
    function successCB() {
        db.transaction(queryDB, errorCB);
    }

    function susses(){
        console.log("exito");
    }
	
	
	   function queryDBproveedor(tx) {
        tx.executeSql('SELECT DISTINCT nombreprove,idregistro FROM producto', [], cargarProveedor, errorCB);
    }
    // Query the success callback
    //
    function cargarProveedor(tx, results) {
        var len = results.rows.length;
        //alert(len);
        var lol = "";
        $( ".selector" ).loader( "show" );
        for (var i=0; i<len; i++){
            lol+= '<option value="'+results.rows.item(i).idregistro+'">'+results.rows.item(i).nombreprove+'</option>';  
			}
        $("#slcproveedor").html(lol);
        //$("#slcproveedor").listview("refresh");
        $( ".selector" ).loader( "hide" );
    }
	
	function successCBProveedor() {
        db.transaction(queryDBproveedor, errorCB);
    }
    function nullMethod(){
        db = window.openDatabase(shorname, version, displayName, maxSize);
    }