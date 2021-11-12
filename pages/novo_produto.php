<?php
    require('../database/connect.php');
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS Externo -->
    <link rel="stylesheet" href="../assets/css/main.css">
    <!-- JS Externo -->
    <script src="../assets/js/main.js"></script>

    <title>Novo Produto</title>
</head>
<body>
    <h1>Cadastro de Produto</h1>

    <form method="post" id="form_produto" name="form_produto" action="database/cadastrar_produto.php">
        
        <label for="nome">Nome</label> <br />
        <input id="nome" name="nome" type="text"  placeholder="Nome do produto"  />
        <br /> <br />

        <label for="cor">Cor</label> <br />
        
        <?php 
            // Trazendo todas as cores
            $sql = "SELECT idcor, cor FROM cores";
            $q = $connect->query($sql);  
            if(count($q->fetchColumn()) > 0){
        ?>
            <select id="cor" name="cor"> 
                <option selected disabled>Selecione...</option>
        <?php
                while($r = $q->fetch()){
                    ?>
                    <option value="<?= $r['idcor'] ?>"><?= $r['cor'] ?></option>
                    <?php 
                }
            }
        ?>
            </select>
        <br /> <br />

        <label for="preco">Preço</label> <br />
        <input id="preco" name="preco" type="number" step=".01" placeholder="0,00"/>
        <br /> <br />

        <input type="hidden" name="metodo" value="1">
        <button type="submit" onclick="return validaSubmitProduto()" id="cadastrar" name="cadastrar" value="1">Cadastrar</button>
    <form>

    <!-- JS Externo -->
    <script src="../assets/js/novo_produto.js"></script> 

</body>
</html>
