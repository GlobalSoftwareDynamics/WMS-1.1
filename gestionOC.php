<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
	include('adminTemplate.php');
	include('funciones.php');

	$idOR = idgen('OR');

    if(isset($_POST['completar'])){
        $query = mysqli_query($link,"UPDATE Transaccion SET idEstado = 5 WHERE idTransaccion = '{$_POST['idTransaccionOC']}'");
    }
	if(isset($_POST['delete'])){
		$delete = mysqli_query($link, "DELETE FROM TransaccionProducto WHERE idTransaccion = '{$_POST['idTransaccionOC']}'");
	    $delete = mysqli_query($link, "DELETE FROM Transaccion WHERE idTransaccion = '{$_POST['idTransaccionOC']}'");

		$queryPerformed = "DELETE FROM Transaccion WHERE idTransaccion = {$_POST['idTransaccionOC']}";
		$queryPerformed2 = "DELETE FROM TransaccionProducto WHERE idTransaccion = {$_POST['idTransaccionOC']}";

		$databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','DELETE','OrdenCompra','{$queryPerformed}')");
		$databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','DELETE','OrdenCompraProducto','{$queryPerformed2}')");
    }

	if(isset($_POST['emitir'])){
		$update = mysqli_query($link, "UPDATE Transaccion SET idEstado = '3' WHERE idTransaccion = '{$_POST['idTransaccionOC']}'");

		$queryPerformed = "UPDATE Transaccion SET idEstado = 3";

		$databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','UPDATE','Emitir OC','{$queryPerformed}')");
	}

	if(isset($_POST['addOC'])){
		$update = mysqli_query($link, "UPDATE Transaccion SET montoTotal = '{$_POST['montoTotalCompra']}', montoRestante = '{$_POST['montoTotalCompra']}' WHERE idTransaccion = '{$_POST['idTransaccion']}'");

		$queryPerformed = "UPDATE Transaccion SET montoTotal = {$_POST['montoTotalCompra']}, montoRestante = {$_POST['montoTotalCompra']} WHERE idTransaccion = {$_POST['idTransaccion']}";

		$databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','UPDATE','Update Monto OC','{$queryPerformed}')");
	}
	?>

    <script>
        function myFunction() {
            // Declare variables
            var input, input2, input3, input4, input5, filter, filter2, filter3, filter4, filter5, table, tr, td, td2, td3, td4, td5, i;
            input = document.getElementById("idTransaccion");
            input2 = document.getElementById("persona");
            input3 = document.getElementById("fechaCreacion");
            input4 = document.getElementById("fechaRecepcion");
            input5 = document.getElementById("estado");
            filter = input.value.toUpperCase();
            filter2 = input2.value.toUpperCase();
            filter3 = input3.value.toUpperCase();
            filter4 = input4.value.toUpperCase();
            filter5 = input5.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                td2 = tr[i].getElementsByTagName("td")[1];
                td3 = tr[i].getElementsByTagName("td")[2];
                td4 = tr[i].getElementsByTagName("td")[3];
                td5 = tr[i].getElementsByTagName("td")[4];
                if ((td)&&(td2)) {
                    if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                        if(td2.innerHTML.toUpperCase().indexOf(filter2) > -1){
                            if(td3.innerHTML.toUpperCase().indexOf(filter3) > -1){
                                if(td4.innerHTML.toUpperCase().indexOf(filter4) > -1){
                                    if(td4.innerHTML.toUpperCase().indexOf(filter4) > -1){
                                        tr[i].style.display = "";
                                    }else{
                                        tr[i].style.display = "none";
                                    }
                                }else{
                                    tr[i].style.display = "none";
                                }
                            }else{
                                tr[i].style.display = "none";
                            }
                        }else{
                            tr[i].style.display = "none";
                        }
                    }else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>

	<section class="container">
		<div class="card">
			<div class="card-header card-inverse card-info">
				<i class="fa fa-list"></i>
				Listado de Órdenes de Compra
				<div class="float-right">
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="nuevaOC.php">Registrar Nueva Orden de Compra</a>
                            <a class="dropdown-item" href="recepcionPremios.php">Recepción de Productos a Costo Cero</a>
                            <a class="dropdown-item" href="files/ordenesCompra.txt" download>Exportar Listado</a>
                        </div>
                    </div>
                </div>
                <div class="float-right">&nbsp;</div>
                <div class="float-right">
                    <button href="#collapsed" class="btn btn-secondary btn-sm" data-toggle="collapse">Mostrar Filtros</button>
                </div>
			</div>
			<div class="card-block">
				<div class="row">
					<div class="col-12">
						<div id="collapsed" class="collapse">
							<form class="form-inline justify-content-center" method="post" action="#">
								<label class="sr-only" for="idTransaccion">Orden #</label>
								<input type="text" class="form-control mt-2 mb-2 mr-2" id="idTransaccion" placeholder="Orden #" onkeyup="myFunction()">
                                <label class="sr-only" for="persona">Persona</label>
                                <input type="text" class="form-control mt-2 mb-2 mr-2" id="persona" placeholder="Nombre" onkeyup="myFunction()">
                                <label class="sr-only" for="fechaCreacion">Fecha de Creación</label>
								<input type="text" class="form-control mt-2 mb-2 mr-2" id="fechaCreacion" placeholder="Fecha de Creación" onkeyup="myFunction()">
								<label class="sr-only" for="fechaRecepcion">Fecha Estimada</label>
								<input type="text" class="search-key form-control mt-2 mb-2 mr-2" id="fechaRecepcion" placeholder="Fecha Estimada/OC Relacionada" onkeyup="myFunction()">
                                <label class="sr-only" for="estado">Estado</label>
                                <select class="form-control mt-2 mb-2 mr-2" id="estado" onchange="myFunction()">
                                    <option disabled selected value="a">Estado</option>
									<?php
									$query = mysqli_query($link, "SELECT * FROM Estado WHERE clase = 'estadoTransaccion'");
									while($row = mysqli_fetch_array($query)){
										echo "<option value='{$row['descripcion']}'>{$row['descripcion']}</option>";
									}
									?>
                                </select>
								<input type="submit" class="btn btn-primary" value="Limpiar" style="padding-left:28px; padding-right: 28px;">
							</form>
						</div>
					</div>
				</div>
			<div class="spacer10"></div>
			<div class="row">
				<div class="col-12">
					<table class="table table-bordered" id="myTable">
						<thead class="thead-default">
						<tr>
							<th class="text-center">Orden #</th>
                            <th class="text-center">Persona</th>
							<th class="text-center">Fecha de Creación</th>
							<th class="text-center">Fecha Estimada de Recepción/OC Relacionada</th>
							<th class="text-center">Estado</th>
							<th class="text-center">Acciones</th>
						</tr>
						</thead>
						<tbody>
						<?php
						$file = fopen("files/ordenesCompra.txt","w") or die("No se encontró el archivo!");
						fwrite($file, pack("CCC",0xef,0xbb,0xbf));
						$txt = "Nro. Orden,Fecha de Creación,Fecha Estimada de Recepción/OC Relacionada,Estado".PHP_EOL;
						fwrite($file, $txt);
						$idOR = idgen("OR");
						$query = mysqli_query($link, "SELECT * FROM Transaccion WHERE idTipoTransaccion = '1' ORDER BY fechaTransaccion DESC");
						while($row = mysqli_fetch_array($query)){
						    $fechaTransaccion = explode("|",$row['fechaTransaccion']);
							$query2 = mysqli_query($link,"SELECT * FROM Estado WHERE idEstado = '{$row['idEstado']}'");
							while($row2 = mysqli_fetch_array($query2)){
								$estado = $row2['descripcion'];
							}
                            $query2 = mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$row['idProveedor']}'");
                            while($row2 = mysqli_fetch_array($query2)){
                                $nombreProveedor = $row2['nombre'];
                            }
							echo "<tr>
                                        <td class=\"text-center\">{$row['idTransaccion']}</td>
                                        <td class=\"text - center\">{$nombreProveedor}</td>
                                        <td class=\"text-center\">{$fechaTransaccion[0]} - {$fechaTransaccion[1]}</td>
                                        <td class=\"text-center\">{$row['fechaEstimada']}</td>
                                        <td class=\"text-center\">{$estado}</td>
                                        <td class=\"text-center\">
                                            <form method='post'>
                                                <div class=\"dropdown\">
                                                    <input type='hidden' name='idTransaccionOC' value='".$row['idTransaccion']."'>
                                                    <input type='hidden' name='idOrdenRecepcion' value='".$idOR."'>
                                                    <input type='hidden' name='crearOR' value='".$idOR."'>
                                                    <button class=\"btn btn-secondary btn-sm dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                                    Acciones
                                                    </button>
                                                    <div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">
                                                        <button name='verProductos' class=\"dropdown-item\" type=\"submit\" formaction='detalleOC.php'>Ver Detalle</button>";
							                            if(substr($row['idTransaccion'],0,3)=='OCP'){
								                            if($estado == 'Abierta'){
									                            echo "<button name='emitir' class=\"dropdown-item\" type=\"submit\" formaction='#'>Emitir</button>";
									                            echo "<button name='delete' class=\"dropdown-item\" type=\"submit\" formaction='#'>Eliminar</button>";
								                            }elseif($estado == 'Emitida'){
									                            echo "<button name='recepcion' class=\"dropdown-item\" type=\"submit\" formaction='nuevaOR.php'>Registrar Recepción</button>";
								                            }elseif($estado == 'Parcial'){
									                            echo "<button name='recepcion' class=\"dropdown-item\" type=\"submit\" formaction='nuevaOR.php'>Registrar Recepción</button>";
								                            }
                                                        }else{
								                            if($estado == 'Abierta'){
									                            echo "<button name='emitir' class=\"dropdown-item\" type=\"submit\" formaction='#'>Emitir</button>";
									                            echo "<button name='delete' style='color: red' class=\"dropdown-item\" type=\"submit\" formaction='#'>Eliminar</button>";
								                            }elseif($estado == 'Emitida'){
									                            echo "<button name='recepcion' class=\"dropdown-item\" type=\"submit\" formaction='nuevaOR.php'>Registrar Recepción</button>";
									                            echo "<button name='recepcion' class=\"dropdown-item\" type=\"submit\" formaction='recepcionPremios.php?idTransaccionRel=".$row['idTransaccion']."'>Registrar OC Premios</button>";
								                            }elseif($estado == 'Parcial'){
									                            echo "<button name='recepcion' class=\"dropdown-item\" type=\"submit\" formaction='nuevaOR.php'>Registrar Recepción</button>";
									                            echo "<button name='recepcion' class=\"dropdown-item\" type=\"submit\" formaction='recepcionPremios.php?idTransaccionRel=".$row['idTransaccion']."'>Registrar OC Premios</button>";
									                            echo "<button name='completar' class=\"dropdown-item\" type=\"submit\" formaction='#'>Completar Orden</button>";
								                            }elseif($estado == 'Completa'){
									                            echo "<button name='recepcion' class=\"dropdown-item\" type=\"submit\" formaction='recepcionPremios.php?idTransaccionRel=".$row['idTransaccion']."'>Registrar OC Premios</button>";
                                                            }
                                                        }
                            echo "                  </div>
                                                </div>
                                            </form>
                                        </td>
                                      </tr>";
							$txt = $row['idTransaccion'].",".$fechaTransaccion[0]." - ".$fechaTransaccion[1].",".$row['fechaEstimada'].",".$estado.PHP_EOL;
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