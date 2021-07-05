<?php
/* Foi usado o Webserver XAMPP com a base de dados SQL MariaDB
Utilizador 'root', sem passoword */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'mysql');

/* Conexão à base de dados */
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verifica a conexão
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>
