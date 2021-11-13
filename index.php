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
    <link rel="stylesheet" href="assets/css/main.css">
    <!-- JS Externo -->
    <script src="assets/js/main.js"></script>
    <title>Pagina Principal</title>
</head>
<body>
    <h2>Produtos</h2>
    <?php
        $sql = "SELECT p.idprod as id, p.nome, pc.preco_descontado, c.cor, c.idcor FROM produtos p JOIN cores c ON p.cor = c.idcor JOIN precos pc ON pc.idprod = p.idprod";
        $q_produtos = $connect->query($sql);
        if ($q_produtos->rowCount() == 0){
            echo "A lista de produtos esta vazia<br>";
        } else {
    ?>

<fieldset>
    <legend>Filtros</legend>
    <input type="text" id="busca" name="busca" placeholder="Buscar Por Nome">
    <select id="cor" name="cor">
        <option value="0" selected>Cores</option>
    <?php 
        $sql = "SELECT c.idcor, c.cor FROM cores c"; 
        $q_cores = $connect->query($sql);
        while($r = $q_cores->fetch()){
            ?>  
                <option value="<?= $r['idcor'] ?>"><?= $r['cor'] ?></option>
            <?php
        }
    ?>
    <select>
    <br><br>
    Preço  
    R$<input id="preco" name="preco" type="number" step=".01" placeholder="0,00" />
    Tipo 
    <select id="tipo" name="tipo">
        <option value="1">Maior que</option>    
        <option value="2">Menor que</option>    
        <option value="3">Igual</option>    
    </select>
    <input type="submit" value="Filtrar" onclick="buscar()">
</fieldset>
<br>
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
                    while ($res = $q_produtos->fetch()){
                        $id_64 = base64_encode($res['id']);
                        ?>
                        <tr>
                            <td><?= $res['nome'] ?></td>
                            <td><?= $res['cor'] ?></td>
                            <td class="number">R$ <?= number_format($res['preco_descontado'], 2, ",", ".") ?></td>
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
        $sql_cores_produtos = "SELECT c.cor, count(p.cor) as qtd_prod FROM cores c LEFT JOIN produtos p ON p.cor = c.idcor GROUP BY idcor";
        $q_cores_produtos = $connect->query($sql_cores_produtos);
        if ($q_cores_produtos->rowCount() <= 0){
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
                    $q_cores_produtos = $connect->query($sql_cores_produtos);
                    while ($res = $q_cores_produtos->fetch()){
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
</body>
</html>

