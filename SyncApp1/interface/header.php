<?php

/* 
 * Copyright (c) 2015 Walther Smith Franco Otero.
 * All rights reserved.
 *    Walther Smith Franco Otero  | @walthersmih | http://about.me/walthermsith
 */


 function getHeader(){
echo ' <header>
            <!-- Fixed navbar -->
            <nav class="navbar navbar-default navbar-fixed-top">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">TOMADOR DE PEDIDOS</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">TOMADOR DE PEDIDOS</a>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <li><a href="#">Inicio</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Vendedor<span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                  <li><a href="metas.php">Metas Diarias</a></li>
                                   <li><a href="meta_listado.php">Listado Metas</a></li>
                                  <li><a href="listado.php">Listado de Clientes</a></li>
								  <li><a href="listado_ventas.php">Informe de Ventas</a></li>                                
                                </ul>
                              </li>
                            <li><a href="sincronizar.php">Sincronizar</a></li>  
                            <li><a href="pendientes.php">Pendientes</a></li>  
                        </ul>         
                    </div><!--/.nav-collapse -->
                </div>
            </nav>
        </header>';
}
