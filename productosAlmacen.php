<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
	include('adminTemplate.php');
?>
		<section class="container">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header card-inverse card-info">
							<div class="float-left">
								<i class="fa fa-info-circle"></i>
								Detalle de Productos en el Almacén <?php echo $_POST['idAlmacen'];?>
							</div>
							<div class="float-right">
								<form method='post' action="gestionAlmacenes.php">
									<button type="submit" class="btn btn-secondary btn-sm">Regresar</button>
								</form>
							</div>
						</div>
						<div class="card-block">
							<div class="col-12">
								<table class="table">
									<thead>
									<tr>
                                        <th class="text-center">
                                            Imágen
                                        </th>
                                        <th class="text-center">
                                            SKU
                                        </th>
                                        <th class="text-center">
                                            Producto
                                        </th>
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
									$query = mysqli_query($link,"SELECT * FROM Ubicacion WHERE idAlmacen = '{$_POST['idAlmacen']}'");
									while($row = mysqli_fetch_array($query)){
										$query2 = mysqli_query($link, "SELECT * FROM UbicacionProducto WHERE idUbicacion = '{$row['idUbicacion']}'");
										while($row2 = mysqli_fetch_array($query2)){
										    if($row2['stock'] <= 0){
                                            }else{
											    $query3 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$row2['idProducto']}'");
											    while($row3 = mysqli_fetch_array($query3)){
												    $descripcionProducto = $row3['nombreCorto'];
												    $imagen = $row3['urlImagen'];
											    }
											    echo "<tr>";
											    echo "<td class='text-center'><a rel=\"popover\" data-img='{$imagen}'><img src='{$imagen}' width='40px' height='40px'></a></td>";
											    echo "<td class='text-center'>{$row2['idProducto']}</td>";
											    echo "<td class='text-center'>{$descripcionProducto}</td>";
											    echo "<td class='text-center'>{$_POST['idAlmacen']}</td>";
											    echo "<td class='text-center'>{$row2['idUbicacion']}</td>";
											    echo "<td class='text-center'>{$row2['stock']}</td>";
											    echo "</tr>";
                                            }
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
	include('footerTemplate.php');
}else{
	include('sessionError.php');
}
