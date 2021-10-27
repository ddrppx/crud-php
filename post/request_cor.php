<?php 
require("../database/connect.php");

    $sucesso = 0;
    $metodo = $_POST['metodo'];

    switch($metodo){
        // Cadastrar nova cor
        case 1:
            $cor = addslashes($_POST['cor']);
            $sql = "INSERT INTO cores(cor) VALUES ('$cor')";

            $INSERT = mysqli_query($connect, $sql);
            if ($INSERT){
                $msg = "Cor $cor cadastrada com sucesso!";
                $sucesso = 1;
            }
            break;
        default:
            $msg = "Houve um erro ao encontrar o metodo";
            break;
    }

    $output = ['sucesso' => $sucesso, 'mensagem'=> $msg];
    echo json_encode($output);
?>
