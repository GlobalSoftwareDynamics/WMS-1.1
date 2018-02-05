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
$nombreColaborador = null;
$query = mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$_POST['idColaboradora']}'");
while($row = mysqli_fetch_array($query)){
	$nombreColaborador = $row['nombres']." ".$row['apellidos'];
}

?>
<div class="spacer30"></div>

<section class="container">
	<div class="row" id="opcionesMetodoPago">
		<div class="col-12">
			<div class="card">
				<div class="card-header card-inverse card-info">
					<div class="float-left">
						<i class="fa fa-camera"></i>
						<?php echo $nombreColaborador." - ";?>Reporte de Compras por Fechas - <?php echo $_POST['fechaInicioReporte'] . " - " . $_POST['fechaFinReporte'];
						$fileName = "files/".$_SESSION['user']."-reporteColaboradoraCompras.txt";?>
					</div>
					<div class="float-right">
						<div class="dropdown">
							<button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Acciones
							</button>
							<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
								<a class="dropdown-item" href="<?php echo $fileName;?>" download>Exportar Listado</a>
                                <form method="post" action="reporteColaboradoraPDF.php">
                                    <input type="hidden" name="fechaInicioReporte" value="<?php echo $_POST['fechaInicioReporte'];?>">
                                    <input type="hidden" name="fechaFinReporte" value="<?php echo $_POST['fechaFinReporte'];?>">
                                    <input type="hidden" name="idColaboradora" value="<?php echo $_POST['idColaboradora'];?>">
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
									<th class="text-center">Item</th>
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
								fwrite($file, pack("CCC",0xef,0xbb,0xbf));
								$txt = "Compras".PHP_EOL."Item,Transacción,Fecha,Producto,Cantidad,Valor Unitario,Total".PHP_EOL;
								fwrite($file, $txt);
								$valorCompras = 0;
								$dateInicio = explode("-", $_POST['fechaInicioReporte']);
								$dateFin = explode("-", $_POST['fechaFinReporte']);
								$fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
								$fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
								$aux = 0;
								$aux2 = 0;
								$query = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTipoTransaccion IN (1) AND idColaborador = '{$_POST['idColaboradora']}' ORDER BY fechaTransaccion DESC");
								while($row = mysqli_fetch_array($query)){
                                    $fechaTransac = explode("|",$row['fechaTransaccion']);
                                    $fechaTransaccionCompleta = $fechaTransac[0];
									if($fechaTransaccionCompleta <= $fechaFin && $fechaTransaccionCompleta >= $fechaInicio){
										$colaboradorasCompras[$aux2] = $row['idColaborador'];
										$query2 = mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['idTransaccion']}'");
										while($row2 = mysqli_fetch_array($query2)){
											$aux++;
											echo "<tr>";
											echo "<td class='text-center'>$aux</td>";
											echo "<td class='text-center'>{$row['idTransaccion']}</td>";
											$fechaTransaccion = explode("|",$row['fechaTransaccion']);
											echo "<td class='text-center'>{$fechaTransaccion[0]}</td>";
											$query3 = mysqli_query($link,"SELECT * FROM Producto WHERE idProducto = '{$row2['idProducto']}'");
											while($row3 = mysqli_fetch_array($query3)){
												$nombreProducto = $row3['nombreCorto'];
												$query4 = mysqli_query($link,"SELECT * FROM Color WHERE idColor = '{$row3['idColor']}'");
												while($row4 = mysqli_fetch_array($query4)){
													$atributo = $row4['descripcion'];
												}
												echo "<td class='text-center'>{$row3['nombreCorto']} {$atributo}</td>";
											}
											echo "<td class='text-center'>{$row2['cantidad']}</td>";
											if($row2['valorUnitario'] == 0){
												echo "<td class='text-center'>0</td>";
												$valorTotal = $row2['cantidad'] * $row2['valorUnitario'];
											}else{
											    $valorUnitario = round($row2['valorUnitario'],2);
												echo "<td class='text-center'>{$valorUnitario}</td>";
												$valorTotal = $row2['cantidad'] * $row2['valorUnitario'];
											}
											$valorTotal = round($valorTotal,2);
											echo "<td class='text-center'>{$valorTotal}</td>";
											$totalCompras += $valorTotal;
											if(!isset($colaboradorasComprasValores[$aux2])){
												$colaboradorasComprasValores[$aux2] = 0;
											}
											$colaboradorasComprasValores[$aux2] += $valorTotal;
											echo "</tr>";
											$txt = $aux.",".$row['idTransaccion'].",".$fechaTransaccion[0].",".$nombreProducto." ".$atributo.",".$row2['cantidad'].",".$row2['valorUnitario'].",".$valorTotal.PHP_EOL;
											fwrite($file, $txt);
										}
										$aux2++;
									}
								}
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

