<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
	include('adminTemplateAutocomplete.php');

	$idOCP = idgen("OCP");
	$proveedor = null;
	if(!isset($_POST['recProd'])){
	    if(isset($_GET['idTransaccionRel'])){
		    $recProd = mysqli_query($link,"INSERT INTO Transaccion VALUES ('{$idOCP}','4','Proveedor1','1','{$_SESSION['user']}', null, null,'{$dateTime}', '{$_GET['idTransaccionRel']}', null, null, null, NULL, null, null)");
		    $queryPerformed = "INSERT INTO Transaccion VALUES ({$idOCP},4,Proveedor1,1,{$_SESSION['user']}, null, null,{$dateTime}, OC - Premios, null, null, null, null, null, null)";
		    $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','OCP','{$queryPerformed}')");
        }else{
		    $recProd = mysqli_query($link,"INSERT INTO Transaccion VALUES ('{$idOCP}','4','Proveedor1','1','{$_SESSION['user']}', null, null,'{$dateTime}', 'OC - Premios', null, null, null, NULL, null, null)");
		    $queryPerformed = "INSERT INTO Transaccion VALUES ({$idOCP},4,Proveedor1,1,{$_SESSION['user']}, null, null,{$dateTime}, OC - Premios, null, null, null, null, null, null)";
		    $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','OCP','{$queryPerformed}')");
        }
	}

	if(isset($_POST['addProducto'])){
		$escalaDescuento = 0;
		$query = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$_POST['idTransaccion']}'");
		$add = mysqli_query($link, "INSERT INTO TransaccionProducto VALUES ('{$_POST['idProductoAdd']}','{$_POST['idTransaccion']}',null,null,null,0,{$_POST['cantidad']},
		null,'{$_POST['notas']}',null,null,null,0)");
		$queryPerformed = "INSERT INTO TransaccionProducto VALUES ({$_POST['idProductoAdd']},{$_POST['idTransaccion']},null,null,null,0,{$_POST['cantidad']},
		null,{$_POST['notas']},null,null,null,0)";
		$databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','OCP-addProducto','{$queryPerformed}')");
	}

	if(isset($_POST['deleteProducto'])){
		$delete = mysqli_query($link, "DELETE FROM TransaccionProducto WHERE idProducto = '{$_POST['idProducto']}' AND idTransaccion = '{$_POST['idTransaccion']}'");
		$queryPerformed = "DELETE FROM TransaccionProducto WHERE idProducto = {$_POST['idProducto']} AND idTransaccion = {$_POST['idTransaccion']}";
		$databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','DELETE','OCP-deleteProducto','{$queryPerformed}')");
	}

	if(isset($_FILES['documento']['name'])){
		$valid_file = false;
		if($_FILES['documento']['name'])
		{
			$valid_file = true;
			//if no errors...
			if(!$_FILES['documento']['error'])
			{
				//now is the time to modify the future file name and validate the file
				$new_file_name = 'Excel.xls'; //rename file
				if($_FILES['documento']['size'] > (3072000)) //can't be larger than 1 MB
				{
					$valid_file = false;
					$message = 'El archivo seleccionado es demasiado grande!.';
				}

				//if the file has passed the test
				if($valid_file)
				{
					//move it to where we want it to be
					move_uploaded_file($_FILES['documento']['tmp_name'], 'uploads/'.$new_file_name);
					$message = 'Archivo subido correctamente';
				}
			}
			//if there is an error...
			else
			{
				$valid_file = false;
				//set that to be the returned message
				$message = 'La subida del archivo devolvió el siguiente error:  '.$_FILES['documento']['error'];
			}
		}

		if($valid_file){
			require_once 'excel_reader2.php';
			$path = "uploads/Excel.xls";
			$data = new Spreadsheet_Excel_Reader($path,false);

			$row = 1;
			$row2 = 10;
			$col = 'B';

			while(($data -> val($row,$col)) !== 'Cantidad'){
				$row++;
			}

			while(($data -> val($row2,'C')) !== 'Dscto.'){
				$row2++;
			}

			$row++;
			$row2++;
			$escalaDscto = substr($data -> val($row2,'C'),0,2);
			$subTotal1 = substr($data -> val($row2,'B'),1);
			$subTotal2 = substr($data -> val($row2,'D'),1);
			$ofertaCapital = substr($data -> val($row2+1,'D'),1);
			$materialPromocional = substr($data -> val($row2+2,'D'),1);
			$subTotal3 = substr($data -> val($row2+3,'D'),1);
			$flete = substr($data -> val($row2+4,'D'),1);
			$precioVenta = substr($data -> val($row2+5,'D'),1);
			$percepcionSUNAT= substr($data -> val($row2+6,'D'),1);
			$totalPagar = substr($data -> val($row2+7,'D'),1);
			$codigo = 'A';
			$cantidad = 'B';
			$descripcion = 'C';
			$pu = 'D';

			$updateTransaccion = mysqli_query($link,"UPDATE Transaccion SET escalaDescuento = {$escalaDscto} WHERE idTransaccion = '{$_POST['idTransaccion']}'");

			while((substr($data -> val($row,$codigo),0,5)) !== 'Total'){
				echo "<tr>";
				$idProdAdd = 0;
				$producto = $data -> val($row,$codigo);
				$cant = $data -> val($row,$cantidad);
				$search = mysqli_query($link, "SELECT * FROM CatalogoProducto WHERE idCatalogoProducto = '{$producto}'");
				if (!$search) {
					echo "<script>alert('La lista de Excel contiene productos que no existen en el catálogo actual. La carga se ha interrumpido.');</script>";
					break;
				}else{
					while($searchIndex = mysqli_fetch_array($search)){
						$idProdAdd = $searchIndex['idProducto'];
						$search2 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$idProdAdd}'");
						while($searchIndex2 = mysqli_fetch_array($search2)){
							if($searchIndex2['idTipoProducto'] == 1){
								$precioUnitario = (((substr($data -> val($row,$pu),1)) * (100 - $escalaDscto)) / 100);
								$insert = mysqli_query($link, "INSERT INTO TransaccionProducto VALUES ('{$idProdAdd}','{$_POST['idTransaccion']}',null,null,null,{$precioUnitario},{$cant},null,'Excel',null,null,TRUE)");
							}else{
								$precioUnitario = (substr($data -> val($row,$pu),1));
								$insert = mysqli_query($link, "INSERT INTO TransaccionProducto VALUES ('{$idProdAdd}','{$_POST['idTransaccion']}',null,null,null,{$precioUnitario},{$cant},null,'Excel',null,null,FALSE)");
							}
						}
					}
					//echo "INSERT INTO TransaccionProducto VALUES ('{$idProdAdd}','{$_POST['idTransaccion']}',null,null,null,{$precioUnitario},{$cant},null,'Excel',null,null,null)";
					//echo "<br>";
					$row++;
				}
			}
		}
	}
	?>

	<section class="container">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header card-inverse card-info">
						<div class="float-left">
							<i class="fa fa-shopping-bag"></i>
							Agregar Productos a Orden de Compra Premios
						</div>
						<div class="float-right">
							<div class="dropdown">
								<button type="submit" value="Guardar" name="recProd" class="btn btn-secondary btn-sm" form="formRecProd">Guardar</button>
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
									<form method="post" action="gestionOC.php" id="formRecProd">
										<table class="table text-center">
											<thead>
											<tr>
												<th class="text-center"><label for="sku">ID Producto</label></th>
												<th class="text-center"><label for="prod">Producto</label></th>
												<th class="text-center"><label for="cantidad">Cantidad</label></th>
												<th class="text-center"><label for="prec">Precio Unitario (S/.)</label></th>
												<th class="text-center"><label for="notas">Notas</label></th>
												<th class="text-center"><label for="addProducto">Acciones</label></th>
											</tr>
											</thead>
											<tbody>
											<tr>
												<td class="text-center"><input type="text" id="sku" name="sku" class="form-control" onchange="nombreProductoSKU(this.value)"></td>
												<td style="width: 30%" class="text-center" id="producto"><input type="text" id="prod" class="form-control"></td>
												<td class="text-center"><input type='hidden' name='idTransaccion' value='<?php if(isset($_POST['idTransaccion'])){echo $_POST['idTransaccion'];}else{echo $idOCP;}?>'>
													<input type='hidden' name='recProd' value='<?php if(isset($_POST['idTransaccion'])){echo $_POST['idTransaccion'];}else{echo $idOCP;}?>'>
													<input type="number" name="cantidad" class="form-control" id="cantidad" min="1"></td>
												<td class="text-center" id="precio"><input type="text" id="prec" class="form-control" value="0" readonly></td>
												<td class="text-center"><input type="text" name="notas" class="form-control" id="notas"></td>
												<td class="text-center"><input type="submit" class="btn btn-primary" value="Agregar" name="addProducto" id="addProducto" formaction="#"></td>
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

	<div class="spacer30"></div>

	<section class="container">
		<table class="table text-center">
			<thead>
			<tr>
				<th class="text-center">Ítem Nro.</th>
				<th class="text-center">Descripción</th>
				<th class="text-center">Cantidad</th>
				<th class="text-center">Notas</th>
				<th class="text-center">Acciones</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$aux = 1;
			if(isset($_POST['recProd'])){
				$query = mysqli_query($link, "SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$_POST['idTransaccion']}'");
				while($row = mysqli_fetch_array($query)){
					echo "<tr>";
					echo "<td>{$aux}</td>";
					$aux ++;
					$query2 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$row['idProducto']}'");
					while($row2 = mysqli_fetch_array($query2)){
						echo "<td>{$row2['idProducto']} - {$row2['nombreCorto']}</td>";
					}
					echo "<td>{$row['cantidad']}</td>";
					echo "<td>{$row['observacion']}</td>";
					echo "<td><form method='post' action='#'>
						<input type='hidden' name='idProducto' value='{$row['idProducto']}'>
						<input type='hidden' name='idTransaccion' value='{$_POST['idTransaccion']}'>
						<input type='hidden' name='recProd' value='{$_POST['idTransaccion']}'>
						<input type='submit' class='btn btn-warning' name='deleteProducto' value='Eliminar'>
					</form></td>";
					echo "</tr>";
				}
			}
			?>
			</tbody>
		</table>
	</section>

	<div class="modal fade" id="modalFile" tabindex="-1" role="dialog" aria-labelledby="modalFile" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Subir documento Excel</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="container-fluid">
						<form id="formExcel" method="post" action="#" enctype="multipart/form-data">
							<div class="form-group row">
								<input type="hidden" name="idTransaccion" value="<?php echo $_POST['idTransaccion'];?>"/>
								<label class="col-form-label" for="documento">Archivo:</label>
								<input type="file" name="documento" id="documento" class="form-control"/>
							</div>
						</form>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" form="formExcel" value="Submit" name="addProductos">Aceptar</button>
				</div>
			</div>
		</div>
	</div>

	<?php
	include('footerTemplate.php');
}else{
	include('sessionError.php');
}
