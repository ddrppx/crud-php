var form_id = "form_produto";

function validaSubmitProduto(){
    var form = document.getElementById(form_id);
   
    // Para validaçao especifica
    var preco = document.getElementById('preco');
    var cor = document.getElementById('cor');

    for(var i = 0; i < form.length; i++){

        if(form[i].value === ""){
            console.log(form[i].value);
            alert("Nao podem ser deixados campos vazios.");
        }

    }

    if(preco.value === "" || preco.value <= 0){
        alert("O preço nao pode ser vazio/menor que zero.");
    }
    return false;
}