<div class="spacer30"></div>

<section class="container">
	<div class="row" id="opcionesMetodoPago">
		<div class="col-12">
			<div class="card">
				<div class="card-header card-inverse card-info">
					<div class="float-left">
						<i class="fa fa-camera"></i>
						<?php echo $nombreColaborador." - ";?>Reporte de Ventas por Fechas - <?php echo $_POST['fechaInicioReporte'] . " - " . $_POST['fechaFinReporte'];
						$fileName = "files/".$_SESSION['user']."-reporteColaboradoraVentas.txt";?>
					</div>
                    <div class="float-right">
                        <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Acciones
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="<?php echo $fileName;?>" download>Exportar Listado</a>
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
								$txt = "Ventas".PHP_EOL."Item,Transacción,Fecha,Producto,Cantidad,Valor Unitario,Total".PHP_EOL;
								fwrite($file, $txt);
								$valorCompras = 0;
								$dateInicio = explode("-", $_POST['fechaInicioReporte']);
								$dateFin = explode("-", $_POST['fechaFinReporte']);
								$fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
								$fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
								$aux = 0;
								$aux2 = 0;
								$aux3 = 0;
								$query = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTipoTransaccion IN (5) AND idColaborador = '{$_POST['idColaboradora']}' ORDER BY fechaTransaccion DESC");
								while($row = mysqli_fetch_array($query)){
                                    $fechaTransac = explode("|",$row['fechaTransaccion']);
                                    $fechaTransaccionCompleta = $fechaTransac[0];
									if($fechaTransaccionCompleta <= $fechaFin && $fechaTransaccionCompleta >= $fechaInicio){
										$colaboradorasVentas[$aux2] = $row['idColaborador'];
										$query2 = mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['idTransaccion']}'");
										while($row2 = mysqli_fetch_array($query2)){
											$aux++;
											echo "<tr>";
											echo "<td class='text-center'>$aux</td>";
											echo "<td class='text-center'>{$row['idTransaccion']}</td>";
											$fechaTransaccion = explode("|",$row['fechaTransaccion']);
											echo "<td class='text-center'>{$fechaTransaccion[0]}</td>";
											$query3 = mysqli_query($link,"SELECT * FROM Producto WHERE idProducto = '{$row2['idProducto']}'");
											while($row3 = mysqli_fetch_array($query3)){
												$nombreProducto = $row3['nombreCorto'];
												$query4 = mysqli_query($link,"SELECT * FROM Color WHERE idColor = '{$row3['idColor']}'");
												while($row4 = mysqli_fetch_array($query4)){
													$atributo = $row4['descripcion'];
												}
												echo "<td class='text-center'>{$row3['nombreCorto']} {$atributo}</td>";
											}
											echo "<td class='text-center'>{$row2['cantidad']}</td>";
											if($row2['valorUnitario'] == 0){
												echo "<td class='text-center'>0</td>";
												$valorTotal = $row2['cantidad'] * $row2['valorUnitario'];
											}else{
											    $valorUnitario = round($row2['valorUnitario'],2);
												echo "<td class='text-center'>{$valorUnitario}</td>";
												$valorTotal = $row2['cantidad'] * $row2['valorUnitario'];
											}
											$valorTotal = round($valorTotal,2);
											echo "<td class='text-center'>{$valorTotal}</td>";
											$totalVentas += $valorTotal;
											if(!isset($colaboradorasVentasValores[$aux2])){
												$colaboradorasVentasValores[$aux2] = 0;
											}
											$colaboradorasVentasValores[$aux2] += $valorTotal;
											echo "</tr>";
											$txt = $aux.",".$row['idTransaccion'].",".$fechaTransaccion[0].",".$nombreProducto." ".$atributo.",".$row2['cantidad'].",".$row2['valorUnitario'].",".$valorTotal.PHP_EOL;
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
						<?php echo $nombreColaborador." - ";?>Reporte de Préstamos por Fechas - <?php echo $_POST['fechaInicioReporte'] . " - " . $_POST['fechaFinReporte'];
						$fileName = "files/".$_SESSION['user']."-reporteColaboradoraPrestamos.txt";?>
					</div>
                    <div class="float-right">
                        <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Acciones
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="<?php echo $fileName;?>" download>Exportar Listado</a>
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
								$txt = "Préstamos".PHP_EOL."Item,Transacción,Fecha,Producto,Cantidad,Valor Unitario,Total".PHP_EOL;
								fwrite($file, $txt);
								$valorCompras = 0;
								$dateInicio = explode("-", $_POST['fechaInicioReporte']);
								$dateFin = explode("-", $_POST['fechaFinReporte']);
								$fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
								$fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
								$aux = 0;
								$aux2 = 0;
								$aux3 = 0;
								$query = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTipoTransaccion IN (6) AND idColaborador = '{$_POST['idColaboradora']}' ORDER BY fechaTransaccion DESC");
								while($row = mysqli_fetch_array($query)){
                                    $fechaTransac = explode("|",$row['fechaTransaccion']);
                                    $fechaTransaccionCompleta = $fechaTransac[0];
									if($fechaTransaccionCompleta <= $fechaFin && $fechaTransaccionCompleta >= $fechaInicio){
										$colaboradorasPrestamos[$aux2] = $row['idColaborador'];
										$query2 = mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['idTransaccion']}'");
										while($row2 = mysqli_fetch_array($query2)){
											$aux++;
											echo "<tr>";
											echo "<td class='text-center'>$aux</td>";
											echo "<td class='text-center'>{$row['idTransaccion']}</td>";
											$fechaTransaccion = explode("|",$row['fechaTransaccion']);
											echo "<td class='text-center'>{$fechaTransaccion[0]}</td>";
											$query3 = mysqli_query($link,"SELECT * FROM Producto WHERE idProducto = '{$row2['idProducto']}'");
											while($row3 = mysqli_fetch_array($query3)){
												$nombreProducto = $row3['nombreCorto'];
												$query4 = mysqli_query($link,"SELECT * FROM Color WHERE idColor = '{$row3['idColor']}'");
												while($row4 = mysqli_fetch_array($query4)){
													$atributo = $row4['descripcion'];
												}
												echo "<td class='text-center'>{$row3['nombreCorto']} {$atributo}</td>";
											}
											echo "<td class='text-center'>{$row2['cantidad']}</td>";
											if($row2['valorUnitario'] == 0){
												echo "<td class='text-center'>0</td>";
												$valorTotal = $row2['cantidad'] * $row2['valorUnitario'];
											}else{
											    $valorUnitario = round($row2['valorUnitario'],2);
												echo "<td class='text-center'>{$valorUnitario}</td>";
												$valorTotal = $row2['cantidad'] * $row2['valorUnitario'];
											}
											$valorTotal = round($valorTotal,2);
											echo "<td class='text-center'>{$valorTotal}</td>";
											$totalPrestamos += $valorTotal;
											if(!isset($colaboradorasPrestamosValores[$aux2])){
												$colaboradorasPrestamosValores[$aux2] = 0;
											}
											$colaboradorasPrestamosValores[$aux2] += $valorTotal;
											echo "</tr>";
											$txt = $aux.",".$row['idTransaccion'].",".$fechaTransaccion[0].",".$nombreProducto." ".$atributo.",".$row2['cantidad'].",".$row2['valorUnitario'].",".$valorTotal.PHP_EOL;
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
						<?php echo $nombreColaborador." - ";?>Reporte Resumen - <?php echo $_POST['fechaInicioReporte'] . " - " . $_POST['fechaFinReporte'];
						$fileName = "files/".$_SESSION['user']."-reporteColaboradoraResumen.txt";?>
					</div>
                    <div class="float-right">
                        <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Acciones
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="<?php echo $fileName;?>" download>Exportar Listado</a>
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
								echo "<td class='text-center'>{$totalCompras}</td>";
								echo "<td class='text-center'>{$totalVentas}</td>";
								echo "<td class='text-center'>{$totalPrestamos}</td>";
								echo "</tr>";

								$file = fopen($fileName,"w") or die("No se encontró el archivo!");
								$txt = 'Resumen'.PHP_EOL;
								fwrite($file, $txt);
								$txt = "Total Compras,".$totalCompras.PHP_EOL;
								fwrite($file, $txt);
								$txt = "Total Ventas,".$totalVentas.PHP_EOL;
								fwrite($file, $txt);
								$txt = "Total Préstamos,".$totalPrestamos.PHP_EOL;
								fwrite($file, $txt);
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
