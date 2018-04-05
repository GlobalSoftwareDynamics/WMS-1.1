<div class="spacer30"></div>

<section class="container">
    <div class="row" id="opcionesMetodoPago">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-inverse card-info">
                    <div class="float-left">
                        <i class="fa fa-camera"></i>
                        Reporte de Premios por Llegar - <?php echo $_POST['fechaInicioReporte'] . " - " . $_POST['fechaFinReporte'];
                        $fileName = "files/".$_SESSION['user']."-reportePremiosPorLlegarFechas.txt";?>
                    </div>
                    <div class="float-right">
                        <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Acciones
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="files/<?php echo $_SESSION['user']?>-reportePremiosPorLlegarFechas.txt" download>Exportar Listado</a>
                                <form method="post" action="reportePremiosPorLlegarFechasPDF.php">
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
                                    <th class="text-center">Item</th>
                                    <th class="text-center">Producto</th>
                                    <th class="text-center">Transacción</th>
                                    <th class="text-center">Fecha Transacción</th>
                                    <th class="text-center">Compra Asociada</th>
                                    <th class="text-center">Cantidad</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $file = fopen($fileName,"w") or die("No se encontró el archivo!");
                                fwrite($file, pack("CCC",0xef,0xbb,0xbf));
                                $txt = "Item,Producto,Transacción,Fecha Transacción,Compra Asociada,Cantidad".PHP_EOL;
                                fwrite($file, $txt);
                                $dateInicio = explode("-", $_POST['fechaInicioReporte']);
                                $dateFin = explode("-", $_POST['fechaFinReporte']);
                                $fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
                                $fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
                                $aux = 0;
                                $result = mysqli_query($link,"SELECT TransaccionProducto.idTransaccion, TransaccionProducto.idProducto, Producto.nombreCorto, Transaccion.fechaEstimada, Transaccion.fechaTransaccion, Transaccion.idEstado, TransaccionProducto.cantidad FROM TransaccionProducto INNER JOIN Producto ON TransaccionProducto.idProducto = Producto.idProducto INNER JOIN Transaccion ON TransaccionProducto.idTransaccion = Transaccion.idTransaccion WHERE TransaccionProducto.idTransaccion LIKE 'OCP%' AND Transaccion.idEstado IN (3,6) AND Transaccion.fechaTransaccion >= '{$fechaInicio} 00:00:00' AND Transaccion.fechaTransaccion <= '{$fechaFin} 23:59:59' ORDER BY Transaccion.fechaTransaccion DESC");
                                while ($fila = mysqli_fetch_array($result)){
                                    $cantidadRecibida = 0;
                                    $result1 = mysqli_query($link,"SELECT idTransaccion, cantidad FROM TransaccionProducto WHERE idTransaccion IN (SELECT idTransaccion FROM Transaccion WHERE referenciaTransaccion = '{$fila['idTransaccion']}' AND idTransaccion LIKE 'OR%') AND idProducto = '{$fila['idProducto']}'");
                                    $numrow = mysqli_num_rows($result1);
                                    if($numrow > 0){
                                        while ($fila1 =  mysqli_fetch_array($result1)){
                                            $cantidadRecibida += $fila1['cantidad'];
                                        }
                                    }

                                    $cantidad = $fila['cantidad'] - $cantidadRecibida;

                                    if($cantidad > 0){
                                        $aux++;
                                        $fechaTransaccion = explode(" ",$fila['fechaTransaccion']);
                                        echo "<tr>";
                                        echo "<td>{$aux}</td>";
                                        echo "<td>{$fila['nombreCorto']}</td>";
                                        echo "<td>{$fila['idTransaccion']}</td>";
                                        echo "<td>{$fechaTransaccion[0]}</td>";
                                        echo "<td>{$fila['fechaEstimada']}</td>";
                                        echo "<td>{$cantidad}</td>";
                                        echo "</tr>";
                                        $txt = $aux.",".$fila['nombreCorto'].",".$fila['idTransaccion'].",".$fechaTransaccion[0].",".$fila['fechaEstimada'].",".$cantidad.PHP_EOL;
                                        fwrite($file, $txt);
                                    }
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