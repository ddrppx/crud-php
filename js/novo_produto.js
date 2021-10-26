// Variaveis para enviar o formulario
var form_id = "form_produto";
var destino = "../post/request_produto.php";
var redirect = "../index.php";

function validaSubmitProduto(){
    var form = document.getElementById(form_id);
   
    // Para validaçao especifica
    var preco = document.getElementById('preco');
    var cor = document.getElementById('cor');

    for(var i = 0; i < form.length; i++){

        // Loop em todos os elementos do formulario e checa se esta vazio
        if(form[i].value === ""){
            alert("Nao podem ser deixados campos vazios.");
            return false;
        }

    }

    // Validaçao especifica
    if(preco.value === "" || preco.value <= 0){
        alert("O preço dever ser um numero e nao pode ser vazio ou menor que zero.");
        return false;
    }

    enviarForm(destino, form_id, redirect);
    return false;
}

