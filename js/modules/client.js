/*
 *this file will  used for  manage the client information 
 *@Developed by Walther smith franco otero
 *@twitter: @walthersmith
 *@link: http://about.me/walthersmith
 */

//Global variables
var db;
var shorname = 'quarxoDb';
var version = '1.0';
var displayName = 'Quarxodb';
var maxSize = 42000000;
db = window.openDatabase(shorname, version, displayName, maxSize);
var dday = 0;
// Query the database
//
function queryDB(tx) {
    //alert('SELECT * FROM CLIENT where idvendedor = '+localStorage.vendedor);
    //tx.executeSql('SELECT * FROM CLIENT where idvendedor = '+localStorage.vendedor, [], cargarClientes, errorCB);
    tx.executeSql("SELECT * FROM CLIENT WHERE comentario LIKE '%" + diaFormatoFiltro() + "%'", [], cargarClientes, errorCB);
    // tx.executeSql('SELECT * FROM CLIENT', [], cargarClientes, errorCB);

}
// Query the success callback
//
function cargarClientes(tx, results) {
    var len = results.rows.length;
    var lol = "";
    if (len > 0) {
        for (var i = 0; i < len; i++) {
            if (results.rows.item(i).estado == "SP") {
                 lol += "<li ><a href=\"infoClient.html?idcl=" + results.rows.item(i).id + "\" rel=\"external\" data-ajax=\"false\" style=\"background:#BBBBBB;\">" + results.rows.item(i).name + " " + results.rows.item(i).lastname1 + " " + results.rows.item(i).lastname2 + "</a></li>";
            } else if(results.rows.item(i).estado == "CP") {
                lol += "<li><a href=\"infoClient.html?idcl=" + results.rows.item(i).id + "\" rel=\"external\" data-ajax=\"false\" style=\"background:#EBF8A4;\">" + results.rows.item(i).name + " " + results.rows.item(i).lastname1 + " " + results.rows.item(i).lastname2 + "</a></li>";
            }else {
                lol += "<li><a href=\"infoClient.html?idcl=" + results.rows.item(i).id + "\" rel=\"external\" data-ajax=\"false\">" + results.rows.item(i).name + " " + results.rows.item(i).lastname1 + " " + results.rows.item(i).lastname2 + "</a></li>";
            }
        }
    } else {
        lol = "<h2>No hay clientes relacionados con  el usuario: " + localStorage.nombre + "</h2>";
    }
    $("#dataClient").html(lol);
    $("#dataClient").listview("refresh");
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

function susses() {
    alert("Cliente Guardado Con Exito");
    window.location.href = "clients.html";
}

function addClient() {
    db.transaction(addItem, errorCB, susses);
}

function addItem(tx) {
    tx.executeSql("INSERT INTO CLIENT(name,lastname1,lastname2,identification,address,phone,email,tipoprecio,idvendedor,new) values(?,?,?,?,?,?,?,?,?,?)",
            [$('#name').val(), $("#lastname1").val(), $("#lastname2").val(), $("#nit").val(), $("#address").val(), $("#phone1").val(), $("#email").val(), 0, localStorage.vendedor, 1]);
}

function  updateEstado(id, estado) {
    var sql = "update  CLIENT set estado = '" + estado + "' where id=" + id;
    db.transaction(function (tx) {
        tx.executeSql(sql);
    }, function (err) {
        console.log("error al realizar la Acción: " + err.message);
    }, function () {
        if(estado ==='SP'){
        alert("El Cliente Se Ha Marcado Como Visto Sin Pedido");
        }else{
            console.log("Estado del cliente Acualizado");
        }
    });

}

function nullMethod() {
    db = window.openDatabase(shorname, version, displayName, maxSize);
}
function getdata(op, day) {

    switch (op) {
        case 1:
            db = window.openDatabase(shorname, version, displayName, maxSize);
            successCB();
            break;
        case 2:
            db = window.openDatabase(shorname, version, displayName, maxSize);
            successCB2(day);
            break;
        case 3:
            db = window.openDatabase(shorname, version, displayName, maxSize);
            successCB3(day);
            break;
        case 4:
            db = window.openDatabase(shorname, version, displayName, maxSize);
            successCB4();
            break;
            
    }

}

function consulta(tx) {

    //tx.executeSql('SELECT * FROM CLIENT where idvendedor = '+localStorage.vendedor, [], cargarClientesPedidos, errorCB);
    tx.executeSql("SELECT * FROM CLIENT WHERE comentario LIKE '%" + diaFormatoSelect(this.dday) + "%' ", [], cargarClientesPedidos, errorCB);
    // tx.executeSql('SELECT * FROM CLIENT ', [], cargarClientesPedidos, errorCB);
}
function successCB2(day) {
     this.dday = day;
    db.transaction(consulta, errorCB);
}


function consultaSelect(tx) {

    //tx.executeSql('SELECT * FROM CLIENT where idvendedor = '+localStorage.vendedor, [], cargarClientesPedidos, errorCB);
    //alert("SELECT * FROM CLIENT WHERE comentario LIKE '%"+this.dday+"%' ");
    tx.executeSql("SELECT * FROM CLIENT WHERE comentario LIKE '%" + diaFormatoSelect(this.dday) + "%' ", [], cargarClientes, errorCB);
    // tx.executeSql('SELECT * FROM CLIENT ', [], cargarClientesPedidos, errorCB);
}

function successCB3(day) {
    this.dday = day;
    db.transaction(consultaSelect, errorCB);
}

function successCB4(){
    db.transaction(queryDbP,errorCB);
}

function queryDbP(tx) {
    //alert('SELECT * FROM CLIENT where idvendedor = '+localStorage.vendedor);
    //tx.executeSql('SELECT * FROM CLIENT where idvendedor = '+localStorage.vendedor, [], cargarClientes, errorCB);
    tx.executeSql("SELECT * FROM CLIENT WHERE comentario LIKE '%" + diaFormatoFiltro() + "%'", [], cargarClientesPedidos, errorCB);
    // tx.executeSql('SELECT * FROM CLIENT', [], cargarClientes, errorCB);

}
function cargarClientesPedidos(tx, results) {
    var len = results.rows.length;
    var lol = "";
    if (len > 0) {
        for (var i = 0; i < len; i++) {
            if(results.rows.item(i).estado =="SP" || results.rows.item(i).estado =="CP"){
                  
            }else{
                lol += '<li><a href="addOrderstp2.html?cl=' + results.rows.item(i).tipoprecio + '&idcl=' + results.rows.item(i).id + '" rel="external" data-ajax="false">' + results.rows.item(i).name + " " + results.rows.item(i).lastname1 + " " + results.rows.item(i).lastname2 + "</a></li>";
            }
        }
    } else {
        lol = "<h2>No hay clientes relacionados con  el usuario: " + localStorage.nombre + "</h2>";
    }
    $("#addOrderClient").html(lol);
    $("#addOrderClient").listview("refresh");

    $("addOrderClient li a");
}



function crearPedido() {

    db.transaction(pedido, Error, success);
}

function pedido(tx) {
    var sql = "insert into pedido (idcliente,fecha,idvendedor,version,valor_pedido,estado)" +
            "values(null,(DATETIME('now')),0,0,0,0)";
    tx.executeSql(sql);
}

function Error(err) {
    console.log("Error al realizar la Acción: " + err.message);
}

function success() {
    console.log("La acción se ha realizado satisfactoriamente.");

}

function diaFormatoFiltro() {
    var diasSemana = new Array("DOMINGO", "LUNES", "MARTES", "MIERCOLES", "JUEVES", "VIERNES", "SABADO");
    var f = new Date();
    var queryform = localStorage.zona + "-" + diasSemana[f.getDay()];
    return queryform;
}

function diaFormatoSelect(day) {
    var diasSemana = new Array("DOMINGO", "LUNES", "MARTES", "MIERCOLES", "JUEVES", "VIERNES", "SABADO");
    var queryform = localStorage.zona + "-" + diasSemana[day];
    return queryform;
}


function getVariables(idcliente){

    db.transaction(function(tx){
       var SQL = "SELECT c.id, c.tipoprecio, c.estado from CLIENT c where c.id = "+idcliente ; 
	  // var SQL  = "select c.* from CLIENT c where c.id = "+idcliente;
	   
       tx.executeSql(SQL,[],function(tx, results){
            if(results.rows.length >0){
                for (var i = 0;i < results.rows.length; i++) {
			this.url = "../order/addOrderstp2.html?cl="+results.rows.item(i).tipoprecio+"&idcl="+idcliente+"&clpedido=ok";
                   // this.url = "mod_addOrderstp2.html?idpedido="+idpedido+"&cl="+results.rows.item(i).tipoprecio+"&idcl="+results.rows.item(i).id;
				  // alert(results.rows.item(i).estado );
				   if(results.rows.item(i).estado == "S" || results.rows.item(i).estado == "SP"){
						//$("#btnidclient").html('<a href="#" class="button" data-role="button">Realizar Pedido</a>');
						 $("#editButton").html('<a href="'+this.url+'" data-role="button"   class="button" rel="external" id="editarPedido">Crear Pedido</a>');
						 $("#editButton").buttonMarkup();
					}
                   
                }
            }else{
				 alert("NO hay registros");
			}
       }, function(err){console.log("Error al procesar la consulta "+err.message);});
    },function(err){console.log("Error en la transacción "+err.message);});
}


function getUrlVars() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}