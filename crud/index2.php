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
    <?php require_once "./app/views/inc/head.php";?>
</head>
<body>
<script>

Swal.fire({
  title: "The Internet?",
  text: "That thing is still around?",
  icon: "question"
});
</script>
    <?php require_once "./app/views/inc/script.php";?>
</body>
</html>