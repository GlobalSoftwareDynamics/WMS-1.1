<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
	include('adminTemplate.php');
	
	?>

	<section class="container">
		<div class="card">
			<div class="card-header card-inverse card-info">
				<i class="fa fa-list"></i>
				Registro de Prestamos Pendientes
				<span class="float-right">
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="reporteDeudasPrestamosPDF.php">Descargar</a>
                            <a class="dropdown-item" href="files/registroPrestamosEnDeuda.txt" download>Exportar Listado</a>
                        </div>
                    </div>
                </span>
			</div>
            <div class="card-block">
				<div class="spacer10"></div>
				<div class="row">
					<div class="col-12">
						<table class="table table-bordered text-center" id="myTable">
							<thead class="thead-default">
							<tr>
								<th class="text-center">idTransaccion</th>
								<th class="text-center">Fecha</th>
                                <th class="text-center">Cliente</th>
								<th class="text-center">Producto</th>
							</tr>
							</thead>
							<tbody>
							<?php
							$file = fopen("files/registroPrestamosEnDeuda.txt","w") or die("No se encontró el archivo!");
                            fwrite($file, pack("CCC",0xef,0xbb,0xbf));
                            $txt = "idTransaccion,Fecha,Cliente,Producto".PHP_EOL;
                            fwrite($file, $txt);
							$query = mysqli_query($link, "SELECT Transaccion.idTransaccion, TransaccionProducto.idProducto, Proveedor.nombre, Transaccion.fechaTransaccion FROM Transaccion INNER JOIN TransaccionProducto ON Transaccion.idTransaccion = TransaccionProducto.idTransaccion INNER JOIN Proveedor ON Transaccion.idProveedor = Proveedor.idProveedor WHERE idTipoTransaccion = 6 AND idEstado = 6 ORDER BY fechaTransaccion DESC");
							while($row = mysqli_fetch_array($query)){
								$query2 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$row['idProducto']}'");
								while($row1 = mysqli_fetch_array($query2)){
									$nombreProducto = $row1['nombreCorto'];
								}
								echo "<tr>";
								echo "<td>{$row['idTransaccion']}</td>";
								echo "<td>{$row['fechaTransaccion']}</td>";
								echo "<td>{$row['nombre']}</td>";
								echo "<td>{$nombreProducto}</td>";
								echo "</tr>";
								$txt = $row['idTransaccion'].",".$row['fechaTransaccion'].",".$row['nombre'].",".$nombreProducto.PHP_EOL;
                                fwrite($file, $txt);
							}
							fclose($file);
							?>
							</tbody>
						</table>
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