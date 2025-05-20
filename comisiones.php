<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calculadora de Comisiones</title>
    <link rel="stylesheet" href="views/css/estiloform.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

</head>
<body>
    
    <h1>Calculadora de Comisiones</h1>

    <div class="contenedor">
        
    <form method="post">

        <label for="tipo">Tipo de Maquina:</label>
        <select name="tipomaq" required>
            <option value="" disabled selected>Seleccione una opción</option>
            <option value="equipo">Equipo</option>
            <option value="aditamento">Aditamento</option>
        </select>

        <label for="lista">Valor de lista:</label>
        <input type="number" step="0.01" name="lista" id="lista" required>

        <label for="venta">Valor de venta:</label>
        <input type="number" step="0.01" name="venta" id="venta" required>

        <label for="inte">Valor Intereses y admon:</label>
        <input type="number" step="0.01" name="inte" id="inte" value="0" required>

        <label for="iva">% IVA:</label>
        <input type="number" step="0.01" name="iva" id="iva" value="0" required>

<!--
        <label for="siniva">Venta sin IVA:</label>
        <input type="number" step="0.01" name="siniva" id="siniva" required>
        

        <label for="">base comision:</label>
        <input type="number" step="0.01" name="recibidos" id="recibidos" required>
-->
        <label for="tipo">Tipo de Venta:</label>
        <select name="tipo" required>
            <option value="" disabled selected>Seleccione una opción</option>
            <option value="credito">Crédito</option>
            <option value="contado">Contado</option>
        </select>

        <label for="estado">Estado:</label>
        <select name="estado" required>
            <option value="" disabled selected>Seleccione una opción</option>
            <option value="nuevo">nuevo</option>
            <option value="usado">usado</option>
        </select>

        <label for="referido">¿Call center?</label>
        <div class="radio-group">
            <label><input type="radio" value="si" name="referido" required> Sí</label>
            <label><input type="radio" value="no" name="referido"> No</label>
        </div>

        <button type="submit">Calcular Comisión</button>
        <button type="button" id="limpiarResultados">Limpiar Resultados</button>
    </form>

    <?php
    $ti = 0;
    $adm = 0.1;//------------------------------variables de g.integral
    $enc = 0.05;//0.05 encuestas
    $ref = 0;
    $castigo=0;

    //aditamentos 15, 1.6 TI
    /**siempre credito va a tener intereses y administracion, contado no
     * 
     * iva depende de tipo de equipo, codigo sr-> mini cargador
     * 
     * contra entrega pagos utilizo para calcular comisiones, en contado es igual a columna base comision en credito no, y el restante seria el saldo x p= saldo pendiente en la proxima factur; en sap estara con iva y retencion, por eso no coincide con sap
     * 
     */

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $valorLista=floatval($_POST["lista"]);
        $venta=floatval($_POST["venta"]);
        $interes = isset($_POST["inte"]) && $_POST["inte"] !== '' ? floatval($_POST["inte"]) : 0;
        $iva = isset($_POST["iva"]) && $_POST["iva"] !== '' ? floatval($_POST["iva"]) : 0;
        //$recibidos = floatval($_POST["recibidos"]);

        $tipoMaquina = $_POST["tipomaq"] ?? "";
        $tipo = $_POST["tipo"] ?? "";
        $referido = $_POST["referido"] ?? "";
        $estado = $_POST["estado"] ?? "";

        if ($tipo == "contado") {
            if ($tipoMaquina=="equipo") {
                $ti = 1.1;
            }else{
                $ti = 1.6;
            }
        }
        
        elseif ($tipo == "credito") {
            if ($tipoMaquina=="equipo") {
                $ti = 0.9;
            }else{
                $ti = 1.5;
            }
            
        }

        if ($referido == "si") {
            $ref = 0;
        } elseif ($referido == "no") {
            $ref = 0.1;
        }

        //calculo % comision
        $porcentajeComision = $ti + $adm + $enc + $ref; 

        //venta-int y admon
        $valorventaRestadoInteres=$venta-$interes;
        //vr venta antes de iva
        $valorVentasinIva = $venta / (1+($iva/100));
        //VR VENTA CREDITO-BASE  COMISION        
        $valorBaseComision=$valorVentasinIva-$interes;

        //valor comision sin castigo PAGO ANTES DE DTOS
        $valorComision=$valorBaseComision*$porcentajeComision/100;


        /*** calculo castigo  a comision respecto a %dto*/        
        $p_dto=($valorLista-$valorventaRestadoInteres)/($valorventaRestadoInteres/100);

        if ($estado == "nuevo") {
            switch (true) {
                case ($p_dto >= 2.1 && $p_dto < 4.1):
                    $castigo = 0.1;
                    break;
                case ($p_dto >= 4.1 && $p_dto < 6.1):
                    $castigo = 0.15;
                    break;
                case ($p_dto >= 6.1 && $p_dto < 8.1):
                    $castigo = 0.3;
                    break;
                case ($p_dto >= 8.1 && $p_dto <= 10):
                    $castigo = 0.5;
                    break;
                
            }
        } else {
            switch (true) {
                case ($p_dto >= 4.1 && $p_dto < 6.1):
                    $castigo = 0.1;
                    break;
                case ($p_dto >= 6.1 && $p_dto < 8.1):
                    $castigo = 0.2;
                    break;
                case ($p_dto >= 8.1 && $p_dto <= 10):
                    $castigo = 0.4;
                    break;
                
            }
        }
        /**valor pago real de comision despues de castigo */
        $valorPagarReal=$valorBaseComision*($porcentajeComision-$castigo)/100;
        
    
        echo "<div class='resultado' id='resultado' style='margin: 2px; border: 2px solid #444; padding: 1px; border-radius: 5px;' data-valor-lista='{$valorLista}' data-venta='{$venta}' data-interes='{$interes}' data-valorventa-restado-interes='{$valorventaRestadoInteres}' data-valor-venta-sin-iva='{$valorVentasinIva}' data-valor-base-comision='{$valorBaseComision}' data-porcentaje-comision='{$porcentajeComision}' data-valor-comision='{$valorComision}' data-p-dto='{$p_dto}' data-castigo='{$castigo}' data-valor-pagar-real='{$valorPagarReal}'>";

        echo "<table>";
        echo "<caption style='caption-side: top; font-weight: bold; font-size: 1.2em; padding-bottom: 10px;'>RESULTADOS</caption>";
        
        echo "<tr><td class='wide-col'><strong>Valor Lista:</strong></td><td>$ " . number_format($valorLista, 2) . "</td></tr>";
        echo "<tr><td class='wide-col'><strong>Valor de venta:</strong></td><td>$ " . number_format($venta, 2) . "</td></tr>";
        echo "<tr><td class='wide-col'><strong>INTERESES + ADMON:</strong></td><td>$ " . number_format($interes, 2) . "</td></tr>";
        echo "<tr><td class='wide-col'><strong>VENTA - INT Y ADMON:</strong></td><td>$ " . number_format($valorventaRestadoInteres, 2) . "</td></tr>";
        echo "<tr><td class='wide-col'><strong>Vr Vta antes de IVA:</strong></td><td>$ " . number_format($valorVentasinIva, 2) . "</td></tr>";
        echo "<tr><td class='wide-col'><strong>VR VENTA CREDITO - BASE COMISION:</strong></td><td>$ " . number_format($valorBaseComision, 2) . "</td></tr>";
        echo "<tr><td class='wide-col'><strong>Porcentaje total comisión:</strong></td><td>" . number_format($porcentajeComision, 2) . " %</td></tr>";
        echo "<tr><td class='wide-col'><strong>Detalle:</strong></td><td>TI: $ti | ADM: $adm | ENC: $enc | REF: $ref</td></tr>";
        echo "<tr><td class='wide-col'><strong>Valor de liquidación a comercial (sin castigo):</strong></td><td>$ " . number_format($valorComision, 2) . "</td></tr>";
        echo "<tr><td class='wide-col'><strong>Porcentaje descuento:</strong></td><td>" . number_format($p_dto, 2) . " %</td></tr>";
        echo "<tr><td class='wide-col'><strong>Castigo:</strong></td><td>" . number_format($castigo, 2) . " %</td></tr>";
        echo "<tr><td class='wide-col'><strong>Porcentaje comisión real (con castigo):</strong></td><td>" . number_format($porcentajeComision - $castigo, 2) . " %</td></tr>";
        echo "<tr><td class='wide-col'><strong>Total comisión pago Real (con castigo):</strong></td><td>$ " . number_format($valorPagarReal, 2) . "</td></tr>";
        echo "</table>";
        echo "</div>";
    }
    ?>

    
    

    </div>
    <button type="button" id="exportarPDF">Exportar a PDF</button>


 <script>
    // Función para formatear números en formato de dos decimales
    function number_format_js(number, decimals = 2) {
        return number.toFixed(decimals).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    document.getElementById('limpiarResultados').addEventListener('click', function() {
        document.querySelector('.resultado').innerHTML = '';
    });

    document.getElementById('exportarPDF').addEventListener('click', function() {
        var resultados = document.querySelector('.resultado');
        if (resultados) {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            doc.setFontSize(18);
            doc.text('Resultado de la Calculadora de Comisiones', 20, 20);

            doc.setFontSize(12);
            let yPos = 30;

            // Obtener valores del HTML y pasarlos a PDF
            doc.text(`Valor Lista: $${number_format_js(parseFloat(resultados.dataset.valorLista))}`, 20, yPos);
            yPos += 10;
            doc.text(`Valor de venta: $${number_format_js(parseFloat(resultados.dataset.venta))}`, 20, yPos);
            yPos += 10;
            doc.text(`INTERESES + ADMON: $${number_format_js(parseFloat(resultados.dataset.interes))}`, 20, yPos);
            yPos += 10;
            doc.text(`VENTA - INT Y ADMON: $${number_format_js(parseFloat(resultados.dataset.valorventaRestadoInteres))}`, 20, yPos);
            yPos += 10;
            doc.text(`Vr Vta antes de IVA: $${number_format_js(parseFloat(resultados.dataset.valorVentasinIva))}`, 20, yPos);
            yPos += 10;
            doc.text(`VR VENTA CREDITO - BASE COMISION: $${number_format_js(parseFloat(resultados.dataset.valorBaseComision))}`, 20, yPos);
            yPos += 10;
            doc.text(`Porcentaje total comisión: ${number_format_js(parseFloat(resultados.dataset.porcentajeComision))} %`, 20, yPos);
            yPos += 10;
            doc.text(`Valor de liquidación a comercial (sin castigo): $${number_format_js(parseFloat(resultados.dataset.valorComision))}`, 20, yPos);
            yPos += 10;
            doc.text(`Porcentaje descuento: ${number_format_js(parseFloat(resultados.dataset.p_dto))} %`, 20, yPos);
            yPos += 10;
            doc.text(`Castigo: ${number_format_js(parseFloat(resultados.dataset.castigo))} %`, 20, yPos);
            yPos += 10;
            doc.text(`Porcentaje comisión real (con castigo): ${number_format_js(parseFloat(resultados.dataset.porcentajeComision) - parseFloat(resultados.dataset.castigo))} %`, 20, yPos);
            yPos += 10;
            doc.text(`Total comisión pago Real (con castigo): $${number_format_js(parseFloat(resultados.dataset.valorPagarReal))}`, 20, yPos);

            doc.save('resultado_comision.pdf');
        } else {
            alert('No hay resultados para exportar.');
        }
    });
    </script>



</body>
</html>