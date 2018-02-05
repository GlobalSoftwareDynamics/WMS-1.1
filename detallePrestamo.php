<?php
include('declaracionFechas.php');
include 'WebClientPrint.php';
use Neodynamic\SDK\Web\WebClientPrint;
use Neodynamic\SDK\Web\Utils;

if(substr($_POST['idTransaccion'],0,2) != 'PS'){
	include('session.php');
	if(isset($_SESSION['login'])) {
		include('adminTemplate.php');
		include('funciones.php');

		$fechaTransaccion = array();
		$result = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$_POST['idTransaccion']}'");
		while($row = mysqli_fetch_array($result)) {
			$fechaTransaccion = explode("|", $row['fechaTransaccion']);
			$result2 = mysqli_query($link, "SELECT * FROM Proveedor WHERE idProveedor = '{$row['idProveedor']}'");
			while ($row2 = mysqli_fetch_array($result2)) {
				$proveedor = $row2['nombre'];
			}
			$result2 = mysqli_query($link, "SELECT * FROM Colaborador WHERE idColaborador = '{$row['idColaborador']}'");
			while ($row2 = mysqli_fetch_array($result2)) {
				$prestamista = $row2['nombres']." ".$row2['apellidos'];
			}
			$fechaVencimiento = $row['fechaVencimiento'];
			$observacion = $row['observacion'];
			$montoTotalTransaccion = $row['montoTotal'];
			$montoRestanteTransaccion = $row['montoRestante'];
		}




		?>
		<section class="container">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header card-inverse card-info">
							<form method="post" action="gestionPrestamos.php" id="formOC">
								<div class="float-left">
									<i class="fa fa-shopping-bag"></i>
									Cancelación de Préstamo
								</div>
								<div class="float-right">
									<input type='hidden' name='idTransaccion' value='<?php echo $_POST['idTransaccion']?>'>
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
									<div class="col-3"><p><b>Responsable:</b></p></div>
									<div class="col-9"><p><?php echo $prestamista; ?></p></div>
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
								<div class="row">
									<div class="col-3"><p><b>Monto de Préstamo:</b></p></div>
									<div class="col-9"><p><?php echo $montoTotalTransaccion; ?></p></div>
								</div>
								<div class="row">
									<div class="col-3"><p><b>Monto Restante:</b></p></div>
									<div class="col-9"><p><?php echo $montoRestanteTransaccion; ?></p></div>
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
                            <div class="float-left">
                                <i class="fa fa-shopping-bag"></i>
                                Historial de Cancelación
                            </div>
                        </div>
                        <div class="card-block">
                            <div class="col-12">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Transacción</th>
                                        <th class="text-center">Fecha</th>
                                        <th class="text-center">Responsable</th>
                                        <th class="text-center">Producto</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-center">Monto</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php
									$query = mysqli_query($link,"SELECT * FROM Movimiento WHERE idTransaccionReferencial = '{$_POST['idTransaccion']}'");
									while($row = mysqli_fetch_array($query)){
										echo "<tr>";
										echo "<td class='text-center'>
                                                <form method='post' action='detalleMovimiento.php'>
                                                    <input type='hidden' name='idMovimiento' value='{$row['idMovimiento']}'>
                                                    <input type='submit' name='detalle' value='{$row['idMovimiento']}' class='btn-link'>
                                                </form>
                                            </td>";
										$fechaMovimiento = explode("|",$row['fecha']);
										echo "<td class='text-center'>{$fechaMovimiento[0]}</td>";
										$query2 = mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$row['idColaborador']}'");
										while($row2 = mysqli_fetch_array($query2)){
											echo "<td class='text-center'>{$row2['nombres']} {$row2['apellidos']}</td>";
										}
										echo "<td class='text-center'> - </td>";
										echo "<td class='text-center'> - </td>";
										echo "<td class='text-center'>{$row['monto']}</td>";
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
}else{

	include('session.php');
	if(isset($_SESSION['login'])) {
		include('adminTemplate.php');
		include('funciones.php');

		$fechaTransaccion = array();
		$result = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$_POST['idTransaccion']}'");
		while($row = mysqli_fetch_array($result)) {
			$fechaTransaccion = explode("|", $row['fechaTransaccion']);
			$result2 = mysqli_query($link, "SELECT * FROM Proveedor WHERE idProveedor = '{$row['idProveedor']}'");
			while ($row2 = mysqli_fetch_array($result2)) {
				$proveedor = $row2['nombre'];
			}
			$result2 = mysqli_query($link, "SELECT * FROM Colaborador WHERE idColaborador = '{$row['idColaborador']}'");
			while ($row2 = mysqli_fetch_array($result2)) {
				$prestamista = $row2['nombres']." ".$row2['apellidos'];
			}
			$fechaVencimiento = $row['fechaVencimiento'];
			$observacion = $row['observacion'];
			$montoRestanteTransaccion = $row['montoRestante'];
		}
		?>
		<section class="container">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header card-inverse card-info">
							<form method="post" action="gestionPrestamos.php" id="formOC">
								<div class="float-left">
									<i class="fa fa-shopping-bag"></i>
									Cancelación de Préstamo
								</div>
								<div class="float-right">
									<input type='hidden' name='idTransaccion' value='<?php echo $_POST['idTransaccion']?>'>
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
									<div class="col-3"><p><b>Responsable:</b></p></div>
									<div class="col-9"><p><?php echo $prestamista; ?></p></div>
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
										<th class="text-center">Valor U. Préstamo (S/.)</th>
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
										echo "<td class='text-center'>".round($row['valorUnitario'],2)."</td>";
										$total = $row['cantidad'] * $row['valorUnitario'];
										$sumaTotal += $total;
										echo "<td class='text-center'>".round($total,2)."</td>";
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
				<div class="col-9">
                    <div class="card">
                        <div class="card-header card-inverse card-info">
                            <div class="float-left">
                                <i class="fa fa-shopping-bag"></i>
                                Historial de Cancelación
                            </div>
                            <div class="float-right">
                                <form method="post" action="#" id="myForm">
                                    <input type='hidden' id='sid' name='sid' value='<?php $sida = session_id(); echo $sida;?>'>
                                    <input type='hidden' id='pid' name='pid' value='0'>
                                    <input type='hidden' name='idTransaccion' value='<?php echo $_POST['idTransaccion']?>'>
                                    <input type="submit" value="Imprimir" name="print" class="btn btn-secondary btn-sm" formaction="#" onclick="javascript:doClientPrint();">
                                </form>
                            </div>
                        </div>
                        <div class="card-block">
                            <div class="col-12">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Transacción</th>
                                        <th class="text-center">Fecha</th>
                                        <th class="text-center">Responsable</th>
                                        <th class="text-center">Producto</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-center">Monto</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php

                                    $date = date('Y-m-d');

                                    $replace = [
	                                    '&lt;' => '', '&gt;' => '', '&#039;' => '', '&amp;' => '',
	                                    '&quot;' => '', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'Ae',
	                                    '&Auml;' => 'A', 'Å' => 'A', 'Ā' => 'A', 'Ą' => 'A', 'Ă' => 'A', 'Æ' => 'Ae',
	                                    'Ç' => 'C', 'Ć' => 'C', 'Č' => 'C', 'Ĉ' => 'C', 'Ċ' => 'C', 'Ď' => 'D', 'Đ' => 'D',
	                                    'Ð' => 'D', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ē' => 'E',
	                                    'Ę' => 'E', 'Ě' => 'E', 'Ĕ' => 'E', 'Ė' => 'E', 'Ĝ' => 'G', 'Ğ' => 'G',
	                                    'Ġ' => 'G', 'Ģ' => 'G', 'Ĥ' => 'H', 'Ħ' => 'H', 'Ì' => 'I', 'Í' => 'I',
	                                    'Î' => 'I', 'Ï' => 'I', 'Ī' => 'I', 'Ĩ' => 'I', 'Ĭ' => 'I', 'Į' => 'I',
	                                    'İ' => 'I', 'Ĳ' => 'IJ', 'Ĵ' => 'J', 'Ķ' => 'K', 'Ł' => 'K', 'Ľ' => 'K',
	                                    'Ĺ' => 'K', 'Ļ' => 'K', 'Ŀ' => 'K', 'Ñ' => 'N', 'Ń' => 'N', 'Ň' => 'N',
	                                    'Ņ' => 'N', 'Ŋ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O',
	                                    'Ö' => 'Oe', '&Ouml;' => 'Oe', 'Ø' => 'O', 'Ō' => 'O', 'Ő' => 'O', 'Ŏ' => 'O',
	                                    'Œ' => 'OE', 'Ŕ' => 'R', 'Ř' => 'R', 'Ŗ' => 'R', 'Ś' => 'S', 'Š' => 'S',
	                                    'Ş' => 'S', 'Ŝ' => 'S', 'Ș' => 'S', 'Ť' => 'T', 'Ţ' => 'T', 'Ŧ' => 'T',
	                                    'Ț' => 'T', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'Ue', 'Ū' => 'U',
	                                    '&Uuml;' => 'Ue', 'Ů' => 'U', 'Ű' => 'U', 'Ŭ' => 'U', 'Ũ' => 'U', 'Ų' => 'U',
	                                    'Ŵ' => 'W', 'Ý' => 'Y', 'Ŷ' => 'Y', 'Ÿ' => 'Y', 'Ź' => 'Z', 'Ž' => 'Z',
	                                    'Ż' => 'Z', 'Þ' => 'T', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
	                                    'ä' => 'ae', '&auml;' => 'ae', 'å' => 'a', 'ā' => 'a', 'ą' => 'a', 'ă' => 'a',
	                                    'æ' => 'ae', 'ç' => 'c', 'ć' => 'c', 'č' => 'c', 'ĉ' => 'c', 'ċ' => 'c',
	                                    'ď' => 'd', 'đ' => 'd', 'ð' => 'd', 'è' => 'e', 'é' => 'e', 'ê' => 'e',
	                                    'ë' => 'e', 'ē' => 'e', 'ę' => 'e', 'ě' => 'e', 'ĕ' => 'e', 'ė' => 'e',
	                                    'ƒ' => 'f', 'ĝ' => 'g', 'ğ' => 'g', 'ġ' => 'g', 'ģ' => 'g', 'ĥ' => 'h',
	                                    'ħ' => 'h', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ī' => 'i',
	                                    'ĩ' => 'i', 'ĭ' => 'i', 'į' => 'i', 'ı' => 'i', 'ĳ' => 'ij', 'ĵ' => 'j',
	                                    'ķ' => 'k', 'ĸ' => 'k', 'ł' => 'l', 'ľ' => 'l', 'ĺ' => 'l', 'ļ' => 'l',
	                                    'ŀ' => 'l', 'ñ' => 'n', 'ń' => 'n', 'ň' => 'n', 'ņ' => 'n', 'ŉ' => 'n',
	                                    'ŋ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'oe',
	                                    '&ouml;' => 'oe', 'ø' => 'o', 'ō' => 'o', 'ő' => 'o', 'ŏ' => 'o', 'œ' => 'oe',
	                                    'ŕ' => 'r', 'ř' => 'r', 'ŗ' => 'r', 'š' => 's', 'ù' => 'u', 'ú' => 'u',
	                                    'û' => 'u', 'ü' => 'ue', 'ū' => 'u', '&uuml;' => 'ue', 'ů' => 'u', 'ű' => 'u',
	                                    'ŭ' => 'u', 'ũ' => 'u', 'ų' => 'u', 'ŵ' => 'w', 'ý' => 'y', 'ÿ' => 'y',
	                                    'ŷ' => 'y', 'ž' => 'z', 'ż' => 'z', 'ź' => 'z', 'þ' => 't', 'ß' => 'ss',
	                                    'ſ' => 'ss', 'ый' => 'iy', 'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G',
	                                    'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I',
	                                    'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
	                                    'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F',
	                                    'Х' => 'H', 'Ц' => 'C', 'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SCH', 'Ъ' => '',
	                                    'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA', 'а' => 'a',
	                                    'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo',
	                                    'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l',
	                                    'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's',
	                                    'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
	                                    'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e',
	                                    'ю' => 'yu', 'я' => 'ya', ':' => '.', '=' => '.'
                                    ];

                                    //Create ESC/POS commands for sample receipt
                                    $esc = '0x1B'; //ESC byte in hex notation
                                    $newLine = '0x0A'; //LF byte in hex notation
                                    $cmds = '';
                                    $cmds = $esc . "@"; //Initializes the printer (ESC @)
                                    $cmds .= $esc . '!' . '0x00'; //Emphasized + Double-height + Double-width mode selected (ESC ! (8 + 16 + 32)) 56 dec => 38 hex
                                    $cmds .= $newLine;
                                    $cmds .= 'HISTORIAL DE CANCELACION - PRESTAMO';
                                    $cmds .= $newLine;
                                    $cmds .= '-------------------------';
                                    $cmds .= $newLine;
                                    $cmds .= 'CARVASQ E.I.R.L.'; //text to print
                                    $cmds .= $newLine;
                                    $cmds .= '-------------------------';
                                    $cmds .= $newLine;
                                    $cmds .= 'ID TRANSACCION  '.$_POST['idTransaccion'];
                                    $cmds .= $newLine;
                                    $cmds .= 'FECHA  '.$date;
                                    $cmds .= $newLine;
                                    $cmds .= 'CODIGO  PRODUCTO  CANT  V.U.  FECHA';
                                    $cmds .= $newLine;
                                    $query = mysqli_query($link,"SELECT * FROM Transaccion WHERE referenciaTransaccion = '{$_POST['idTransaccion']}'");
                                    while($row = mysqli_fetch_array($query)){
                                        echo "<tr>";
                                            echo "<td class='text-center'>{$row['idTransaccion']}</td>";
	                                        $fechaTransaccion = explode("|",$row['fechaTransaccion']);
                                            echo "<td class='text-center'>{$fechaTransaccion[0]}</td>";
                                            $query2 = mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$row['idColaborador']}'");
                                            while($row2 = mysqli_fetch_array($query2)){
                                                echo "<td class='text-center'>{$row2['nombres']} {$row2['apellidos']}</td>";
                                            }
                                            $query2 = mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['idTransaccion']}'");
                                            while($row2 = mysqli_fetch_array($query2)){
                                                $query3 = mysqli_query($link,"SELECT * FROM Producto WHERE idProducto = '{$row2['idProducto']}'");
                                                while($row3 = mysqli_fetch_array($query3)){
                                                    $nombreCorto = $row3['nombreCorto'];
                                                    echo "<td class='text-center'>{$row3['nombreCorto']}</td>";
                                                }
	                                            $nombreCorto = strtoupper($nombreCorto);
	                                            $nombreCorto = str_replace(array_keys($replace),$replace,$nombreCorto);
	                                            $cantidad = $row2['cantidad'];
	                                            $valorUnitario = $row2['valorUnitario'];
                                                echo "<td class='text-center'>{$row2['cantidad']}</td>";
                                                $montoProductos = $row2['cantidad'] * $row2['valorUnitario'];
                                                echo "<td class='text-center'>".round($montoProductos,2)."</td>";

	                                            $cmds .= $row2['idProducto']." ".strtoupper(substr($nombreCorto,0,10))." ".$cantidad."    ".round($valorUnitario,2)."  ".$fechaTransaccion[0];
	                                            $cmds .= $newLine;
                                            }
                                        echo "</tr>";
                                    }
                                    $cmds .= '-------------------------';
                                    $cmds .= $newLine;
                                    $cmds .= 'MOVIMIENTO    MONTO FECHA';
                                    $cmds .= $newLine;
                                    $query = mysqli_query($link,"SELECT * FROM Movimiento WHERE idTransaccionReferencial = '{$_POST['idTransaccion']}'");
                                    while($row = mysqli_fetch_array($query)){
                                        echo "<tr>";
	                                    echo "<td class='text-center'>
                                                <form method='post' action='detalleMovimiento.php'>
                                                    <input type='hidden' name='idMovimiento' value='{$row['idMovimiento']}'>
                                                    <input type='submit' name='detalle' value='{$row['idMovimiento']}' class='btn-link'>
                                                </form>
                                            </td>";
                                            $fechaMovimiento = explode("|",$row['fecha']);
	                                        echo "<td class='text-center'>{$fechaMovimiento[0]}</td>";
                                            $query2 = mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$row['idColaborador']}'");
                                            while($row2 = mysqli_fetch_array($query2)){
                                                echo "<td class='text-center'>{$row2['nombres']} {$row2['apellidos']}</td>";
                                            }
                                            echo "<td class='text-center'> - </td>";
	                                        echo "<td class='text-center'> - </td>";
	                                        echo "<td class='text-center'>".round($row['monto'],2)."</td>";
	                                    $cmds .= $row['idMovimiento']." ".round($row['monto'],2)."   ".$fechaMovimiento[0];
	                                    $cmds .= $newLine;
                                    }
                                    $cmds .= $newLine.$newLine.$newLine;
                                    $cmds .= '------------------';
                                    $cmds .= $newLine;
                                    $cmds .= ' FIRMA CONSULTORA';
                                    $cmds .=$newLine.$newLine.$newLine.$newLine.$newLine;
                                    ?>
                                    <textarea id='printerCommands' name='printerCommands' class='form-control' form="myForm" hidden><?php echo $cmds;?></textarea>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
				<div class="col-3">
					<table class="table align-content-end">
						<tbody>
						<tr>
							<th>Total (S/.)</th>
							<td><?php $sumaTotalRedondeada = round($sumaTotal,2); echo $sumaTotalRedondeada;?></td>
						</tr>

						<?php
						$search = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$_POST['idTransaccion']}'");
						while($searchIndex = mysqli_fetch_array($search)){
							$montoRestanteShow = round($searchIndex['montoRestante'],2);
						}
						?>

						<tr>
							<th>Restante (S/.)</th>
							<td><?php echo $montoRestanteShow;?></td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
		</section>


		<?php

		//Get Absolute URL of this page
		$currentAbsoluteURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
		$currentAbsoluteURL .= $_SERVER["SERVER_NAME"];
		if($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443")
		{
			$currentAbsoluteURL .= ":".$_SERVER["SERVER_PORT"];
		}
		$currentAbsoluteURL .= $_SERVER["REQUEST_URI"];

		//WebClientPrinController.php is at the same page level as WebClientPrint.php
		$webClientPrintControllerAbsoluteURL = substr($currentAbsoluteURL, 0, strrpos($currentAbsoluteURL, '/')).'/WebClientPrintController.php';

		//DemoPrintCommandsProcess.php is at the same page level as WebClientPrint.php
		$demoPrintCommandsProcessAbsoluteURL = substr($currentAbsoluteURL, 0, strrpos($currentAbsoluteURL, '/')).'/DemoPrintCommandsProcess.php';

		//Specify the ABSOLUTE URL to the WebClientPrintController.php and to the file that will create the ClientPrintJob object (DemoPrintCommandsProcess.php)
		echo WebClientPrint::createScript($webClientPrintControllerAbsoluteURL, $demoPrintCommandsProcessAbsoluteURL, session_id());
		?>


        <script type="text/javascript">

            function doClientPrint() {

                //collect printer settings and raw commands
                var printJobInfo = $("#myForm").serialize();

                // Launch WCPP at the client side for printing...
                jsWebClientPrint.print(printJobInfo);

            }


            $(document).ready(function () {
                //jQuery-based Wizard
                $("#myForm").formToWizard();

                //change printer options based on user selection
                $("#pid").change(function () {
                    var printerId = $("select#pid").val();

                    displayInfo(printerId);
                    hidePrinters();
                    if (printerId == 2)
                        $("#installedPrinter").show();
                    else if (printerId == 3)
                        $("#netPrinter").show();
                    else if (printerId == 4)
                        $("#parallelPrinter").show();
                    else if (printerId == 5)
                        $("#serialPrinter").show();
                });

                hidePrinters();
                displayInfo(0);


            });

            function displayInfo(i) {
                if (i == 0)
                    $("#info").html('This will make the WCPP to send the commands to the printer installed in your machine as "Default Printer" without displaying any dialog!');
                else if (i == 1)
                    $("#info").html('This will make the WCPP to display the Printer dialog so you can select which printer you want to use.');
                else if (i == 2)
                    $("#info").html('Please specify the <b>Printer\'s Name</b> as it figures installed under your system.');
                else if (i == 3)
                    $("#info").html('Please specify the Network Printer info.<br /><strong>On Linux &amp; Mac</strong> it\'s recommended you install the printer through <strong>CUPS</strong> and set the assigned printer name to the <strong>"Use an installed Printer"</strong> option on this demo.');
                else if (i == 4)
                    $("#info").html('Please specify the Parallel Port which your printer is connected to.<br /><strong>On Linux &amp; Mac</strong> you must install the printer through <strong>CUPS</strong> and set the assigned printer name to the <strong>"Use an installed Printer"</strong> option on this demo.');
                else if (i == 5)
                    $("#info").html('Please specify the Serial RS232 Port info which your printer does support.<br /><strong>On Linux &amp; Mac</strong> you must install the printer through <strong>CUPS</strong> and set the assigned printer name to the <strong>"Use an installed Printer"</strong> option on this demo.');
            }

            function hidePrinters() {
                $("#installedPrinter").hide(); $("#netPrinter").hide(); $("#parallelPrinter").hide(); $("#serialPrinter").hide();
            }




            /* FORM to WIZARD */
            /* Created by jankoatwarpspeed.com */

            (function ($) {
                $.fn.formToWizard = function () {

                    var element = this;

                    var steps = $(element).find("fieldset");
                    var count = steps.size();


                    // 2
                    $(element).before("<ul id='steps'></ul>");

                    steps.each(function (i) {
                        $(this).wrap("<div id='step" + i + "'></div>");
                        $(this).append("<p id='step" + i + "commands'></p>");

                        // 2
                        var name = $(this).find("legend").html();
                        $("#steps").append("<li id='stepDesc" + i + "'>Step " + (i + 1) + "<span>" + name + "</span></li>");

                        if (i == 0) {
                            createNextButton(i);
                            selectStep(i);
                        }
                        else if (i == count - 1) {
                            $("#step" + i).hide();
                            createPrevButton(i);
                        }
                        else {
                            $("#step" + i).hide();
                            createPrevButton(i);
                            createNextButton(i);
                        }
                    });

                    function createPrevButton(i) {
                        var stepName = "step" + i;
                        $("#" + stepName + "commands").append("<a href='#' id='" + stepName + "Prev' class='prev btn btn-info'>< Back</a>");

                        $("#" + stepName + "Prev").bind("click", function (e) {
                            $("#" + stepName).hide();
                            $("#step" + (i - 1)).show();

                            selectStep(i - 1);
                        });
                    }

                    function createNextButton(i) {
                        var stepName = "step" + i;
                        $("#" + stepName + "commands").append("<a href='#' id='" + stepName + "Next' class='next btn btn-info'>Next ></a>");

                        $("#" + stepName + "Next").bind("click", function (e) {
                            $("#" + stepName).hide();
                            $("#step" + (i + 1)).show();

                            selectStep(i + 1);
                        });
                    }

                    function selectStep(i) {
                        $("#steps li").removeClass("current");
                        $("#stepDesc" + i).addClass("current");
                    }

                }
            })(jQuery);

        </script>



		<?php

		include('footerTemplate.php');
	}else{
		include('sessionError.php');
	}
}

?>