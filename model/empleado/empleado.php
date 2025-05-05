<?php
// Incluir el archivo de conexión
include '../../controller/db_connection.php';

$message = "";

// Obtener lista de sedes para el select
$sedes = [];
$result_sedes = $conn->query("SELECT id, sede FROM sede");
if ($result_sedes) {
    while ($row = $result_sedes->fetch_assoc()) {
        $sedes[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = $conn->real_escape_string($_POST['dni']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellido = $conn->real_escape_string($_POST['apellido']);
    $sexo = $conn->real_escape_string($_POST['sexo']);
    $fecha_nacimiento = $conn->real_escape_string($_POST['fecha_nacimiento']);
    $codigo = $conn->real_escape_string($_POST['codigo']);
    $sede = $conn->real_escape_string($_POST['sede']);
    $estado = $conn->real_escape_string($_POST['estado']);

    $sql = "INSERT INTO empleado (dni, nombre, apellido, sexo, fecha_nacimiento, codigo, sede, estado) VALUES ('$dni', '$nombre', '$apellido', '$sexo', '$fecha_nacimiento', '$codigo', '$sede', '$estado')";

    if ($conn->query($sql) === TRUE) {
        $message = "Empleado registrado correctamente.";
    } else {
        $message = "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
</head>
<body>
    <div class="container" >
        <table class="table">
            <tr>
                <td><a href="../../index.php"><img src="../../views/resources/pe2.png" alt="Inicio" style="height:30px;"></a></td>
                <td><h1>Registro de Empleados</h1></td>
            </tr>
        </table>
        
        <form method="post" action="">
            <table class="table">
                <tr>
                    <td><label for="dni">DNI:</label></td>
                    <td><input type="text" id="dni" name="dni" required></td>
                </tr>
                <tr>
                    <td><label for="nombre">Nombre:</label></td>
                    <td><input type="text" id="nombre" name="nombre" required></td>
                </tr>
                <tr>
                    <td><label for="apellido">Apellido:</label></td>
                    <td><input type="text" id="apellido" name="apellido" required></td>
                </tr>
                <tr>
                    <td><label for="sexo">Sexo (M/F):</label></td>
                    
                    <td>
                        <select name="sexo" id="sexo" required>
                            <option value="" disabled selected>---</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                            <option value="N">N/A</option>
                        </select>    
                    </td>
                </tr>
                <tr>
                    <td><label for="fecha_nacimiento">Fecha de Nacimiento:</label></td>
                    <td><input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required></td>
                </tr>
                <tr>
                    <td><label for="codigo">Código:</label></td>
                    <td><input type="text" id="codigo" name="codigo" required></td>
                </tr>
                <tr>
                    <td><label for="sede">Sede:</label></td>
                    <td>
                        <select id="sede" name="sede" required>
                            <option value="" disabled selected>Seleccione una sede</option>
                            <?php foreach ($sedes as $sede_option): ?>
                                <option value="<?php echo htmlspecialchars($sede_option['id']); ?>">
                                    <?php echo htmlspecialchars($sede_option['sede']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select> 
                    </td>
                </tr>
                <tr>
                    <td><label for="estado">Estado:</label></td>
                    <td>
                        <select name="estado" id="estado" required>
                            <option value="" disabled selected>---</option>
                            <option value="T">Activo</option>
                            <option value="F">Inactivo</option>
                        </select> 
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="Registrar" class="btn btn-primary"></td>
                </tr>
            </table>
        </form>


        <?php if (isset($message)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
