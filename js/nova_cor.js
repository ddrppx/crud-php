var form_id = "form_cor";
var destino = "../post/request_cor.php";
var redirect = "../index.php";

function validaSubmitCor(){
    var form = document.getElementById(form_id);


    for(var i = 0; i < form.length; i++){

        // Loop em todos os elementos do formulario e checa se esta vazio
        if(form[i].value === ""){
            console.log(form[i].value);
            alert("Nao podem ser deixados campos vazios.");
        }

    }
    // Envia o formulario como post
    enviarForm(destino, form_id, redirect);
    return false;
}

