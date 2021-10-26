<?php 
# Incluindo as constantes de configuraçao do banco
require('db_config.php');

$servername = SERVER_NAME;
$username = USER_NAME;
$password = PASSWORD;
$db_name = DB_NAME;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

# Estabelecendo conexao com o banco
$connect = mysqli_connect(
    $servername, 
    $username, 
    $password, 
    $db_name
);

mysqli_set_charset($connect, "utf8");

if (mysqli_connect_error()) {
    echo "Erro na conexão: ". mysqli_connect_error();
}

?>
