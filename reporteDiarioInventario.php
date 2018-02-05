
<div class="spacer30"></div>

<section class="container">
	<div class="row" id="opcionesMetodoPago">
		<div class="col-12">
			<div class="card">
				<div class="card-header card-inverse card-info">
					<div class="float-left">
						<i class="fa fa-camera"></i>
						Reporte de Inventario Diario - <?php echo $_POST['fechaReporte']?>
					</div>
                    <div class="float-right">
                        <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Acciones
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="files/reporteInventarioDiario.txt" download>Exportar Listado</a>
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
									<th class="text-center">Transacción</th>
									<th class="text-center">Referencia</th>
									<th class="text-center">Producto</th>
									<th class="text-center">Almacén</th>
									<th class="text-center">Ubicación</th>
									<th class="text-center">Cantidad</th>
									<th class="text-center">Valor Unitario (S/.)</th>
									<th class="text-center">Total (S/.)</th>
								</tr>
								</thead>
								<tbody>
								<?php
								$file = fopen("files/reporteInventarioDiario.txt","w") or die("No se encontró el archivo!");
								fwrite($file, pack("CCC",0xef,0xbb,0xbf));
								$txt = "Ingresos".PHP_EOL."Item,Transacción,Referencia,Producto,Almacén,Ubicación,Cantidad,Valor Unitario,Total".PHP_EOL;
								fwrite($file, $txt);
								$date = explode("-",$_POST['fechaReporte']);
								$aux = 0;
								$transaccionReferencia = "-";
								$query = mysqli_query($link,"SELECT * FROM Transaccion WHERE fechaTransaccion LIKE '{$date[2]}/{$date[1]}/{$date[0]}%' AND idTipoTransaccion IN (2,4)");
								while($row = mysqli_fetch_array($query)){
									$valorReferencia = 0;
									$query2 = mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['idTransaccion']}'");
									while($row2 = mysqli_fetch_array($query2)){
										if(substr($row['idTransaccion'],0,2) == 'OR'){
											$transaccionReferencia = $row['referenciaTransaccion'];
											$query3 = mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['referenciaTransaccion']}' AND idProducto = '{$row2['idProducto']}'");
											while($row3 = mysqli_fetch_array($query3)){
												$valorReferencia = $row3['valorUnitario'];
											}
										}
										$aux++;
										echo "<tr>";
										echo "<td class='text-center'>$aux</td>";
										echo "<td class='text-center'>{$row['idTransaccion']}</td>";
										echo "<td class='text-center'>{$transaccionReferencia}</td>";
										$query3 = mysqli_query($link,"SELECT * FROM Producto WHERE idProducto = '{$row2['idProducto']}'");
										while($row3 = mysqli_fetch_array($query3)){
											$nombreProducto = $row3['nombreCorto'];
											$query4 = mysqli_query($link,"SELECT * FROM Color WHERE idColor = '{$row3['idColor']}'");
											while($row4 = mysqli_fetch_array($query4)){
												$atributo = $row4['descripcion'];
											}
											echo "<td class='text-center'>{$row3['nombreCorto']} {$atributo}</td>";
										}
										$query3 = mysqli_query($link, "SELECT * FROM Ubicacion WHERE idUbicacion = '{$row2['idUbicacionFinal']}'");
										while($row3 = mysqli_fetch_array($query3)){
											$query4 = mysqli_query($link, "SELECT * FROM Almacen WHERE idAlmacen = '{$row3['idAlmacen']}'");
											while($row4 = mysqli_fetch_array($query4)){
											    $nombreAlmacen = $row4['descripcion'];
												echo "<td class='text-center'>{$row4['descripcion']}</td>";
											}
										}
										echo "<td class='text-center'>{$row2['idUbicacionFinal']}</td>";
										echo "<td class='text-center'>{$row2['cantidad']}</td>";
										if($valorReferencia != 0){
											echo "<td class='text-center'>{$valorReferencia}</td>";
											$valorTotal = $row2['cantidad'] * $valorReferencia;
											$valorUnitPrint = $valorReferencia;
										}else{
											if($row2['valorUnitario'] == 0){
												echo "<td class='text-center'>0</td>";
												$valorTotal = $row2['cantidad'] * $row2['valorUnitario'];
												$valorUnitPrint = 0;
											}else{
												echo "<td class='text-center'>{$row2['valorUnitario']}</td>";
												$valorTotal = $row2['cantidad'] * $row2['valorUnitario'];
												$valorUnitPrint = $row2['valorUnitario'];
											}
										}
										echo "<td class='text-center'>{$valorTotal}</td>";
										echo "</tr>";
										$txt = $aux.",".$row['idTransaccion'].",".$transaccionReferencia.",".$nombreProducto." ".$atributo.",".$nombreAlmacen.",".$row2['idUbicacionFinal'].",".$row2['cantidad'].",".$valorUnitPrint.",".$valorTotal.PHP_EOL;
										fwrite($file, $txt);
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
							<h6 class="text-center" style="text-decoration: underline">Salidas de Mercadería</h6>
							<div class="spacer10"></div>
							<table class="table">
								<thead>
								<tr>
									<th class="text-center">Item</th>
									<th class="text-center">Transacción</th>
									<th class="text-center">Producto</th>
									<th class="text-center">Cantidad</th>
									<th class="text-center">Valor Unitario (S/.)</th>
									<th class="text-center">Total (S/.)</th>
								</tr>
								</thead>
								<tbody>
								<?php
								$txt = PHP_EOL.PHP_EOL."Salidas".PHP_EOL."Item,Transacción,Producto,Cantidad,Valor Unitario,Total".PHP_EOL;
								fwrite($file, $txt);
								$date = explode("-",$_POST['fechaReporte']);
								$aux = 0;
								$query = mysqli_query($link,"SELECT * FROM Transaccion WHERE fechaTransaccion LIKE '{$date[2]}/{$date[1]}/{$date[0]}%' AND idTipoTransaccion IN (5,6)");
								while($row = mysqli_fetch_array($query)){
									$valorReferencia = 0;
									$query2 = mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['idTransaccion']}'");
									while($row2 = mysqli_fetch_array($query2)){
										if(substr($row['idTransaccion'],0,2) == 'OR'){
											$transaccionReferencia = $row['referenciaTransaccion'];
											$query3 = mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['referenciaTransaccion']}' AND idProducto = '{$row2['idProducto']}'");
											while($row3 = mysqli_fetch_array($query3)){
												$valorReferencia = $row3['valorUnitario'];
											}
										}
										$aux++;
										echo "<tr>";
										echo "<td class='text-center'>$aux</td>";
										echo "<td class='text-center'>{$row['idTransaccion']}</td>";
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
										if($valorReferencia != 0){
											echo "<td class='text-center'>{$valorReferencia}</td>";
											$valorTotal = $row2['cantidad'] * $valorReferencia;
											$valorUnitPrint = $valorReferencia;
										}else{
											if($row2['valorUnitario'] == 0){
												echo "<td class='text-center'>0</td>";
												$valorTotal = $row2['cantidad'] * $row2['valorUnitario'];
												$valorUnitPrint = 0;
											}else{
												echo "<td class='text-center'>{$row2['valorUnitario']}</td>";
												$valorTotal = $row2['cantidad'] * $row2['valorUnitario'];
												$valorUnitPrint = $row2['valorUnitario'];
											}
										}
										echo "<td class='text-center'>{$valorTotal}</td>";
										echo "</tr>";
										$txt = $aux.",".$row['idTransaccion'].",".$nombreProducto." ".$atributo.",".$row2['cantidad'].",".$valorUnitPrint.",".$valorTotal.PHP_EOL;
										fwrite($file, $txt);
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
							<h6 class="text-center" style="text-decoration: underline">Transferencias de Mercadería</h6>
							<div class="spacer10"></div>
							<table class="table">
								<thead>
								<tr>
									<th class="text-center">Item</th>
									<th class="text-center">Transacción</th>
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
								$date = explode("-",$_POST['fechaReporte']);
								$aux = 0;
								$txt = PHP_EOL.PHP_EOL."Transferencias".PHP_EOL."Item,Transacción,Producto,Almacén de Salida,Ubicación de Salida,Almacén de Ingreso,Ubicación de Ingreso,Cantidad".PHP_EOL;
								fwrite($file, $txt);
								$query = mysqli_query($link,"SELECT * FROM Transaccion WHERE fechaTransaccion LIKE '{$date[2]}/{$date[1]}/{$date[0]}%' AND idTipoTransaccion IN (7)");
								while($row = mysqli_fetch_array($query)){
									$valorReferencia = 0;
									$query2 = mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['idTransaccion']}'");
									while($row2 = mysqli_fetch_array($query2)){
										if(substr($row['idTransaccion'],0,2) == 'OR'){
											$transaccionReferencia = $row['referenciaTransaccion'];
											$query3 = mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['referenciaTransaccion']}' AND idProducto = '{$row2['idProducto']}'");
											while($row3 = mysqli_fetch_array($query3)){
												$valorReferencia = $row3['valorUnitario'];
											}
										}
										$aux++;
										echo "<tr>";
										echo "<td class='text-center'>$aux</td>";
										echo "<td class='text-center'>{$row['idTransaccion']}</td>";
										$query3 = mysqli_query($link,"SELECT * FROM Producto WHERE idProducto = '{$row2['idProducto']}'");
										while($row3 = mysqli_fetch_array($query3)){
											$nombreProducto = $row3['nombreCorto'];
											$query4 = mysqli_query($link,"SELECT * FROM Color WHERE idColor = '{$row3['idColor']}'");
											while($row4 = mysqli_fetch_array($query4)){
												$atributo = $row4['descripcion'];
											}
											echo "<td class='text-center'>{$row3['nombreCorto']} {$atributo}</td>";
										}
										$query3 = mysqli_query($link, "SELECT * FROM Ubicacion WHERE idUbicacion = '{$row2['idUbicacionInicial']}'");
										while($row3 = mysqli_fetch_array($query3)){
											$query4 = mysqli_query($link, "SELECT * FROM Almacen WHERE idAlmacen = '{$row3['idAlmacen']}'");
											while($row4 = mysqli_fetch_array($query4)){
											    $nombreAlmacen = $row4['descripcion'];
												echo "<td class='text-center'>{$row4['descripcion']}</td>";
											}
										}
										echo "<td class='text-center'>{$row2['idUbicacionInicial']}</td>";
										$query3 = mysqli_query($link, "SELECT * FROM Ubicacion WHERE idUbicacion = '{$row2['idUbicacionFinal']}'");
										while($row3 = mysqli_fetch_array($query3)){
											$query4 = mysqli_query($link, "SELECT * FROM Almacen WHERE idAlmacen = '{$row3['idAlmacen']}'");
											while($row4 = mysqli_fetch_array($query4)){
											    $nombreAlmacenFinal = $row4['descripcion'];
												echo "<td class='text-center'>{$row4['descripcion']}</td>";
											}
										}
										echo "<td class='text-center'>{$row2['idUbicacionFinal']}</td>";
										echo "<td class='text-center'>{$row2['cantidad']}</td>";
										echo "</tr>";
										$txt = $aux.",".$row['idTransaccion'].",".$nombreProducto." ".$atributo.",".$nombreAlmacen.",".$row2['idUbicacionInicial'].",".$nombreAlmacenFinal.",".$row2['idUbicacionFinal'].",".$row2['cantidad'].PHP_EOL;
										fwrite($file, $txt);
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
							<h6 class="text-center" style="text-decoration: underline">Correcciones de Inventario</h6>
							<div class="spacer10"></div>
							<table class="table">
								<thead>
								<tr>
									<th class="text-center">Item</th>
									<th class="text-center">Transacción</th>
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
								$txt = PHP_EOL.PHP_EOL."Correcciones".PHP_EOL."Item,Transacción,Producto,Almacén,Ubicación,Cantidad Inicial,Cantidad Final,Motivo".PHP_EOL;
								fwrite($file, $txt);
								$date = explode("-",$_POST['fechaReporte']);
								$aux = 0;
								$query = mysqli_query($link,"SELECT * FROM Transaccion WHERE fechaTransaccion LIKE '{$date[2]}/{$date[1]}/{$date[0]}%' AND idTipoTransaccion IN (8)");
								while($row = mysqli_fetch_array($query)){
									$valorReferencia = 0;
									$query2 = mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['idTransaccion']}'");
									while($row2 = mysqli_fetch_array($query2)){
										$aux++;
										echo "<tr>";
										echo "<td class='text-center'>$aux</td>";
										echo "<td class='text-center'>{$row['idTransaccion']}</td>";
										$query3 = mysqli_query($link,"SELECT * FROM Producto WHERE idProducto = '{$row2['idProducto']}'");
										while($row3 = mysqli_fetch_array($query3)){
										    $nombreProducto = $row3['nombreCorto'];
											$query4 = mysqli_query($link,"SELECT * FROM Color WHERE idColor = '{$row3['idColor']}'");
											while($row4 = mysqli_fetch_array($query4)){
												$atributo = $row4['descripcion'];
											}
											echo "<td class='text-center'>{$row3['nombreCorto']} {$atributo}</td>";
										}
										$query3 = mysqli_query($link, "SELECT * FROM Ubicacion WHERE idUbicacion = '{$row2['idUbicacionInicial']}'");
										while($row3 = mysqli_fetch_array($query3)){
											$query4 = mysqli_query($link, "SELECT * FROM Almacen WHERE idAlmacen = '{$row3['idAlmacen']}'");
											while($row4 = mysqli_fetch_array($query4)){
											    $nombreAlmacen = $row4['descripcion'];
												echo "<td class='text-center'>{$row4['descripcion']}</td>";
											}
										}
										echo "<td class='text-center'>{$row2['idUbicacionInicial']}</td>";
										echo "<td class='text-center'>{$row2['stockInicial']}</td>";
										echo "<td class='text-center'>{$row2['stockFinal']}</td>";
										echo "<td class='text-center'>{$row2['observacion']}</td>";
										echo "</tr>";
										$txt = $aux.",".$row['idTransaccion'].",".$nombreProducto." ".$atributo.",".$nombreAlmacen.",".$row2['idUbicacionInicial'].",".$row2['stockInicial'].",".$row2['stockFinal'].",".$row2['observacion'].PHP_EOL;
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
		