<?php
    require('../database/connect.php');

   // Resolve o id no parametro
    $id = base64_decode($_GET['i']);

    // Adiciona escapes caso seja inserido um texto
    $id = addslashes($id);

    $sql = "SELECT p.nome, p.cor, pr.preco FROM produtos p JOIN precos pr ON pr.idprod = p.idprod WHERE p.idprod='$id'";

    $q = mysqli_query($connect, $sql);

    if($q){
        $res = mysqli_fetch_array($q);

        $id_64 = $_GET['i'];
    } else {
         $alert = "<script>alert('Nao foi possivel encontrar o produto')</script>";
    }

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

    <title>Editar Produto</title>
</head>
<body>
    <?= $alert ?>
    <h2>Ediçao de Produto</h2>
    <fieldset>
        <legend><?= ucfirst($res['nome']); ?></legend>
        <form method="post" id="form_produto" name="form_produto" action="database/cadastrar_produto.php">
            
            <label for="nome">Nome</label> <br />
            <input id="nome" name="nome" type="text" value="<?= $res['nome'] ?>" placeholder="Nome do produto"  />
            <br /> <br />

            <label for="cor">Cor</label> <br />
            
            <?php 
                $cor = $res['cor'];
                $sql = "SELECT idcor, cor FROM cores WHERE idcor = '$cor'";
                $q = mysqli_query($connect, $sql);   
                $r = mysqli_fetch_array($q);
            ?>
            <input type="text" disabled value="<?= $r['cor'] ?>"></input>
            <br /> <br />

            <label for="preco">Preço</label> <br />
            <input id="preco" name="preco" type="number" step=".01" value="<?= number_format($res['preco'], 2, ",", "") ?>" placeholder="0,00"/>
            <br /> <br />

            <input type="hidden" name="metodo" value="2">
            <input type="hidden" name="i" value="<?= $id_64 ?>">
            <button type="submit" onclick="return validaSubmitProduto()" id="cadastrar" name="cadastrar" value="1">Atualizar</button>
            <a href="../index.php" ><button type="button" name="button" value="0">Voltar</button></a>

        </form>
    </fieldset>
    <!-- JS Externo -->
    <script src="../assets/js/novo_produto.js"></script> 

</body>
</html>

