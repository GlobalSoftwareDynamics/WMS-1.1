<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
	include('adminTemplate.php');
	if(isset($_POST['addAlmacen'])){
		$query2 = mysqli_query($link, "SELECT * FROM Almacen");
		$rows2 = mysqli_num_rows($query2) + 1;
		$insert = mysqli_query($link, "INSERT INTO Almacen(descripcion,prioridad) VALUES ('{$_POST['nombreAlmacen']}','{$rows2}')");

		$queryPerformed = "INSERT INTO Almacen(descripcion,prioridad) VALUES ({$_POST['nombreAlmacen']},{$rows2})";

		$databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','Almacen','{$queryPerformed}')");
	}

	if(isset($_POST['aumentarPrioridad'])){
	    $prioridadAnt = $_POST['prioridad'] - 1;
	    if($prioridadAnt < 1){

        }else{
		    $select = mysqli_query($link, "SELECT * FROM Almacen WHERE prioridad = '{$prioridadAnt}'");
		    while($row = mysqli_fetch_array($select)){
		        $idAnt = $row['idAlmacen'];
		        $idAct = $_POST['idAlmacen'];
		        $update = mysqli_query($link, "UPDATE Almacen SET prioridad = '{$_POST['prioridad']}' WHERE idAlmacen = '{$idAnt}'");
			    $update = mysqli_query($link, "UPDATE Almacen SET prioridad = '{$prioridadAnt}' WHERE idAlmacen = '{$idAct}'");

			    $queryPerformed = "UPDATE Almacen SET prioridad = {$_POST['prioridad']} WHERE idAlmacen = {$idAnt}";

			    $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','UPDATE','PrioridadAlmacen','{$queryPerformed}')");

			    $queryPerformed = "UPDATE Almacen SET prioridad = {$prioridadAnt} WHERE idAlmacen = {$idAct}";

			    $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','UPDATE','PrioridadAlmacen','{$queryPerformed}')");
            }
        }
    }
	?>

	<section class="container">
		<div class="card">
			<div class="card-header card-inverse card-info">
				<i class="fa fa-list"></i>
				Listado de Almacenes
				<span class="float-right">
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="nuevoAlmacen.php">Registrar Nuevo Almacén</a>
                            <a class="dropdown-item" href="gestionInventario.php">Regresar</a>
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
								<th class="text-center">Descripción</th>
								<th class="text-center">Número de Ubicaciones</th>
                                <th class="text-center">Prioridad para Ventas</th>
								<th class="text-center">Acciones</th>
							</tr>
							</thead>
							<tbody>
							<?php
							$query = mysqli_query($link, "SELECT * FROM Almacen");
							while($row = mysqli_fetch_array($query)){
								$query2 = mysqli_query($link, "SELECT * FROM Ubicacion WHERE idAlmacen = '{$row['idAlmacen']}'");
								$rows = mysqli_num_rows($query2);
								echo "<tr>
                                        <td class=\"text-center\">{$row['descripcion']}</td>
                                        <td class=\"text-center\">{$rows}</td>
                                        <td class=\"text-center\">{$row['prioridad']}</td>
                                        <td class=\"text-center\">
                                            <form method='post'>
                                                <div class=\"dropdown\">
                                                    <input type='hidden' name='idAlmacen' value='".$row['idAlmacen']."'>
                                                    <input type='hidden' name='prioridad' value='".$row['prioridad']."'>
                                                    <input type='hidden' name='descripcionAlmacen' value='".$row['descripcion']."'>
                                                    <button class=\"btn btn-secondary btn-sm dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                                    Acciones
                                                    </button>
                                                    <div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">
                                                        <button name='aumentarPrioridad' class=\"dropdown-item\" type=\"submit\" formaction='#'>Aumentar Prioridad para Ventas</button>
                                                        <button name='gestionUbicaciones' class=\"dropdown-item\" type=\"submit\" formaction='gestionUbicaciones.php'>Gestionar Ubicaciones</button>
                                                        <button name='verProductos' class=\"dropdown-item\" type=\"submit\" formaction='productosAlmacen.php'>Ver Listado de Productos Almacenados</button>
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
		</div>
	</section>

	<?php
	include('footerTemplate.php');
}else{
	include('sessionError.php');
}