
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

    // Apagar produto
function apagarProd(destino, id, nome, redirect){

    // Pede confirmação diante à exclusão
    var r = confirm("Deseja realmente excluir o produto: " + nome+ "?");
    if(!r){
        return;
    }

    var xhttp = new XMLHttpRequest();
    var url = destino;
        
    // Formulario à ser enviado
    var data = new FormData();
    data.append('metodo', 3);
    data.append('id', id);

    xhttp.overrideMimeType("application/json");  
    // Tipo de requisicao
    xhttp.open('POST', url, true);

    xhttp.onreadystatechange = function() {
        // Caso a requisiçao volte sucesso
        if (this.readyState == 4 && this.status == 200) {
            console.log(response);
          var response = JSON.parse(xhttp.responseText);

          console.log(response);
            // Mostra um alerta com a resposta do servidor
          alert(`Produto: ${nome}; ${response.mensagem}.`);

            if (redirect && response.success !== 0){
                window.location.href = redirect;    
            }
        }
      };
      xhttp.send(data);
}

