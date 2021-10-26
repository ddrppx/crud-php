<?php 
    require_once "database/connect.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <title>Pagina Principal</title>
</head>
<body>
    <h2>Produtos</h2>
    <?php
        $sql = "SELECT p.nome, c.cor FROM produtos p JOIN cores c ON p.cor = c.idcor JOIN precos pc ON pc.idprod = p.idprod";
        $q = mysqli_query($connect, $sql);

        if (mysqli_num_rows($q) == 0){
            echo "A lista de produtos esta vazia<br>";
        } else {
    ?>
    <table>
        <thead>
            <tr>
                <th>Nome do Produto</th>
                <th>Cor</th>
                <th>Preço</th>
            </tr>
        </thead>

        <tbody>
            <?php 
                    while ($res = mysqli_fetch_array($q)){
                        echo "<tr>";
                        echo "<td>". $res['nome'] ."</td>";
                        echo "<td>". $res['cor'] ."</td>";
                        echo "<td>". $res['preco']."</td>";
                        echo "<td><a href='update.php?id=". $data['id']."'>Editar</a>";
                        echo "<td><a href='database/delete.php?id=". $data['id']. "'>Apagar</a>";
                        echo "</tr>";
                    }
            ?>
        </tbody>
    </table>
    <?php 
        }
    ?>
<br>
    <a href="create.php"><button>Cadastrar Produto</button></a><br />
<br>
 <h2>Cores</h2>
    <?php
        $sql = "SELECT cor FROM cores";
        $q = mysqli_query($connect, $sql);

        if (mysqli_num_rows($q) == 0){
            echo "A lista de cores esta vazia<br>";
        } else {
    ?>
    <table>
        <thead>
            <tr>
                <th>Cor</th>
                <th>Prod. Com cor</th>
            </tr>
        </thead>

        <tbody>
            <?php 
                    while ($res = mysqli_fetch_array($q)){
                        echo "<tr>";
                        echo "<td>". $res['cor'] ."</td>";
                        echo "<td></td>";
                        echo "<td><a href='update.php?id=". $data['id']."'>Editar</a>";
                        echo "<td><a href='database/delete.php?id=". $data['id']. "'>Apagar</a>";
                        echo "</tr>";
                    }
            ?>
        </tbody>
    </table>
    <?php 
        }
    ?>

<br>
    <a href="create.php"><button>Cadastrar Cor</button></a><br />
</body>
</html>

