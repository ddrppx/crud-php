<?php 
    $dados = print_r($_POST, true);

    $output = ['mensagem'=> $dados];
    echo json_encode($output);
?>
