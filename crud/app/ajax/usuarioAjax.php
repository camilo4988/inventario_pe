<?php
//recibira peticiones ajax del modulo usuarios.
require_once "../../config/app.php";
require_once "../views/inc/session.php";
require_once "../../autoload.php";

use app\controllers\userController;

if (isset($_POST['modulo_usuario'])) {
    $insUsuario=new UserController();
    if($_POST['modulo_usuario']=='registrar'){
        echo $insUsuario->registrarUsuarioControlador();

    }
}else {

    session_destroy();
    //print_r('holii');die();
    header('Location: '.APP_URL.'login/');
}

?>