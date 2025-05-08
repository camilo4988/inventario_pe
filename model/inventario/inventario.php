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

// Obtener lista de empleados
$empleados = [];
$result_empleados = $conn->query("SELECT  codigo, nombre, apellido FROM empleado");
if ($result_empleados) {
    while ($row = $result_empleados->fetch_assoc()) {
        $empleados[] = $row;
    }
}




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $serial = $conn->real_escape_string($_POST['serial']);
    $codigo_empleado = $conn->real_escape_string($_POST['codigo_empleado']);
    $estado = $conn->real_escape_string($_POST['estado']);
    $sede = $conn->real_escape_string($_POST['sede']);
    //$fecha_edicion = $conn->real_escape_string($_POST['fecha_edicion']);
   
  

    $sql = "INSERT INTO equipo_empleado (serial, codigo_empleado, sede, estado,fecha_edicion) VALUES ('$serial', '$codigo_empleado', '$sede', '$estado', NOW())";

    if ($conn->query($sql) === TRUE) {
        $message = "Activo registrado satisfactoriamente en inventario.";
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
                <td><h1>Registro de Inventario TIC</h1></td>
            </tr>
        </table>
        
        
        <form method="post" action="">
            <table class="table">
               
                <tr>
                    <td><label for="serial">Serial:</label></td>
                    <td><input type="text" id="serial" name="serial" required></td>
                </tr>
                <tr>
                    <td><label for="codigo_empleado">Asignado a:</label></td>
                    <td>
                        <select id="codigo_empleado" name="codigo_empleado" required>
                            <option value="" disabled selected>Seleccione un empleado</option>
                            <?php foreach ($empleados as $empleado_option): ?>
                                <option value="<?php echo htmlspecialchars($empleado_option['codigo']); ?>">
                                    <?php echo htmlspecialchars($empleado_option['codigo'].'--'.$empleado_option['nombre'].' '.$empleado_option['apellido']); ?>
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
                    <td><label for="sede">Sede:</label></td>
                    <td>
                        <select id="sede" name="sede" required>
                            <option value="">Seleccione una sede</option>
                            <?php foreach ($sedes as $sede_option): ?>
                                <option value="<?php echo htmlspecialchars($sede_option['id']); ?>" <?php if (isset($_POST['sede']) && $_POST['sede'] == $sede_option['id']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($sede_option['sede']); ?>
                                </option>
                            <?php endforeach; ?>
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