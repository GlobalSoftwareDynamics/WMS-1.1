<?php
include('session.php');
if(isset($_SESSION['login'])) {
	include('adminTemplateAutocomplete.php');

	$date = date('Y-m-d');
	$anio = date('Y');
	$anioSiguiente = $anio + 1;
	$anioAnterior = $anio - 1;
	$transaccionOriginal = $_POST['idTransaccion'];

	$result = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$_POST['idTransaccion']}'");
	while($row = mysqli_fetch_array($result)) {
		$montoTotal = $row['montoTotal'];
		$montoRestante = $row['montoRestante'];
	}

	$query = mysqli_query($link,"SELECT * FROM Catalogo WHERE fechaInicio <= '{$date}' AND fechaFin >= '{$date}'");
	while($row = mysqli_fetch_array($query)) {
		$catalogoActual = $row['idCatalogo'];
		$campañaActual = $row['idCampana'];
		$campañaAnterior = $campañaActual - 1;
		$campañaSiguiente = $campañaActual + 1;
		if($campañaActual == 13){
			$campañaSiguiente = 1;
		}elseif($campañaActual == 1){
			$campañaAnterior = 13;
		}
		if($campañaSiguiente == 1){
			$catalogoAnterior = $anio."C".$campañaAnterior;
			$catalogoSiguiente = $anioSiguiente."C".$campañaSiguiente;
		}elseif($campañaAnterior == 13){
			$catalogoAnterior = $anioAnterior."C".$campañaAnterior;
			$catalogoSiguiente = $anio."C".$campañaSiguiente;
		}else{
			$catalogoAnterior = $anio."C".$campañaAnterior;
			$catalogoSiguiente = $anio."C".$campañaSiguiente;
		}
	}
	?>

	<section class="container">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header card-inverse card-info">
						<div class="float-left">
							<i class="fa fa-camera"></i>
							Cancelación de Préstamo
						</div>
					</div>
					<div class="card-block">
						<div class="col-12">
							<div class="row">
								<div class="col-3"><p><b>Código:</b></p></div>
								<div class="col-9"><p><?php echo $_POST['idTransaccion']; ?></p></div>
							</div>
							<div class="row">
								<div class="col-3"><p><b>Monto Total: S/.</b></p></div>
								<div class="col-9"><p><?php echo $montoTotal; ?></p></div>
							</div>
							<div class="row">
								<div class="col-3"><p><b>Monto Restante: S/.</b></p></div>
								<div class="col-9"><p><?php echo $montoRestante; ?></p></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br>
		<div class="row" id="opcionesMetodoPago">
			<div class="col-12">
				<div class="card">
					<div class="card-header card-inverse card-info">
						<div class="float-left">
							<i class="fa fa-camera"></i>
							Detalle de Cancelación
						</div>
						<div class="float-right">
							<button type="submit" form="formPago" name="cancelarPrestamoPago" class="btn btn-secondary btn-sm">Procesar Pago</button>
							<button type="submit" form="formPago" name="volver" class="btn btn-secondary btn-sm">Volver</button>
						</div>
					</div>
					<div class="card-block">
						<div class="col-12">
							<ul class="nav nav-tabs" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" data-toggle="tab" href="#producto"
									   role="tab">Producto</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#efectivo"
									   role="tab">Efectivo/Tarjeta</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#deposito"
									   role="tab">Depósito a cuenta</a>
								</li>
							</ul>
							<form method="post" action="nuevaCancelacionPrestamo.php" id="formPago">
								<input type='hidden' name='idTransaccion' value='<?php echo $_POST['idTransaccion']?>'>
								<div class="tab-content">
									<div class="tab-pane active" id="producto" role="tabpanel">
										<div class="spacer30"></div>
										<div class="row form-group">
											<div class="col-2">
												<label for="Productos">Producto:</label>
											</div>
											<div class="col-8">
												<input type="text" name="productoSelect" id="Productos" class="form-control" onchange="getprecioprom(this.value)">
											</div>
										</div>
										<div class="row form-group">
											<div class="col-2">
												<label for="cantidadProducto">Cantidad:</label>
											</div>
											<div class="col-8">
												<input type="text" name="cantidadProducto" id="cantidadProducto" class="form-control">
											</div>
										</div>
										<div class="row form-group">
											<div class="col-2">
												<label for="precio">Valor de Producto:</label>
											</div>
											<div class="col-8" id="precioprom">
												<input type="text" class="form-control" readonly>
											</div>
										</div>
										<div class="form-group row">
											<label for="selectAlmacen" class="col-2 col-form-label">Selección de Almacén:</label>
											<div class="col-8">
												<select id="selectAlmacen" name="selectAlmacen" class="form-control" onchange="getUbicacionAlmacen(this.value)">
													<option selected disabled>Seleccionar</option>
													<?php
													$search = mysqli_query($link,"SELECT * FROM Almacen");
													while($searchIndex = mysqli_fetch_array($search)){
														echo "<option value='{$searchIndex['idAlmacen']}'>{$searchIndex['descripcion']}</option>";
													}
													?>
												</select>
											</div>
										</div>
										<div class="form-group row">
											<label for="ubicacionAlmacen" class="col-2 col-form-label">Selección de Ubicación:</label>
											<div class="col-8">
												<select id="ubicacionAlmacen" name="ubicacionAlmacen" class="form-control">
												</select>
											</div>
										</div>
									</div>
									<div class="tab-pane" id="efectivo" role="tabpanel">
										<div class="spacer30"></div>
										<div class="row form-group">
											<div class="col-2">
												<label for="medioPago">Seleccione Medio de Pago:</label>
											</div>
											<div class="col-8">
												<select class="form-control" name="medioPago" id="medioPago">
													<option selected disabled>Seleccionar</option>
													<option value="1">Efectivo</option>
													<option value="2">Tarjeta</option>
												</select>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-2">
												<label for="montoEfectivo">Monto:</label>
											</div>
											<div class="col-8">
												<input type="text" name="montoEfectivo" id="montoEfectivo" class="form-control">
											</div>
										</div>
									</div>
									<div class="tab-pane" id="deposito" role="tabpanel">
										<div class="spacer30"></div>
										<div class="row form-group">
											<div class="col-2">
												<label for="ctaBanco">Cuenta Bancaria:</label>
											</div>
											<div class="col-8">
												<select name="ctaBanco" id="ctaBanco" class="form-control">
													<option selected disabled>Seleccionar</option>
													<?php
													$search = mysqli_query($link,"SELECT * FROM Cuenta");
													while($fila = mysqli_fetch_array($search)){
														echo "<option value='{$fila['idCuenta']}'>{$fila['alias']}</option>";
													}
													?>
												</select>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-2">
												<label for="montoCuenta">Monto:</label>
											</div>
											<div class="col-8">
												<input type="text" name="montoCuenta" id="montoCuenta" class="form-control">
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
	include('footerTemplateAutocomplete.php');
}else{
	include('sessionError.php');
}
