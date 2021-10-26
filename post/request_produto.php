<?php 
require('../database/connect.php');
    $metodo = $_POST['metodo'];
    $msg = "";

    switch($metodo){
        case 1:
            $nome = addslashes($_POST['nome']);

            // Validar cor como inteiro
            if (filter_var($_POST['cor'], FILTER_VALIDATE_INT)){
               $cor = $_POST['cor']; 
            }

            // Validar preco como float

            if (filter_var($_POST['preco'], FILTER_VALIDATE_FLOAT)){
                $preco = str_replace(',', '.',$_POST['preco']);
            }

            if(!empty($nome) && !empty($cor) && !empty($preco)){
                $insert = "INSERT INTO produtos (nome, cor) VALUES ('$nome', '$cor')";
                $INSERT = mysqli_query($connect, $insert);

                if($INSERT){
                    $id_produto = mysqli_insert_id($connect);
                  
                    $insert_preco = "INSERT INTO precos (idprod, preco) VALUES($id_produto, $preco)";
                    $INSERT_PRECO = mysqli_query($connect, $insert_preco);
                    $success = 1;
                }
            } 

            $msg .= "Produto $nome cadastrado com sucesso!";

            break;
        default:
            $msg = "Houve um erro...";
            $success = 0;
            break;
    }

    $output = ['success'=> $success, 'mensagem'=> $msg];
    echo json_encode($output);
?>
