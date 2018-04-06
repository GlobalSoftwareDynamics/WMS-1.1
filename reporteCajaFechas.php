<div class="spacer30"></div>

<section class="container">
    <div class="row" id="opcionesMetodoPago">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-inverse card-info">
                    <div class="float-left">
                        <i class="fa fa-bar-chart"></i>
                        Reporte de Caja Diario - <?php echo $_POST['fechaInicioReporte'] . " - " . $_POST['fechaFinReporte'];
						$fileName = "files/".$_SESSION['user']."-reporteCajaFechas.txt";?>
                    </div>
                    <div class="float-right">
                        <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Acciones
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="files/<?php echo $_SESSION['user']?>-reporteCajaFechas.txt" download>Exportar Listado</a>
                                <form method="post" action="reporteCajaFechasPDF.php">
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
                            <h6 class="text-center" style="text-decoration: underline">Ingresos</h6>
                            <div class="spacer10"></div>
                            <table class="table text-center">
                                <thead>
                                <tr>
                                    <th class="text-center">Item</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Movimiento</th>
                                    <th class="text-center">Responsable</th>
                                    <th class="text-center">Cliente/Proveedor</th>
                                    <th class="text-center">Tipo</th>
                                    <th class="text-center">Monto</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								$file = fopen($fileName,"w") or die("No se encontró el archivo!");
								fwrite($file, pack("CCC",0xef,0xbb,0xbf));
								$txt = "Ingresos".PHP_EOL."Item,Fecha,Movimiento,Responsable,Cliente/Proveedor,Tipo,Monto".PHP_EOL;
								fwrite($file, $txt);
								$dateInicio = explode("-", $_POST['fechaInicioReporte']);
								$dateFin = explode("-", $_POST['fechaFinReporte']);
								$fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
								$fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
								$aux = 0;
								$totalingresos = 0;
								$query = mysqli_query($link,"SELECT * FROM Movimiento WHERE idTipoMovimiento IN (SELECT idTipoMovimiento FROM TipoMovimiento WHERE tipo = 1) AND idCuenta = 1 ORDER BY fecha DESC");
								while($row = mysqli_fetch_array($query)){
									if($row['monto'] > 0){
										$fechaTransac = explode(" ",$row['fecha']);
										$fechaTransaccionCompleta = $fechaTransac[0];
										if($fechaTransaccionCompleta <= $fechaFin && $fechaTransaccionCompleta >= $fechaInicio){
											$aux++;
											$fecha=explode(" ",$row['fecha']);
											echo "<tr>";
											echo "<td>{$aux}</td>";
											echo "<td>{$fecha[0]}</td>";
											$result = mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$row['idColaborador']}'");
											while ($fila=mysqli_fetch_array($result)){
												$nombre = $fila['nombres']." ".$fila['apellidos'];
											}
											$result = mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$row['idProveedor']}'");
											while ($fila=mysqli_fetch_array($result)){
												$nombreProveedor = $fila['nombre'];
											}
											$result = mysqli_query($link,"SELECT * FROM TipoMovimiento WHERE idTipoMovimiento = '{$row['idTipoMovimiento']}'");
											while ($fila=mysqli_fetch_array($result)){
												$tipo = $fila['descripcion'];
											}
											echo "<td>{$row['idMovimiento']}</td>";
											echo "<td>{$nombre}</td>";
											echo "<td>{$nombreProveedor}</td>";
											echo "<td>{$tipo}</td>";
											echo "<td>S/. + {$row['monto']}</td>";
											echo "</tr>";
											$totalingresos += $row['monto'];
											$txt = $aux.",".$fecha[0].",".$row['idMovimiento'].",".$nombre.",".$nombreProveedor.",".$tipo.",".$row['monto'].PHP_EOL;
											fwrite($file, $txt);
										}
									}
								}
								?>
                                <tr>
                                    <td colspan="6" class="text-left font-weight-bold">Total Ingresos</td>
                                    <td><?php echo "S/. +".$totalingresos;
										$txt = PHP_EOL."Total,".$totalingresos.PHP_EOL;
										fwrite($file, $txt);?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-center" style="text-decoration: underline">Egresos</h6>
                            <div class="spacer10"></div>
                            <table class="table text-center">
                                <thead>
                                <tr>
                                    <th class="text-center">Item</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Movimiento</th>
                                    <th class="text-center">Responsable</th>
                                    <th class="text-center">Cliente/Proveedor</th>
                                    <th class="text-center">Tipo</th>
                                    <th class="text-center">Monto</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								$txt = PHP_EOL.PHP_EOL."Egresos".PHP_EOL."Item,Fecha,Movimiento,Responsable,Cliente/Proveedor,Tipo,Monto".PHP_EOL;
								fwrite($file, $txt);
								$dateInicio = explode("-", $_POST['fechaInicioReporte']);
								$dateFin = explode("-", $_POST['fechaFinReporte']);
								$fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
								$fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
								$aux = 0;
								$totalegresos = 0;
								$query = mysqli_query($link,"SELECT * FROM Movimiento WHERE idTipoMovimiento IN (SELECT idTipoMovimiento FROM TipoMovimiento WHERE tipo = 0) AND idCuenta = 1 ORDER BY fecha DESC");
								while($row = mysqli_fetch_array($query)){
									if($row['monto'] > 0){
										$fechaTransac = explode(" ",$row['fecha']);
										$fechaTransaccionCompleta = $fechaTransac[0];
										if($fechaTransaccionCompleta <= $fechaFin && $fechaTransaccionCompleta >= $fechaInicio){
											$fecha=explode(" ",$row['fecha']);
											echo "<tr>";
											echo "<td>{$aux}</td>";
											echo "<td>{$fecha[0]}</td>";
											$result = mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$row['idColaborador']}'");
											while ($fila=mysqli_fetch_array($result)){
												$nombre = $fila['nombres']." ".$fila['apellidos'];
											}
											$result = mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$row['idProveedor']}'");
											while ($fila=mysqli_fetch_array($result)){
												$nombreProveedor = $fila['nombre'];
											}
											$result = mysqli_query($link,"SELECT * FROM TipoMovimiento WHERE idTipoMovimiento = '{$row['idTipoMovimiento']}'");
											while ($fila=mysqli_fetch_array($result)){
												$tipo = $fila['descripcion'];
											}
											echo "<td>{$row['idMovimiento']}</td>";
											echo "<td>{$nombre}</td>";
											echo "<td>{$nombreProveedor}</td>";
											echo "<td>{$tipo}</td>";
											echo "<td>S/. - {$row['monto']}</td>";
											echo "</tr>";
											$totalegresos += $row['monto'];
											$txt = $aux.",".$fecha[0].",".$row['idMovimiento'].",".$nombre.",".$nombreProveedor.",".$tipo.",".$row['monto'].PHP_EOL;
											fwrite($file, $txt);
										}
									}
								}
								?>
                                <tr>
                                    <td colspan="6" class="text-left font-weight-bold">Total Egresos</td>
                                    <td><?php echo "S/. -".$totalegresos;
										$txt = PHP_EOL."Total,".$totalegresos.PHP_EOL;
										fwrite($file, $txt);?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-center" style="text-decoration: underline">Cupones</h6>
                            <div class="spacer10"></div>
                            <table class="table text-center">
                                <thead>
                                <tr>
                                    <th class="text-center">Item</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Movimiento</th>
                                    <th class="text-center">Transacción Primaria</th>
                                    <th class="text-center">Transacción Referencial</th>
                                    <th class="text-center">Cliente</th>
                                    <th class="text-center">Monto</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								$txt = PHP_EOL.PHP_EOL."Cupones".PHP_EOL."Item,Fecha,Movimiento,Responsable,Cliente/Proveedor,Tipo,Monto".PHP_EOL;
								fwrite($file, $txt);
								$dateInicio = explode("-", $_POST['fechaInicioReporte']);
								$dateFin = explode("-", $_POST['fechaFinReporte']);
								$fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
								$fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
								$aux = 0;
								$totalcupones = 0;
								$query = mysqli_query($link,"SELECT * FROM Movimiento WHERE idMedioPago = 3 ORDER BY fecha DESC");
								while($row = mysqli_fetch_array($query)){
									if($row['monto'] > 0){
										$fechaTransac = explode(" ",$row['fecha']);
										$fechaTransaccionCompleta = $fechaTransac[0];
										if($fechaTransaccionCompleta <= $fechaFin && $fechaTransaccionCompleta >= $fechaInicio){
											$fecha=explode(" ",$row['fecha']);
											$aux++;
											echo "<tr>";
											echo "<td>{$aux}</td>";
											echo "<td>{$fecha[0]}</td>";
											$result = mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$row['idProveedor']}'");
											while ($fila=mysqli_fetch_array($result)){
												$nombreProveedor = $fila['nombre'];
											}
											echo "<td>{$row['idMovimiento']}</td>";
											echo "<td>{$row['idTransaccionPrimaria']}</td>";
											echo "<td>{$row['idTransaccionReferencial']}</td>";
											echo "<td>{$nombreProveedor}</td>";
											echo "<td>S/. + {$row['monto']}</td>";
											echo "</tr>";
											$totalcupones += $row['monto'];
											$txt = $aux.",".$fecha[0].",".$row['idMovimiento'].",".$row['idTransaccionPrimaria'].",".$row['idTransaccionReferencial'].",".$nombreProveedor.",".$row['monto'].PHP_EOL;
											fwrite($file, $txt);
										}
									}
								}
								?>
                                <tr>
                                    <td colspan="6" class="text-left font-weight-bold">Total Cupones</td>
                                    <td><?php echo "S/. +".$totalcupones;
										$txt = PHP_EOL."Total,".$totalcupones.PHP_EOL;
										fwrite($file, $txt);?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <h6 class="text-center" style="text-decoration: underline">Saldos</h6>
                            <div class="spacer10"></div>
                            <table class="table text-center">
                                <thead>
                                <tr>
                                    <th class="text-center">Cuenta</th>
                                    <th class="text-center">Saldo</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								$txt = PHP_EOL.PHP_EOL.'Saldos'.PHP_EOL;
								fwrite($file, $txt);
								$dateFin = explode("-", $_POST['fechaFinReporte']);
								$fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
								$fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
								$query = mysqli_query($link,"SELECT * FROM Cuenta");
								while ($row=mysqli_fetch_array($query)) {
									$query3 = mysqli_query($link, "SELECT * FROM LogSaldos WHERE idCuenta = '{$row['idCuenta']}' AND fecha = '{$fechaFin}'");
									$numrows = mysqli_num_rows($query3);
									if ($numrows > 0) {
										while ($rows3 = mysqli_fetch_array($query3)) {
											echo "<tr>";
											echo "<td>{$row['alias']}</td>";
											$txt = PHP_EOL . $row['alias'] . PHP_EOL;
											fwrite($file, $txt);
											echo "<td>S/. {$rows3['saldo']}</td>";
											echo "</tr>";
											$txt = PHP_EOL . $rows3['saldo'] . PHP_EOL;
											fwrite($file, $txt);
										}
									}elseif($numrows==0){
										while ($rows3 = mysqli_fetch_array($query3)) {
											echo "<tr>";
											$alias = mysqli_query($link, "SELECT * FROM Cuenta WHERE idCuenta = '{$row['idCuenta']}'");
											while ($fila1 = mysqli_fetch_array($alias)) {
												echo "<td>{$fila1['alias']}</td>";
												$txt = PHP_EOL . $fila1['alias'] . PHP_EOL;
												fwrite($file, $txt);
											}
											echo "<td>S/. {$row['saldo']}</td>";
											echo "</tr>";
											$txt = PHP_EOL . $row['saldo'] . PHP_EOL;
											fwrite($file, $txt);
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
