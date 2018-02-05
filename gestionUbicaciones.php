<?php
include('session.php');
include ('declaracionFechas.php');
if(isset($_SESSION['login'])) {
	include('adminTemplate.php');

	if(isset($_POST['addUbicacion'])){
		$insert = mysqli_query($link, "INSERT INTO Ubicacion VALUES ('{$_POST['idUbicacion']}','{$_POST['idAlmacen']}','{$_POST['descripcionUbicacion']}')");

		$queryPerformed = "INSERT INTO Ubicacion VALUES ({$_POST['idUbicacion']},{$_POST['idAlmacen']},{$_POST['descripcionUbicacion']})";

		$databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','Ubicacion','{$queryPerformed}')");
	}

	if(isset($_POST['editUbicacion'])){
		$insert = mysqli_query($link, "UPDATE Ubicacion SET idUbicacion = '{$_POST['idUbicacion']}', idAlmacen = '{$_POST['idAlmacen']}',
		descripcion = '{$_POST['descripcionUbicacion']}' WHERE idUbicacion = '{$_POST['idUbicacionAnt']}'");

		$queryPerformed = "UPDATE Ubicacion SET idUbicacion = {$_POST['idUbicacion']},idAlmacen = {$_POST['idAlmacen']},
		descripcion = {$_POST['descripcionUbicacion']} WHERE idUbicacion = {$_POST['idUbicacionAnt']}";

		$databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','UPDATE','Ubicacion','{$queryPerformed}')");
	}
	?>

	<section class="container">
		<div class="card">
			<div class="card-header card-inverse card-info">
				<i class="fa fa-list"></i>
				Listado de Ubicaciones de <?php echo $_POST['descripcionAlmacen'];?>
				<span class="float-right">
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
	                        <form method="post">
		                        <input type='hidden' name='idUbicacion' value='<?php echo $row['idUbicacion'];?>'>
		                        <input type='hidden' name='idAlmacen' value='<?php echo $_POST['idAlmacen'];?>'>
		                        <input type='hidden' name='descripcionAlmacen' value='<?php echo $_POST['descripcionAlmacen'];?>'>
	                            <input name='editarUbicacion' class="dropdown-item" type="submit" formaction='nuevaUbicacion.php' value="Nueva Ubicación">
		                        <input name='verProductos' class="dropdown-item" type="submit" formaction='productosAlmacen.php' value="Ver Listado de Productos Almacenados">
                                <input name='back' class="dropdown-item" type="submit" formaction='gestionAlmacenes.php' value="Regresar">
	                        </form>
                        </div>
                    </div>
                </span>
			</div>
            <div class="card-block">
			<div class="spacer10"></div>
			<div class="row">
				<div class="col-12">
					<table class="table table-bordered" id="myTable">
						<thead class="thead-default">
						<tr>
							<th class="text-center">ID Ubicación</th>
							<th class="text-center">Descripción</th>
							<th class="text-center">Acciones</th>
						</tr>
						</thead>
						<tbody>
						<?php
						$query = mysqli_query($link, "SELECT * FROM Ubicacion WHERE idAlmacen = '{$_POST['idAlmacen']}'");
						while($row = mysqli_fetch_array($query)){
							echo "<tr>
                                        <td class=\"text-center\">{$row['idUbicacion']}</td>
                                        <td class=\"text-center\">{$row['descripcion']}</td>
                                        <td class=\"text-center\">
                                            <form method='post'>
                                                <div class=\"dropdown\">
                                                    <input type='hidden' name='idUbicacion' value='".$row['idUbicacion']."'>
                                                    <input type='hidden' name='descripcionUbicacion' value='".$row['descripcion']."'>
                                                    <input type='hidden' name='idAlmacen' value='".$_POST['idAlmacen']."'>
                                                    <input type='hidden' name='descripcionAlmacen' value='".$_POST['descripcionAlmacen']."'>
                                                    <button class=\"btn btn-secondary btn-sm dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                                    Acciones
                                                    </button>
                                                    <div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">
                                                        <button name='editarUbicacion' class=\"dropdown-item\" type=\"submit\" formaction='editarUbicacion.php'>Editar Ubicación</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                      </tr>";
						}
						?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</section>

	<?php
	include('footerTemplate.php');
}else{
	include('sessionError.php');
}