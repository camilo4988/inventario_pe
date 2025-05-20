<?php 
    namespace app\controllers;
    use app\models\viewsModel;

    class viewsController extends viewsModel{
          public function obtenerVistasControlador($vista){
            if (!empty($vista)) {
               // print_r($vista);die();
                // Llamar al método del padre para evitar recursión infinita
                $respuesta = $this->obtenerVistasModelo($vista);
                //print_r($respuesta);die();
            }
            else{
                print_r("entro aca");die;
                $respuesta="login";

            }
            return $respuesta;
        
    }
}

?>