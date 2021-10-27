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

    <title>Nova Cor</title>
</head>
<body>
    <h1>Cadastro de Cor</h1>

        <?php 
            $sql = "SELECT idcor, cor FROM cores";
            $q = mysqli_query($connect, $sql);   
            if(mysqli_num_rows($q) > 0){
        ?>
            <table>
            <tr><td colspan="2">Cores</td></tr>
        <?php
        ?>
        <?php
            while($r = mysqli_fetch_array($q)){
                ?>
                <tr>
                    <td><?= $r['idcor'] ?></td>
                    <td><?= $r['cor'] ?></td>
                </tr>
                <?php 
            }
        ?>
            </table>
        <?php 
        }
    ?>

    <br>
    <form method="post" id="form_cor" name="form_cor">

        <label for="cor">Cor</label> <br />
        <input id="cor" name="cor" type="text"  placeholder="Insira o nome da cor"/>
        <br /> <br />

        <input type="hidden" name="metodo" value="1">
        <button type="submit" onclick="return validaSubmitCor()" id="cadastrar" name="cadastrar" value="1">Cadastrar</button>
    <form>

    <!-- JS Externo -->
    <script src="../assets/js/nova_cor.js"></script> 

</body>
</html>
