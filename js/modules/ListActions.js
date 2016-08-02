/*
 *@Developed by Walther smith franco otero
 *twitter: @walthersmith
 *@licenced: Creative commons
 */

//Global variables
var db;
var shorname = 'quarxoDb';
var version = '1.0';
var displayName = 'Quarxodb';
var maxSize = 42000000;
var id = getUrlVars();
var url = "";

/*$(function  () {
 db = window.openDatabase(shorname, version, displayName, maxSize);
 getProducts(id['id_pedido']);
 })
 */
function onDeviceReady() {
    db = window.openDatabase(shorname, version, displayName, maxSize);
  //  getVariables();
    getProducts();
    Proveedor();

}



function getProducts() {

    db.transaction(function (tx) {
         var sql = 'select  pr.idproducto, pr.descripcion, ' +
                'pr.nommedida, sum(pr.precioventa) as precioventa, sum(pp.cantidad) as cantidad , sum(pp.valor*pp.cantidad) as valor, p.id as id_pedido, p.descripcion as desc_p ' +
                'from producto pr join producto_pedidos pp on pr.idproducto = pp.idproducto ' +
                'join pedido p on p.id = pp.idpedido where p.estado = 1  GROUP BY pr.idproducto order by pr.descripcion';
        tx.executeSql(sql, [], cargarPedidos, function(err){
            console.log(err.message);
        });
    }, Error);
}




function cargarPedidos(tx, results) {
    var len = results.rows.length;
    var lol = "";
    var pop = "";
    var valor = 0;
    var infoCient = "";
    var Total = 0;
    if (len <= 0) {
        //alert("Este Pedido No tine Productos");
        $("#listaPedidos").html("<li><h2>Este pedido no tiene productos</h2>/li>");      
        $('#page').trigger('create');
        
    } else {
        for (var i = 0; i < len; i++) {

            valor = results.rows.item(i).valor;
            Total += (valor);
            lol += '<li><a href="#"><h2>' + results.rows.item(i).descripcion + '</h2> <p> <strong>Total: </strong>' + formatNumber((valor), "$") + ' | X  ' + results.rows.item(i).cantidad + " " + results.rows.item(i).nommedida + '</p></a>' +
                    '<a href="#purchase' + results.rows.item(i).idproducto + '" value="' + results.rows.item(i).idproducto + ',' + results.rows.item(i).id_pedido + ',' + (valor * results.rows.item(i).cantidad) + ',' + results.rows.item(i).cantidad + '"  class="btndeleteP">Producto 1</a> </li>';
            infoCient = '<h2>Total: ' + formatNumber(Total, "$") + '</h2><h4>Productos (' + len + ') </h4>';
        }

        $("#infoClient").html(infoCient);
        $("#listaPedidos").html(lol);
        $("#listaPedidos").listview("refresh");
        //$("#popDelete").html(pop);             
        $('#page').trigger('create');
    }

}


function Error(err) {
    alert("Ups!! ha ocurrido un problema.");
    console.log(err.message);
    return false;
}



function convertDate(inputFormat) {
    var d = new Date(inputFormat);
    return [d.getDate(), d.getMonth() + 1, d.getFullYear()].join('/');
}


function formatNumber(num, prefix) {
    num = Math.round(parseFloat(num) * Math.pow(10, 2)) / Math.pow(10, 2);
    prefix = prefix || '';
    num += '';
    var splitStr = num.split('.');
    var splitLeft = splitStr[0];
    var splitRight = splitStr.length > 1 ? '.' + splitStr[1] : '.00';
    splitRight = splitRight + '00';
    splitRight = splitRight.substr(0, 3);
    var regx = /(\d+)(\d{3})/;
    while (regx.test(splitLeft)) {
        splitLeft = splitLeft.replace(regx, '$1' + ',' + '$2');
    }
    return prefix + splitLeft + splitRight;
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
 

function actualizarCantidadMetaPedidoEliminado(idpedido) {
    db.transaction(function (tx) {
        var SQL = "SELECT * from producto_pedidos  where idpedido = " + idpedido;
        tx.executeSql(SQL, [], function (tx, results) {
            if (results.rows.length > 0) {
                for (var i = 0; i < results.rows.length; i++) {
                    tx.executeSql("UPDATE metasdiarias set cantidadvendida = (cantidadvendida - " + results.rows.item(i).cantidad + ") where idproducto = " + results.rows.item(i).idproducto);
                }
            }
        }, function (err) {
            console.log("Error al procesar la consulta " + err.message);
        }, function () {
            console.log("pecio total metasDiariasActualizadas");
        });
    }, function (err) {
        console.log("Error en la transacción " + err.message);
    });
} 

function getProductsFiltro(idregistro) {

    db.transaction(function (tx) {
        var sql = 'select  pr.idproducto, pr.descripcion, ' +
                'pr.nommedida, sum(pr.precioventa) as precioventa, sum(pp.cantidad) as cantidad , sum(pp.valor*pp.cantidad) as valor, p.id as id_pedido, p.descripcion as desc_p ' +
                'from producto pr join producto_pedidos pp on pr.idproducto = pp.idproducto ' +
                'join pedido p on p.id = pp.idpedido  where p.estado = 1 and pr.idregistro= '+idregistro+"  GROUP BY pr.idproducto order by pr.descripcion";
        tx.executeSql(sql, [], cargarPedidosFiltro, Error);
    }, Error);
}


function cargarPedidosFiltro(tx, results) {
    var len = results.rows.length;
    var lol = "";
    var pop = "";
    var valor = 0;
    var infoCient = "";
    var Total = 0;
    if (len <= 0) {
        //alert("Este Pedido No tine Productos");
        $("#listaPedidos").html("<li><h2>NO hay productos con el filtro seleccionado</h2></li>");
        $('#page').trigger('create');
        $("#infoClient").html("");
    } else {
        for (var i = 0; i < len; i++) {

            valor = results.rows.item(i).valor;
            Total += (valor );
            lol += '<li><a href="#"><h2>' + results.rows.item(i).descripcion + '</h2> <p> <strong>Total: </strong>' + formatNumber((valor), "$") + ' | X  ' + results.rows.item(i).cantidad + " " + results.rows.item(i).nommedida + '</p></a>' +
                    '<a href="#purchase' + results.rows.item(i).idproducto + '" value="' + results.rows.item(i).idproducto + ',' + results.rows.item(i).id_pedido + ',' + (valor * results.rows.item(i).cantidad) + ',' + results.rows.item(i).cantidad + '"  class="btndeleteP">Producto 1</a> </li>';

            infoCient = '<h2>Total: ' + formatNumber(Total, "$") + '</h2><h4>Productos (' + len + ') </h4>';
        }

        $("#infoClient").html(infoCient);
        $("#listaPedidos").html(lol);
        $("#listaPedidos").listview("refresh");
        //$("#popDelete").html(pop);    
        $('#page').trigger('create');
    }

}

function Proveedor() { 
    
    db.transaction(function (tx) {
        tx.executeSql('SELECT DISTINCT nombreprove,idregistro FROM producto', [], function (tx, results) {
            var len = results.rows.length;
           // alert(len);
            var lol = "";          
            for (var i = 0; i < len; i++) {              
                lol += '<option value="' + results.rows.item(i).idregistro + '">' + results.rows.item(i).nombreprove + '</option>';
                 
            }
            // alert(lol);
             $("#selectProveedor").html(lol);
        }, function (err) {
            alert("Error al procesar la información ");
            console.log(err.message)
        });
    }, function (err) {
        alert("Error al procesar la información ");
        console.log(err.message)
    });
}



// Query the success callback
//


