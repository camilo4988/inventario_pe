<?php
session_start();
// Incluir el archivo de conexión
include '../../controller/db_connection.php';

$message = "";
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// Obtener lista de sedes para el select
$tipos = [];
$result_tipos = $conn->query("SELECT id, nombre FROM tipo_activo");

if ($result_tipos) {
    while ($row = $result_tipos->fetch_assoc()) {
        $tipos[] = $row;
    }
}

$estados = [];
$result_estados = $conn->query("SELECT id, nombre FROM estado_equipo");

if ($result_estados) {
    while ($row = $result_estados->fetch_assoc()) {
        $estados[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serial = $conn->real_escape_string($_POST['serial']);
    $tipo = $conn->real_escape_string($_POST['tipo']);
    $marca = $conn->real_escape_string($_POST['marca']);
    $estado_inicial = $conn->real_escape_string($_POST['estado_inicial']);
    $fecha_adquisicion = $conn->real_escape_string($_POST['fecha_adquisicion']);
    $modelo = $conn->real_escape_string($_POST['modelo']);
    
    $sql = "INSERT INTO equipo (serial, tipo, marca, estado_inicial, fecha_adquisicion, fecha_registro, modelo) VALUES ('$serial', '$tipo', '$marca', '$estado_inicial', '$fecha_adquisicion', NOW(), '$modelo')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Activo registrado satisfactoriamente.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
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
    <title>Equipos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <table class="table">
            <tr>
                <td><a href="../../index.php"><img src="../../views/resources/pe2.png" alt="Inicio" style="height:30px;"></a></td>
                <td><h1>Registro de Activos TIC</h1></td>
            </tr>
        </table>
        <form method="post" action="">
            <table class="table">
                <tr>
                    <td><label for="serial">Serial:</label></td>
                    <td><input type="text" id="serial" name="serial" required></td>
                </tr>
                <tr>
                    <td><label for="tipo">Tipo:</label></td>
                    <td>
                        <select id="tipo" name="tipo" required>
                            <option value="" disabled selected>Seleccione un tipo</option>
                            <?php foreach ($tipos as $tipo_option): ?>
                                <option value="<?php echo htmlspecialchars($tipo_option['id']); ?>">
                                    <?php echo htmlspecialchars($tipo_option['id'] . ' - ' . $tipo_option['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="marca">Marca:</label></td>
                    <td><input type="text" id="marca" name="marca" required></td>
                </tr>
                <tr>
                    <td><label for="estado_inicial">Estado de Adquisición:</label></td>
                    <td>
                        <select id="estado_inicial" name="estado_inicial" required>
                            <option value="" disabled selected>Seleccione un estado</option>
                            <?php foreach ($estados as $estado_option): ?>
                                <option value="<?php echo htmlspecialchars($estado_option['nombre']); ?>">
                                    <?php echo htmlspecialchars($estado_option['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="modelo">Modelo:</label></td>
                    <td><input type="text" id="modelo" name="modelo" required></td>
                </tr>
                <tr>
                    <td><label for="fecha_adquisicion">Fecha de adquisición:</label></td>
                    <td><input type="date" id="fecha_adquisicion" name="fecha_adquisicion" required></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="Registrar" class="btn btn-primary"></td>
                </tr>
            </table>
        </form>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
