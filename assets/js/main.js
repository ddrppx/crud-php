
    // Enviar formularios via ajax
function enviarForm(destino, form_id, redirect = undefined) {
    var xhttp = new XMLHttpRequest();
    var url = destino;
        
        //Passar id do formulario para o query selector para ser enviado junto a requisiçao
    var formData = document.querySelector('#' + form_id);
    var data = new FormData(formData);

    xhttp.overrideMimeType("application/json");  
    
    xhttp.open('POST', url, true);

    xhttp.onreadystatechange = function() {
        // Caso a requisiçao volte sucesso
        if (this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(xhttp.responseText);

            // Mostra um alerta com a resposta do servidor
            alert(`${response.mensagem}`);

            if (redirect && response.success !== 0){
                window.location.href = redirect;    
            }
        }
    };

    xhttp.send(data);
}

    // Permite enviar dados com form feito por meio do FormData
function enviarFormManual(destino, formData, callback){
    var xhttp = new XMLHttpRequest();
    var url = destino;

    xhttp.overrideMimeType("application/json");  
    // Tipo de requisicao
    xhttp.open('POST', url, true);

    xhttp.onreadystatechange = function() {
        // Caso a requisiçao volte sucesso
        if (this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(xhttp.responseText);
            callback(response);
        };
    } 
    xhttp.send(formData);

}

    // Apagar produto
function apagarProd(destino, id, nome, redirect){

    // Pede confirmação diante à exclusão
    var r = confirm("Deseja realmente excluir o produto: " + nome+ "?");

    if(r){
        // Formulario à ser enviado
        var data = new FormData();
        data.append('metodo', 3);
        data.append('id', id);

        // Funçao callback para lidar com os valores da função assincrona
        function callback(response){

            // Mostra um alerta com a resposta do servidor
            alert(`Produto: ${nome} ${response.mensagem}`);

            if (redirect && response.success !== 0){
                window.location.href = 'index.php';    
            }
        }

        // Chamando a função
        // Destino da requisição
        // Formulario
        // Função de callback
        enviarFormManual(destino, data, callback);
     
        
    }

}

function buscar(){
    var destino = "post/request_produto.php";
    var busca = document.getElementById('busca');
    var cor = document.getElementById('cor');
    var preco = document.getElementById('preco');
    var tipo = document.getElementById('tipo');

    // Formulario à ser enviado
    var data = new FormData();
    data.append('metodo', 4);
    data.append('busca', busca.value);
    data.append('cor', cor.value);
    data.append('preco', preco.value);
    data.append('tipo', tipo.value);

    function callback(response) {
        if(response.dados === null){
            alert('Nenhum resultado para esta busca.');
        } else {
            var html = "";
            for(var i = 0; i < response.dados.length; i++){
                var produto = response.dados[i];
                html += formatarHTML(produto);
            }
            inserirHTML('conteudo', html);
        }
    }

    enviarFormManual(destino, data, callback);
}

    // Formata de objeto para table row/table data, para inserir na tabela html 
function formatarHTML(item){

    // Link para a pagina de edição de produto
    // btoa = base64_encode
    var editar = `pages/edit_produto.php?i=${btoa(item.id)}`;

    // onclick para apagar o produto 
    var apagar = `apagarProd('post/request_produto.php', '${item.id}', '${item.nome}', 'index.php')`;

        // number_format... em javascript
    var preco = new Intl.NumberFormat('pt-BR', { 
        style: 'currency', 
        currency: 'BRA' 
    }).format(item.preco); 

    // NumberFormat para a currency retorna um BRA no começo
    preco = preco.replace("BRA", "");
    var html = `
        <tr>
            <td>${item.nome}</td>
            <td>${item.cor}</td>
            <td class="number">R$${preco}</td>
            <td class="centro"><a href="${editar}">Editar</td>
            <td class="centro"><a href="#" onclick="${apagar}">Apagar</td>
        </tr>`;

    return html;
}

    // Insere na table html
function inserirHTML(id, html){
    var html_element = document.getElementById(id);

    html_element.innerHTML = html;
}
