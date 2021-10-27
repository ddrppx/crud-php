<?php 
require_once '../database/connect.php';
require_once '../assets/functions.php';

    $metodo = $_POST['metodo'];
    $msg = "";

    // Switch metodo
    // 1 - Criar (Insert)
    // 2 - Atualizar (Update)
    // 3 - Exclusão (Delete)
    // 4 - Busca/Filtragem (Select)
    switch($metodo){
        // Cadastrar novo produto
        case 1:
            $nome = addslashes($_POST['nome']);

            // Validar cor como inteiro
            if (filter_var($_POST['cor'], FILTER_VALIDATE_INT)){
               $cor = $_POST['cor']; 
            }

            // Validar preço como float
            if (filter_var($_POST['preco'], FILTER_VALIDATE_FLOAT)){
                $preco = str_replace(',', '.',$_POST['preco']);
            }

            // Checa por variaveis vazias
            if(!empty($nome) && !empty($cor) && !empty($preco)){
                $insert = "INSERT INTO produtos (nome, cor) VALUES ('$nome', '$cor')";
                $INSERT = mysqli_query($connect, $insert);

                if($INSERT){
                    $id_produto = mysqli_insert_id($connect);
                  
                    $preco_descontado = resolvePreco($cor, $preco);
                    $insert_preco = "INSERT INTO precos (idprod, preco, preco_descontado) VALUES($id_produto, $preco, $preco_descontado)";
                    $INSERT_PRECO = mysqli_query($connect, $insert_preco);

                    $msg .= "Produto $nome cadastrado com sucesso!";
                    $success = 1;
                }
            } else {
                $msg = "Você tem dados sobrando para completar o cadastro.";
                $success = 0;
            } 


            break;
            // Update do produto
        case 2:
            $nome = addslashes($_POST['nome']);
            $preco = str_replace(',','.', $_POST['preco']);

            // Descodifica o ID
            $id_POST = base64_decode($_POST['i']);

            if (filter_var($id_POST, FILTER_VALIDATE_INT) && filter_var($preco, FILTER_VALIDATE_FLOAT)){
               $id = $id_POST; 
               $success = 1;
            } else {
                $success = 0;
            }

            $sql = "SELECT p.idprod, p.nome, pr.preco, p.cor FROM produtos p JOIN precos pr ON pr.idprod = p.idprod WHERE p.idprod='$id'";

            // Checa se existe o id e se passou pelos filtros
            if($id && $success) {
                $q = mysqli_query($connect, $sql);
                $r = mysqli_fetch_array($q);
                
                $id_prod = $r['idprod'];

                // Armazenar os itens atualizados para mostrar na mensagem 
                $atualizados = [];

                // Se o nome tiver mudado entra na condiçao
                // e executa o update
                if($r['nome'] != $nome){
                    $sql = "UPDATE produtos SET nome = '$nome' WHERE idprod = '$id'" ;
                    $update = mysqli_query($connect, $sql);
                    
                    if($update){
                        $atualizados[] = "Nome";
                    } else {
                        $success = 0;
                    }
                }

                // Se o preço tiver mudado entra na condiçao
                // e executa o update
                if($r['preco'] != $preco){ 
                    $preco_descontado = resolvePreco($r['cor'], $preco);
                    $sql = "UPDATE precos SET preco = '$preco', preco_descontado = '$preco_descontado' WHERE idprod = '$id'";
                    $update_preco = mysqli_query($connect, $sql);
                    if($update_preco){
                       $atualizados[] = "Preço";
                    } else {
                        $success = 0;
                    }
                    
                }
                // Verifica se atualizou mais de um
                // e põe um 'e' entre eles 
                if (count($atualizados) > 1) {
                    $att = implode(' e ', $atualizados);
                } else {
                    $att = $atualizados[0];
                }
                $msg .= "$att Atualizado(s).";
            } 

            break;
            // Exclusão de produtos
        case 3:
            // id
            $id_POST = $_POST['id'];

            // Verifica o id enviado pela requisição
            if (filter_var($id_POST, FILTER_VALIDATE_INT)){
                $id = $id_POST; 

                // Se validado o id, executa a exclusão
                $sql_preco = "DELETE FROM precos WHERE idprod = '$id'";
                $DELETE_PC = mysqli_query($connect, $sql_preco);

                if($DELETE_PC) {
                    $sql_prod = "DELETE FROM produtos WHERE idprod = '$id'";
                    $DELETE = mysqli_query($connect, $sql_prod);

                    if($DELETE){
                        $msg = "deletado com sucesso.";
                        $success = 1;
                    } else {
                        $msg = "Erro ao deletar...";
                        $success = 0;
                    }
                } else {
                    $success = 0;
                }

            }

                
            break;
            // Busca de produtos
        case 4:
            $success = 0;
            $busca = addslashes($_POST['busca']);
            $cor = filter_var($_POST['cor'], FILTER_VALIDATE_INT) ? addslashes($_POST['cor']) : null;
            $tipo = filter_var($_POST['tipo'], FILTER_VALIDATE_INT) ? addslashes($_POST['tipo']) : null;
            $preco = filter_var($_POST['preco'], FILTER_VALIDATE_FLOAT) ? addslashes($_POST['preco']) : null;
            if(!empty($cor)){
                $sql_cor = "AND c.idcor = '$cor'";
            }
            if(!empty($preco)){
                /*
                    1 - Maior que
                    2 - Menor que
                    3 - Igual
                */
                switch($tipo){
                    case 1:
                        $acao = " >= ";
                        break;
                    case 2:
                        $acao = " <= ";
                        break;
                    case 3:
                        $acao = " = ";
                        break;
                }
                $sql_preco = "AND pc.preco_descontado $acao $preco";
        }
        $sql = "SELECT p.idprod as id, p.nome, pc.preco_descontado, c.cor, c.idcor 
            FROM produtos p 
            JOIN cores c ON p.cor = c.idcor 
            JOIN precos pc ON pc.idprod = p.idprod
            WHERE nome LIKE '%$busca%'
            $sql_cor
            $sql_preco
            ";
        
        $msg = $sql;
        $q = mysqli_query($connect, $sql);

        if(mysqli_num_rows($q)){
            $dados = [];
            while($r = mysqli_fetch_array($q)){

                // Dados da tabela à enviar como resposta do ajax
                $dados[] = [
                    'id' => $r['id'],
                        'nome' => $r['nome'],
                        'preco' => $r['preco_descontado'],
                        'cor' => $r['cor']
                    ];
                    $preco = "";
                }
            }
            break;
        default:
            $msg = "Houve um erro ao encontrar o metodo";
            $success = 0;
            break;
    }

    $output = ['success'=> $success, 'mensagem'=> $msg, 'dados' => $dados];
    echo json_encode($output);
?>
