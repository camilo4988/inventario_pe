<nav class="navbar is-white" role="navigation" aria-label="main navigation">
  <div class="navbar-brand">
    <a class="navbar-item" href="#">
<img src="/inventario_pe/views/resources/pe2.png" alt="PARTEQUIPOS" style="height: 30px;">

    </a>

    <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
    </a>
  </div>

  <div id="navbarBasicExample" class="navbar-menu">
    <div class="navbar-start">
    
      <a class="navbar-item" href="<?php echo APP_URL; ?>dashboard/">
        Dashboard
      </a>
      <a href="/inventario_pe/model/equipo/equipo.php" class="navbar-item">
        Equipo
      </a>

      <a href="/inventario_pe/model/empleado/empleado.php" class="navbar-item">
        Empleado
      </a>

      <a href="/inventario_pe/model/inventario/inventario.php" class="navbar-item">
        Inventario
      </a>

      <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link" href="#">
                    Usuarios
                </a>
                <div class="navbar-dropdown is-boxed">

                    <a class="navbar-item" href="#">
                        Nuevo
                    </a>
                    <a class="navbar-item" href="#">
                        Lista
                    </a>
                    <a class="navbar-item" href="#">
                        Buscar
                    </a>

        </div>
      </div>
    </div>
  </div>
</nav>

