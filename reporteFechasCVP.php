<?php
$totalPrestamos = 0;
$totalVentas = 0;
$totalCompras = 0;

$colaboradorasCompras = array();
$colaboradorasVentas = array();
$colaboradorasPrestamos = array();

$colaboradorasComprasValores = array();
$colaboradorasVentasValores = array();
$colaboradorasPrestamosValores = array();

$colaboradorasComprasTotales = array();
$colaboradorasVentasTotales = array();
$colaboradorasPrestamosTotales = array();

$colaboradorasComprasActual = array();
$colaboradorasVentasActual = array();
$colaboradorasPrestamosActual = array();

$aux3 = 0;
?>
<div class="spacer30"></div>

<section class="container">
    <div class="row" id="opcionesMetodoPago">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-inverse card-info">
                    <div class="float-left">
                        <i class="fa fa-camera"></i>
                        Reporte de Compras por Fechas - <?php echo $_POST['fechaInicioReporte'] . " - " . $_POST['fechaFinReporte'];
	                    $fileName = "files/".$_SESSION['user']."-reporteCompras.txt";?>
                    </div>
                    <div class="float-right">
                        <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Acciones
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="files/<?php echo $_SESSION['user']?>-reporteCompras.txt" download>Exportar Listado</a>
                                <form method="post" action="reporteFechasCVPPDF.php">
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
                            <h6 class="text-center" style="text-decoration: underline">Compras de Mercadería</h6>
                            <div class="spacer10"></div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-center" style="width: 5%">Item</th>
                                    <th class="text-center" style="width: 15%">Colaboradora</th>
                                    <th class="text-center" style="width: 15%">Cliente</th>
                                    <th class="text-center" style="width: 10%">Transacción</th>
                                    <th class="text-center" style="width: 10%">Fecha</th>
                                    <th class="text-center" style="width: 15%">Producto</th>
                                    <th class="text-center" style="width: 10%">Cantidad</th>
                                    <th class="text-center" style="width: 10%">Valor Unitario (S/.)</th>
                                    <th class="text-center" style="width: 10%">Total (S/.)</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								$file = fopen($fileName,"w") or die("No se encontró el archivo!");
								fwrite($file, pack("CCC",0xef,0xbb,0xbf));
								$txt = "Compras".PHP_EOL."Item,Colaboradora,Cliente,Transacción,Fecha,Producto,Cantidad,Valor Unitario,Total".PHP_EOL;
								fwrite($file, $txt);
								$valorCompras = 0;
								$dateInicio = explode("-", $_POST['fechaInicioReporte']);
								$dateFin = explode("-", $_POST['fechaFinReporte']);
								$fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
								$fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
								$aux = 0;
								$aux2 = 0;
								$query = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTipoTransaccion IN (1)");
								while($row = mysqli_fetch_array($query)){
                                    $fechaTransac = explode(" ",$row['fechaTransaccion']);
                                    $fechaTransaccionCompleta = $fechaTransac[0];
									if($fechaTransaccionCompleta <= $fechaFin && $fechaTransaccionCompleta >= $fechaInicio){
										$colaboradorasCompras[$aux2] = $row['idColaborador'];
										$query2 = mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['idTransaccion']}'");
										while($row2 = mysqli_fetch_array($query2)){
											$aux++;
											echo "<tr>";
											echo "<td class='text-center'>$aux</td>";
											$query3 = mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$row['idColaborador']}'");
											while($row3 = mysqli_fetch_array($query3)){
												$nombreColaborador = $row3['nombres'];
												$nombreColaborador = $nombreColaborador." ".$row3['apellidos'];
												echo "<td class='text-center'>{$row3['nombres']} {$row3['apellidos']}</td>";
											}
											$query3 = mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$row['idProveedor']}'");
											while($row3 = mysqli_fetch_array($query3)){
												$nombreProveedor = $row3['nombre'];
												echo "<td class='text-center'>{$row3['nombre']}</td>";
											}
											echo "<td class='text-center'>{$row['idTransaccion']}</td>";
											$fechaTransaccion = explode(" ",$row['fechaTransaccion']);
											echo "<td class='text-center'>{$fechaTransaccion[0]}</td>";
											$query3 = mysqli_query($link,"SELECT * FROM Producto WHERE idProducto = '{$row2['idProducto']}'");
											while($row3 = mysqli_fetch_array($query3)){
												$nombreProducto = $row3['nombreCorto'];
												$query4 = mysqli_query($link,"SELECT * FROM Color WHERE idColor = '{$row3['idColor']}'");
												while($row4 = mysqli_fetch_array($query4)){
													$atributo = $row4['descripcion'];
												}
												echo "<td class='text-center'>{$row3['nombreCorto']}</td>";
											}
											echo "<td class='text-center'>{$row2['cantidad']}</td>";
											if($row2['valorUnitario'] == 0){
												echo "<td class='text-center'>0</td>";
												$valorTotal = $row2['cantidad'] * $row2['valorUnitario'];
											}else{
											    $valorUnitario = round($row2['valorUnitario'],2);
												echo "<td class='text-center'>S/. {$valorUnitario}</td>";
												$valorTotal = $row2['cantidad'] * $row2['valorUnitario'];
											}
											$valorTotal = round($valorTotal,2);
											echo "<td class='text-center'>S/. {$valorTotal}</td>";
											$totalCompras += $valorTotal;
											if(!isset($colaboradorasComprasValores[$aux2])){
												$colaboradorasComprasValores[$aux2] = 0;
											}
											$colaboradorasComprasValores[$aux2] += $valorTotal;
											echo "</tr>";
											$txt = $aux.",".$nombreColaborador.",".$nombreProveedor.",".$row['idTransaccion'].",".$fechaTransaccion[0].",".$nombreProducto.",".$row2['cantidad'].",".$row2['valorUnitario'].",".$valorTotal.PHP_EOL;
											fwrite($file, $txt);
										}
										$aux2++;
									}
								}
								fclose($file);
								foreach(array_unique($colaboradorasCompras) as $comprador){
									for($i=0;$i<count($colaboradorasCompras);$i++){
										if($comprador == $colaboradorasCompras[$i]){
											if(!isset($colaboradorasComprasTotales[$aux3])){
												$colaboradorasComprasTotales[$aux3] = 0;
											}
											if(isset($colaboradorasComprasValores[$i])){
												$colaboradorasComprasTotales[$aux3] += $colaboradorasComprasValores[$i];
											}
										}
									}
									$colaboradorasComprasActual[$aux3] = $comprador;
									$aux3++;
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

<div class="spacer30"></div>

<section class="container">
    <div class="row" id="opcionesMetodoPago">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-inverse card-info">
                    <div class="float-left">
                        <i class="fa fa-camera"></i>
                        Reporte de Ventas por Fechas - <?php echo $_POST['fechaInicioReporte'] . " - " . $_POST['fechaFinReporte'];
	                    $fileName = "files/".$_SESSION['user']."-reporteVentas.txt";?>
                    </div>
                    <div class="float-right">
                        <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Acciones
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="files/<?php echo $_SESSION['user']?>-reporteVentas.txt" download>Exportar Listado</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-block">
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-center" style="text-decoration: underline">Ventas de Mercadería</h6>
                            <div class="spacer10"></div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-center">Item</th>
                                    <th class="text-center">Colaboradora</th>
                                    <th class="text-center">Cliente</th>
                                    <th class="text-center">Transacción</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Producto</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-center">Valor Unitario (S/.)</th>
                                    <th class="text-center">Total (S/.)</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								$file = fopen($fileName,"w") or die("No se encontró el archivo!");
								$txt = "Ventas".PHP_EOL."Item,Colaboradora,Cliente,Transacción,Fecha,Producto,Cantidad,Valor Unitario,Total".PHP_EOL;
								fwrite($file, $txt);
								$valorCompras = 0;
								$dateInicio = explode("-", $_POST['fechaInicioReporte']);
								$dateFin = explode("-", $_POST['fechaFinReporte']);
								$fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
								$fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
								$aux = 0;
								$aux2 = 0;
								$aux3 = 0;
								$query = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTipoTransaccion IN (5)");
								while($row = mysqli_fetch_array($query)){
                                    $fechaTransac = explode(" ",$row['fechaTransaccion']);
                                    $fechaTransaccionCompleta = $fechaTransac[0];
									if($fechaTransaccionCompleta <= $fechaFin && $fechaTransaccionCompleta >= $fechaInicio){
										$colaboradorasVentas[$aux2] = $row['idColaborador'];
										$query2 = mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['idTransaccion']}'");
										while($row2 = mysqli_fetch_array($query2)){
											$aux++;
											echo "<tr>";
											echo "<td class='text-center'>$aux</td>";
											$query3 = mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$row['idColaborador']}'");
											while($row3 = mysqli_fetch_array($query3)){
												$nombreColaborador = $row3['nombres'];
												$nombreColaborador = $nombreColaborador." ".$row3['apellidos'];
												echo "<td class='text-center'>{$row3['nombres']} {$row3['apellidos']}</td>";
											}
											$query3 = mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$row['idProveedor']}'");
											while($row3 = mysqli_fetch_array($query3)){
												$nombreProveedor = $row3['nombre'];
												echo "<td class='text-center'>{$row3['nombre']}</td>";
											}
											echo "<td class='text-center'>{$row['idTransaccion']}</td>";
											$fechaTransaccion = explode(" ",$row['fechaTransaccion']);
											echo "<td class='text-center'>{$fechaTransaccion[0]}</td>";
											$query3 = mysqli_query($link,"SELECT * FROM Producto WHERE idProducto = '{$row2['idProducto']}'");
											while($row3 = mysqli_fetch_array($query3)){
												$nombreProducto = $row3['nombreCorto'];
												$query4 = mysqli_query($link,"SELECT * FROM Color WHERE idColor = '{$row3['idColor']}'");
												while($row4 = mysqli_fetch_array($query4)){
													$atributo = $row4['descripcion'];
												}
												echo "<td class='text-center'>{$row3['nombreCorto']}</td>";
											}
											echo "<td class='text-center'>{$row2['cantidad']}</td>";
											if($row2['valorUnitario'] == 0){
												echo "<td class='text-center'>0</td>";
												$valorTotal = $row2['cantidad'] * $row2['valorUnitario'];
											}else{
											    $valorUnitario = round($row2['valorUnitario'],2);
												echo "<td class='text-center'>S/. {$valorUnitario}</td>";
												$valorTotal = $row2['cantidad'] * $row2['valorUnitario'];
											}
											$valorTotal = round($valorTotal,2);
											echo "<td class='text-center'>S/. {$valorTotal}</td>";
											$totalVentas += $valorTotal;
											if(!isset($colaboradorasVentasValores[$aux2])){
												$colaboradorasVentasValores[$aux2] = 0;
											}
											$colaboradorasVentasValores[$aux2] += $valorTotal;
											echo "</tr>";
											$txt = $aux.",".$nombreColaborador.",".$nombreProveedor.",".$row['idTransaccion'].",".$fechaTransaccion[0].",".$nombreProducto.",".$row2['cantidad'].",".$row2['valorUnitario'].",".$valorTotal.PHP_EOL;
											fwrite($file, $txt);
										}
										$aux2++;
									}
								}
								fclose($file);
								foreach(array_unique($colaboradorasVentas) as $comprador){
									for($i=0;$i<count($colaboradorasVentas);$i++){
										if($comprador == $colaboradorasVentas[$i]){
											if(!isset($colaboradorasVentasTotales[$aux3])){
												$colaboradorasVentasTotales[$aux3] = 0;
											}
											if(isset($colaboradorasVentasValores[$i])){
												$colaboradorasVentasTotales[$aux3] += $colaboradorasVentasValores[$i];
											}
										}
									}
									$colaboradorasVentasActual[$aux3] = $comprador;
									$aux3++;
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

<div class="spacer30"></div>

<section class="container">
    <div class="row" id="opcionesMetodoPago">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-inverse card-info">
                    <div class="float-left">
                        <i class="fa fa-camera"></i>
                        Reporte de Préstamos por Fechas - <?php echo $_POST['fechaInicioReporte'] . " - " . $_POST['fechaFinReporte'];
	                    $fileName = "files/".$_SESSION['user']."-reportePrestamos.txt";?>
                    </div>
                    <div class="float-right">
                        <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Acciones
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="files/<?php echo $_SESSION['user']?>-reportePrestamos.txt" download>Exportar Listado</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-block">
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-center" style="text-decoration: underline">Préstamos de Mercadería</h6>
                            <div class="spacer10"></div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-center">Item</th>
                                    <th class="text-center">Colaboradora</th>
                                    <th class="text-center">Cliente</th>
                                    <th class="text-center">Transacción</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Producto</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-center">Valor Unitario (S/.)</th>
                                    <th class="text-center">Total (S/.)</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								$file = fopen($fileName,"w") or die("No se encontró el archivo!");
								$txt = "Préstamos".PHP_EOL."Item,Colaboradora,Cliente,Transacción,Fecha,Producto,Cantidad,Valor Unitario,Total".PHP_EOL;
								fwrite($file, $txt);
								$valorCompras = 0;
								$dateInicio = explode("-", $_POST['fechaInicioReporte']);
								$dateFin = explode("-", $_POST['fechaFinReporte']);
								$fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
								$fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
								$aux = 0;
								$aux2 = 0;
								$aux3 = 0;
								$query = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTipoTransaccion IN (6)");
								while($row = mysqli_fetch_array($query)){
                                    $fechaTransac = explode(" ",$row['fechaTransaccion']);
                                    $fechaTransaccionCompleta = $fechaTransac[0];
									if($fechaTransaccionCompleta <= $fechaFin && $fechaTransaccionCompleta >= $fechaInicio){
										$colaboradorasPrestamos[$aux2] = $row['idColaborador'];
										$query2 = mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['idTransaccion']}'");
										while($row2 = mysqli_fetch_array($query2)){
											$aux++;
											echo "<tr>";
											echo "<td class='text-center'>$aux</td>";
											$query3 = mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$row['idColaborador']}'");
											while($row3 = mysqli_fetch_array($query3)){
												$nombreColaborador = $row3['nombres'];
												$nombreColaborador = $nombreColaborador." ".$row3['apellidos'];
												echo "<td class='text-center'>{$row3['nombres']} {$row3['apellidos']}</td>";
											}
											$query3 = mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$row['idProveedor']}'");
											while($row3 = mysqli_fetch_array($query3)){
												$nombreProveedor = $row3['nombre'];
												echo "<td class='text-center'>{$row3['nombre']}</td>";
											}
											echo "<td class='text-center'>{$row['idTransaccion']}</td>";
											$fechaTransaccion = explode(" ",$row['fechaTransaccion']);
											echo "<td class='text-center'>{$fechaTransaccion[0]}</td>";
											$query3 = mysqli_query($link,"SELECT * FROM Producto WHERE idProducto = '{$row2['idProducto']}'");
											while($row3 = mysqli_fetch_array($query3)){
												$nombreProducto = $row3['nombreCorto'];
												$query4 = mysqli_query($link,"SELECT * FROM Color WHERE idColor = '{$row3['idColor']}'");
												while($row4 = mysqli_fetch_array($query4)){
													$atributo = $row4['descripcion'];
												}
												echo "<td class='text-center'>{$row3['nombreCorto']}</td>";
											}
											echo "<td class='text-center'>{$row2['cantidad']}</td>";
											if($row2['valorUnitario'] == 0){
												echo "<td class='text-center'>0</td>";
												$valorTotal = $row2['cantidad'] * $row2['valorUnitario'];
											}else{
											    $valorUnitario = round($row2['valorUnitario'],2);
												echo "<td class='text-center'>S/. {$valorUnitario}</td>";
												$valorTotal = $row2['cantidad'] * $row2['valorUnitario'];
											}
											$valorTotal = round($valorTotal,2);
											echo "<td class='text-center'>S/. {$valorTotal}</td>";
											$totalPrestamos += $valorTotal;
											if(!isset($colaboradorasPrestamosValores[$aux2])){
												$colaboradorasPrestamosValores[$aux2] = 0;
											}
											$colaboradorasPrestamosValores[$aux2] += $valorTotal;
											echo "</tr>";
											$txt = $aux.",".$nombreColaborador.",".$nombreProveedor.",".$row['idTransaccion'].",".$fechaTransaccion[0].",".$nombreProducto.",".$row2['cantidad'].",".$row2['valorUnitario'].",".$valorTotal.PHP_EOL;
											fwrite($file, $txt);
										}
										$aux2++;
									}
								}
								fclose($file);
								foreach(array_unique($colaboradorasPrestamos) as $comprador){
									for($i=0;$i<count($colaboradorasPrestamos);$i++){
										if($comprador == $colaboradorasPrestamos[$i]){
											if(!isset($colaboradorasPrestamosTotales[$aux3])){
												$colaboradorasPrestamosTotales[$aux3] = 0;
											}
											if(isset($colaboradorasPrestamosValores[$i])){
												$colaboradorasPrestamosTotales[$aux3] += $colaboradorasPrestamosValores[$i];
											}
										}
									}
									$colaboradorasPrestamosActual[$aux3] = $comprador;
									$aux3++;
								}

								$totalCompras = round($totalCompras,2);
								$totalVentas = round($totalVentas,2);
								$totalPrestamos = round($totalPrestamos,2);
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

