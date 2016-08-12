/*
 *this file will  used for  manage the product information 
 *@Developed by Walther smith franco otero
 *@twitter: @walthersmith
 *@licenced: Creative commons v3
 */

//Global variables
var db;
var shorname = 'quarxoDb';
var version = '1.0';
var displayName = 'Quarxodb';
var maxSize = 42000000;

/* $(function (){
 alert("hello");
 db = window.openDatabase(shorname, version, displayName, maxSize);
 getProductos();
 storageGetValues();
 });*/

function onDeviceReady() {
    db = window.openDatabase(shorname, version, displayName, maxSize);
    getProductos();
    storageGetValues();
}

function d(err) {
    console.log("error al realizar la Acción: " + err.message);
}

function successG() {
    console.log("La acción se ha realizado satisfactoriamente.");
}

function borrarItem(id_item,cantidad) {
    var answer = confirm("¿Esta seguro/a que desea ELIMINAR este producto?");
    if (answer) {
        db.transaction(function (tx) {

            tx.executeSql("UPDATE metasdiarias set cantidadvendida = (cantidadvendida - "+cantidad+") where idproducto = " + id_item);
        }, function (err) {
            console.log("error al realizar la Acción: " + err.message);
        }, function () {
             console.log("Metas Actualizadas Antes de la Eliminacion del producto");
             db.transaction(function (tx) {
              tx.executeSql('DELETE FROM producto_pedidos WHERE idproducto = ' + id_item + " and idpedido = (select max(id) from pedido)");

            }, Error, function () {
                console.log("El producto se ha eliminado con exito");
                 getProductos();
            });   
        });
       
        // $("#purchase"+id_item).popup( "close" );
    }

}
function getProductosQuery(tx) {
    var SQL = "select pr.idproducto, pr.descripcion, pr.nommedida, pr.precioventa, pr.precioespecial1, pr.precioespecial2, pp.cantidad ,pp.valor,pp.id , (c.name ||' '|| c.lastname1 ||' '|| c.lastname2) as 'nombre'" +
            "from producto pr join producto_pedidos pp on pr.idproducto = pp.idproducto join pedido p  on p.id = pp.idpedido join client c on c.id = p.idcliente " +
            "where p.id = (select max(id) from pedido) and c.id = " + localStorage.idCliente;
    tx.executeSql(SQL, [], cargarProductos, function (err) {
        console.log("error al realizar la Acción: " + err.message);
    });
}

function getProductos() {
    db.transaction(getProductosQuery, function (err) {
        console.log("error al realizar la Acción: " + err.message);
    });
}

function cargarProductos(tx, results) {

    var len = results.rows.length;
    var lol = "";
    var id = getUrlVars();
    var pop = "";
    var clientInfo = "";
    var total = 0;
    valor = 0;
    if (len <= 0) {
        lol = "<li><h2>No hay productos agregados al pedido</h2>";
        $("#listProduct").html(lol);
        $("#listProduct").listview("refresh");
    } else {
        for (var i = 0; i < len; i++) {
            /*	if (localStorage.tipoPrecio == 'null' || localStorage.tipoPrecio == 0 || localStorage.tipoPrecio == 1 ) {
             valor = results.rows.item(i).precioventa;
             } else if(localStorage.tipoPrecio == 2){
             valor = results.rows.item(i).precioespecial1;
             }else if(localStorage.tipoPrecio == 3){
             valor = results. rows.item(i).precioespecial2;
             }*/
            valor = results.rows.item(i).valor;
            lol += '<li><a href="#"><h2>' + results.rows.item(i).descripcion + '</h2> <p><strong>$' + (valor * results.rows.item(i).cantidad) + '</strong> X ' + results.rows.item(i).cantidad + '  ' + results.rows.item(i).nommedida + ' (Unidad: $' + valor + ')</p></a>' +
                    '<a href="#purchase' + results.rows.item(i).idproducto + '"  value="' + results.rows.item(i).idproducto + '|'+results.rows.item(i).cantidad+'" class="btndelConf">Producto 1</a></li>';

            /*	pop+='<div data-role="popup" id="purchase'+results.rows.item(i).idproducto+'" data-theme="a" data-overlay-theme="b" class="ui-content" style="max-width:340px; padding-bottom:2em;">'+
             '<h3>¿Seguro/a Quieres eliminar este producto?</h3>'+
             '<button data-rel="back" class="ui-shadow ui-btn ui-corner-all ui-btn-b ui-icon-check ui-btn-icon-left ui-btn-inline ui-mini" id="deleteproduct'+results.rows.item(i).idproducto+'" value="'+results.rows.item(i).idproducto+'">Eliminar</button>'+
             '<a href="index.html" data-rel="back" class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-mini">Cancelar</a>'+
             '</div>';*/
            total += (valor * results.rows.item(i).cantidad);
            clientInfo = "<h2>Cliente: " + results.rows.item(i).nombre + "</h2>" +
                    "<h2>Total a pagar: " + formatNumber(total, "$") + "</h2>" +
                    "<h3>Productos</h3>";
        }
        saveTotal(total);//Guarda el valor total del pedido
        $("#listProduct").html(lol);
        $("#listProduct").listview("refresh");
        //$("#popup").html(pop);
        $('#page').trigger('create');
        $('#clientInfo').html(clientInfo);

        $(".btndelConf").on('click', function () {
            //alert($(this).val());
            var values = $(this).attr("value").split("|");
            borrarItem(values[0],values[1]);
        });
    }

}

function  confirmar(descripcion, fecha) {
    var sql = "update  pedido set estado = 1, descripcion = '" + descripcion + "', fecha='" + fecha + "'  where id = (select max(id) from pedido)";
    db.transaction(function (tx) {
        tx.executeSql(sql);
    }, function (err) {
        console.log("error al realizar la Acción: " + err.message);
    }, function () {
        alert("El Pedido se ha Guardado y Confirmado");
        window.location.href = "../../modules/order/orders.html";
    });

}

function  saveTotal(vl_pedido) {
    var sql = "update  pedido set  valor_pedido = " + vl_pedido + " where id = (select max(id) from pedido)";
    db.transaction(function (tx) {
        tx.executeSql(sql);
    }, function (err) {
        console.log("error al realizar la Acción: " + err.message);
    }, function () {
        console.log("El Valor total del pedido se ha guardado");

    });

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

function storageGetValues() {
    var id = getUrlVars();
    if (id.length > 0) {
        localStorage.tipoPrecio = id['tc'];
        localStorage.idCliente = id['clid'];
        $("#ufo").attr('href', 'addOrderstp2.html?cl=' + id['tc'] + '&idcl=' + id['clid']);
        // alert('addOrderstp2.html?cl='+ id['tc'] +'&idcl='+id['clid']);
    }
}

function formatNumber(num, prefix) {
    num = Math.round(parseFloat(num) * Math.pow(10, 2)) / Math.pow(10, 2)
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