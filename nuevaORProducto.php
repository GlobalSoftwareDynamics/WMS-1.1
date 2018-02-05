<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
	include('adminTemplate.php');
	include('funciones.php');

	?>

	<section class="container">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header card-inverse card-info">
						<form method="post" action="nuevaOR.php" id="formOR">
							<div class="float-left">
								<i class="fa fa-shopping-bag"></i>
								Registro de Recepción de Producto
							</div>
							<span class="float-right">
								<div class="dropdown">
									<input type="submit" value="Guardar" name="recibirProducto" class="btn btn-secondary btn-sm">
                                </div>
                            </span>
					</div>
					<div class="card-block">
						<div class="row">
							<?php
							$nombreProducto = null;
							$urlImagen = null;
							$query = mysqli_query($link,"SELECT * FROM Producto WHERE idProducto = '{$_POST['idProducto']}'");
							while($row = mysqli_fetch_array($query)){
								$nombreProducto = $row['nombreCorto'];
								$urlImagen = $row['urlImagen'];
							}
							$cantidadRestante = $_POST['cantidadTotal'] - $_POST['cantidadRecibida'];
							?>
							<div class="col-2 text-center">
								<img src="<?php echo $urlImagen;?>" width="100px" height="auto">
							</div>
							<div class="col-10 my-auto">
								<?php
								echo "<h4>{$nombreProducto}</h4>";
								?>
							</div>
						</div>
						<div class="spacer30"></div>
						<div class="col-12">
							<ul class="nav nav-tabs" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" data-toggle="tab" href="#general" role="tab">Detalles</a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="general" role="tabpanel">
									<div class="spacer30"></div>
                                    <input type="hidden" name="idTransaccionOC" value="<?php echo $_POST['idTransaccionOC'];?>">
                                    <input type="hidden" name="idOrdenRecepcion" value="<?php echo $_POST['idOrdenRecepcion'];?>">
                                    <input type="hidden" name="idProducto" value="<?php echo $_POST['idProducto'];?>">
                                    <input type="hidden" name="" value="">
									<div class="form-group row">
										<label for="cantidadRestante" class="col-2 col-form-label">Cantidad por Recibir:</label>
										<div class="col-10">
											<input class="form-control" type="text" id="cantidadRestante" name="cantidadRestante" value="<?php echo $cantidadRestante;?>" readonly>
										</div>
									</div>
									<div class="form-group row">
										<label for="almacen" class="col-2 col-form-label">Almacén:</label>
										<div class="col-10">
											<select class="form-control" id="almacen" name="almacen" onchange="getUbicacionAlmacen(this.value)">
												<option selected>Seleccionar</option>
												<?php
												$query2 = mysqli_query($link,"SELECT * FROM Almacen");
												while($row2 = mysqli_fetch_array($query2)){
													echo "<option value='{$row2['idAlmacen']}'>{$row2['descripcion']}</option>";
												}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row">
                                        <label for='ubicacionAlmacen' class='col-2 col-form-label'>Ubicación:</label>
                                        <div class='col-10'>
                                            <select class='form-control' id='ubicacionAlmacen' name='ubicacionAlmacen'>
                                                <option selected>Seleccionar</option>
                                            </select>
                                        </div>
									</div>
                                    <div class="form-group row">
                                        <label for="cantidadRecibida" class="col-2 col-form-label">Cantidad Recibida:</label>
                                        <div class="col-10">
                                            <input type="number" class="form-control" name="cantidadRecibida" id="cantidadRecibida" min="0" max="<?php echo $cantidadRestante;?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="observacion" class="col-2 col-form-label">Observaciones:</label>
                                        <div class="col-10">
                                            <input type="text" class="form-control" name="observacion" id="observacion">
                                        </div>
                                    </div>
								</div>
							</div>
						</div>
					</div>
					</form>
				</div>
			</div>
		</div>
	</section>

	<?php
	include('footerTemplate.php');
}else{
	include('sessionError.php');
}















