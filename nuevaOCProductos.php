<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
	include('adminTemplateAutocomplete.php');
	$idOC = idgen("OC");
	$proveedor = null;
	if(isset($_POST['addOC'])){
		$observacion = str_replace(array_keys($replace2),$replace2,$_POST['observaciones']);

	    $query = mysqli_query($link, "SELECT * FROM Proveedor WHERE nombre = '{$_POST['nombreProveedorNoCliente']}'");
	    while($row = mysqli_fetch_array($query)){
	        $proveedor = $row['idProveedor'];
        }

        $costoEnvio = $_POST['costoEnvio'];

		$addOC = mysqli_query($link,"INSERT INTO Transaccion VALUES ('{$_POST['idTransaccion']}','4','{$proveedor}','1','{$_SESSION['user']}',null,'{$_POST['tipoOC']}','{$dateTime}','{$_POST['fechaEstimada']}',
		'{$_POST['fechaVencimiento']}','{$costoEnvio}','{$observacion}',NULL,null,null)");

		$queryPerformed = "INSERT INTO Transaccion VALUES ({$_POST['idTransaccion']},4,{$proveedor},1,{$_SESSION['user']},null,{$_POST['tipoOC']},{$dateTime},{$_POST['fechaEstimada']},
		{$_POST['fechaVencimiento']},{$costoEnvio},{$observacion},NULL,null,null)";

		$databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','OC','{$queryPerformed}')");
	}

	if(isset($_POST['addProducto'])){
	    $notas = str_replace(array_keys($replace2),$replace2,$_POST['notas']);
	    $escalaDescuento = 0;
	    $query = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$_POST['idTransaccion']}'");
	    while($row = mysqli_fetch_array($query)){
	        $escalaDescuento = $row['escalaDescuento'];
        }
	    if(isset($_POST['checkboxDscto'])){
	        $dscto = 1;
	        $precio = $_POST['precioUnitario'] * ((100 - $escalaDescuento)/100);
        }else{
	        $dscto = 0;
	        $precio = $_POST['precioUnitario'];
        }
		$add = mysqli_query($link, "INSERT INTO TransaccionProducto VALUES ('{$_POST['idProductoAdd']}','{$_POST['idTransaccion']}',null,null,null,{$precio},{$_POST['cantidad']},
		'{$_POST['promocion']}','{$notas}',null,null,$dscto,0)");

		$queryPerformed = "INSERT INTO TransaccionProducto VALUES ({$_POST['idProductoAdd']},{$_POST['idTransaccion']},null,null,null,{$precio},{$_POST['cantidad']},
		{$_POST['promocion']},{$notas},null,null,$dscto,0)";

		$databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','Producto OC','{$queryPerformed}')");
	}

	if(isset($_POST['deleteProducto'])){
		$delete = mysqli_query($link, "DELETE FROM TransaccionProducto WHERE idProducto = '{$_POST['idProducto']}' AND idTransaccion = '{$_POST['idTransaccion']}'");

		$queryPerformed = "DELETE FROM TransaccionProducto WHERE idProducto = '{$_POST['idProducto']}' AND idTransaccion = '{$_POST['idTransaccion']}'";

		$databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','DELETE','OC-deleteProducto','{$queryPerformed}')");
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
			$row3 = 5;
			$col = 'B';
			$codigoUnique = null;

			while(($data -> val($row,$col)) !== 'Cantidad'){
				$row++;
			}

			while(($data -> val($row2,'C')) !== 'Dscto.'){
				$row2++;
			}

			$row++;
			$row2++;
			$codigoUnique = $data -> val(5,'A');
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

			$updateTransaccion = mysqli_query($link,"UPDATE Transaccion SET escalaDescuento = {$escalaDscto}, referenciaTransaccion = '{$codigoUnique}' WHERE idTransaccion = '{$_POST['idTransaccion']}'");

			while((substr($data -> val($row,$codigo),0,5)) !== 'Total'){
				echo "<tr>";
				$idProdAdd = 0;
				$producto = $data -> val($row,$codigo);
				$cant = $data -> val($row,$cantidad);
				$tipoProd = $data -> val($row,'F');
				$search = mysqli_query($link, "SELECT * FROM CatalogoProducto WHERE idCatalogoProducto = '{$producto}'");
				if (!$search) {
					echo "<script>alert('La lista de Excel contiene productos que no existen en el catálogo actual. La carga se ha interrumpido.');</script>";
				    break;
                }else{
					while($searchIndex = mysqli_fetch_array($search)){
						$idProdAdd = $searchIndex['idProducto'];
						$search2 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$idProdAdd}'");
						while($searchIndex2 = mysqli_fetch_array($search2)){
						    if($tipoProd == 1){
							    $precioUnitario = (((substr($data -> val($row,$pu),1)) * (100 - $escalaDscto)) / 100);
							    $insert = mysqli_query($link, "INSERT INTO TransaccionProducto VALUES ('{$idProdAdd}','{$_POST['idTransaccion']}',null,null,null,{$precioUnitario},{$cant},null,'Excel',null,null,TRUE,0)");
                            }else{
							    $precioUnitario = (substr($data -> val($row,$pu),1));
							    $insert = mysqli_query($link, "INSERT INTO TransaccionProducto VALUES ('{$idProdAdd}','{$_POST['idTransaccion']}',null,null,null,{$precioUnitario},{$cant},null,'Excel',null,null,FALSE,0)");
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

	$sumaTotal = 0;
	$costoEnvio = 0;

	$query = mysqli_query($link, "SELECT * FROM Transaccion WHERE idTransaccion = '{$_POST['idTransaccion']}'");
	while($row = mysqli_fetch_array($query)){
		$costoEnvio = $row['costoTransaccion'];
	}

	$query = mysqli_query($link, "SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$_POST['idTransaccion']}'");
	while($row = mysqli_fetch_array($query)){
		$valorUnitario = round($row['valorUnitario'],2);
		$total = $row['cantidad'] * $row['valorUnitario'];
		$sumaTotal += $total;
	}

	$totalOC = $sumaTotal + $costoEnvio;
	$impuesto = $totalOC * 0.02;
	$montoTotalCompra = round(($totalOC+$impuesto),2);
	?>

	<section class="container">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header card-inverse card-info">
							<div class="float-left">
								<i class="fa fa-shopping-bag"></i>
								Agregar Productos a Orden de Compra <?php echo $_POST['idTransaccion']?>
							</div>
							<div class="float-right">
								<div class="dropdown">
                                    <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#modalFile">Agregar Excel</button>
                                    <button type="submit" value="Guardar" name="addOC" class="btn btn-secondary btn-sm" form="formOC">Guardar</button>
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
                                    <form method="post" action="gestionOC.php" id="formOC">
                                        <table class="table text-center">
                                            <thead>
                                            <tr>
                                                <th class="text-center"><label for="catalogo">ID Producto Catálogo</label></th>
                                                <th class="text-center"><label for="prod">Producto</label></th>
                                                <th class="text-center"><label for="cantidad">Cantidad</label></th>
                                                <th class="text-center"><label for="prec">Precio Unitario (S/.)</label></th>
                                                <th class="text-center"><label for="promo">Promoción</label></th>
                                                <th class="text-center"><label for="notas">Notas</label></th>
                                                <th class="text-center"><label for="dscto">Descuento</label></th>
                                                <th class="text-center"><label for="addProducto">Acciones</label></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td class="text-center"><input type="text" id="catalogo" name="catalogo" class="form-control" onchange="nombreProducto(this.value,<?php echo $_POST['campana']?>);precioUnitarioCatalogo(this.value,<?php echo $_POST['campana']?>);promocionCatalogo(this.value,<?php echo $_POST['campana']?>)"></td>
                                                <td style="width: 30%" class="text-center" id="producto"><input type="text" id="prod" class="form-control"></td>
                                                <td class="text-center"><input type='hidden' name='idTransaccion' value='<?php echo $_POST['idTransaccion'];?>'>
                                                    <input type='hidden' name='costoEnvio' value='<?php echo $_POST['costoEnvio'];?>'>
                                                    <input type='hidden' name='montoTotalCompra' value='<?php echo $montoTotalCompra;?>'>
                                                    <input type='hidden' name='campana' value='<?php echo $_POST['campana'];?>'>
                                                    <input type="number" name="cantidad" class="form-control" id="cantidad" min="1"></td>
                                                <td class="text-center" id="precio"><input type="text" id="prec" class="form-control"></td>
                                                <td class="text-center" id="promocion"><input type="text" id="promo" class="form-control"></td>
                                                <td class="text-center"><input type="text" name="notas" class="form-control" id="notas"></td>
                                                <td class="text-center"><label class="custom-control custom-checkbox al">
                                                        <input type="checkbox" class="custom-control-input" name="checkboxDscto">
                                                        <span class="custom-control-indicator"></span>
                                                        <span class="custom-control-description">Sí</span>
                                                    </label></td>
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
					<th class="text-center">Precio Unitario (S/.)</th>
					<th class="text-center">Total Ítem (S/.)</th>
					<th class="text-center">Notas</th>
					<th class="text-center">Acciones</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$aux = 1;
			$sumaTotal = 0;
			$total = 0;
			$ofertaCapitalizacion = 0;
			$ofertaCapTotal = 0;
			$matPromocional = 0;
			$matPromoTotal = 0;
			$flag = 0;
			$query = mysqli_query($link, "SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$_POST['idTransaccion']}'");
			while($row = mysqli_fetch_array($query)){
				echo "<tr>";
					echo "<td>{$aux}</td>";
					$aux ++;
					$query2 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$row['idProducto']}'");
					while($row2 = mysqli_fetch_array($query2)){
						echo "<td>{$row2['idProducto']} - {$row2['nombreCorto']}</td>";
						if($row2['idTipoProducto'] == 1){
						    $flag = 1;
                        }elseif($row2['idTipoProducto'] == 2){
						    $flag = 2;
                        }else{
                            $flag = 3;
                        }
					}
					echo "<td>{$row['cantidad']}</td>";
					$valorUnitario = round($row['valorUnitario'],2);
					echo "<td>{$valorUnitario}</td>";
					if($flag == 1){
						$total = $row['cantidad'] * $row['valorUnitario'];
						$sumaTotal += $total;
                    }elseif($flag == 2){
					    $ofertaCapitalizacion = $row['cantidad'] * $row['valorUnitario'];
					    $ofertaCapTotal += $ofertaCapitalizacion;
                    }elseif($flag == 3){
                        $matPromocional = $row['cantidad'] * $row['valorUnitario'];
                        $matPromoTotal += $matPromocional;
                    }
					echo "<td>";
                    switch ($flag){
                        case 1:
                            echo round($total,2);
                            break;
                        case 2:
                            echo round($ofertaCapitalizacion,2);
                            break;
                        case 3:
                            echo round($matPromocional,2);
                            break;
                    }

                $escalaDescuento = 0;
				$search = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$_POST['idTransaccion']}'");
				while($index = mysqli_fetch_array($search)){
					$escalaDescuento = $index['escalaDescuento'];
				}

					echo "</td>";
					echo "<td>{$row['observacion']}</td>";
					echo "<td><form method='post' action='#'>
						<input type='hidden' name='idProducto' value='{$row['idProducto']}'>
						<input type='hidden' name='idTransaccion' value='{$_POST['idTransaccion']}'>
						<input type='hidden' name='costoEnvio' value='{$_POST['costoEnvio']}'>
						<input type='hidden' name='campana' value='{$_POST['campana']}'>
						<input type='hidden' name='escalaDescuento' value='{$escalaDescuento}'>
						<input type='submit' class='btn btn-warning' name='deleteProducto' value='Eliminar'>
					</form></td>";
				echo "</tr>";
			}
			?>
			</tbody>
		</table>
	</section>

    <div class="spacer30"></div>

    <section class="container">
        <div class="row">
            <div class="col-8"></div>
            <div class="col-4">
                <table class="table align-content-end">
                    <tbody>
                    <tr>
                        <th>Subtotal (S/.)</th>
                        <td><?php
                            if(isset($subTotal1)){
                                echo $subTotal1;
                            }else{
                                if(!isset($escalaDescuento)){
                                    $escalaDescuento = 0;
                                }
                                echo round(($sumaTotal) * (100/(100-$escalaDescuento)),2);
                            }
                           ?></td>
                    </tr>
                    <tr>
                        <th>Escala de Descuento (%)</th>
                        <td><?php
                            if(isset($escalaDscto)){
                                echo $escalaDscto;
                            }elseif(isset($escalaDescuento)){
                                echo $escalaDescuento;
                            }
                            ?></td>
                    </tr>
                    <tr>
                        <th>Subtotal c/Descuento (S/.)</th>
                        <td><?php
                            if(isset($subTotal2)){
                                echo $subTotal2;
                            }else{
	                            echo round(($sumaTotal),2);
                            }
                            ?></td>
                    </tr>
                    <tr>
                        <th>Of. Capitalización (S/.)</th>
                        <td><?php
                            if(isset($ofertaCapital)){
                                echo $ofertaCapital;
                            }else{
                                echo round(($ofertaCapitalizacion),2);
                            }
                            ?></td>
                    </tr>
                    <tr>
                        <th>Material Promocional (S/.)</th>
                        <td><?php
                            if(isset($materialPromocional)){
                                echo $materialPromocional;
                            }else{
                                echo round(($matPromocional),2);
                            }
                            ?></td>
                    </tr>
                    <tr>
                        <th>Costo de Envío (S/.)</th>
                        <td><?php
                            if(isset($flete)){
                                echo $flete;
                            }else{
                                echo $_POST['costoEnvio'];
                            }
                            ?></td>
                    </tr>
                    <tr>
                        <th>Precio Venta (S/.)</th>
                        <td><?php
                            if(isset($precioVenta)){
                                echo $precioVenta;
                            }else{
                                echo round((($sumaTotal)+($ofertaCapitalizacion)+($matPromocional)+($_POST['costoEnvio'])),2);
                            }
                            ?></td>
                    </tr>
                    <tr>
                        <th>Percepción RS.261-2005 SUNAT 2% (S/.)</th>
                        <td><?php
                            if(isset($percepcionSUNAT)){
                                echo $percepcionSUNAT;
                            }else{
                                echo round(($sumaTotal+($ofertaCapitalizacion)+($matPromocional)+($_POST['costoEnvio']))*0.02,2);
                            }
                            ?></td>
                    </tr>
                    <tr>
                        <th>Total a Pagar (S/.)</th>
                        <td><?php
                            if(isset($totalPagar)){
                                echo $totalPagar;
                            }else{
                                echo round(((($sumaTotal)+($ofertaCapitalizacion)+($matPromocional)+($_POST['costoEnvio']))*0.02)+(($sumaTotal)+($ofertaCapitalizacion)+($matPromocional)+($_POST['costoEnvio'])),2);
                            }
                            ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
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
                                <input type="hidden" name="costoEnvio" value="<?php echo $_POST['costoEnvio'];?>"/>
                                <input type="hidden" name="idTransaccion" value="<?php echo $_POST['idTransaccion'];?>"/>
                                <input type="hidden" name="campana" value="<?php echo $_POST['campana'];?>"/>
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