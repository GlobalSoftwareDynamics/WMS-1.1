<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
	include('adminTemplateAutocomplete.php');


	if(isset($_POST['addPS'])){

		$id=mysqli_query($link,"SELECT * FROM Proveedor WHERE nombre = '{$_POST['nombreProveedorNoCliente']}'");
		while ($fila=mysqli_fetch_array($id)){
			$idProveedor = $fila['idProveedor'];
		}

		$addOV = mysqli_query($link,"INSERT INTO Transaccion VALUES ('{$_POST['idTransaccion']}',4,'{$idProveedor}',6,'{$_SESSION['user']}',null,null,'{$dateTime}',null,
		'{$_POST['fechaVencimiento']}',null,'{$_POST['observaciones']}',NULL,NULL,NULL)");

		$queryPerformed = "INSERT INTO Transaccion VALUES ({$_POST['idTransaccion']},4,{$idProveedor},6,{$_SESSION['user']},null,null,{$dateTime},null,
		{$_POST['fechaVencimiento']},null,{$_POST['observaciones']},NULL,NULL,NULL)";

		$databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','Prestamo','{$queryPerformed}')");
	}

	if(isset($_POST['addProducto'])){

		$nombreProducto = explode("_",$_POST['producto']);
		$id=mysqli_query($link,"SELECT * FROM Producto WHERE nombreCorto = '{$nombreProducto[0]}'");
		while ($fila=mysqli_fetch_array($id)){
			$idProducto = $fila['idProducto'];
		}

		$stockinicial=0;
		$stock=mysqli_query($link,"SELECT * FROM UbicacionProducto WHERE idProducto = '{$idProducto}'");
		while ($fila=mysqli_fetch_array($stock)){
			$stockinicial=$stockinicial+$fila['stock'];
		}
		$stockfinal=$stockinicial-$_POST['cantidad'];

		$promo="Sin Promoción";
		switch ($_POST['promo']){
			case 1:
				$promo="NA";
				break;
			case 0.5:
				$promo="2x1";
				break;
			case 0.666667:
				$promo="6x4";
				break;
			case 0.75:
				$promo="4x3";
				break;
		}

		if($_POST['notas'] != null){
			$_POST['notas']="{$_POST['notas']} (Promoción: {$promo}, Descuento: {$_POST['promocion']}%)";
		}elseif(!empty($_POST['promocion'])){
			$_POST['notas']="Promoción: {$promo}, Descuento: {$_POST['promocion']}%";
		}elseif(empty($_POST['promocion'])){
			$_POST['notas']="Promoción: {$promo}";
		}else{
			$_POST['notas']=null;
		}

		$precio=$_POST['precio']*$_POST['promo'];

		$add = mysqli_query($link, "INSERT INTO TransaccionProducto VALUES ('{$idProducto}','{$_POST['idTransaccion']}',null,null,null,'{$precio}','{$_POST['cantidad']}',
		null,'{$_POST['notas']}','{$stockinicial}','{$stockfinal}',false,0)");

		$queryPerformed = "INSERT INTO TransaccionProducto VALUES ({$idProducto},{$_POST['idTransaccion']},null,null,null,{$precio},{$_POST['cantidad']},
		null,{$_POST['notas']},{$stockinicial},{$stockfinal},false,0)";

		$databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','Préstamos-addProducto','{$queryPerformed}')");

	}

	if(isset($_POST['deleteProducto'])){
		$delete = mysqli_query($link, "DELETE FROM TransaccionProducto WHERE idProducto = '{$_POST['idProducto']}' AND idTransaccion = '{$_POST['idTransaccion']}'");

		$queryPerformed = "DELETE FROM TransaccionProducto WHERE idProducto = '{$_POST['idProducto']}' AND idTransaccion = '{$_POST['idTransaccion']}'";

		$databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','DELETE','Préstamo-deleteProducto','{$queryPerformed}')");
	}
	?>

    <section class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-inverse card-info">
                        <div class="float-left">
                            <i class="fa fa-shopping-cart"></i>
                            Agregar Productos a Préstamo <?php echo $_POST['idTransaccion']?>
                        </div>
                        <div class="float-right">
                            <div class="dropdown">
                                <button form="formPS" name="formPS" class="btn btn-secondary btn-sm">Guardar</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="col-12">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#general" role="tab">Productos</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="general" role="tabpanel">
                                    <div class="spacer30"></div>
                                    <form method="post" action="nuevoPrestamo_Ubicaciones.php" id="formPS">
                                        <input type='hidden' name='idTransaccion' value='<?php echo $_POST['idTransaccion'];?>'>
                                        <table class="table text-center">
                                            <thead>
                                            <tr>
                                                <th class="text-center" style="width: 30%"><label for="Productos">Producto</label></th>
                                                <th class="text-center" style="width: 15%"><label for="cantidad">Cantidad</label></th>
                                                <th class="text-center" style="width: 25%"><label for="precio">Precio Unitario (S/.)</label></th>
                                                <th class="text-center" style="width: 15%"><label for="descento">Promoción</label></th>
                                                <th class="text-center" style="width: 15%"><label for="notas">Notas</label></th>
                                                <th class="text-center" style="width: 10%"><label for="addProducto">Acciones</label></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td><input type="text" class="form-control" name="producto" id="Productos" onchange="getcantidadprod(this.value);getprecioprom(this.value)"></td>
                                                <td id="maxcantidad"><input type="number" min="0" name="cantidad" class="form-control" id="cantidad"></td>
                                                <td id="precioprom"><input type="text" name="precio" class="form-control" id="precio"></td>
                                                <td>
                                                    <select id="descento" class="form-control" name="promo">
                                                        <option value="1">Sin Promoción</option>
                                                        <option value="0.5">2X1</option>
                                                        <option value="0.75">4X3</option>
                                                        <option value="0.666667">6X4</option>
                                                    </select>
                                                </td>
                                                <td><input type="text" name="notas" class="form-control" id="notas"></td>
                                                <td><input type="submit" class="btn btn-primary" value="Agregar" name="addProducto" id="addProducto" formaction="#"></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="spacer50"></div>

    <section class="container">
        <table class="table text-center">
            <thead>
            <tr>
                <th class="text-center">Ítem Nro.</th>
                <th class="text-center">Descripción</th>
                <th class="text-center">Cantidad</th>
                <th class="text-center">Precio Unitario</th>
                <th class="text-center">Notas</th>
                <th class="text-center">Acciones</th>
            </tr>
            </thead>
            <tbody>
			<?php
			$aux = 1;
			$query = mysqli_query($link, "SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$_POST['idTransaccion']}'");
			while($row = mysqli_fetch_array($query)){
				echo "<tr>";
				echo "<td>{$aux}</td>";
				$aux ++;
				$query2 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$row['idProducto']}'");
				while($row2 = mysqli_fetch_array($query2)){
					echo "<td>{$row2['idProducto']} - {$row2['nombreCorto']}</td>";
				}
				$valor=round($row['valorUnitario'],2);
				echo "<td>{$row['cantidad']}</td>";
				echo "<td>S/. {$valor}</td>";
				echo "<td>{$row['observacion']}</td>";
				echo "<td><form method='post' action='#'>
						<input type='hidden' name='idProducto' value='{$row['idProducto']}'>
						<input type='hidden' name='idTransaccion' value='{$_POST['idTransaccion']}'>
						<input type='submit' class='btn btn-warning' name='deleteProducto' value='Eliminar'>
					</form></td>";
				echo "</tr>";
			}
			?>
            </tbody>
        </table>
    </section>

	<?php
	include('footerTemplate.php');
}else{
	include('sessionError.php');
}
?>