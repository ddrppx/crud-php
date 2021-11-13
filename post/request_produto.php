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

                // Try catch para poder retornar uma falha (se houver alguma)
                // O PDO para a execução do script ao encontrar erro
            try {
            // Checa por variaveis vazias
                if(!empty($nome) && !empty($cor) && !empty($preco)){
                    $insert = "INSERT INTO produtos (nome, cor) VALUES (:nome, :cor)";
                    # Insert statement
                    $insert_stmt = $connect->prepare($insert);

                    # Dados a serem inseridos
                    $dados = [
                        'nome' => $nome,
                        'cor' => $cor
                    ];

                    # Inserindo dados e executando
                    $insert_stmt->execute($dados);

                    // Pega o id do produto inserido
                    $id_produto = $connect->lastInsertId();
                      
                        // Resolve o preço descontado para coloca-lo no banco
                    $preco_descontado = resolvePreco($cor, $preco);
                    $insert_preco = "INSERT INTO precos (idprod, preco, preco_descontado) VALUES(:id_produto, :preco, :preco_descontado)";
                    $preco_stmt = $connect->prepare($insert_preco);
                    $dados = [
                        'id_produto' => $id_produto,
                        'preco' => $preco,
                        'preco_descontado' => $preco_descontado
                    ];
                    $preco_stmt->execute($dados);
                    
                    $msg .= "Produto $nome cadastrado com sucesso!";
                    $success = 1;
                }
            } catch (PDOException $e){
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

            $sql = "SELECT p.idprod, p.nome, pr.preco, p.cor FROM produtos p JOIN precos pr ON pr.idprod = p.idprod WHERE p.idprod= :id";
           
            // Checa se existe o id e se passou pelos filtros
            if($id && $success) {
                $select_stmt = $connect->prepare($sql);
                $select_stmt->execute(array(
                    'id' => $id
                ));

                if($select_stmt->rowCount() > 0) {
                    $r = $select_stmt->fetch();
                    
                    $id_prod = $r['idprod'];

                    // Armazenar os itens atualizados para mostrar na mensagem 
                    $atualizados = [];

                    // Se o nome tiver mudado entra na condiçao
                    // e executa o update
                    if($r['nome'] != $nome){
                        $sql_update = "UPDATE produtos SET nome = :nome WHERE idprod = :id";

                        $update_stmt = $connect->prepare($sql_update);
                        $dados = [
                            'nome' => $nome,
                            'id' => $id_prod
                        ];
                        $update_stmt->execute($dados);

                        $atualizados[] = "Nome";
                    }

                    // Se o preço tiver mudado entra na condiçao
                    // e executa o update
                    if($r['preco'] != $preco){ 

                        // Resolve o novo preco descontado e atualiza o banco
                        $preco_descontado = resolvePreco($r['cor'], $preco);

                        $sql_update_preco = "UPDATE precos SET preco = :preco, preco_descontado = :preco_descontado WHERE idprod = :id";
                        $update_preco_stmt = $connect->prepare($sql_update_preco);

                        $dados = [
                            'id' => $id_prod,
                            'preco' => $preco,
                            'preco_descontado' => $preco_descontado
                        ];
                        
                        $update_preco_stmt->execute($dados);
                        $atualizados[] = "Preço";
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
                    $success = 1;
                } else  {
                    $msg = "Não foi possível encontrar o produto...";
                    $success = 0;            
                }
        break;
            // Exclusão de produtos
        case 3:
            // id
            $id_POST = $_POST['id'];

            // Verifica o id enviado pela requisição
            if (filter_var($id_POST, FILTER_VALIDATE_INT)){
                $id = $id_POST; 
    
                    // Try catch para poder retornar uma falha (se houver alguma)
                    // O PDO para a execução do script ao encontrar erro
                try {  
                    // A constraint FK nao deixa deletar se ainda tiver preco vinculado
                $sql_preco = "DELETE FROM precos WHERE idprod = :id";
                $del_preco_stmt = $connect->prepare($sql_preco);
                $del_preco_stmt->execute(array(
                    'id' => $id
                ));

                $sql_prod = "DELETE FROM produtos WHERE idprod = :id";
                $del_prod_stmt = $connect->prepare($sql_prod);
                $del_prod_stmt->execute(array(
                    'id' => $id
                ));
                    $msg = "deletado com sucesso.";
                    $success = 1;
                } catch (PDOException $e){
                    $msg = "Erro ao deletar...";
                    $success = 0;
                }

            }

                
            break;
            // Busca de produtos
        case 4:
            $success = 0;
            $busca = addslashes($_POST['busca']);

            // Usando filter validate para validar os valores enviados pelo POST
            $cor = filter_var($_POST['cor'], FILTER_VALIDATE_INT) ? addslashes($_POST['cor']) :0;
            $tipo = filter_var($_POST['tipo'], FILTER_VALIDATE_INT) ? addslashes($_POST['tipo']) : 0;
            $preco = filter_var($_POST['preco'], FILTER_VALIDATE_FLOAT) ? addslashes($_POST['preco']) : 0;

            // Resolve qual operador irá usar de acordo com a escolha do usuário
            //  1 - Maior que | 2 - Menor que | 3 - Igual
            switch($tipo){
                case 1:
                    $acao = '>=';
                    break;
                case 2:
                    $acao = '<=';
                    break;
                case 3:
                    $acao = '=';
                    break;
            }

            try {
                $sql_busca = "SELECT p.idprod as id, p.nome, pc.preco_descontado, c.cor, c.idcor 
                    FROM produtos p 
                    JOIN cores c ON p.cor = c.idcor 
                    JOIN precos pc ON pc.idprod = p.idprod
                    WHERE nome LIKE :busca
                    AND IF( :preco > 0, pc.preco_descontado $acao :preco, true)
                    AND IF( :cor > 0, c.idcor = :cor, true)
                    ";
                
                $busca_stmt = $connect->prepare($sql_busca);
                $dados_busca = [
                    'busca' => "%$busca%",
                    'preco' => $preco,
                    'cor' => $cor
                ];
                
                $busca_stmt->execute($dados_busca);

                if($busca_stmt->rowCount() > 0){

                    // Para retornar todos os valores encontrados na query select
                    $dados = [];
                    while($r = $busca_stmt->fetch()){

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
                $success = 1;
            } catch(PDOException $e){
                $msg = "Houve eu erro ao executar a busca...\n". $e->getMessage();
                $success = 0;
            }
            break;
        default:
            $msg = "Houve um erro ao encontrar o metodo";
            $success = 0;
            break;
    }

    // Envia de volta a resposta
    $output = [
        'success'=> $success, 
        'mensagem'=> $msg, 
        'dados' => $dados
    ];

    echo json_encode($output);
?>
