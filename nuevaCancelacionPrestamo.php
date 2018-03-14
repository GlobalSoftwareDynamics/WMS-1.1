<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
	include('adminTemplate.php');
	include('funciones.php');


	if(isset($_POST['cancelarPrestamoDevolucion'])){
	    $idPago = idgen("CPD");
	    $stockTotal = 0;
	    $search = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$_POST['idTransaccion']}'");
	    while($searchIndex = mysqli_fetch_array($search)){
	        $idDeudor = $searchIndex['idProveedor'];
	        $montoTotal = $searchIndex['montoTotal'];
	        $montoRestante = $searchIndex['montoRestante'];
        }
        $search = mysqli_query($link, "SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$_POST['idTransaccion']}' AND idProducto = '{$_POST['idProductoSelect']}'");
	    while($searchIndex = mysqli_fetch_array($search)){
	        $valorUnitarioPrestamo = $searchIndex['valorUnitario'];
	        $cantidadPrestamo = $searchIndex['cantidad'];
        }

		$stockTotal = 0;

		$select = mysqli_query($link,"SELECT * FROM UbicacionProducto WHERE idProducto = '{$_POST['idProductoSelect']}'");
		while($row = mysqli_fetch_array($select)){
			$stockTotal += $row['stock'];
		}

		$stockUbicacion = 0;
		$flagUbicacion = false;

		$select = mysqli_query($link, "SELECT * FROM UbicacionProducto WHERE idProducto = '{$_POST['idProductoSelect']}' AND idUbicacion = '{$_POST['ubicacionAlmacen']}'");
		while($row = mysqli_fetch_array($select)){
		    $stockUbicacion += $row['stock'];
		    $flagUbicacion = true;
        }

        $stockUbicacion += $_POST['cantidadDevuelta'];
		$stockFinal = $stockTotal + $_POST['cantidadDevuelta'];
		$montoRestante -= ($_POST['cantidadDevuelta'] * $valorUnitarioPrestamo);

	    $insert = mysqli_query($link,"INSERT INTO Transaccion VALUES ('{$idPago}',5,'{$idDeudor}',2,'{$_SESSION['user']}',null,null,'{$dateTime}',null,null,0,'{$_POST['idProductoSelect']}','{$_POST['idTransaccion']}',null,null)");
	    $insert = mysqli_query($link,"INSERT INTO TransaccionProducto VALUES ('{$_POST['idProductoSelect']}','{$idPago}',null,'{$_POST['ubicacionAlmacen']}',null,
                  {$valorUnitarioPrestamo},{$_POST['cantidadDevuelta']},null,'{$_POST['idProductoSelect']}',{$stockTotal},{$stockFinal},null,0)");
	    $update = mysqli_query($link,"UPDATE Transaccion SET montoRestante = {$montoRestante} WHERE idTransaccion = '{$_POST['idTransaccion']}'");
	    if($flagUbicacion){
		    $update = mysqli_query($link, "UPDATE UbicacionProducto SET stock = '{$stockUbicacion}', fechaModificacion = '{$date}' WHERE idProducto = '{$_POST['idProductoSelect']}' AND idUbicacion = '{$_POST['ubicacionAlmacen']}'");
        }else{
	        $insert = mysqli_query($link, "INSERT INTO UbicacionProducto VALUES('{$_POST['idProductoSelect']}', '{$_POST['ubicacionAlmacen']}', {$stockUbicacion},'{$dateTime}')");
        }

    }

	$fechaTransaccion = array();
	$result = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$_POST['idTransaccion']}'");
	while($row = mysqli_fetch_array($result)) {
		$fechaTransaccion = explode(" ", $row['fechaTransaccion']);
		$result2 = mysqli_query($link, "SELECT * FROM Proveedor WHERE idProveedor = '{$row['idProveedor']}'");
		while ($row2 = mysqli_fetch_array($result2)) {
			$proveedor = $row2['nombre'];
		}
		$fechaVencimiento = $row['fechaVencimiento'];
		$observacion = $row['observacion'];
		$montoRestanteTransaccion = $row['montoRestante'];
	}

	if(isset($_POST['cancelarPrestamoPago'])){
		$idPago = idgen("CPP");
		$idMovimiento = idgen("MOV");

	    if(($_POST['productoSelect']) != ''){
		    $arrayProducto = explode("_",$_POST['productoSelect']);
		    $nombreCorto = $arrayProducto[0];
		    $idProductoPago = null;
		    $stockTotal = 0;

		    $search = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$_POST['idTransaccion']}'");
		    while($searchIndex = mysqli_fetch_array($search)){
			    $idDeudor = $searchIndex['idProveedor'];
			    $montoTotal = $searchIndex['montoTotal'];
			    $montoRestante = $searchIndex['montoRestante'];
		    }

		    $select = mysqli_query($link,"SELECT * FROM Producto WHERE nombreCorto = '{$nombreCorto}'");
		    while($row = mysqli_fetch_array($select)){
			    $idProductoPago = $row['idProducto'];
		    }

		    $select = mysqli_query($link,"SELECT * FROM UbicacionProducto WHERE idProducto = '{$idProductoPago}'");
		    while($row = mysqli_fetch_array($select)){
			    $stockTotal += $row['stock'];
		    }

		    $stockFinal = $stockTotal + $_POST['cantidadProducto'];

		    $stockUbicacion = 0;
		    $flagUbicacion2 = false;

		    $select = mysqli_query($link, "SELECT * FROM UbicacionProducto WHERE idProducto = '{$idProductoPago}' AND idUbicacion = '{$_POST['ubicacionAlmacen']}'");
		    while($row = mysqli_fetch_array($select)){
			    $stockUbicacion += $row['stock'];
			    $flagUbicacion2 = true;
		    }

		    $stockUbicacion += $_POST['cantidadProducto'];
		    $valorPago = $_POST['cantidadProducto'] * $_POST['precio'];
		    $montoRestanteFinal = $montoRestante - $valorPago;

		    if($flagUbicacion2){
			    $update = mysqli_query($link, "UPDATE UbicacionProducto SET stock = '{$stockUbicacion}', fechaModificacion = '{$dateTime}' WHERE idProducto = '{$idProductoPago}' AND idUbicacion = '{$_POST['ubicacionAlmacen']}'");
		    }else{
			    $insert = mysqli_query($link, "INSERT INTO UbicacionProducto VALUES('{$idProductoPago}', '{$_POST['ubicacionAlmacen']}', {$stockUbicacion},'{$dateTime}')");
		    }
	        $insert = mysqli_query($link,"INSERT INTO Transaccion VALUES ('{$idPago}',5,'{$idDeudor}',2,'{$_SESSION['user']}',null,null,'{$dateTime}',null,null,0,'{$idProductoPago}','{$_POST['idTransaccion']}',null,null)");

	        $insert = mysqli_query($link,"INSERT INTO TransaccionProducto VALUES ('{$idProductoPago}','{$idPago}',null,'{$_POST['ubicacionAlmacen']}',null,{$_POST['precio']},{$_POST['cantidadProducto']},null,
                                                '{$idProductoPago}',{$stockTotal},{$stockFinal},null,0)");
	        $update = mysqli_query($link,"UPDATE Transaccion SET montoRestante = '{$montoRestanteFinal}' WHERE idTransaccion = '{$_POST['idTransaccion']}'");
        }

		if(($_POST['montoEfectivo']) != ''){
	        $saldoCuenta = 0;
	        $montoRestante = 0;
			$search = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$_POST['idTransaccion']}'");
			while($searchIndex = mysqli_fetch_array($search)){
				$idDeudor = $searchIndex['idProveedor'];
				$montoTotal = $searchIndex['montoTotal'];
				$montoRestante = $searchIndex['montoRestante'];
			}

			$insert = mysqli_query($link,"INSERT INTO Movimiento VALUES('{$idMovimiento}',1,'{$idDeudor}',null,'{$_POST['idTransaccion']}','{$_POST['medioPago']}',null,8,'{$_SESSION['user']}','{$dateTime}',null,'{$_POST['montoEfectivo']}','Pago Prestamo')");
			$montoRestanteFinal = $montoRestante - $_POST['montoEfectivo'];
			$update = mysqli_query($link,"UPDATE Transaccion SET montoRestante = '{$montoRestanteFinal}' WHERE idTransaccion = '{$_POST['idTransaccion']}'");
			$search = mysqli_query($link, "SELECT * FROM Cuenta WHERE idCuenta = 1");
			while($searchIndex = mysqli_fetch_array($search)){
			    $saldoCuenta = $searchIndex['saldo'];
            }
            $saldoFinal = $saldoCuenta + $_POST['montoEfectivo'];
			$update = mysqli_query($link, "UPDATE Cuenta SET saldo = '{$saldoFinal}', fechaActualizacion = '{$date}' WHERE idCuenta = 1");

			$logsaldo = mysqli_query($link,"SELECT * FROM LogSaldos WHERE idCuenta = 1 AND fecha = '{$date}'");
			$numrows=mysqli_num_rows($logsaldo);
			if($numrows>0){
				while ($filax=mysqli_fetch_array($logsaldo)){
					$query = mysqli_query($link,"UPDATE LogSaldos SET saldo = {$saldoFinal} WHERE idLogSaldos = '{$filax['idLogSaldos']}'");
					$queryPerformed="UPDATE LogSaldos SET saldo = {$saldoFinal} WHERE idLogSaldos = {$filax['idLogSaldos']}";
					$databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','UPDATE','LogSaldos','{$queryPerformed}')");
				}
			}else{
				$query = mysqli_query($link,"INSERT INTO LogSaldos(idCuenta,saldo,fecha) VALUES(1,'{$saldoFinal}','{$date}')");
				$queryPerformed="INSERT INTO LogSaldos(idCuenta,saldo,fecha) VALUES(1,{$saldoFinal},{$date})";
				$databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','LogSaldos','{$queryPerformed}')");
			}
		}

		if(($_POST['montoCuenta']) != ''){
			$saldoCuenta = 0;
			$montoRestante = 0;
			$search = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$_POST['idTransaccion']}'");
			while($searchIndex = mysqli_fetch_array($search)){
				$idDeudor = $searchIndex['idProveedor'];
				$montoTotal = $searchIndex['montoTotal'];
				$montoRestante = $searchIndex['montoRestante'];
			}

			$insert = mysqli_query($link,"INSERT INTO Movimiento VALUES('{$idMovimiento}','{$_POST['ctaBanco']}','{$idDeudor}',null,'{$_POST['idTransaccion']}','4',null,8,'{$_SESSION['user']}','{$dateTime}',null,'{$_POST['montoCuenta']}','Pago Prestamo')");
			$montoRestanteFinal = $montoRestante - $_POST['montoCuenta'];
			$update = mysqli_query($link,"UPDATE Transaccion SET montoRestante = '{$montoRestanteFinal}' WHERE idTransaccion = '{$_POST['idTransaccion']}'");
			$search = mysqli_query($link, "SELECT * FROM Cuenta WHERE idCuenta = '{$_POST['ctaBanco']}'");
			while($searchIndex = mysqli_fetch_array($search)){
				$saldoCuenta = $searchIndex['saldo'];
			}
			$saldoFinal = $saldoCuenta + $_POST['montoCuenta'];
			$update = mysqli_query($link, "UPDATE Cuenta SET saldo = '{$saldoFinal}', fechaActualizacion = '{$date}' WHERE idCuenta = '{$_POST['ctaBanco']}'");

			$logsaldo = mysqli_query($link,"SELECT * FROM LogSaldos WHERE idCuenta = '{$_POST['ctaBanco']}' AND fecha = '{$date}'");
			$numrows=mysqli_num_rows($logsaldo);
			if($numrows>0){
				while ($filax=mysqli_fetch_array($logsaldo)){
					$query = mysqli_query($link,"UPDATE LogSaldos SET saldo = {$saldoFinal} WHERE idLogSaldos = '{$filax['idLogSaldos']}'");
					$queryPerformed="UPDATE LogSaldos SET saldo = {$saldoFinal} WHERE idLogSaldos = {$filax['idLogSaldos']}";
					$databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','UPDATE','LogSaldos','{$queryPerformed}')");
				}
			}else{
				$query = mysqli_query($link,"INSERT INTO LogSaldos(idCuenta,saldo,fecha) VALUES('{$_POST['ctaBanco']}','{$saldoFinal}','{$date}')");
				$queryPerformed="INSERT INTO LogSaldos(idCuenta,saldo,fecha) VALUES(1,{$saldoFinal},{$date})";
				$databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','LogSaldos','{$queryPerformed}')");
			}
		}
	}
		?>
        <section class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-inverse card-info">
                            <form method="post" action="nuevaCancelacionPrestamoPago.php" id="formOC">
                                <div class="float-left">
                                    <i class="fa fa-shopping-bag"></i>
                                    Cancelación de Préstamo
                                </div>
                                <div class="float-right">
                                    <input type='hidden' name='idTransaccion' value='<?php echo $_POST['idTransaccion']?>'>
                                    <input type="submit" value="Registrar Pago" name="cancelarPrestamo" class="btn btn-secondary btn-sm">
                                    <input type="submit" value="Volver" name="volver" class="btn btn-secondary btn-sm" formaction="gestionPrestamos.php">
                                </div>
                            </form>
                        </div>
                        <div class="card-block">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-3"><p><b>Número de Orden:</b></p></div>
                                    <div class="col-9"><p><?php echo $_POST['idTransaccion']; ?></p></div>
                                </div>
                                <div class="row">
                                    <div class="col-3"><p><b>Deudor:</b></p></div>
                                    <div class="col-9"><p><?php echo $proveedor; ?></p></div>
                                </div>
                                <div class="row">
                                    <div class="col-3"><p><b>Fecha de Préstamo:</b></p></div>
                                    <div class="col-9"><p><?php echo $fechaTransaccion[0]; ?></p></div>
                                </div>
                                <div class="row">
                                    <div class="col-3"><p><b>Fecha de Vencimiento:</b></p></div>
                                    <div class="col-9"><p><?php echo $fechaVencimiento; ?></p></div>
                                </div>
                                <div class="row">
                                    <div class="col-3"><p><b>Observaciones:</b></p></div>
                                    <div class="col-9"><p><?php echo $observacion; ?></p></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="spacer30"></div>

        <section class="container">
        <div class="row">
        <div class="col-12">
        <div class="card">
        <div class="card-header card-inverse card-info">
            <form method="post" action="gestionOC.php" id="formOC">
                <div class="float-left">
                    <i class="fa fa-shopping-bag"></i>
                    Listado de Productos
                </div>
            </form>
        </div>
        <div class="card-block">
        <div class="col-12">
        <table class="table">
        <thead>
        <tr>
            <th class="text-center">Ítem</th>
            <th class="text-center">Descripción</th>
            <th class="text-center">Cantidad</th>
            <th class="text-center">P.U. Promedio (S/.)</th>
            <th class="text-center">Total (S/.)</th>
        </tr>
        </thead>
        <tbody>
		<?php

	$aux = 1;
	$sumaTotal = 0;
	$query = mysqli_query($link, "SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$_POST['idTransaccion']}'");
	while($row = mysqli_fetch_array($query)){
		$cantidadDevueltaTotal = 0;
		echo "<tr>";
		echo "<td class='text-center'>{$aux}</td>";
		$aux ++;
		$query2 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$row['idProducto']}'");
		while($row2 = mysqli_fetch_array($query2)){
			echo "<td class='text-center'>{$row2['idProducto']} - {$row2['nombreCorto']}</td>";
		}
		echo "<td class='text-center'>{$row['cantidad']}</td>";
		echo "<td class='text-center'>S/. {$row['valorUnitario']}</td>";
		$total = $row['cantidad'] * $row['valorUnitario'];
		$sumaTotal += $total;
		echo "<td class='text-center'>S/. {$total}</td>";
		$review2 = mysqli_query($link,"SELECT * FROM Transaccion WHERE referenciaTransaccion = '{$_POST['idTransaccion']}'");
		while($reviewIndex2 = mysqli_fetch_array($review2)){
			$review3 = mysqli_query($link, "SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$reviewIndex2['idTransaccion']}' AND idProducto = '{$row['idProducto']}'");
			while($reviewIndex3 = mysqli_fetch_array($review3)){
				$cantidadDevueltaTotal += $reviewIndex3['cantidad'];
			}
		}
		if($row['cantidad'] > $cantidadDevueltaTotal){
			echo "
                                                                <td class='text-center'><form method='post' action='nuevaCancelacionPrestamoProducto.php'>
                                                                    <input type='hidden' name='idTransaccion' value='{$_POST['idTransaccion']}'>
                                                                    <input type='hidden' name='idProducto' value='{$row['idProducto']}'>
                                                                    <input type='submit' name='registroCancelacion' value='Registrar Devolución' class='btn btn-primary btn-sm'>
                                                                </form></td>";
		}
		echo "</tr>";
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

    <div class="spacer30"></div>

    <section class="container">
        <div class="row">
            <div class="col-8"></div>
            <div class="col-4">
                <table class="table align-content-end">
                    <tbody>
                    <tr>
                        <th>Total (S/.)</th>
                        <td>S/. <?php echo $sumaTotal;?></td>
                    </tr>

                    <?php
                    $search = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$_POST['idTransaccion']}'");
                    while($searchIndex = mysqli_fetch_array($search)){
	                    $montoRestanteShow = $searchIndex['montoRestante'];
                    }
                    ?>

                    <tr>
                        <th>Restante (S/.)</th>
                        <td>S/. <?php echo $montoRestanteShow;?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

	<?php

    include('footerTemplate.php');
}else{
	include('sessionError.php');
}