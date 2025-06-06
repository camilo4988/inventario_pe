<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Activos TIC</title>
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

        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="hostname">Hostname:</label>
                        <input type="text" class="form-control" id="hostname" name="hostname" required>
                    </div>

                    <div class="form-group">
                        <label for="serial">Serial:</label>
                        <input type="text" class="form-control" id="serial" name="serial" required>
                    </div>

                    <div class="form-group">
                        <label for="tipo">Tipo:</label>
                        <select class="form-control" id="tipo" name="tipo" required>
                            <option value="" disabled selected>Seleccione un tipo</option>
                            <?php foreach ($tipos as $tipo_option): ?>
                                <option value="<?php echo htmlspecialchars($tipo_option['id']); ?>">
                                    <?php echo htmlspecialchars($tipo_option['id'] . ' - ' . $tipo_option['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="marca">Marca:</label>
                        <input type="text" class="form-control" id="marca" name="marca" required>
                    </div>

                    <div class="form-group">
                        <label for="estado_inicial">Estado:</label>
                        <select class="form-control" id="estado_inicial" name="estado_inicial" required>
                            <option value="" disabled selected>Seleccione un estado</option>
                            <?php foreach ($estados as $estado_option): ?>
                                <option value="<?php echo htmlspecialchars($estado_option['nombre']); ?>">
                                    <?php echo htmlspecialchars($estado_option['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="modelo">Modelo:</label>
                        <input type="text" class="form-control" id="modelo" name="modelo" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fecha_adquisicion">Fecha de adquisición:</label>
                        <input type="date" class="form-control" id="fecha_adquisicion" name="fecha_adquisicion" required>
                    </div>

                    <div class="form-group">
                        <label for="sop">Sistema Operativo:</label>
                        <input type="text" class="form-control" id="sop" name="sop" required>
                    </div>

                    <div class="form-group">
                        <label for="procesador">Procesador:</label>
                        <input type="text" class="form-control" id="procesador" name="procesador" required>
                    </div>

                    <div class="form-group">
                        <label for="sede">Sede:</label>
                        <select class="form-control" id="sede" name="sede" required>
                            <option value="" disabled selected>Seleccione una sede</option>
                            <?php foreach ($sedes as $sede_option): ?>
                                <option value="<?php echo htmlspecialchars($sede_option['id']); ?>">
                                    <?php echo htmlspecialchars($sede_option['sede']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="revisadopor">Registrado por:</label>
                        <select class="form-control" id="revisadopor" name="revisadopor" required>
                            <option value="" disabled selected>Seleccione un agente</option>
                            <?php foreach ($agentes as $agente_option): ?>
                                <option value="<?php echo htmlspecialchars($agente_option['id']); ?>">
                                    <?php echo htmlspecialchars($agente_option['nombre'] . ' ' . $agente_option['apellido']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="observaciones">Observaciones:</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="4" required></textarea>
                    </div>
                </div>
            </div>

            <input type="submit" value="Registrar" class="btn btn-primary">
        </form>
    </div>
</body>
</html>
