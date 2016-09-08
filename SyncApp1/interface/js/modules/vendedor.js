/* 
 * Copyright (c) 2015 Walther Smith Franco Otero.
 * All rights reserved. 
 *    Walther Smith Franco Otero  | @walthersmih | http://about.me/walthermsith
 */



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
