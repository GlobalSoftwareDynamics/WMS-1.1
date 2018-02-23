<?php
include('session.php');
include ('declaracionFechas.php');
if(isset($_SESSION['login'])) {
	include('adminTemplate.php');
	
	if(isset($_POST['update'])){
		for($i=0;$i < $_POST['filas']; $i++){
			$nombre = "nombre".$i;
			$colorInput = "color".$i;
			$subcategoria = "subcategoria".$i;
			$costo = "costo".$i;
			
			$color = null;
			$addColor = 1;
			$colorAdd = str_replace(array_keys($replace2),$replace2,$_POST[$colorInput]);
			$query = mysqli_query($link, "SELECT * FROM Color");
			while ($row = mysqli_fetch_array($query)){
				if($row['descripcion']==$colorAdd){
					$addColor = 0;
				}
			}
			if(($addColor == 1) && ($colorAdd != null)){
				$insert = mysqli_query($link, "INSERT INTO Color (descripcion) VALUES ('{$colorAdd}')");
				$addColor = 0;
			}
			if($addColor == 0){
				$query = mysqli_query($link, "SELECT * FROM Color");
				while ($row = mysqli_fetch_array($query)){
					if($row['descripcion']==$_POST[$colorInput]){
						$color = $row['idColor'];
					}
				}
			}
			
			$query = mysqli_query($link,"UPDATE Producto SET nombreCorto = '{$_POST[$nombre]}', idColor = '{$color}', idSubCategoria = '{$_POST[$subcategoria]}', costoEstimado = '{$_POST[$costo]}' WHERE idProducto = '{$_POST[$i]}'");
			
			$queryPerformed = "UPDATE Producto SET nombreCorto = '{$_POST[$nombre]}', idColor = {$color}, idSubCategoria = {$_POST[$subcategoria]}, costoEstimado = {$_POST[$costo]} WHERE idProducto = {$_POST[$i]}";
						
			$databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','UPDATE','Producto','{$queryPerformed}')");
		}
	}
?>

	<section class="container">
		<div class="row" id="opcionesMetodoPago">
			<div class="col-10 offset-1">
				<div class="card">
					<div class="card-header card-inverse card-info">
						<div class="float-left">
							<i class="fa fa-camera"></i>
							Modificación de Datos
						</div>
					</div>
					<div class="card-block">
						<div class="row">
							<div class="col-12">
								<form method="post" action="#" class="form-inline justify-content-center">
									<div class="form-group  mt-2 mb-2 mr-2">
										<label class="sr-only" for="nombre">Nombre O Fragmento</label>
										<input type="text" name="nombre" id="nombre" placeholder="Nombre o Fragmento" class="form-control">
									</div>
									<div class="form-group  mt-2 mb-2 mr-2">
										<input type="submit" name="generar" class="btn btn-outline-warning" value="Generar">
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
	if(isset($_POST['generar']) && $_POST['nombre']) {
	    ?>
        <section class="container">
            <div class="row" id="opcionesMetodoPago">
                <div class="col-10 offset-1">
                    <div class="card">
                        <div class="card-header card-inverse card-info">
                            <div class="float-left">
                                <i class="fa fa-camera"></i>
                                Productos Encontrados
                            </div>
                            <div class="float-right">
                                <button href="#" form='updateProductos' class="btn btn-secondary btn-sm" name='update'>Guardar</button>
                            </div>
                        </div>
                        <div class="card-block">
                            <div class="row">
                                <div class="col-12">
                                    <table class='table text-center'>
                                    	<thead>
                                        <tr>
                                        	<th class='text-center' style='width: 13%'>SKU</th>
                                            <th class='text-center' style='width: 35%'>Nombre</th>
                                            <th class='text-center' style='width: 20%'>Atributo</th>
                                            <th class='text-center' style='width: 20%'>SubCategoría</th>
                                            <th class='text-center' style='width: 12%'>V.U. Promedio</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <form id='updateProductos' method='post'>
                                        	<?php
											$aux=0;
											$result = mysqli_query($link,"SELECT idProducto,nombreCorto,idColor,idSubCategoria,costoEstimado FROM Producto WHERE nombreCorto LIKE '%{$_POST['nombre']}%'");
										
											while($fila = mysqli_fetch_array($result)){
												$valorPromedio = round($fila['costoEstimado'],1);
												echo "<tr>";
												echo "<td><input class='form-control' type='text' value='{$fila['idProducto']}' name='{$aux}' readonly></td>";
												echo "<td><input class='form-control' type='text' value='{$fila['nombreCorto']}' name='nombre{$aux}'></td>";
												$result1 = mysqli_query($link,"SELECT idColor,descripcion FROM Color WHERE idColor = '{$fila['idColor']}'");
												while($fila1=mysqli_fetch_array($result1)){
													echo "<td><input class='form-control' type='text' value='{$fila1['descripcion']}' name='color{$aux}'></td>";
												}
												echo "<td><select name='subcategoria{$aux}' class='form-control'>";
												$result1 = mysqli_query($link,"SELECT idSubCategoria,descripcion FROM SubCategoria WHERE idSubCategoria = '{$fila['idSubCategoria']}'");
												while($fila1=mysqli_fetch_array($result1)){
													echo "<option value='{$fila['idSubCategoria']}' selected>{$fila1['descripcion']}</option>";
												}
												$result1 = mysqli_query($link,"SELECT idSubCategoria,descripcion FROM SubCategoria");
												while($fila1=mysqli_fetch_array($result1)){
													echo "<option value='{$fila1['idSubCategoria']}'>{$fila1['descripcion']}</option>";
												}											
												echo "</select></td>";
												echo "<td><input class='form-control' type='number' step='0.1' min='0' value='{$valorPromedio}' name='costo{$aux}'></td>";
												echo "</tr>";
												$aux++;
											}
											?>
                                            <input class='form-control' type='hidden' value='<?php echo $aux;?>' name='filas'>
                                            </form>
                                        </tbody>
                                    </table>
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