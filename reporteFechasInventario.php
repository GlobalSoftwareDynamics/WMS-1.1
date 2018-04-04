<div class="spacer30"></div>

<section class="container">
    <div class="row" id="opcionesMetodoPago">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-inverse card-info">
                    <div class="float-left">
                        <i class="fa fa-camera"></i>
                        Reporte de Inventario por Fechas - <?php echo $_POST['fechaInicioReporte'] . " - " . $_POST['fechaFinReporte'];
						$fileName = "files/".$_SESSION['user']."-reporteInventarioFechas.txt";?>
                    </div>
                    <div class="float-right">
                        <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Acciones
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="files/<?php echo $_SESSION['user']?>-reporteInventarioFechas.txt" download>Exportar Listado</a>
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
                            <h6 class="text-center" style="text-decoration: underline">Ingresos de Mercadería</h6>
                            <div class="spacer10"></div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-center">Item</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Transacción</th>
                                    <th class="text-center">Producto</th>
                                    <th class="text-center">Almacén</th>
                                    <th class="text-center">Ubicación</th>
                                    <th class="text-center">Cantidad</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								$file = fopen($fileName,"w") or die("No se encontró el archivo!");
								fwrite($file, pack("CCC",0xef,0xbb,0xbf));
								$txt = "Ingresos".PHP_EOL."Item,Fecha,Transacción,Producto,Almacén,Ubicación,Cantidad".PHP_EOL;
								fwrite($file, $txt);
								$dateInicio = explode("-", $_POST['fechaInicioReporte']);
								$dateFin = explode("-", $_POST['fechaFinReporte']);
								$fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
								$fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
								$aux = 0;
								$transaccionReferencia = "-";
								$query = mysqli_query($link, "SELECT * FROM Transaccion WHERE idTipoTransaccion IN (2,4) ORDER BY fechaTransaccion DESC");
								while ($row = mysqli_fetch_array($query)) {
									$fechaTransac = explode(" ",$row['fechaTransaccion']);
									$fechaTransaccionCompleta = $fechaTransac[0];
									if($fechaTransaccionCompleta <= $fechaFin && $fechaTransaccionCompleta >= $fechaInicio){
										$valorReferencia = 0;
										$query2 = mysqli_query($link, "SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['idTransaccion']}'");
										while ($row2 = mysqli_fetch_array($query2)) {
											$aux++;
											echo "<tr>";
											echo "<td class='text-center'>$aux</td>";
                                            $fechaTransaccion = explode(" ",$row['fechaTransaccion']);
                                            echo "<td class='text-center'>{$fechaTransaccion[0]}</td>";
											echo "<td class='text-center'>{$row['idTransaccion']}</td>";
											$query3 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$row2['idProducto']}'");
											while ($row3 = mysqli_fetch_array($query3)) {
												$nombreProducto = $row3['nombreCorto'];
												echo "<td class='text-center'>{$row3['nombreCorto']}</td>";
											}
											$query3 = mysqli_query($link, "SELECT * FROM Ubicacion WHERE idUbicacion = '{$row2['idUbicacionFinal']}'");
											while ($row3 = mysqli_fetch_array($query3)) {
												$query4 = mysqli_query($link, "SELECT * FROM Almacen WHERE idAlmacen = '{$row3['idAlmacen']}'");
												while ($row4 = mysqli_fetch_array($query4)) {
													$nombreAlmacen = $row4['descripcion'];
													echo "<td class='text-center'>{$row4['descripcion']}</td>";
												}
											}
											echo "<td class='text-center'>{$row2['idUbicacionFinal']}</td>";
											echo "<td class='text-center'>{$row2['cantidad']}</td>";
											echo "</tr>";
											$txt = $aux.",".$fechaTransaccion[0].",".$row['idTransaccion'].",".$nombreProducto.",".$nombreAlmacen.",".$row2['idUbicacionFinal'].",".$row2['cantidad'].PHP_EOL;
											fwrite($file, $txt);
											$atributo = null;
										}
									}
								}
								?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-center" style="text-decoration: underline">Salidas de
                                Mercadería</h6>
                            <div class="spacer10"></div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-center">Item</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Transacción</th>
                                    <th class="text-center">Producto</th>
                                    <th class="text-center">Almacén</th>
                                    <th class="text-center">Ubicación</th>
                                    <th class="text-center">Cantidad</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								$txt = PHP_EOL.PHP_EOL."Salidas".PHP_EOL."Item,Fecha,Transacción,Producto,Almacén,Ubicación,Cantidad".PHP_EOL;
								fwrite($file, $txt);
								$dateInicio = explode("-", $_POST['fechaInicioReporte']);
								$dateFin = explode("-", $_POST['fechaFinReporte']);
								$fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
								$fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
								$aux = 0;
								$query = mysqli_query($link, "SELECT * FROM Transaccion WHERE idTipoTransaccion IN (5,6) ORDER BY fechaTransaccion DESC");
								while ($row = mysqli_fetch_array($query)) {
									$fechaTransac = explode(" ",$row['fechaTransaccion']);
									$fechaTransaccionCompleta = $fechaTransac[0];
									if($fechaTransaccionCompleta <= $fechaFin && $fechaTransaccionCompleta >= $fechaInicio){
										$valorReferencia = 0;
										$query2 = mysqli_query($link, "SELECT * FROM logMovimientosAlmacen WHERE idTransaccion = '{$row['idTransaccion']}'");
										while ($row2 = mysqli_fetch_array($query2)) {

										    $aux++;
											echo "<tr>";
											echo "<td class='text-center'>$aux</td>";
                                            $fechaTransaccion = explode(" ",$row['fechaTransaccion']);
                                            echo "<td class='text-center'>{$fechaTransaccion[0]}</td>";
											echo "<td class='text-center'>{$row['idTransaccion']}</td>";
											$query3 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$row2['idProducto']}'");
											while ($row3 = mysqli_fetch_array($query3)) {
												$nombreProducto = $row3['nombreCorto'];
												echo "<td class='text-center'>{$row3['nombreCorto']}</td>";
											}
                                            $query3 = mysqli_query($link, "SELECT * FROM Ubicacion WHERE idUbicacion = '{$row2['idUbicacion']}'");
                                            while ($row3 = mysqli_fetch_array($query3)) {
                                                $query4 = mysqli_query($link, "SELECT * FROM Almacen WHERE idAlmacen = '{$row3['idAlmacen']}'");
                                                while ($row4 = mysqli_fetch_array($query4)) {
                                                    $nombreAlmacen = $row4['descripcion'];
                                                    echo "<td class='text-center'>{$row4['descripcion']}</td>";
                                                }
                                            }
                                            echo "<td class='text-center'>{$row2['idUbicacion']}</td>";
											echo "<td class='text-center'>{$row2['cantidad']}</td>";
											echo "</tr>";
											$txt = $aux.",".$row['idTransaccion'].",".$fechaTransaccion[0].",".$nombreProducto.",".$nombreAlmacen.",".$row2['idUbicacion'].",".$row2['cantidad'].PHP_EOL;
											fwrite($file, $txt);
											$atributo = null;
										}
									}
								}
								?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-center" style="text-decoration: underline">Transferencias de
                                Mercadería</h6>
                            <div class="spacer10"></div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-center">Item</th>
                                    <th class="text-center">Transacción</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Producto</th>
                                    <th class="text-center">Almacén de Salida</th>
                                    <th class="text-center">Ubicación de Salida</th>
                                    <th class="text-center">Almacén de Ingreso</th>
                                    <th class="text-center">Ubicación de Ingreso</th>
                                    <th class="text-center">Cantidad</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								$txt = PHP_EOL.PHP_EOL."Transferencias".PHP_EOL."Item,Transacción,Fecha,Producto,Almacén de Salida,Ubicación de Salida,Almacén de Ingreso,Ubicación de Ingreso,Cantidad".PHP_EOL;
								fwrite($file, $txt);
								$dateInicio = explode("-", $_POST['fechaInicioReporte']);
								$dateFin = explode("-", $_POST['fechaFinReporte']);
								$fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
								$fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
								$aux = 0;
								$query = mysqli_query($link, "SELECT * FROM Transaccion WHERE idTipoTransaccion IN (7) ORDER BY fechaTransaccion DESC");
								while ($row = mysqli_fetch_array($query)) {
									$fechaTransac = explode(" ",$row['fechaTransaccion']);
									$fechaTransaccionCompleta = $fechaTransac[0];
									if($fechaTransaccionCompleta <= $fechaFin && $fechaTransaccionCompleta >= $fechaInicio){
										$valorReferencia = 0;
										$query2 = mysqli_query($link, "SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['idTransaccion']}'");
										while ($row2 = mysqli_fetch_array($query2)) {
											if (substr($row['idTransaccion'], 0, 2) == 'OR') {
												$transaccionReferencia = $row['referenciaTransaccion'];
												$query3 = mysqli_query($link, "SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['referenciaTransaccion']}' AND idProducto = '{$row2['idProducto']}'");
												while ($row3 = mysqli_fetch_array($query3)) {
													$valorReferencia = $row3['valorUnitario'];
												}
											}
											$aux++;
											echo "<tr>";
											echo "<td class='text-center'>$aux</td>";
											echo "<td class='text-center'>{$row['idTransaccion']}</td>";
											$fechaTransaccion = explode(" ",$row['fechaTransaccion']);
											echo "<td class='text-center'>{$fechaTransaccion[0]}</td>";
											$query3 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$row2['idProducto']}'");
											while ($row3 = mysqli_fetch_array($query3)) {
												$nombreProducto = $row3['nombreCorto'];
												$query4 = mysqli_query($link,"SELECT * FROM Color WHERE idColor = '{$row3['idColor']}'");
												while($row4 = mysqli_fetch_array($query4)){
													$atributo = $row4['descripcion'];
												}
												echo "<td class='text-center'>{$row3['nombreCorto']} {$atributo}</td>";
											}
											$query3 = mysqli_query($link, "SELECT * FROM Ubicacion WHERE idUbicacion = '{$row2['idUbicacionInicial']}'");
											while ($row3 = mysqli_fetch_array($query3)) {
												$query4 = mysqli_query($link, "SELECT * FROM Almacen WHERE idAlmacen = '{$row3['idAlmacen']}'");
												while ($row4 = mysqli_fetch_array($query4)) {
													$nombreAlmacen = $row4['descripcion'];
													echo "<td class='text-center'>{$row4['descripcion']}</td>";
												}
											}
											echo "<td class='text-center'>{$row2['idUbicacionInicial']}</td>";
											$query3 = mysqli_query($link, "SELECT * FROM Ubicacion WHERE idUbicacion = '{$row2['idUbicacionFinal']}'");
											while ($row3 = mysqli_fetch_array($query3)) {
												$query4 = mysqli_query($link, "SELECT * FROM Almacen WHERE idAlmacen = '{$row3['idAlmacen']}'");
												while ($row4 = mysqli_fetch_array($query4)) {
													$nombreAlmacenFinal = $row4['descripcion'];
													echo "<td class='text-center'>{$row4['descripcion']}</td>";
												}
											}
											echo "<td class='text-center'>{$row2['idUbicacionFinal']}</td>";
											echo "<td class='text-center'>{$row2['cantidad']}</td>";
											echo "</tr>";
											$txt = $aux.",".$row['idTransaccion'].",".$fechaTransaccion[0].",".$nombreProducto." ".$atributo.",".$nombreAlmacen.",".$row2['idUbicacionInicial'].",".$nombreAlmacenFinal.",".$row2['idUbicacionFinal'].",".$row2['cantidad'].PHP_EOL;
											fwrite($file, $txt);
											$atributo = null;
										}
									}
								}
								?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-center" style="text-decoration: underline">Correcciones de
                                Inventario</h6>
                            <div class="spacer10"></div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-center">Item</th>
                                    <th class="text-center">Transacción</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Producto</th>
                                    <th class="text-center">Almacén</th>
                                    <th class="text-center">Ubicación</th>
                                    <th class="text-center">Cantidad Inicial</th>
                                    <th class="text-center">Cantidad Final</th>
                                    <th class="text-center">Motivo</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								$txt = PHP_EOL.PHP_EOL."Correcciones".PHP_EOL."Item,Transacción,Fecha,Producto,Almacén,Ubicación,Cantidad Inicial,Cantidad Final,Motivo".PHP_EOL;
								fwrite($file, $txt);
								$dateInicio = explode("-", $_POST['fechaInicioReporte']);
								$dateFin = explode("-", $_POST['fechaFinReporte']);
								$fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
								$fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
								$aux = 0;
								$query = mysqli_query($link, "SELECT * FROM Transaccion WHERE idTipoTransaccion IN (8) ORDER BY fechaTransaccion DESC");
								while ($row = mysqli_fetch_array($query)) {
									$fechaTransac = explode(" ",$row['fechaTransaccion']);
									$fechaTransaccionCompleta = $fechaTransac[0];
									if($fechaTransaccionCompleta <= $fechaFin && $fechaTransaccionCompleta >= $fechaInicio){
										$valorReferencia = 0;
										$query2 = mysqli_query($link, "SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['idTransaccion']}'");
										while ($row2 = mysqli_fetch_array($query2)) {
											$aux++;
											echo "<tr>";
											echo "<td class='text-center'>$aux</td>";
											echo "<td class='text-center'>{$row['idTransaccion']}</td>";
											$fechaTransaccion = explode(" ",$row['fechaTransaccion']);
											echo "<td class='text-center'>{$fechaTransaccion[0]}</td>";
											$query3 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$row2['idProducto']}'");
											while ($row3 = mysqli_fetch_array($query3)) {
												$nombreProducto = $row3['nombreCorto'];
												$query4 = mysqli_query($link,"SELECT * FROM Color WHERE idColor = '{$row3['idColor']}'");
												while($row4 = mysqli_fetch_array($query4)){
													$atributo = $row4['descripcion'];
												}
												echo "<td class='text-center'>{$row3['nombreCorto']} {$atributo}</td>";
											}
											$query3 = mysqli_query($link, "SELECT * FROM Ubicacion WHERE idUbicacion = '{$row2['idUbicacionInicial']}'");
											while ($row3 = mysqli_fetch_array($query3)) {
												$query4 = mysqli_query($link, "SELECT * FROM Almacen WHERE idAlmacen = '{$row3['idAlmacen']}'");
												while ($row4 = mysqli_fetch_array($query4)) {
													$nombreAlmacen = $row4['descripcion'];
													echo "<td class='text-center'>{$row4['descripcion']}</td>";
												}
											}
											echo "<td class='text-center'>{$row2['idUbicacionInicial']}</td>";
											echo "<td class='text-center'>{$row2['stockInicial']}</td>";
											echo "<td class='text-center'>{$row2['stockFinal']}</td>";
											echo "<td class='text-center'>{$row['observacion']}</td>";
											echo "</tr>";
											$txt = $aux.",".$row['idTransaccion'].",".$fechaTransaccion[0].",".$nombreProducto." ".$atributo.",".$nombreAlmacen.",".$row2['idUbicacionInicial'].",".$row2['stockInicial'].",".$row2['stockFinal'].",".$row['observacion'].PHP_EOL;
											fwrite($file, $txt);
											$atributo = null;
										}
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