<?php

class Connection {

    /**
     * Gestiona la conexión con la base de datos
     */
    private $dbhost = 'localhost';
    private $dbuser = 'root';
    private $dbpass = '';
    private $dbname = 'bd_app';

    public function Connect() {
        /**
         * @return object link_id con la conexión
         */
        $link_id = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
        if ($link_id->connect_error) {
            echo "Error de Connexion ($link_id->connect_errno)
$link_id->connect_error\n";
            header('Location: error-conexion.php');
            exit;
        } else {
            mysqli_set_charset($link_id,"utf8"); 
            return $link_id;
        }
    }

}

?>