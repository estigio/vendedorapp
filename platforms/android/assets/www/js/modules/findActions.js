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
    getVariables(id['id_pedido']);
    getProducts(id['id_pedido']);
    Proveedor();

}



function getProducts(id_pedido) {

    db.transaction(function (tx) {
        var sql = 'select ( c.name ||" "|| c.lastname1 ||" "|| c.lastname2 ) as nombre, c.tipoprecio,  pr.idproducto, pr.descripcion,' +
                'pr.nommedida, pr.precioventa, pr.precioespecial1, pr.precioespecial2, pp.cantidad,pp.valor, p.id as id_pedido, p.descripcion as desc_p ' +
                'from producto pr join producto_pedidos pp on pr.idproducto = pp.idproducto ' +
                'join pedido p on p.id = pp.idpedido join client c on c.id = p.idcliente where p.id = ' + id_pedido;
        tx.executeSql(sql, [], cargarPedidos, Error);
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
        $("#delButton").html('<a data-role="button"  id="borrarPedido">Eliminar Pedido</a>');
        $('#page').trigger('create');
        $("#borrarPedido").click(function () {
            deleteOrder(id['id_pedido']);
        });
    } else {
        for (var i = 0; i < len; i++) {
            /*if (results.rows.item(i).tipoprecio == null || results.rows.item(i).tipoprecio == 0 || results.rows.item(i).tipoprecio == 1) {
             valor = results.rows.item(i).precioventa;
             }else if (results.rows.item(i).tipoprecio == 2) {
             valor = results.rows.item(i).precioespecial1;
             }else if (results.rows.item(i).tipoprecio == 3) {
             valor = results.rows.item(i).precioespecial2;
             }*/
            valor = results.rows.item(i).valor;
            Total += (valor * results.rows.item(i).cantidad);
            lol += '<li><a href="#"><h2>' + results.rows.item(i).descripcion + '</h2> <p> <strong>Total: </strong>' + formatNumber((valor * results.rows.item(i).cantidad), "$") + ' | X  ' + results.rows.item(i).cantidad + " " + results.rows.item(i).nommedida + '</p></a>' +
                    '<a href="#purchase' + results.rows.item(i).idproducto + '" value="' + results.rows.item(i).idproducto + ',' + results.rows.item(i).id_pedido + ',' + (valor * results.rows.item(i).cantidad) + ',' + results.rows.item(i).cantidad + '"  class="btndeleteP">Producto 1</a> </li>';

            /*
             '<a href="#purchase'+results.rows.item(i).idproducto+'" data-rel="popup" data-position-to="window" data-transition="pop">Producto 1</a> </li>';
             */

            /*pop += '<div data-role="popup" id="purchase'+results.rows.item(i).idproducto+'" data-theme="a" data-overlay-theme="b" class="ui-content" style="max-width:340px; padding-bottom:2em;">'+
             '<h3>¿Desea Eliminar este producto del pedido?</h3>'+
             '<p>'+results.rows.item(i).descripcion+' Valor: '+formatNumber((valor*results.rows.item(i).cantidad),"$")+'</p>'+
             '<button value="'+results.rows.item(i).idproducto+','+results.rows.item(i).id_pedido+','+(valor*results.rows.item(i).cantidad)+','+results.rows.item(i).cantidad+'" data-rel="back" class="btnselect ui-shadow ui-btn ui-corner-all ui-btn-b ui-icon-check ui-btn-icon-left ui-btn-inline ui-mini">Eliminar</button>'+
             '<a href="index.html" data-rel="back" class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-mini">X</a>'+
             '</div>';	*/

            infoCient = '<h2>Cliente: ' + results.rows.item(i).nombre + '</h2> <h2>Total a Pagar: ' + formatNumber(Total, "$") + '</h2><strong>Descripcion</strong><div class="ui-body ui-body-a ui-corner-all"><p>' + results.rows.item(i).desc_p + '</p></div><h4>Productos (' + len + ') </h4>';
        }

        $("#infoClient").html(infoCient);
        $("#listaPedidos").html(lol);
        $("#listaPedidos").listview("refresh");
        //$("#popDelete").html(pop);       
        $("#delButton").html('<a data-role="button"  id="borrarPedido">Eliminar Pedido</a>');

        $('#page').trigger('create');

        $("#borrarPedido").click(function () {
            deleteOrder(id['id_pedido']);
        });

        /*   $(".btndeleteP").on('click', function(){	 
         //var ident = ($(this).val()+","+Total).split(","); 
         var ident = $(this).attr("value");
         alert("hello: "+ ident);
         });
         */
//                 $("#editarPedido").click(function(){
//	        	window.location.href=this.url;
//	       	});
        $(".btndeleteP").click(function () {
            var ident = ($(this).attr("value") + "," + Total).split(",");
            deleteProduct(ident);
            getProducts(id['id_pedido']);
            //$("#purchase"+ident[0]).popup( "close" );
        });

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



function deleteProduct(IDs) {
    //alert(IDs[3]);
    var answer = confirm("¿Esta seguro/a que desea ELIMINAR este producto?");
    if (answer) {
        db.transaction(function (tx) {
            var sql = "DELETE  from producto_pedidos where idpedido =" + IDs[1] + " and idproducto = " + IDs[0];
            tx.executeSql(sql);
        }, Error, function () {
            alert("El producto Seleccionado se ha eliminado con exito");

            db.transaction(function (tx) {
                tx.executeSql("UPDATE pedido set valor_pedido = (valor_pedido-"+IDs[2]+") where id = " + IDs[1]);
                tx.executeSql("UPDATE metasdiarias set cantidadvendida = (cantidadvendida - " + (IDs[3]) + ") where idproducto = " + IDs[0]);
            }, Error, function () {
                console.log("pecio total actualizado");
            });

        });
    } else {
        alert("Ningun producto se eliminara");
    }
}




function deleteOrder(id_order) {
    var answer = confirm("¿Esta seguro/a que desea eliminar este pedido?");
    if (answer) {
        actualizarCantidadMetaPedidoEliminado(id_order);
        updateEstado(id_order, "SP");
        db.transaction(function (tx) {
            var sql = "DELETE FROM producto_pedidos WHERE idpedido = " + id_order;
            tx.executeSql(sql);
        }, Error, function () {
            db.transaction(function (tx) {
                var sq = "DELETE FROM pedido where id = " + id_order;
                tx.executeSql(sq);
            }, Error, function () {
                alert("El pedido se ha borrado exitosamente");
                window.location.href = "findOrder.html";
            });
        });
    } else {
        console.log("no se realiza ninguna acción");
    }
}

function getVariables(idpedido) {
    db.transaction(function (tx) {
        var SQL = "SELECT c.id, c.tipoprecio  from CLIENT c join  pedido p on c.id = p.idcliente  " +
                " where p.id =" + idpedido;
        tx.executeSql(SQL, [], function (tx, results) {
            if (results.rows.length > 0) {
                for (var i = 0; i < results.rows.length; i++) {
                    this.url = "mod_addOrderstp2.html?idpedido=" + idpedido + "&cl=" + results.rows.item(i).tipoprecio + "&idcl=" + results.rows.item(i).id;
                    $("#editButton").html('<a href="' + this.url + '" data-role="button"  rel="external" id="editarPedido">Editar Pedido</a>');
                }
            }
        }, function (err) {
            console.log("Error al procesar la consulta " + err.message);
        });
    }, function (err) {
        console.log("Error en la transacción " + err.message);
    });
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


function  updateEstado(id, estado) {
    var sql = "update  CLIENT set estado = '" + estado + "' where id= (select idcliente from pedido where id = " + id + ")";
    db.transaction(function (tx) {
        tx.executeSql(sql);
    }, function (err) {
        console.log("error al realizar la Acción: " + err.message);
    }, function () {
        if (estado === 'SP') {
            alert("El Cliente Se Ha Marcado Como Visto Sin Pedido");
        } else {
            console.log("Estado del cliente Acualizado");
        }
    });

}





function getProductsFiltro(id_pedido,idregistro) {

    db.transaction(function (tx) {
        var sql = 'select ( c.name ||" "|| c.lastname1 ||" "|| c.lastname2 ) as nombre, c.tipoprecio,  pr.idproducto, pr.descripcion,' +
                'pr.nommedida, pr.precioventa, pr.precioespecial1, pr.precioespecial2, pp.cantidad,pp.valor, p.id as id_pedido, p.descripcion as desc_p ' +
                'from producto pr join producto_pedidos pp on pr.idproducto = pp.idproducto ' +
                'join pedido p on p.id = pp.idpedido join client c on c.id = p.idcliente where p.id = ' + id_pedido+" and pr.idregistro= "+idregistro;
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
        $("#delButton").html('');
        $('#page').trigger('create');
        $("#infoClient").html("");
    } else {
        for (var i = 0; i < len; i++) {

            valor = results.rows.item(i).valor;
            Total += (valor * results.rows.item(i).cantidad);
            lol += '<li><a href="#"><h2>' + results.rows.item(i).descripcion + '</h2> <p> <strong>Total: </strong>' + formatNumber((valor * results.rows.item(i).cantidad), "$") + ' | X  ' + results.rows.item(i).cantidad + " " + results.rows.item(i).nommedida + '</p></a>' +
                    '<a href="#purchase' + results.rows.item(i).idproducto + '" value="' + results.rows.item(i).idproducto + ',' + results.rows.item(i).id_pedido + ',' + (valor * results.rows.item(i).cantidad) + ',' + results.rows.item(i).cantidad + '"  class="btndeleteP">Producto </a> </li>';

            infoCient = '<h2>Cliente: ' + results.rows.item(i).nombre + '</h2> <h2>Total: ' + formatNumber(Total, "$") + '</h2><strong>Descripcion</strong><div class="ui-body ui-body-a ui-corner-all"><p>' + results.rows.item(i).desc_p + '</p></div><h4>Productos (' + len + ') </h4>';
        }

        $("#infoClient").html(infoCient);
        $("#listaPedidos").html(lol);
        $("#listaPedidos").listview("refresh");
        //$("#popDelete").html(pop);       
        $("#delButton").html('<a data-role="button"  id="borrarPedido">Eliminar Pedido</a>');

        $('#page').trigger('create');

        $("#borrarPedido").click(function () {
            deleteOrder(id['id_pedido']);
        });


        $(".btndeleteP").click(function () {
            var ident = ($(this).attr("value") + "," + Total).split(",");
            deleteProduct(ident);
            getProducts(id['id_pedido']);
            //$("#purchase"+ident[0]).popup( "close" );
        });

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


