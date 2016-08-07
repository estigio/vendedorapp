/*
 *@Developed by Walther smith franco otero
 *twitter: @walthersmith
 *@licenced: Creative common
 */
var db;
var shorname = 'quarxoDb';
var version = '1.0';
var displayName = 'Quarxodb';
var maxSize = 42000000;
db = window.openDatabase(shorname, version, displayName, maxSize);
function onDeviceReady() {
    db = window.openDatabase(shorname, version, displayName, maxSize);
    successCB();
    console.log("productos");
    storageGetValues();
    // crearPedido();
    updatePedido(localStorage.idCliente);

}



function successCB() {
    db.transaction(queryDB, errorCB);
}

// Query the database
//
function queryDB(tx) {
    /*var SQL="select idproducto, descripcion,"+
     "case (select tipoprecio from client where id="+2+" ) when  tipo is null or tipo ==0 or tipo == 1 then"+
     "precioventa, when tipo == 2 then precioespecial1 when tipo == 3 then precioespecial2 end as precio, nommedida"+
     "from productos";*/

    var SQL = "SELECT * FROM producto LIMIT 50";
    tx.executeSql(SQL, [], cargarProductos, errorCB);
}
// success  query callback
//
function cargarProductos(tx, results) {
    var len = results.rows.length;
    var id = getUrlVars();
    var lol = "";
    var valorProducto = 0;

    for (var i = 0; i < len; i++) {

        if (localStorage.tipoPrecio == 'null' || localStorage.tipoPrecio == '0' || localStorage.tipoPrecio == '1') {
            valorProducto = results.rows.item(i).precioventa;
        } else if (localStorage.tipoPrecio == '2') {
            if (results.rows.item(i).precioespecial1 = 0) {
                valorProducto = results.rows.item(i).precioventa;
            } else {
                valorProducto = results.rows.item(i).precioespecial1;
            }
        } else if (localStorage.tipoPrecio == '3') {
            if (results.rows.item(i).precioespecial2 = 0.0) {
                valorProducto = results.rows.item(i).precioventa;
            } else {
                valorProducto = results.rows.item(i).precioespecial2;
            }
        }
        lol += '<li><a value="' + results.rows.item(i).idproducto + '"><h2>' + results.rows.item(i).descripcion + "</h2>" +
                '<p>$<span  id="valproducto' + results.rows.item(i).idproducto + '" value="' + valorProducto + '">' + valorProducto + '</span> X ' + results.rows.item(i).nommedida + '</p> </a>' +
                '<a   value="' + results.rows.item(i).idproducto + '" class="mib" id="msgAdd">Producto 1</a></li>';

        /*lol+= '<li><a value="'+results.rows.item(i).idproducto+'"><h2>'+ results.rows.item(i).descripcion+"</h2> <p>$";
         if(localStorage.tipoPrecio == 'null' || localStorage.tipoPrecio == '0' || localStorage.tipoPrecio == '1' ){
         lol+=results.rows.item(i).precioventa+" X "+results.rows.item(i).nommedida+'</p> </a>'+
         '<a   value="'+results.rows.item(i).idproducto+'" class="mib" id="msgAdd">Producto 1</a></li>';             		
         
         }else if (localStorage.tipoPrecio == '2') {
         lol+=results.rows.item(i).precioespecial1+" "+results.rows.item(i).nommedida+'</p>'+
         '</a><a   value="'+results.rows.item(i).idproducto+'" class="mib" id="msgAdd">Producto 1</a></li>'; 
         
         
         }else if(localStorage.tipoPrecio == '3'){
         lol+=results.rows.item(i).precioespecial2+" "+results.rows.item(i).nommedida+'</p>'+
         '</a><a   value="'+results.rows.item(i).idproducto+'" class="mib"   id="msgAdd" >Producto </a></li>'; 
         
         
         }*/

    }
    $("#listProduct").html(lol);
    $("#listProduct").listview("refresh");
    //$("#popup").html(pop);
    //$("#page").trigger( "updatelayout");
    $(".mib").click(function () {
        var cantidad = prompt('Ingrese la cantidad  para el producto seleccionado:', '');
        // alert("id producto "+$(this).attr('value')+" Cantidad: "+ cantidad);
        if (cantidad == null || cantidad == "" || cantidad.length <= 0 || !cantidad.match('[0-9]')) {
            alert("El valor Ingresado no es correcto");
        } else {
            //alert($("#valproducto"+$(this).attr('value')).attr('value'));
            precioPorcantidad($(this).attr('value'), cantidad, $("#valproducto" + $(this).attr('value')).attr('value'));
        }

        return false;
    });
    $('#page').trigger('create');
    $("button").on('click', function () {
        if ($("#amount" + $(this).val()).val() == 0 || $("#amount" + $(this).val()).val() == "") {
            alert("Ingresa Una cantidad valida");
        } else {
            addProductoPedido($(this).val(), $("#amount" + $(this).val()).val());
            $("#amount" + $(this).val()).val("");
            //$("#purchase"+$(this).val()).popup( "close" );
        }
    });



}

