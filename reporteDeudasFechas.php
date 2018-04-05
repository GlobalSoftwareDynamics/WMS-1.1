<div class="spacer30"></div>

<section class="container">
    <div class="row" id="opcionesMetodoPago">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-inverse card-info">
                    <div class="float-left">
                        <i class="fa fa-camera"></i>
                        Reporte de Deudas - <?php echo $_POST['fechaInicioReporte'] . " - " . $_POST['fechaFinReporte'];
                        $fileName = "files/".$_SESSION['user']."-reporteDeudasFechas.txt";?>
                    </div>
                    <div class="float-right">
                        <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Acciones
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="files/<?php echo $_SESSION['user']?>-reporteDeudasFechas.txt" download>Exportar Listado</a>
                                <form method="post" action="reporteDeudasFechasPDF.php">
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
                            <h6 class="text-center" style="text-decoration: underline">Deudas de Terceros</h6>
                            <div class="spacer10"></div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-center">Item</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Transacción</th>
                                    <th class="text-center">Cliente</th>
                                    <th class="text-center">Monto Pendiente</th>
                                    <th class="text-center">Fecha de Vencimiento</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $file = fopen($fileName,"w") or die("No se encontró el archivo!");
                                fwrite($file, pack("CCC",0xef,0xbb,0xbf));
                                $txt = PHP_EOL.PHP_EOL."Deudas de Terceros".PHP_EOL."Item,Fecha,Transacción,Cliente,Monto Pendiente,Fecha de Vencimiento".PHP_EOL;
                                fwrite($file, $txt);
                                $dateInicio = explode("-", $_POST['fechaInicioReporte']);
                                $dateFin = explode("-", $_POST['fechaFinReporte']);
                                $fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
                                $fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
                                $aux = 0;
                                $query = mysqli_query($link, "SELECT Transaccion.idTransaccion, Transaccion.montoRestante, Transaccion.fechaVencimiento, Transaccion.fechaTransaccion, Transaccion.idEstado, Proveedor.nombre FROM Transaccion INNER JOIN Proveedor ON Transaccion.idProveedor = Proveedor.idProveedor WHERE Transaccion.idTipoTransaccion IN (5,6) AND Transaccion.idEstado IN (3,6) AND Transaccion.fechaVencimiento >= '{$fechaInicio}' AND Transaccion.fechaVencimiento <= '{$fechaFin}' ORDER BY Transaccion.fechaVencimiento DESC, Transaccion.fechaTransaccion DESC");
                                while ($row = mysqli_fetch_array($query)) {
                                    $aux++;
                                    $fechaTransaccion = explode(" ",$row['fechaTransaccion']);
                                    echo "<tr>";
                                    echo "<td class='text-center'>$aux</td>";
                                    echo "<td class='text-center'>{$fechaTransaccion[0]}</td>";
                                    echo "<td class='text-center'>{$row['idTransaccion']}</td>";
                                    echo "<td class='text-center'>{$row['nombre']}</td>";
                                    echo "<td class='text-center'>S/ {$row['montoRestante']}</td>";
                                    echo "<td class='text-center'>{$row['fechaVencimiento']}</td>";
                                    echo "</tr>";
                                    $txt = $aux.",".$fechaTransaccion[0].",".$row['idTransaccion'].",".$row['nombre'].",S/ ".$row['montoRestante'].",".$row['fechaVencimiento'].PHP_EOL;
                                    fwrite($file, $txt);
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-center" style="text-decoration: underline">Deudas Propias</h6>
                            <div class="spacer10"></div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-center">Item</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Transacción</th>
                                    <th class="text-center">Consultora</th>
                                    <th class="text-center">Monto</th>
                                    <th class="text-center">Fecha de Vencimiento</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $txt = PHP_EOL.PHP_EOL."Deudas Propias".PHP_EOL."Item,Fecha,Transacción,Cliente,Monto Pendiente,Fecha de Vencimiento".PHP_EOL;
                                fwrite($file, $txt);
                                $dateInicio = explode("-", $_POST['fechaInicioReporte']);
                                $dateFin = explode("-", $_POST['fechaFinReporte']);
                                $fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
                                $fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
                                $aux = 0;
                                $query = mysqli_query($link, "SELECT Transaccion.idTransaccion, Transaccion.montoRestante, Transaccion.fechaVencimiento, Transaccion.fechaTransaccion, Transaccion.idEstado, Proveedor.nombre FROM Transaccion INNER JOIN Proveedor ON Transaccion.idProveedor = Proveedor.idProveedor WHERE Transaccion.idTipoTransaccion = 1 AND Transaccion.montoRestante > 0 AND Transaccion.fechaVencimiento >= '{$fechaInicio}' AND Transaccion.fechaVencimiento <= '{$fechaFin}' ORDER BY Transaccion.fechaVencimiento DESC, Transaccion.fechaTransaccion DESC");
                                while ($row = mysqli_fetch_array($query)) {
                                    $aux++;
                                    $fechaTransaccion = explode(" ",$row['fechaTransaccion']);
                                    echo "<tr>";
                                    echo "<td class='text-center'>$aux</td>";
                                    echo "<td class='text-center'>{$fechaTransaccion[0]}</td>";
                                    echo "<td class='text-center'>{$row['idTransaccion']}</td>";
                                    echo "<td class='text-center'>{$row['nombre']}</td>";
                                    echo "<td class='text-center'>S/ {$row['montoRestante']}</td>";
                                    echo "<td class='text-center'>{$row['fechaVencimiento']}</td>";
                                    echo "</tr>";
                                    $txt = $aux.",".$fechaTransaccion[0].",".$row['idTransaccion'].",".$row['nombre'].",S/ ".$row['montoRestante'].",".$row['fechaVencimiento'].PHP_EOL;
                                    fwrite($file, $txt);
                                }
                                fclose($file);
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