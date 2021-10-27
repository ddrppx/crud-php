<?php

    function resolvePreco($cor, $preco){
        /*
        Azul, Vermelho: 20%
        Amarelo: 10%
        Vermelho, R$ > 50: 5% 
        */
        
        /* 
        1 - Amarelo
        2 - Azul
        3 - Vermelho
        */ 
        switch($cor){
            // Amarelo
            case 1:
                $desconto = 0.1;
                break;
            // Azul
            case 2:
                $desconto = 0.2;
                break;
            //Vermelho
            case 3:
                if($preco > 50){
                    $desconto = 0.05; 
                } else {
                    $desconto = 0.2;
                }
                break;
        }


        // Faz os calculos corretos de desconto e retorna o valor
        $valor = ($preco - ($preco * $desconto));
        return $valor;
    }
?>