//function
function precioPorcantidad(idproducto, cantidad, valproducto) {
    db.transaction(
            function (tx) {
                var SQL = "SELECT * FROM prodprecioscant where idproducto = " + idproducto + " and cantidad = " + cantidad;
                tx.executeSql(SQL, [], function (tx, results) {
                    var len = results.rows.length;
                    if (len > 0) {
                        for (var i = 0; i < len; i++) {
                            addProductoPedido(idproducto, cantidad, results.rows.item(i).precio);
                            alert("Producto con  descuento por cantidad de " + cantidad + " unidades.\n Precio original x unidad ($" + valproducto + ")\n Precio con descuento x unidad ($" + results.rows.item(i).precio + ")");
                        }
                    } else {
                        addProductoPedido(idproducto, cantidad, valproducto);
                    }
                }, function (err) {
                    console.log("Error en el procesamiento de la informacion:  " + err.message);
                });
            },
            function (err) {
                console.log("Error en el procesamiento de la informacion:  " + err.message);
            });
}


//function
function updateMetasCancelarPedido() {
    db.transaction(
            function (tx) {
                var SQL = "SELECT * FROM producto_pedidos where idpedido = (select max(id) from pedido) ";
                tx.executeSql(SQL, [], function (tx, results) {
                    var len = results.rows.length;
                    if (len > 0) {
                        for (var i = 0; i < len; i++) {
                            tx.executeSql("UPDATE metasdiarias set cantidadvendida = (cantidadvendida - " +results.rows.item(i).cantidad+ ") where idproducto = " + results.rows.item(i).idproducto);
                        }
                    } else {
                        tx.executeSql("delete from pedido where id = (select max(p.id) from pedido p) ");
                    }
                }, function (err) {
                    console.log("Error en el procesamiento de la informacion:  " + err.message);
                });
            },
            function (err) {
                console.log("Error en el procesamiento de la informacion:  " + err.message);
            },function(){
                 window.location.href="addOrder.html";
            });
}





// Transaction error callback
function errorCB(err) {
    console.log("Error al procesar la consultas: " + err.message);
}

// Transaction success callback
function successCB() {
    db.transaction(queryDB, errorCB);
}

function susses() {
    console.log("exito");
}

function nullMethod() {
    db = window.openDatabase(shorname, version, displayName, maxSize);
}


function getUrlVars() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for (var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}



function Error(err) {
    console.log("Error al realizar la Acción: " + err.message);
}

function successG() {
    console.log("La acción se ha realizado satisfactoriamente.");
}

function updatePedido(id_cliente) {
    db.transaction(function (tx) {
        // alert(id_cliente);
        tx.executeSql("update pedido set idcliente = " + id_cliente + ", idvendedor = '" + localStorage.vendedor + "' where id = (select max(id) from pedido)");
    }, Error, successG);
}

//Functions Save data pedido
function addProductoPedido(id_producto, candidad, valproducto) {
    db.transaction(function (tx) {
        var SQL = "insert into producto_pedidos(idpedido, idproducto,cantidad, valor) values((select max(id) from pedido)," + id_producto + "," + candidad + "," + valproducto + ")";
        // alert(SQL)
        tx.executeSql(SQL);
        tx.executeSql("UPDATE metasdiarias set cantidadvendida = (cantidadvendida + " + candidad + ") where idproducto = " + id_producto);
    }, function(err){
                    alert("Ha ocurrido un error al Agregar el producto");
                    console.log(err.message);
                }, function () {
        alert("Se ha agregado el producto al pedido.");
    });
}

function storageGetValues() {
    var id = getUrlVars();
    if (id.length > 0) {
        localStorage.tipoPrecio = id['cl'];
        localStorage.idCliente = id['idcl'];
    }
}



function searchProducts(exp) {
    if (exp.length >= 3) {
        db.transaction(function (tx) {
            var SQL = "SELECT * FROM producto  where upper(descripcion) like upper('%" + exp + "%')";
            tx.executeSql(SQL, [], cargarProductos, errorCB);
        }, Error);
    } else if (exp.length <= 0) {
        db.transaction(function (tx) {
            var SQL = "SELECT * FROM producto LIMIT 50";
            tx.executeSql(SQL, [], cargarProductos, errorCB);
        }, Error);
    }
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
    console.log("El pedido se ha creado.");

}
