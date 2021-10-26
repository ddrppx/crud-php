
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

          console.log(response);
            // Mostra um alerta com a resposta do servidor
          alert(`${response.mensagem}`);

            if (redirect && response.success !== 0){
                window.location.href = redirect;    
            }
        }
      };

      xhttp.send(data);
    }



