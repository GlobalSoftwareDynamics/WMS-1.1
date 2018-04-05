<div class="spacer30"></div>

<section class="container">
    <div class="row" id="opcionesMetodoPago">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-inverse card-info">
                    <div class="float-left">
                        <i class="fa fa-camera"></i>
                        Reporte de Cobranza por Fechas - <?php echo $_POST['fechaInicioReporte'] . " - " . $_POST['fechaFinReporte'];
                        $fileName = "files/".$_SESSION['user']."-reporteCobranzaFechas.txt";?>
                    </div>
                    <div class="float-right">
                        <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Acciones
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="files/<?php echo $_SESSION['user']?>-reporteCobranzaFechas.txt" download>Exportar Listado</a>
                                <form method="post" action="reporteCobranzaFechasPDF.php">
                                    <input type="hidden" name="fechaInicioReporte" value="<?php echo $_POST['fechaInicioReporte'];?>">
                                    <input type="hidden" name="fechaFinReporte" value="<?php echo $_POST['fechaFinReporte'];?>">
                                    <input type="submit" name="pdf" value="Descargar" class="dropdown-item" style="font-size: 16px">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-block">
                    <div class="row">
                        <div class="col-12">
                            <div class="spacer10"></div>
                            <table class="table text-center">
                                <thead>
                                <tr>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Movimiento</th>
                                    <th class="text-center">Transacción</th>
                                    <th class="text-center">Responsable</th>
                                    <th class="text-center">Cliente</th>
                                    <th class="text-center">Monto</th>
                                    <th class="text-center">Monto Restante</th>
                                    <th class="text-center">Vencimiento</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $file = fopen($fileName,"w") or die("No se encontró el archivo!");
                                fwrite($file, pack("CCC",0xef,0xbb,0xbf));
                                $txt = PHP_EOL."Fecha,Movimiento,Transacción,Responsable,Cliente,Monto,Monto Restante,Vencimiento".PHP_EOL;
                                fwrite($file, $txt);
                                $dateInicio = explode("-", $_POST['fechaInicioReporte']);
                                $dateFin = explode("-", $_POST['fechaFinReporte']);
                                $fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
                                $fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
                                $transaccionReferencia = "-";
                                $query = mysqli_query($link, "SELECT Movimiento.idTransaccionPrimaria, Movimiento.idMovimiento, Movimiento.fecha, Movimiento.monto, Colaborador.nombres, Colaborador.apellidos, Proveedor.nombre, Transaccion.montoRestante, Transaccion.fechaVencimiento FROM Movimiento INNER JOIN Transaccion ON Movimiento.idTransaccionPrimaria = Transaccion.idTransaccion INNER JOIN Colaborador ON Movimiento.idColaborador = Colaborador.idColaborador INNER JOIN Proveedor ON Movimiento.idProveedor = Proveedor.idProveedor WHERE Movimiento.fecha >= '{$fechaInicio} 00:00:00' AND Movimiento.fecha <= '{$fechaFin} 23:59:59' AND Movimiento.monto > 0 ORDER BY Movimiento.fecha");
                                while ($row = mysqli_fetch_array($query)) {
                                    echo "<tr>";
                                    $fechaTransaccion = explode(" ",$row['fecha']);
                                    echo "<td class='text-center'>{$fechaTransaccion[0]}</td>";
                                    echo "<td class='text-center'>{$row['idMovimiento']}</td>";
                                    echo "<td class='text-center'>{$row['idTransaccionPrimaria']}</td>";
                                    echo "<td class='text-center'>{$row['nombres']} {$row['apellidos']}</td>";
                                    echo "<td class='text-center'>{$row['nombre']}</td>";
                                    echo "<td class='text-center'>{$row['monto']}</td>";
                                    echo "<td class='text-center'>{$row['montoRestante']}</td>";
                                    echo "<td class='text-center'>{$row['fechaVencimiento']}</td>";
                                    echo "</tr>";
                                    $txt = $fechaTransaccion[0].",".$row['idMovimiento'].",".$row['idTransaccionPrimaria'].",".$row['nombres']." ".$row['apellidos'].",".$row['nombre'].",".$row['monto'].",".$row['montoRestante'].",".$row['fechaVencimiento'].PHP_EOL;
                                    fwrite($file, $txt);
                                    $atributo = null;
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>