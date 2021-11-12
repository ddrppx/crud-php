<?php 
# Incluindo as constantes de configuraçao do banco
require('db_config.php');

$servername = SERVER_NAME;
$username = USER_NAME;
$password = PASSWORD;
$db_name = DB_NAME;

# Estabelecendo conexao com o banco
# PDO connection
try{ 
    $connect = new PDO("mysql:host=$servername;dbname=$db_name", $username, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e){
    ECHO "ERROR: ". $e->getMessage();
}


?>
