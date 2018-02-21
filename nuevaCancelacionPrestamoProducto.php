<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
	include('adminTemplateAutocomplete.php');

	$anio = date('Y');
	$anioSiguiente = $anio + 1;
	$anioAnterior = $anio - 1;
	$transaccionOriginal = $_POST['idTransaccion'];

	$result = mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$_POST['idTransaccion']}' AND idProducto = '{$_POST['idProducto']}'");
	while($row = mysqli_fetch_array($result)) {

	    $valorUnitario = $row['valorUnitario'];
	    $cantidadPrestada = $row['cantidad'];
		$result2 = mysqli_query($link,"SELECT * FROM Producto WHERE idProducto = '{$row['idProducto']}'");
		while($row2 = mysqli_fetch_array($result2)){
			$descripcionProducto = $row2['nombreCorto'];
			$valorPromedio = $row2['costoEstimado'];
		}
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

	$query=mysqli_query($link,"SELECT * FROM CatalogoProducto WHERE idCatalogo = '{$catalogoAnterior}' AND idProducto = '{$_POST['idProducto']}'");
	while ($row=mysqli_fetch_array($query)){
	    $precioAnterior = $row['precio'];
	}
	if(!isset($precioAnterior)){
	    $precioAnterior = '-';
    }

	$query=mysqli_query($link,"SELECT * FROM CatalogoProducto WHERE idCatalogo = '{$catalogoActual}' AND idProducto = '{$_POST['idProducto']}'");
	while ($row=mysqli_fetch_array($query)){
		$precioActual = $row['precio'];
	}
	if(!isset($precioActual)){
		$precioActual = '-';
	}

	$query=mysqli_query($link,"SELECT * FROM CatalogoProducto WHERE idCatalogo = '{$catalogoSiguiente}' AND idProducto = '{$_POST['idProducto']}'");
	while ($row=mysqli_fetch_array($query)){
		$precioSiguiente = $row['precio'];
	}
	if(!isset($precioSiguiente)){
		$precioSiguiente = '-';
	}

		?>

		<section class="container">
			<div class="row">
				<div class="col-7">
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
									<div class="col-3"><p><b>SKU Producto:</b></p></div>
									<div class="col-9"><p><?php echo $_POST['idProducto']; ?></p></div>
								</div>
								<div class="row">
									<div class="col-3"><p><b>Descripcion:</b></p></div>
									<div class="col-9"><p><?php echo $descripcionProducto; ?></p></div>
								</div>
								<div class="row">
									<div class="col-3"><p><b>Cantidad Prestada:</b></p></div>
									<div class="col-9"><p><?php echo $cantidadPrestada; ?></p></div>
								</div>
								<div class="row">
									<div class="col-3"><p><b>Total (S/.):</b></p></div>
									<div class="col-9"><p>S/. <?php $totalPrestado = $cantidadPrestada * $valorUnitario; echo $totalPrestado; ?></p></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-5">
					<div class="card">
						<div class="card-header card-inverse card-info">
							<div class="float-left">
								<i class="fa fa-reorder"></i>
								Listado de Precios (S/.)
							</div>
						</div>
						<div class="card-block">
							<div class="col-12">
                                <table class="table-striped text-center" style='width: 100%'>
                                    <tbody>
                                    <tr>
                                        <td><p><b>Valor Unitario de Préstamo:</b></p></td>
                                        <td><p>S/. <?php echo $valorUnitario; ?></p></td>
                                    </tr>
                                    <tr>
                                        <td><p><b>Valor Promedio:</b></p></td>
                                        <td><p>S/. <?php echo $valorPromedio; ?></p></td>
                                    </tr>
                                    <tr>
                                        <td><p><b>Valor Catálogo Anterior:</b></p></td>
                                        <td><p>S/. <?php echo $precioAnterior; ?></p></td>
                                    </tr>
                                    <tr>
                                        <td><p><b>Valor Catálogo Actual:</b></p></td>
                                        <td><p>S/. <?php echo $precioActual; ?></p></td>
                                    </tr>
                                    <tr>
                                        <td><p><b>Valor Catálogo Siguiente:</b></p></td>
                                        <td><p>S/. <?php echo $precioSiguiente; ?></p></td>
                                    </tr>
                                    </tbody>
                                </table>
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
                                <button type="submit" form="formDevolucion" name="cancelarPrestamoDevolucion" class="btn btn-secondary btn-sm">Guardar</button>
                                <button type="submit" form="formDevolucion" name="volver" class="btn btn-secondary btn-sm">Volver</button>
                            </div>
                        </div>
                        <div class="card-block">
                            <div class="col-12">
                                <form method="post" id="formDevolucion" action="nuevaCancelacionPrestamo.php">
                                    <div class="form-group row">
                                        <label for="cantidadDevuelta" class="col-2 col-form-label">Cantidad:</label>
                                        <div class="col-10">
                                            <input type="hidden" name="idProductoSelect" value="<?php echo $_POST['idProducto'];?>">
                                            <input type="hidden" name="idTransaccion" value="<?php echo $_POST['idTransaccion'];?>">
                                            <input class="form-control" type="text" id="cantidadDevuelta" name="cantidadDevuelta">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="selectAlmacen" class="col-2 col-form-label">Selección de Almacén:</label>
                                        <div class="col-10">
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
                                        <div class="col-10">
                                            <select id="ubicacionAlmacen" name="ubicacionAlmacen" class="form-control">
                                            <?php
                                                $search = mysqli_query($link,"SELECT idUbicacion FROM UbicacionProducto WHERE idProducto = '{$_POST['idProducto']}'");
                                                while($searchIndex = mysqli_fetch_array($search)){
                                                echo "<option value='{$searchIndex['idUbicacion']}'>{$searchIndex['idUbicacion']}</option>";
                                                }
                                            ?>
                                            </select>
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
