<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
	include('adminTemplate.php');
	$result=mysqli_query($link,"SELECT * FROM Producto WHERE idProducto='{$_POST['idProducto']}'");
	while($fila=mysqli_fetch_array($result)) {
		?>

		<section class="container">
			<div class="row">
				<div class="col-5">
					<div class="card">
						<div class="card-header card-inverse card-info">
							<div class="float-left">
								<i class="fa fa-camera"></i>
								Fotografía del Producto
							</div>
						</div>
						<div class="card-block">
							<div class="row">
								<img src="<?php echo $fila['urlImagen']; ?>" alt="foto" height="120" width="120" class="offset-4">
							</div>
						</div>
					</div>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header card-inverse card-info">
							<form method="post" action="gestionProductos.php">
								<div class="float-left">
									<i class="fa fa-info-circle"></i>
									Detalle del Producto
								</div>
								<div class="float-right">
									<input type="hidden" name="codigoAnt" value="<?php echo $_POST['idProducto'];?>">
									<input type="submit" value="Guardar" name="nuevoProductoPlantilla" class="btn btn-secondary btn-sm">
                                    <input type="submit" value="Regresar" name="back" class="btn btn-secondary btn-sm" formaction="gestionProductos.php">
								</div>
						</div>
						<div class="card-block">
							<div class="col-12">
								<ul class="nav nav-tabs" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" data-toggle="tab" href="#general" role="tab">General</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#atributos" role="tab">Atributos</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#inventario" role="tab">Logística</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#url" role="tab">URL</a>
									</li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="general" role="tabpanel">
										<div class="spacer20"></div>
										<div class="form-group row">
											<label for="nombreCorto" class="col-2 col-form-label">Nombre Corto:</label>
											<div class="col-10">
												<input class="form-control" type="text" id="nombreCorto" name="nombreCorto" value="<?php echo $fila['nombreCorto']?>">
											</div>
										</div>
										<div class="form-group row">
											<label for="descripcion" class="col-2 col-form-label">Descripcion:</label>
											<div class="col-10">
												<input class="form-control" type="text" id="descripcion" name="descripcion" value="<?php echo $fila['descripcion']?>">
											</div>
										</div>
										<div class="form-group row">
											<label for="codigo" class="col-2 col-form-label">Código SKU:</label>
											<div class="col-10">
												<input class="form-control" type="text" id="codigo" name="codigo">
											</div>
										</div>
										<div class="form-group row">
											<label for="tipoProducto" class="col-2 col-form-label">Tipo de Producto:</label>
											<div class="col-6">
												<select class="form-control" name="tipoProducto" id="tipoProducto">
													<?php
													$query = mysqli_query($link,"SELECT * FROM TipoProducto");
													while($row = mysqli_fetch_array($query)){
														if($fila['idTipoProducto']==$row['idTipoProducto']){
															echo "<option selected value='{$fila['idTipoProducto']}'>{$row['descripcion']}</option>";
														}else{
															echo "<option value='{$row['idTipoProducto']}'>{$row['descripcion']}</option>";
														}
													}
													?>
												</select>
											</div>
										</div>
                                        <div class="form-group row">
                                            <label for="subcategoriaFija" class="col-2 col-form-label">Subcategoría:</label>
                                            <div class="col-6">
	                                            <?php
	                                            $query = mysqli_query($link,"SELECT * FROM SubCategoria");
	                                            while($row = mysqli_fetch_array($query)){
		                                            if($fila['idSubCategoria'] == $row['idSubCategoria']){
		                                                echo "<input type='hidden' name='subcategoria' value='{$row['idSubCategoria']}'>";
			                                            echo "<input type='text' class='form-control' name='subcategoriaFija' id='subcategoriaFija' value='{$row['descripcion']}' readonly>";
		                                            }
	                                            }
	                                            ?>
                                            </div>
                                        </div>
									</div>
									<div class="tab-pane" id="atributos" role="tabpanel">
										<div class="spacer20"></div>
										<div class="form-group row">
											<label for="unidadMedida" class="col-2 col-form-label">Unidad de Medida:</label>
											<div class="col-10">
												<select class="form-control" name="unidadMedida" id="unidadMedida">
													<?php
													$query = mysqli_query($link,"SELECT * FROM UnidadMedida");
													while($row = mysqli_fetch_array($query)){
														if($fila['idUnidadMedida'] == $row['idUnidadMedida']){
															echo "<option selected value='{$fila['idUnidadMedida']}'>{$row['descripcion']}</option>";
														}else{
															echo "<option value='{$row['idUnidadMedida']}'>{$row['descripcion']}</option>";
														}
													}
													?>
												</select>
											</div>
										</div>
										<div class="form-group row">
											<?php
											$result1=mysqli_query($link,"SELECT * FROM Color WHERE idColor ='{$fila['idColor']}'");
											while ($fila1=mysqli_fetch_array($result1)){
												$color = $fila1['descripcion'];
											}
											?>
											<label for="color" class="col-2 col-form-label">Atributo:</label>
											<div class="col-10">
												<input class="form-control" type="text" id="color" name="color" value="<?php echo $color;?>">
											</div>
										</div>
										<div class="form-group row">
											<label for="tamano" class="col-2 col-form-label">Tamaño:</label>
											<div class="col-6">
												<select class="form-control" name="tamano" id="tamano">
													<?php
													$query = mysqli_query($link,"SELECT * FROM Tamaño");
													while($row = mysqli_fetch_array($query)){
														if($fila['idTamaño'] == $row['idTamaño']){
															echo "<option selected value='{$fila['idTamaño']}'>{$row['nombre']}</option>";
														}else{
															echo "<option value='{$row['idTamaño']}'>{$row['nombre']}</option>";
														}
													}
													?>
												</select>
											</div>
										</div>
                                        <div class="form-group row">
                                            <label for="genero" class="col-2 col-form-label">Género:</label>
                                            <div class="col-6">
                                                <select class="form-control" name="genero" id="genero">
                                                    <option disabled selected>Seleccionar</option>
													<?php
													$query = mysqli_query($link,"SELECT * FROM Genero");
													while($row = mysqli_fetch_array($query)){
														if($fila['idGenero'] == $row['idGenero']){
															echo "<option selected value='{$fila['idGenero']}'>{$row['descripcion']}</option>";
														}else{
															echo "<option value='{$row['idGenero']}'>{$row['descripcion']}</option>";
														}
													}
													?>
                                                </select>
                                            </div>
                                        </div>
									</div>
									<div class="tab-pane" id="inventario" role="tabpanel">
										<div class="spacer20"></div>
										<div class="form-group row">
											<label for="stockReposicion" class="col-2 col-form-label">Stock de Reposición:</label>
											<div class="col-10">
												<input class="form-control" type="text" id="stockReposicion" name="stockReposicion" value="<?php echo $fila['puntoReposicion'];?>">
											</div>
										</div>
									</div>
									<div class="tab-pane" id="url" role="tabpanel">
										<div class="spacer20"></div>
										<div class="form-group row">
											<label for="urlImagen" class="col-2 col-form-label">URL Imágen:</label>
											<div class="col-10">
												<input class="form-control" type="text" id="urlImagen" name="urlImagen">
											</div>
										</div>
										<div class="form-group row">
											<label for="urlProducto" class="col-2 col-form-label">URL Producto:</label>
											<div class="col-10">
												<input class="form-control" type="text" id="urlProducto" name="urlProducto">
											</div>
										</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<?php
	}
	include('footerTemplate.php');
}else{
	include('sessionError.php');
}
