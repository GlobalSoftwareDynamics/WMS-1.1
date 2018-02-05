<?php
include('session.php');
if(isset($_SESSION['login'])) {
	include('adminTemplate.php');
	include('funciones.php');
	include('declaracionFechas.php');

	$result = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$_POST['idTransaccion']}'");
	while($row = mysqli_fetch_array($result)) {
		$result2 = mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$row['idProveedor']}'");
		while($row2 = mysqli_fetch_array($result2)){
			$proveedor = $row2['nombre'];
		}

		$result2 = mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$row['idColaborador']}'");
		while($row2 = mysqli_fetch_array($result2)){
			$colaborador = $row2['nombres']." ".$row2['apellidos'];
		}

		?>
		<section class="container">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header card-inverse card-info">
							<form method="post" action="gestionOC.php" id="formOC">
								<div class="float-left">
									<i class="fa fa-shopping-bag"></i>
									Cancelación de Préstamo
								</div>
								<div class="float-right">
									<div class="dropdown">
										<input type="submit" value="Volver" name="volver" class="btn btn-secondary btn-sm">
									</div>
                                </div>
							</form>
						</div>
						<div class="card-block">
							<div class="col-12">
								<div class="row">
									<div class="col-3"><p><b>Número de Orden:</b></p></div>
									<div class="col-9"><p><?php echo $_POST['idTransaccion']; ?></p></div>
								</div>
								<div class="row">
									<div class="col-3"><p><b>Deudor:</b></p></div>
									<div class="col-9"><p><?php echo $proveedor; ?></p></div>
								</div>
								<div class="row">
									<div class="col-3"><p><b>Responsable:</b></p></div>
									<div class="col-9"><p><?php echo $colaborador; ?></p></div>
								</div>
								<div class="row">
									<div class="col-3"><p><b>Fecha de Vencimiento:</b></p></div>
									<div class="col-9"><p><?php echo $row['fechaVencimiento']; ?></p></div>
								</div>
								<div class="row">
									<div class="col-3"><p><b>Costo de Envío (S/.):</b></p></div>
									<div class="col-9"><p><?php $costoEnvio = $row['costoTransaccion']; echo $row['costoTransaccion']; ?></p></div>
								</div>
								<div class="row">
									<div class="col-3"><p><b>Observaciones:</b></p></div>
									<div class="col-9"><p><?php echo $row['observacion']; ?></p></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<div class="spacer30"></div>

		<section class="container">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header card-inverse card-info">
							<form method="post" action="gestionOC.php" id="formOC">
								<div class="float-left">
									<i class="fa fa-shopping-bag"></i>
									Listado de Productos
								</div>
							</form>
						</div>
						<div class="card-block">
							<div class="col-12">
								<table class="table">
									<thead>
									<tr>
										<th class="text-center">Ítem Nro.</th>
										<th class="text-center">Descripción</th>
										<th class="text-center">Cantidad</th>
										<th class="text-center">Precio Unitario (S/.)</th>
										<th class="text-center">Total Ítem (S/.)</th>
										<th class="text-center">Notas</th>
										<th class="text-center">Acciones</th>
									</tr>
									</thead>
									<tbody>
									<?php
									$aux = 1;
									$sumaTotal = 0;
									$query = mysqli_query($link, "SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$_POST['idTransaccion']}'");
									while($row = mysqli_fetch_array($query)){
										echo "<tr>";
										echo "<td class='text-center'>{$aux}</td>";
										$aux ++;
										$query2 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$row['idProducto']}'");
										while($row2 = mysqli_fetch_array($query2)){
											echo "<td class='text-center'>{$row2['idProducto']} - {$row2['nombreCorto']}</td>";
										}
										echo "<td class='text-center'>{$row['cantidad']}</td>";
										$valorUnitario = round($row['valorUnitario'],2);
										echo "<td class='text-center'>{$valorUnitario}</td>";
										$total = $row['cantidad'] * $row['valorUnitario'];
										$sumaTotal += $total;
										$totalRedondeo = round($total,2);
										echo "<td class='text-center'>{$totalRedondeo}</td>";
										echo "<td class='text-center'>{$row['observacion']}</td>";
										echo "<td>
												<form method='post'>
                                                	<div class=\"dropdown\">
                                                    	<input type='hidden' name='idTransaccion' value='{$_POST['idTransaccion']}}'>
                                                    	<button class=\"btn btn-secondary btn-sm dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                                    	Acciones
                                                    	</button>
                                                    	<div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">
	                                                        <button name='registroPago' class=\"dropdown-item\" type=\"submit\" formaction='.php'>Registrar Pago</button>
    	                                                </div>
        	                                        </div>
            	                                </form>
                                            </td>";
										echo "</tr>";
									}
									?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<div class="spacer30"></div>

		<section class="container">
			<div class="row">
				<div class="col-8"></div>
				<div class="col-4">
					<table class="table align-content-end">
						<tbody>
						<tr>
							<th>Subtotal (S/.)</th>
							<td><?php echo round($sumaTotal,2);?></td>
						</tr>
						<tr>
							<th>Costo de Envío (S/.)</th>
							<td><?php echo round($costoEnvio,2);?></td>
						</tr>
						<tr>
							<th>Total (S/.)</th>
							<?php
							$totalP = $sumaTotal + $costoEnvio;
							?>
							<td><?php echo round($totalP,2);?></td>
						</tr>
						<tr>
							<th>Total a Pagar (S/.)</th>
							<td><?php echo round(($totalP),2);?></td>
						</tr>
						<tr>
							<th>Total Cancelado (S/.)</th>
							<td><?php echo round(($totalP),2);?></td>
						</tr>
						<tr>
							<th>Pendiente de Pago (S/.)</th>
							<td><?php echo round(($totalP),2);?></td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
		</section>

		<?php
	}
	include('footerTemplate.php');
}else{
	include('sessionError.php');
}