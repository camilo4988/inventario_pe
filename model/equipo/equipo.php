<?php
session_start();
include '../../controller/db_connection.php';

$message = "";

// Si viene de redirect
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// Obtener datos para selects
function obtenerDatos($conn, $query) {
    $datos = [];
    $result = $conn->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $datos[] = $row;
        }
    }
    return $datos;
}

$tipos = obtenerDatos($conn, "SELECT id, nombre FROM tipo_activo");
$estados = obtenerDatos($conn, "SELECT id, nombre FROM estado_equipo");
$sedes = obtenerDatos($conn, "SELECT id, sede FROM sede");
$agentes = obtenerDatos($conn, "SELECT id, nombre, apellido FROM empleado");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hostname = $conn->real_escape_string($_POST['hostname']);
    $serial = $conn->real_escape_string($_POST['serial']);
    $tipo = $conn->real_escape_string($_POST['tipo']);
    $marca = $conn->real_escape_string($_POST['marca']);
    $estado_inicial = $conn->real_escape_string($_POST['estado_inicial']);
    $fecha_adquisicion = $conn->real_escape_string($_POST['fecha_adquisicion']);
    $modelo = $conn->real_escape_string($_POST['modelo']);
    $sop = $conn->real_escape_string($_POST['sop']);
    $procesador = $conn->real_escape_string($_POST['procesador']);
    $sede = $conn->real_escape_string($_POST['sede']);
    $observaciones = $conn->real_escape_string($_POST['observaciones']);
    $revisadopor = $conn->real_escape_string($_POST['revisadopor']);

    $sql = "INSERT INTO equipo (hostname,serial, tipo, marca, estado_inicial, fecha_adquisicion, fecha_registro, modelo,sop,procesador,sede,observaciones,revisadopor)
            VALUES ('$hostname','$serial', '$tipo', '$marca', '$estado_inicial', '$fecha_adquisicion', NOW(), '$modelo','$sop','$procesador','$sede','$observaciones','$revisadopor')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Activo registrado satisfactoriamente.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Obtener listado de equipos registrados
$equipos = obtenerDatos($conn, "SELECT * FROM equipo");

$conn->close();



include '../../views/equipo/registrar_equipo.php';
?>
<!-- Aquí comienza la sección para mostrar el listado de equipos -->
<div class="container">
    <h2>Listado de Equipos Registrados</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Hostname</th>
                <th>Serial</th>
                <th>Tipo</th>
                <th>Marca</th>
                <th>Estado Inicial</th>
                <th>Fecha de Adquisición</th>
                <th>Modelo</th>
                <th>SOP</th>
                <th>Procesador</th>
                <th>Sede</th>
                <th>Observaciones</th>
                <th>Revisado Por</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($equipos as $equipo): ?>
                <tr>
                    <td><?php echo htmlspecialchars($equipo['hostname']); ?></td>
                    <td><?php echo htmlspecialchars($equipo['serial']); ?></td>
                    <td><?php echo htmlspecialchars($equipo['tipo']); ?></td>
                    <td><?php echo htmlspecialchars($equipo['marca']); ?></td>
                    <td><?php echo htmlspecialchars($equipo['estado_inicial']); ?></td>
                    <td><?php echo htmlspecialchars($equipo['fecha_adquisicion']); ?></td>
                    <td><?php echo htmlspecialchars($equipo['modelo']); ?></td>
                    <td><?php echo htmlspecialchars($equipo['sop']); ?></td>
                    <td><?php echo htmlspecialchars($equipo['procesador']); ?></td>
                    <td><?php echo htmlspecialchars($equipo['sede']); ?></td>
                    <td><?php echo htmlspecialchars($equipo['observaciones']); ?></td>
                    <td><?php echo htmlspecialchars($equipo['revisadopor']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>