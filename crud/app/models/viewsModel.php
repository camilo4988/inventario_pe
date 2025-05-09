<?php  
namespace app\models;


/***PENDIENTE ARREGLAR CONDICIONALES, EN EL TUTORIAL ESTAN HORRIBLES, SE PUEDE SIMPLIFICAR */
class viewsModel{
    protected function obtenerVistasModelo($vista) {
        //array que contendra las palabras complementarias admitidas en la url
        $listaBlanca=["dashboard"];
        //valido que el nombre de la vista esta definida en el array
        if(in_array($vista,$listaBlanca)){
            //ahora comprobamos si existe en el directorio content
            if(is_file("./app/views/content/".$vista."-view.php")){
                //si existe se crea la ruta en la variable content
                $content="./app/views/content/".$vista."-view.php";
        
                }
                else {
                    $content= "404";
                }
            }elseif($vista=="login" || $vista== "index"){
                $content= "login";

            }
            else {
                $content= "404";
            }
            //retorna la url
            return $content;
        }
    }
?>