<div class="spacer30"></div>

<section class="container">
    <div class="row" id="opcionesMetodoPago">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-inverse card-info">
                    <div class="float-left">
                        <i class="fa fa-camera"></i>
                        Reporte Resumen - <?php echo $_POST['fechaInicioReporte'] . " - " . $_POST['fechaFinReporte'];
	                    $fileName = "files/".$_SESSION['user']."-reporteResumenCVP.txt";?>?>
                    </div>
                    <div class="float-right">
                        <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Acciones
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="files/<?php echo $_SESSION['user']?>-reporteResumenCVP.txt" download>Exportar Listado</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-block">
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-center" style="text-decoration: underline">Resumen</h6>
                            <div class="spacer10"></div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-center">Total Compras (S/.)</th>
                                    <th class="text-center">Total Ventas (S/.)</th>
                                    <th class="text-center">Total Préstamos (S/.)</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								echo "<tr>";
								echo "<td class='text-center'>S/. {$totalCompras}</td>";
								echo "<td class='text-center'>S/. {$totalVentas}</td>";
								echo "<td class='text-center'>S/. {$totalPrestamos}</td>";
								echo "</tr>";
								$file = fopen($fileName,"w") or die("No se encontró el archivo!");
								$txt = 'Resumen'.PHP_EOL;
								fwrite($file, $txt);
								$txt = PHP_EOL."Total Compras,".$totalCompras.PHP_EOL;
								fwrite($file, $txt);
								$txt = "Total Ventas,".$totalVentas.PHP_EOL;
								fwrite($file, $txt);
								$txt = "Total Préstamos,".$totalPrestamos.PHP_EOL;
								fwrite($file, $txt);
								?>
                                </tbody>
                            </table>
                            <hr>
                            <h6 class="text-center" style="text-decoration: underline">Resumen por Colaboradora</h6>
                            <div class="spacer10"></div>
                            <table class="table">
                                <tbody>
                                <tr class="bg-faded">
                                    <th colspan="3" class="text-center">Compras</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Nombre</th>
                                    <th class="text-center">Código</th>
                                    <th class="text-center">Monto</th>
                                </tr>
								<?php
								$txt = PHP_EOL.PHP_EOL.'Compras'.PHP_EOL;
								fwrite($file, $txt);
								$aux = 0;
								foreach($colaboradorasComprasActual as $comprador){
									echo "<tr>";
									$query = mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$comprador}'");
									while($row = mysqli_fetch_array($query)){
										$nombreColaborador = $row['nombres'];
										$nombreColaborador = $nombreColaborador." ".$row['apellidos'];
										echo"<td class='text-center'>{$row['nombres']} {$row['apellidos']}</td>";
									}
									echo"<td class='text-center'>{$comprador}</td>";
									echo"<td class='text-center'>S/. {$colaboradorasComprasTotales[$aux]}</td>";
									echo "</tr>";
									$txt = PHP_EOL."Nombre,Código,Monto".PHP_EOL;
									fwrite($file, $txt);
									$txt = $nombreColaborador.",".$comprador.",".$colaboradorasComprasTotales[$aux].PHP_EOL;
									fwrite($file, $txt);
									$aux++;
								}
								?>
                                </tbody>
                            </table>
                            <div class="spacer30"></div>
                            <table class="table">
                                <tbody>
                                <tr class="bg-faded">
                                    <th colspan="3" class="text-center">Ventas</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Nombre</th>
                                    <th class="text-center">Código</th>
                                    <th class="text-center">Monto</th>
                                </tr>
								<?php
								$txt = PHP_EOL.PHP_EOL.'Ventas'.PHP_EOL;
								fwrite($file, $txt);
								$aux = 0;
								foreach($colaboradorasVentasActual as $comprador){
									echo "<tr>";
									$query = mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$comprador}'");
									while($row = mysqli_fetch_array($query)){
										$nombreColaborador = $row['nombres'];
										$nombreColaborador = $nombreColaborador." ".$row['apellidos'];
										echo"<td class='text-center'>{$row['nombres']} {$row['apellidos']}</td>";
									}
									echo"<td class='text-center'>{$comprador}</td>";
									echo"<td class='text-center'>S/. {$colaboradorasVentasTotales[$aux]}</td>";
									echo "</tr>";
									$txt = PHP_EOL."Nombre,Código,Monto".PHP_EOL;
									fwrite($file, $txt);
									$txt = $nombreColaborador.",".$comprador.",".$colaboradorasVentasTotales[$aux].PHP_EOL;
									fwrite($file, $txt);
									$aux++;
								}
								?>
                                </tbody>
                            </table>
                            <div class="spacer30"></div>
                            <table class="table">
                                <tbody>
                                <tr class="bg-faded">
                                    <th colspan="3" class="text-center">Prestamos</th>
                                </tr>
                                <tr>
                                    <th class="text-center" width="33%">Nombre</th>
                                    <th class="text-center" width="33%">Código</th>
                                    <th class="text-center" width="33%">Monto</th>
                                </tr>
								<?php
								$txt = PHP_EOL.PHP_EOL.'Préstamos'.PHP_EOL;
								fwrite($file, $txt);
								$aux = 0;
								foreach($colaboradorasPrestamosActual as $comprador){
									echo "<tr>";
									$query = mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$comprador}'");
									while($row = mysqli_fetch_array($query)){
										$nombreColaborador = $row['nombres'];
										$nombreColaborador = $nombreColaborador." ".$row['apellidos'];
										echo"<td class='text-center'>{$row['nombres']} {$row['apellidos']}</td>";
									}
									echo"<td class='text-center'>{$comprador}</td>";
									echo"<td class='text-center'>S/. {$colaboradorasPrestamosTotales[$aux]}</td>";
									echo "</tr>";
									$txt = PHP_EOL."Nombre,Código,Monto".PHP_EOL;
									fwrite($file, $txt);
									$txt = $nombreColaborador.",".$comprador.",".$colaboradorasPrestamosTotales[$aux].PHP_EOL;
									fwrite($file, $txt);
									$aux++;
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
