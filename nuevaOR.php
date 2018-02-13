<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
	include('adminTemplate.php');

	if(isset($_POST['crearOR'])){
		$idOR = $_POST['idOrdenRecepcion'];
        $update = mysqli_query($link,"UPDATE Transaccion SET idEstado = '6' WHERE idTransaccion = '{$_POST['idTransaccionOC']}'");
        $insert = mysqli_query($link,"INSERT INTO Transaccion VALUES ('{$idOR}','5','20100102413','4','{$_SESSION['user']}',NULL,NULL,'{$dateTime}',null,null,null,null,'{$_POST['idTransaccionOC']}',null,null)");
	}

	if(isset($_POST['recibirProducto'])){
	    /*      Costo Promedio de Producto      */
		$cantidadOR = 0;
		$costoTotalOR = 0;
		$promedio = 0;
		$select = mysqli_query($link, "SELECT * FROM Transaccion WHERE idTransaccion = '{$_POST['idOrdenRecepcion']}'");
		while($row = mysqli_fetch_array($select)){
			$select2 = mysqli_query($link, "SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['referenciaTransaccion']}' and idProducto = '{$_POST['idProducto']}'");
			while($row2 = mysqli_fetch_array($select2)){
				$cantidadOC = $row2['cantidad'];
				$cantidadOR = $_POST['cantidadRecibida'];
				$costoTotalOC = $row2['cantidad'] * $row2['valorUnitario'];
				$costoTotalOR = $cantidadOR * $row2['valorUnitario'];
			}
		}

		$stock = 0;
		$select2 = mysqli_query($link, "SELECT * FROM UbicacionProducto WHERE idProducto = '{$_POST['idProducto']}'");
		while($row2 = mysqli_fetch_array($select2)) {
			$stock += $row2['stock'];
		}

		$select = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$_POST['idProducto']}'");
		while($row = mysqli_fetch_array($select)){
			$costoStock = $row['costoEstimado'] * $stock;
			$cantidadTotal = $cantidadOR + $stock;
			$costoTotal = $costoStock + $costoTotalOR;
			$promedio = $costoTotal/$cantidadTotal;
		}

		$update = mysqli_query($link,"UPDATE Producto SET costoEstimado = '{$promedio}' WHERE idProducto = '{$_POST['idProducto']}'");

		/*      Orden de Recepción      */

		$idOR = $_POST['idOrdenRecepcion'];
		$previousStock = 0;
		$stockUpdate = $_POST['cantidadRecibida'];
		$flag = false;

		$stockTotal = 0;
		$select = mysqli_query($link,"SELECT * FROM UbicacionProducto WHERE idProducto = '{$_POST['idProducto']}'");
		while($row = mysqli_fetch_array($select)){
			$stockTotal += $row['stock'];
		}

		$select = mysqli_query($link,"SELECT * FROM UbicacionProducto WHERE idUbicacion = '{$_POST['ubicacionAlmacen']}' AND idProducto = '{$_POST['idProducto']}'");
		while($row = mysqli_fetch_array($select)){
		    $flag = true;
			$previousStock = $row['stock'];
			$stockUpdate = $previousStock + $_POST['cantidadRecibida'];
			$update = mysqli_query($link, "UPDATE UbicacionProducto SET stock = '{$stockUpdate}', fechaModificacion = '{$date}' WHERE idUbicacion = '{$_POST['ubicacionAlmacen']}' AND idProducto = '{$_POST['idProducto']}'");
        }

        $stockTotalUpdate = $stockTotal + $_POST['cantidadRecibida'];

        if(!$flag){
	        $insert = mysqli_query($link,"INSERT INTO UbicacionProducto VALUES ('{$_POST['idProducto']}','{$_POST['ubicacionAlmacen']}','{$_POST['cantidadRecibida']}','{$date}')");
        }

        $insert = mysqli_query($link,"INSERT INTO TransaccionProducto VALUES ('{$_POST['idProducto']}','{$_POST['idOrdenRecepcion']}',null,'{$_POST['ubicacionAlmacen']}',
        null,null,'{$_POST['cantidadRecibida']}',null,'{$_POST['observacion']}','{$stockTotal}',{$stockTotalUpdate},FALSE,0)");
        if(!$insert){
            $cantidadRecibidaTotal = $_POST['cantidadRecibida'];
            $search = mysqli_query($link, "SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$_POST['idOrdenRecepcion']}' AND idProducto = '{$_POST['idProducto']}'");
            while($index = mysqli_fetch_array($search)){
                $cantidadRecibidaTotal += $index['cantidad'];
                $stockFinal = $previousStock + $cantidadRecibidaTotal;
            }
            $update = mysqli_query($link, "UPDATE TransaccionProducto SET cantidad = '{$cantidadRecibidaTotal}', stockFinal = '{$stockTotalUpdate}' WHERE idTransaccion = '{$_POST['idOrdenRecepcion']}' AND idProducto = '{$_POST['idProducto']}'");
        }
    }
	?>

	<script>
        function myFunction() {
            // Declare variables
            var input, input2, filter, filter2, table, tr, td, td2, i;
            input = document.getElementById("SKU");
            input2 = document.getElementById("nombreCorto");
            filter = input.value.toUpperCase();
            filter2 = input2.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];
                td2 = tr[i].getElementsByTagName("td")[2];
                if ((td)&&(td2)) {
                    if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                        if(td2.innerHTML.toUpperCase().indexOf(filter2) > -1){
                            tr[i].style.display = "";
                        }else{
                            tr[i].style.display = "none";
                        }
                    }else{
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
				Registro de Recepción de Productos
				<span class="float-right">
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <form>
                                    <input name="submitOR" class="dropdown-item" type="submit" formaction="gestionOC.php" value="Completar Procesamiento de Orden">
                                </form>
                        </div>
                    </div>
                </span>
				<span class="float-right">&nbsp;&nbsp;&nbsp;&nbsp;</span>
				<span class="float-right">
                    <button href="#collapsed" class="btn btn-secondary btn-sm" data-toggle="collapse">Mostrar Filtros</button>
                </span>
			</div>
			<div class="card-block">
				<div class="row">
					<div class="col-12">
						<div id="collapsed" class="collapse">
							<form class="form-inline justify-content-center" method="post" action="#">
								<label class="sr-only" for="SKU">SKU</label>
								<input type="text" class="form-control mt-2 mb-2 mr-2" id="SKU" placeholder="SKU" onkeyup="myFunction()">
								<label class="sr-only" for="nombreCorto">Nombre Corto</label>
								<input type="text" class="form-control mt-2 mb-2 mr-2" id="nombreCorto" placeholder="Nombre Corto" onkeyup="myFunction()">
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
								<th class="text-center">Ítem #</th>
								<th class="text-center">SKU</th>
								<th class="text-center">Nombre Corto</th>
                                <th class="text-center">Atributo</th>
								<th class="text-center">Cantidad Ordenada</th>
								<th class="text-center">Cantidad Recibida</th>
                                <th class="text-center">Acciones</th>
							</tr>
							</thead>
							<tbody>
							<?php
							$aux = 0;
							$flag = true;
							$productoSel = array();
							$cantidadSel = array();
							$query = mysqli_query($link, "SELECT * FROM Transaccion WHERE referenciaTransaccion = '{$_POST['idTransaccionOC']}' AND idTipoTransaccion = '4'");
							while($row = mysqli_fetch_array($query)){
							    $query2 = mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['idTransaccion']}'");
							    while($row2 = mysqli_fetch_array($query2)){
							        if(isset($productoSel[$row2['idProducto']])){
								        $productoSel[$row2['idProducto']] = $row2['idProducto'];
								        $cantidadSel[$row2['idProducto']] += $row2['cantidad'];
                                    }else{
								        $productoSel[$row2['idProducto']] = $row2['idProducto'];
								        $cantidadSel[$row2['idProducto']] = $row2['cantidad'];
                                    }
                                }
                            }
							$query = mysqli_query($link, "SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$_POST['idTransaccionOC']}'");
							while($row = mysqli_fetch_array($query)){
								$aux++;
								$query2 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$row['idProducto']}'");
								while($row2 = mysqli_fetch_array($query2)){
									$nombreCorto = $row2['nombreCorto'];
									$query3 = mysqli_query($link, "SELECT * FROM Color WHERE idColor = '{$row2['idColor']}'");
									while($row3 = mysqli_fetch_array($query3)){
									    $color = $row3['descripcion'];
                                    }
								}
								if(!isset($cantidadSel[$row['idProducto']])){
									$cantidadSel[$row['idProducto']]=0;
                                }
                                if($cantidadSel[$row['idProducto']]<$row['cantidad']){
								    $flag = false;
                                }
								echo "<tr>
                                        <td class=\"text-center\">{$aux}</td>
                                        <td class=\"text-center\">{$row['idProducto']}</td>
                                        <td class=\"text-center\">{$nombreCorto}</td>
                                        <td class=\"text-center\">{$color}</td>
                                        <td class=\"text-center\">{$row['cantidad']}</td>
                                        <td class=\"text-center\">{$cantidadSel[$row['idProducto']]}</td>
                                        <td class='text-center'><form method='post' action='nuevaORProducto.php'>
							                <input type='hidden' name='idTransaccionOC' value='{$_POST['idTransaccionOC']}'>
							                <input type='hidden' name='idOrdenRecepcion' value='{$_POST['idOrdenRecepcion']}'>
							                <input type='hidden' name='idProducto' value='{$row['idProducto']}'>
							                <input type='hidden' name='cantidadTotal' value='{$row['cantidad']}'>
							                <input type='hidden' name='cantidadRecibida' value='{$cantidadSel[$row['idProducto']]}'>
							                <input type='submit' name='registroOR' value='Registrar Recepción' class='btn btn-primary btn-sm'>
                                        </form></td>
                                      </tr>";
							}
							?>
							</tbody>
						</table>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php

    if($flag){
	    $update = mysqli_query($link,"UPDATE Transaccion SET idEstado = '5' WHERE idTransaccion = '{$_POST['idTransaccionOC']}'");
    }

	include('footerTemplate.php');
}else{
	include('sessionError.php');
}