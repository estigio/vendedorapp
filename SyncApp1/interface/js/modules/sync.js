/* 
 * Copyright (c) 2015 Walther Smith Franco Otero.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Walther Smith Franco Otero  | @walthersmih | http://about.me/walthermsith
 */

function syncPedidosTablet() {
    $("#log").html("<h2>Procesando la Información</h2>");
    $.ajax({
        url: "../API/procesoSync",
        type: 'post',
        async: true,
        format: "jsonp",
        crossDomain: true
    }).done(function (data) {
        $("#log").html(data);
        $('#Process').modal('hide');
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error.code;
        alert("Request Failed: " + err);
    });
}

function syncPedidosFacturaTablet() {
    $("#log").html("<h2>Procesando la Información</h2>");
    exeShow();
    $.ajax({
        url: "../API/procesoSyncFactura",
        type: 'post',
        async: true,
        format: "jsonp",
        crossDomain: true
    }).done(function (data) {
        $("#log").html(data);
         $('#Process').modal('hide');
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error.code;
        alert("Request Failed: " + err);
    });
}


function nuevatablet() {
    $("#log").html("<h2>Preparando para una nueva sincronizacion</h2>");
    $.ajax({
        url: "../API/limpiarTablasincronizacion",
        type: 'post',
        async: true,
        format: "jsonp",
        crossDomain: true
    }).done(function (data) {
        $("#log").html("ya puede sincronizar una nueva tablet");
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error.code;
        alert("Request Failed: " + err);
    });
}


function exeShow() {
    
        $('#Process').modal({
            backdrop: 'static',
            keyboard: true,
            show: true
        });
   
}

