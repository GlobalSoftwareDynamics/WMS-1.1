<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
	include('adminTemplate.php');
	$result=mysqli_query($link,"SELECT * FROM Producto WHERE idProducto='{$_POST['idProducto']}'");
	while($fila=mysqli_fetch_array($result)) {

	    $resultado = mysqli_query($link,"SELECT * FROM Subcategoria WHERE idSubcategoria = '{$fila['idSubCategoria']}'");
	    while($filas = mysqli_fetch_array($resultado)){
	        $idSubcategoria = $fila['idSubCategoria'];
	        $subcategoria = $filas['descripcion'];
	        $resultado2 = mysqli_query($link,"SELECT * FROM Categoria WHERE idCategoria = '{$filas['idCategoria']}'");
	        while($filas2 = mysqli_fetch_array($resultado2)){
	            $idCategoria = $filas['idCategoria'];
	            $categoria = $filas2['descripcion'];
            }
        }

		if(isset($_POST['addCategoria'])){
			$insert = mysqli_query($link,"INSERT INTO Categoria (descripcion) VALUES ('{$_POST['descripcionCategoria']}')");
			if($insert){
			}else{
				echo 'Error ingresando datos a la base de datos';
			}
		}

		if(isset($_POST['addSubcategoria'])){
			$insert = mysqli_query($link,"INSERT INTO Subcategoria (idCategoria, descripcion) VALUES ('{$_POST['selectCategoria']}','{$_POST['descripcionSubcategoria']}')");
			if($insert){
			}else{
				echo 'Error ingresando datos a la base de datos';
			}
		}

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

							<div class="float-left">
								<i class="fa fa-info-circle"></i>
								Detalle del Producto
							</div>
							<div class="float-right">
                                <button type="submit" form="editarProd" value="Guardar" name="editarProducto" class="btn btn-secondary btn-sm">Guardar</button>
                                <button type="submit" form="editarProd" value="Regresar" name="back" class="btn btn-secondary btn-sm" formaction="gestionProductos.php">Regresar</button>
							</div>
						</div>
						<div class="card-block">
                            <form method="post" action="gestionProductos.php" id="editarProd">
                                <input type="hidden" name="codigoAnt" value="<?php echo $_POST['idProducto'];?>">
							<div class="col-12">
								<ul class="nav nav-tabs" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" data-toggle="tab" href="#general"
										   role="tab">General</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#atributos"
										   role="tab">Atributos</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#inventario"
										   role="tab">Logística</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#url" role="tab">URL</a>
									</li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="general" role="tabpanel">
										<div class="spacer10"></div>
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
												<input class="form-control" type="text" id="codigo" name="codigo" value="<?php echo $fila['idProducto']?>">
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
                                            <label for="categoria" class="col-2 col-form-label">Categoría:</label>
                                            <div class="col-6">
                                                <select class="form-control" name="categoria" id="categoria" onchange="getSubcategoria(this.value)">
                                                    <option value="<?php echo $idCategoria;?>" selected><?php echo $categoria?></option>
													<?php
													$query = mysqli_query($link,"SELECT * FROM Categoria");
													while($row = mysqli_fetch_array($query)){
														echo "<option value='{$row['idCategoria']}'>{$row['descripcion']}</option>";
													}
													?>
                                                </select>
                                            </div>
                                            <div class="col-2">
                                                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#modalCategoria">Agregar Categoría</button>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="subcategoria" class="col-2 col-form-label">Subcategoría:</label>
                                            <div class="col-6">
                                                <select class="form-control" name="subcategoria" id="subcategoria">
                                                    <option selected value="<?php echo $fila['idSubCategoria'];?>"><?php echo $subcategoria;?></option>
                                                </select>
                                            </div>
                                            <div class="col-2">
                                                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#modalSubcategoria">Agregar Subcategoría</button>
                                            </div>
                                        </div>
									</div>
									<div class="tab-pane" id="atributos" role="tabpanel">
										<div class="spacer10"></div>
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
                                            <label for="genero" class="col-2 col-form-label">Genero:</label>
                                            <div class="col-6">
                                                <select class="form-control" name="genero" id="genero">
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
                                        <div class="form-group row">
                                            <label for="estado" class="col-2 col-form-label">Estado:</label>
                                            <div class="col-6">
                                                <select class="form-control" name="estado" id="estado">
													<?php
													$query = mysqli_query($link,"SELECT * FROM Estado WHERE clase = 'estadoProducto'");
													while($row = mysqli_fetch_array($query)){
														if($fila['idEstado']==$row['idEstado']){
															echo "<option selected value='{$fila['idEstado']}'>{$row['descripcion']}</option>";
														}else{
															echo "<option value='{$row['idEstado']}'>{$row['descripcion']}</option>";
														}
													}
													?>
                                                </select>
                                            </div>
                                        </div>
									</div>
									<div class="tab-pane" id="inventario" role="tabpanel">
										<div class="spacer10"></div>
										<div class="form-group row">
											<label for="stockReposicion" class="col-2 col-form-label">Stock de Reposición:</label>
											<div class="col-10">
												<input class="form-control" type="text" id="stockReposicion" name="stockReposicion" value="<?php echo $fila['puntoReposicion'];?>">
											</div>
										</div>
									</div>
									<div class="tab-pane" id="url" role="tabpanel">
										<div class="spacer10"></div>
										<div class="form-group row">
											<label for="urlImagen" class="col-2 col-form-label">URL Imágen:</label>
											<div class="col-10">
												<input class="form-control" type="text" id="urlImagen" name="urlImagen" value="<?php echo $fila['urlImagen']?>">
											</div>
										</div>
										<div class="form-group row">
											<label for="urlProducto" class="col-2 col-form-label">URL Producto:</label>
											<div class="col-10">
												<input class="form-control" type="text" id="urlProducto" name="urlProducto" value="<?php echo $fila['urlProducto']?>">
											</div>
										</div>
									</div>
								</div>
							</div>
                            </form>
						</div>
					</div>
				</div>
			</div>
		</section>

        <div class="modal fade" id="modalCategoria" tabindex="-1" role="dialog" aria-labelledby="modalCategoria" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Categoría</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form id="formCategoria" method="post" action="#">
                                <div class="form-group row">
                                    <label class="col-form-label" for="descripcionCategoria">Nombre de Categoria:</label>
                                    <input type="text" name="descripcionCategoria" id="descripcionCategoria" class="form-control">
                                    <input type="hidden" name="idProducto" id="idProducto" value="<?php echo $_POST['idProducto'];?>">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" form="formCategoria" value="Submit" name="addCategoria">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalSubcategoria" tabindex="-1" role="dialog" aria-labelledby="modalSubcategoria" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Subcategoría</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form id="formSubcategoria" method="post" action="#">
                                <div class="form-group row">
                                    <input type="hidden" name="idProducto" id="idProducto" value="<?php echo $_POST['idProducto'];?>">
                                    <label class="col-form-label" for="selectCategoria">Categoría:</label>
                                    <select name="selectCategoria" id="selectCategoria" class="form-control">
                                        <option disabled selected>Seleccionar</option>
										<?php
										$query = mysqli_query($link, "SELECT * FROM Categoria");
										while($row = mysqli_fetch_array($query)){
											echo "<option value='{$row['idCategoria']}'>{$row['descripcion']}</option>";
										}
										?>
                                    </select>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label" for="descripcionSubcategoria">Nombre de Subcategoria:</label>
                                    <input type="text" name="descripcionSubcategoria" id="descripcionSubcategoria" class="form-control">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" form="formSubcategoria" value="Submit" name="addSubcategoria">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

		<?php
	}
	include('footerTemplate.php');
}else{
	include('sessionError.php');
}
