<?php 
    require_once "./autoload.php";
    require_once "./config/app.php";
    require_once "./app/views/inc/session.php";

//separar valores de la URL
    if (isset($_GET['views'])) {
        $url=explode("/",$_GET['views']);
    } else {
        $url=['login'];
    }
    

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php 
        require_once "./app/views/inc/head.php";
    ?>
</head>
<body>
<script>
/****preueba del sweet alert */
/*Swal.fire({
  title: "The Internet?",
  text: "That thing is still around?",
  icon: "question"
});*/</script>
    <?php 

        use  app\controllers\viewsController;
        require_once "./app/views/inc/script.php";

        $viewsController = new viewsController();
        $vista= $viewsController->obtenerVistasControlador($url[0]);
        //print_r("jajaja");die();
        if ($vista== "login" || $vista== "404" ) {
            require_once "./app/views/content/".$vista."-view.php";
        }else {
            require_once $vista;
        }
        
    ?>
</body>
</html>