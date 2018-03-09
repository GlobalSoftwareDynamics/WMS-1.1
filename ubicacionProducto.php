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
				<div class="col-6 offset-1"></div>
			</div>
			<br>
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header card-inverse card-info">
							<div class="float-left">
								<i class="fa fa-info-circle"></i>
								Detalle de Ubicaciones
							</div>
							<div class="float-right">
								<form method='post'>
									<div class="dropdown">
										<input type="hidden" name="idProducto" value="<?php echo $_POST['idProducto'];?>">
										<button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Acciones
										</button>
										<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
											<input type="submit" name='regresar' class="dropdown-item" formaction='gestionInventario.php' value="Regresar">
										</div>
									</div>
								</form>
							</div>
						</div>
						<div class="card-block">
							<div class="col-12">
								<table class="table">
									<thead>
									<tr>
										<th class="text-center">
											Almacén
										</th>
										<th class="text-center">
											Ubicación
										</th>
										<th class="text-center">
											Cantidad
										</th>
									</tr>
									</thead>
									<tbody>
									<?php
									$query = mysqli_query($link,"SELECT * FROM UbicacionProducto WHERE idProducto = '{$_POST['idProducto']}'");
									while($row = mysqli_fetch_array($query)){
									    if($row['stock']<=0){
                                        }else{
										    $query2 = mysqli_query($link, "SELECT * FROM Ubicacion WHERE idUbicacion = '{$row['idUbicacion']}'");
										    while($row2 = mysqli_fetch_array($query2)){
											    echo "<tr>";
											    $query3 = mysqli_query($link,"SELECT * FROM Almacen WHERE idAlmacen = '{$row2['idAlmacen']}'");
											    while($row3 = mysqli_fetch_array($query3)){
												    echo "<td class='text-center'>{$row3['descripcion']}</td>";
											    }
										    }
										    echo "<td class='text-center'>{$row['idUbicacion']}</td>";
										    echo "<td class='text-center'>{$row['stock']}</td>";
										    echo "</tr>";
                                        }
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

		<?php
	}
	include('footerTemplate.php');
}else{
	include('sessionError.php');
}
