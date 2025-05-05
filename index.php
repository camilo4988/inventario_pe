<?php
   $message = "Inventario PARTEQUIPOS   2025";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Control y registro de Inventario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
   
</head>
<body>
    <h1>BIENVENIDO USUARIO</h1>
<!-- -->
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">
                  <img alt="PARTEQUIPOS" src="views/resources/pe2.png" style="height:30px;">
          </a>

        </div>
                        
          <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="nav navbar-nav ms-auto">
                  <li class="nav-item">
                      <a class= "nav-link" href="./model/equipo/equipo.php" >Equipo <span class="sr-only">(current)</span></a>
                  </li>
                  <li class="nav-item">
                      <a class= "nav-link" href="./model/empleado/empleado.php">Empleado</a>
                  </li>
                  <li class="nav-item">
                      <a class= "nav-link" href="./model/inventario/inventario.php">Inventario</a>
                  </li>          
              </ul>
          </div>      <!-- /.navbar-collapse -->
        
      </div><!-- /.container-fluid -->
    </nav>

    <div style="text-align: center;">
    <img alt="PARTEQUIPOS" src="views/resources/index.jpg" style="opacity: 0.3; display: inline-block;">
    </div>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
</body>

</html>