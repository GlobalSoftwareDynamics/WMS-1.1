<div class="spacer30"></div>

<section class="container">
    <div class="row" id="opcionesMetodoPago">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-inverse card-info">
                    <div class="float-left">
                        <i class="fa fa-camera"></i>
                        Reporte de Inventario Simple por Fechas - <?php echo $_POST['fechaInicioReporte'] . " - " . $_POST['fechaFinReporte'];
                        $fileName = "files/".$_SESSION['user']."-reporteInventarioSimpleFechas.txt";?>
                    </div>
                    <div class="float-right">
                        <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Acciones
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="files/<?php echo $_SESSION['user']?>-reporteInventarioSimpleFechas.txt" download>Exportar Listado</a>
                                <form method="post" action="reporteInventarioPDF.php">
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
                            <h6 class="text-center" style="text-decoration: underline">Ingresos y Salidas de Mercadería</h6>
                            <div class="spacer10"></div>
                            <table class="table text-center">
                                <thead>
                                <tr>
                                    <th class="text-center">Item</th>
                                    <th class="text-center">Producto</th>
                                    <th class="text-center">Ingreso</th>
                                    <th class="text-center">Salida</th>
                                    <th class="text-center">Stock Actual</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $file = fopen($fileName,"w") or die("No se encontró el archivo!");
                                fwrite($file, pack("CCC",0xef,0xbb,0xbf));
                                $txt = "Item,Producto,Ingreso,Salida,Stock Actual,".PHP_EOL;
                                fwrite($file, $txt);
                                $dateInicio = explode("-", $_POST['fechaInicioReporte']);
                                $dateFin = explode("-", $_POST['fechaFinReporte']);
                                $fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
                                $fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
                                $aux = 0;
                                $transaccionReferencia = "-";
                                $query = mysqli_query($link, "SELECT idProducto, SUM(CASE WHEN idTransaccion IN (SELECT idTransaccion FROM Transaccion WHERE idTipoTransaccion IN (2,4) AND fechaTransaccion >= '{$fechaInicio} 00:00:00' AND fechaTransaccion <= '{$fechaFin} 23:59:59') THEN cantidad ELSE 0 END) AS CantidadIngreso, SUM(CASE WHEN idTransaccion IN (SELECT idTransaccion FROM Transaccion WHERE idTipoTransaccion IN (5,6) AND fechaTransaccion >= '{$fechaInicio} 00:00:00' AND fechaTransaccion <= '{$fechaFin} 23:59:59') THEN cantidad ELSE 0 END) AS CantidadSalida FROM TransaccionProducto GROUP BY idProducto");
                                while ($row = mysqli_fetch_array($query)) {
                                    if($row['CantidadIngreso'] == 0 && $row['CantidadSalida'] == 0){

                                    }else{
                                        $aux++;
                                        echo "<tr>";
                                        echo "<td>{$aux}</td>";
                                        $query3 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$row['idProducto']}'");
                                        while ($row3 = mysqli_fetch_array($query3)) {
                                            $nombreProducto = $row3['nombreCorto'];
                                            echo "<td class='text-center'>{$row3['nombreCorto']}</td>";
                                        }
                                        echo "<td>{$row['CantidadIngreso']}</td>";
                                        $cantidadIngreso = $row['CantidadIngreso'];
                                        echo "<td>{$row['CantidadSalida']}</td>";
                                        $cantidadSalida = $row['CantidadSalida'];
                                        $stockActual = 0;
                                        $select2 = mysqli_query($link, "SELECT * FROM UbicacionProducto WHERE idProducto = '{$row['idProducto']}'");
                                        while($row2 = mysqli_fetch_array($select2)){
                                            $stockActual += $row2['stock'];
                                        }
                                        echo "<td>{$stockActual}</td>";
                                        echo "</tr>";
                                        $txt = $aux.",".$nombreProducto.",".$cantidadIngreso.",".$cantidadSalida.",".$stockActual.PHP_EOL;
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