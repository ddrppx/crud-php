// Variaveis para enviar o formulario
var form_id = "form_produto";
var destino = "../post/request_produto.php";
var redirect = "../index.php";

    // Faz a validação (Checa por campos vazios, etc)
function validaSubmitProduto(){
    var form = document.getElementById(form_id);
   
    // Para validaçao especifica
    var preco = document.getElementById('preco');
    var cor = document.getElementById('cor');

    for(var i = 0; i < form.length; i++){

        // Loop em todos os elementos do formulario e checa se esta vazio
        if(form[i].value === ""){
            console.log(form[i]);
            alert("Nao podem ser deixados campos vazios.");
            return false;
        }

    }

    // Validaçao especifica
    if(preco.value === "" || preco.value <= 0){
        alert("O preço dever ser um numero e nao pode ser vazio ou menor que zero.");
        return false;
    }

    // Envia o formulario
    enviarForm(destino, form_id, redirect);

    // Retorna false para o html não redirecionar a pagina
    // Pois será redirecionado pelo callback
    return false;
}

