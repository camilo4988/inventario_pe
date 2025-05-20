<?php  
namespace app\models;


/***PENDIENTE ARREGLAR CONDICIONALES, EN EL TUTORIAL ESTAN HORRIBLES, SE PUEDE SIMPLIFICAR */
class viewsModel{
    
    protected function obtenerVistasModelo($vista) {
        $var="./app/views/content/";
        $complemento="-view.php";
        //array que contendra las palabras complementarias admitidas en la url
        $inicio=["login","index"];
        
        $content=$var.$vista.$complemento; 
        
// Comprobar si la vista está en el array de inicio
if (in_array($vista, $inicio)) {
    //print_r($vista.'entor'); die;
    return "login"; // Retorna "login" si la vista es válida
}

// Comprobar si el archivo existe en el directorio content
    if (is_file($content)) {
        return $content; // Retorna la ruta del archivo si existe
    } 





    // Retorna "404" si no se encuentra la vista
    return "404";

          
        }
    }

?>