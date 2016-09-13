/*
*this file will  used for  manage the client information 
*@Developed by Walther smith franco otero
*twitter: @walthersmith
*@licenced: Creative commons
*/

//Global variables
var db ;
var shorname ='quarxoDb';
var version = '1.0';
var displayName = 'Quarxodb';
var maxSize = 42000000;
db = window.openDatabase(shorname, version, displayName, maxSize);
/*ar diasSemana = new Array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
var f=new Date();
var queryform = localStorage.zona+"-"+diasSemana[f.getDay()]
*/
    // Query the database
    //
    function queryDB(tx) {
        //alert('SELECT * FROM CLIENT where idvendedor = '+localStorage.vendedor);
        //tx.executeSql('SELECT * FROM CLIENT where idvendedor = '+localStorage.vendedor, [], cargarClientes, errorCB);
        tx.executeSql("SELECT * FROM transportador ", [], cargarTransportador, errorCB);
       // tx.executeSql('SELECT * FROM CLIENT', [], cargarClientes, errorCB);
       
    }
    // Query the success callback
    //
    function cargarTransportador(tx, results) {
        var len = results.rows.length;
        var lol = "<option value=\"NINGUNO\">----Seleccionar---</option>";
        if(len >0){
            for (var i=0; i<len; i++){
                lol+="<option value=\""+results.rows.item(i).nombres+"\">" + results.rows.item(i).nombres + "<option>";
            }
        }else{
            lol ="<option>No hay  transportadores </option>";
        }  
        $("#datostrasport").html(lol);
        $("#datostrasport").selectmenu('refresh', true);
    }

    // Transaction error callback
    function errorCB(err) {
        alert("Error Al Procesar la consulta ");
        console.log(err.message);
    }

    // Transaction success callback
    function successCB() {
        db.transaction(queryDB, errorCB);
    }