<?php 
    require_once "database/connect.php";
    require_once "assets/functions.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS Externo -->
    <link rel="stylesheet" href="css/main.css">
    <!-- JS Externo -->
    <script src="js/main.js"></script>
    <title>Pagina Principal</title>
</head>
<body>
    <h2>Produtos</h2>
    <?php
        $sql = "SELECT p.idprod as id, p.nome, pc.preco, c.cor, c.idcor FROM produtos p JOIN cores c ON p.cor = c.idcor JOIN precos pc ON pc.idprod = p.idprod";
        $q = mysqli_query($connect, $sql);

        if (mysqli_num_rows($q) == 0){
            echo "A lista de produtos esta vazia<br>";
        } else {
    ?>

<fieldset>
    <legend>Filtros</legend>
    <input type="text" id="busca" name="busca" placeholder="Buscar produtos">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email">
    <input type="submit" value="Filtrar" onclick="buscar()">
  </fieldset>

    <table class="produtos">
        <thead>
            <tr>
                <th>Nome do Produto</th>
                <th>Cor</th>
                <th>Preço</th>
                <th colspan="2" class="centro">Açoes</th>
            </tr>
        </thead>

        <tbody id="conteudo">
            <?php 
                    $param = base64_encode('id');
                    while ($res = mysqli_fetch_array($q)){
                        $id_64 = base64_encode($res['id']);
                        $preco = resolvePreco($res['idcor'], $res['preco']);
                        ?>
                        <tr>
                            <td><?= $res['nome'] ?></td>
                            <td><?= $res['cor'] ?></td>
                            <td class="number">R$ <?= number_format($preco, 2, ",", ".") ?></td>
                            <td class="centro"><a href='pages/edit_produto.php?i=<?= $id_64 ?>'>Editar</a>
                            <td class="centro"><a href="#" onclick="apagarProd('post/request_produto.php', <?= $res['id'] ?>, '<?= $res['nome'] ?>', 'index.php')">Apagar</a>
                        </tr>
                        <?php 
                    }
            ?>
        </tbody>
    </table>
    <?php 
        }
    ?>
<br>
    <a href="pages/novo_produto.php"><button>Cadastrar Produto</button></a><br />
<br>
 <h2>Cores</h2>
    <?php
        $sql = "SELECT c.cor, count(p.cor) as qtd_prod FROM cores c LEFT JOIN produtos p ON p.cor = c.idcor  GROUP BY idcor";
        $q = mysqli_query($connect, $sql);

        if (mysqli_num_rows($q) == 0){
            echo "A lista de cores esta vazia<br>";
        } else {
    ?>
    <table>
        <thead>
            <tr>
                <th>Cor</th>
                <th>Quant. Produtos</th>
            </tr>
        </thead>

        <tbody>
            <?php 
                    while ($res = mysqli_fetch_array($q)){
                    ?>
                        <tr>
                            <td><?= $res['cor'] ?></td>
                            <td class="number"><?= $res['qtd_prod'] ?></td>
                        </tr>
                    <?php
                    }
            ?>
        </tbody>
    </table>
    <?php 
        }
    ?>

    <!-- 
    <br>
    <a href="pages/nova_cor.php"><button>Cadastrar Cor</button></a><br />
    -->
</body>
</html>

