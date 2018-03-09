<div class="spacer30"></div>

<section class="container">
    <div class="row" id="opcionesMetodoPago">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-inverse card-info">
                    <div class="float-left">
                        <i class="fa fa-camera"></i>
                        Reporte de Stock por Fechas - <?php echo $_POST['fechaInicioReporte'] . " - " . $_POST['fechaFinReporte'];
                        $fileName = "files/".$_SESSION['user']."-reporteStockFechas.txt";?>
                    </div>
                    <div class="float-right">
                        <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Acciones
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="files/<?php echo $_SESSION['user']?>-reporteStockFechas.txt" download>Exportar Listado</a>
                                <form method="post" action="reporteFechasStockPDF.php">
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
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Producto</th>
                                    <th class="text-center">Ubicación</th>
                                    <th class="text-center">Stock</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $file = fopen($fileName,"w") or die("No se encontró el archivo!");
                                fwrite($file, pack("CCC",0xef,0xbb,0xbf));
                                $txt = "Item,Fecha,Producto,Ubicación,Stock".PHP_EOL;
                                fwrite($file, $txt);
                                $dateInicio = explode("-", $_POST['fechaInicioReporte']);
                                $dateFin = explode("-", $_POST['fechaFinReporte']);
                                $fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
                                $fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
                                $aux = 0;
                                $result = mysqli_query($link,"SELECT * FROM LogStock WHERE fechaCierre >= '{$fechaInicio}' AND fechaCierre <= '{$fechaFin}'");
                                while ($fila = mysqli_fetch_array($result)){
                                    $aux++;
                                    echo "<tr>";
                                    echo "<td>{$aux}</td>";
                                    echo "<td>{$fila['fechaCierre']}</td>";
                                    $query3 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$fila['idProducto']}'");
                                    while ($row3 = mysqli_fetch_array($query3)) {
                                        $nombreProducto = $row3['nombreCorto'];
                                        echo "<td>{$row3['nombreCorto']}</td>";
                                    }
                                    echo "<td>{$fila['idUbicacion']}</td>";
                                    echo "<td>{$fila['stock']}</td>";
                                    echo "</tr>";
                                    $txt = $aux.",".$fila['fechaCierre'].",".$nombreProducto.",".$fila['idUbicacion'].",".$fila['stock'].PHP_EOL;
                                    fwrite($file, $txt);
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