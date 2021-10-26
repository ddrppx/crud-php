var form_id = "form_cor";
var destino = "../post/request_cor.php";

function validaSubmitCor(){
    var form = document.getElementById(form_id);


    for(var i = 0; i < form.length; i++){

        if(form[i].value === ""){
            console.log(form[i].value);
            alert("Nao podem ser deixados campos vazios.");
        }

    }
    // Envia o formulario como post
    enviarForm(destino, form_id);
    return false;
}

