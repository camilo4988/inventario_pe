<?php 
//obtiene el nombre de las clases de nuestro sistema
    spl_autoload_register(function($clase){
        $archivo=__DIR__."/".$clase.".php";
        //print_r($archivo);die;
        $archivo=str_replace("\\","/",$archivo);
        if(is_file($archivo)){
            require_once $archivo;

        }

    });
?>