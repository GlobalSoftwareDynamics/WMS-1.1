<?php
require_once __DIR__ . '/lib/mpdf/mpdf.php';

include('session.php');
include('funciones.php');
if(isset($_SESSION['login'])){

	$html='
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/Formatospdf.css" rel="stylesheet">
    </head>
    <body class="portrait">
        <section class="container">
            <div class="row">
                        <div class="col-12">
                            <h6 class="text-center" style="text-decoration: underline">Ingresos</h6>
                            <div class="spacer10"></div>
                            <table class="table text-center">
                                <thead>
                                <tr>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Movimiento</th>
                                    <th class="text-center">Responsable</th>
                                    <th class="text-center">Cliente/Proveedor</th>
                                    <th class="text-center">Tipo</th>
                                    <th class="text-center">Notas</th>
                                    <th class="text-center">Monto</th>
                                </tr>
                                </thead>
                                <tbody>';
	$dateInicio = explode("-", $_POST['fechaInicioReporte']);
	$dateFin = explode("-", $_POST['fechaFinReporte']);
	$fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
	$fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
	$aux = 0;
	$totalingresos = 0;
	$query = mysqli_query($link,"SELECT * FROM Movimiento WHERE idTipoMovimiento IN (SELECT idTipoMovimiento FROM TipoMovimiento WHERE tipo = 1) ORDER BY fecha DESC");
	while($row = mysqli_fetch_array($query)){
		if($row['monto'] > 0){
			$fechaTransac = explode(" ",$row['fecha']);
			$fechaTransaccionCompleta = $fechaTransac[0];
			if($fechaTransaccionCompleta <= $fechaFin && $fechaTransaccionCompleta >= $fechaInicio){
				$aux++;
				$fecha=explode(" ",$row['fecha']);
				$html .='<tr>
											<td>'.$fecha[0].'</td>';
				$result = mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$row['idColaborador']}'");
				while ($fila=mysqli_fetch_array($result)){
					$nombre = $fila['nombres']." ".$fila['apellidos'];
				}
				$result = mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$row['idProveedor']}'");
				while ($fila=mysqli_fetch_array($result)){
					$nombreProveedor = $fila['nombre'];
				}
				$result = mysqli_query($link,"SELECT * FROM TipoMovimiento WHERE idTipoMovimiento = '{$row['idTipoMovimiento']}'");
				while ($fila=mysqli_fetch_array($result)){
					$tipo = $fila['descripcion'];
				}
				$html .='<td>'.$row['idMovimiento'].'</td>
												<td>'.$nombre.'</td>";
												<td>'.$nombreProveedor.'</td>
												<td>'.$tipo.'</td>";
												<td>'.$row['observaciones'].'</td>
												<td>S/. + '.$row['monto'].'</td>
												</tr>';
				$totalingresos += $row['monto'];
			}
		}
	}
	$html .='
                                <tr>
                                    <td colspan="5" class="text-left font-weight-bold">Total Ingresos</td>
                                    <td colspan="2">S/. +'.$totalingresos.'</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-center" style="text-decoration: underline">Egresos</h6>
                            <div class="spacer10"></div>
                            <table class="table text-center">
                                <thead>
                                <tr>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Movimiento</th>
                                    <th class="text-center">Responsable</th>
                                    <th class="text-center">Cliente/Proveedor</th>
                                    <th class="text-center">Tipo</th>
                                    <th class="text-center">Notas</th>
                                    <th class="text-center">Monto</th>
                                </tr>
                                </thead>
                                <tbody>';
	$dateInicio = explode("-", $_POST['fechaInicioReporte']);
	$dateFin = explode("-", $_POST['fechaFinReporte']);
	$fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
	$fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
	$aux = 0;
	$totalegresos = 0;
	$query = mysqli_query($link,"SELECT * FROM Movimiento WHERE idTipoMovimiento IN (SELECT idTipoMovimiento FROM TipoMovimiento WHERE tipo = 0) ORDER BY fecha DESC");
	while($row = mysqli_fetch_array($query)){
		if($row['monto'] > 0){
			$fechaTransac = explode(" ",$row['fecha']);
			$fechaTransaccionCompleta = $fechaTransac[0];
			if($fechaTransaccionCompleta <= $fechaFin && $fechaTransaccionCompleta >= $fechaInicio){
				$fecha=explode(" ",$row['fecha']);
				$html .='<tr>
											<td>'.$fecha[0].'</td>';
				$result = mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$row['idColaborador']}'");
				while ($fila=mysqli_fetch_array($result)){
					$nombre = $fila['nombres']." ".$fila['apellidos'];
				}
				$result = mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$row['idProveedor']}'");
				while ($fila=mysqli_fetch_array($result)){
					$nombreProveedor = $fila['nombre'];
				}
				$result = mysqli_query($link,"SELECT * FROM TipoMovimiento WHERE idTipoMovimiento = '{$row['idTipoMovimiento']}'");
				while ($fila=mysqli_fetch_array($result)){
					$tipo = $fila['descripcion'];
				}
				$html .='<td>'.$row['idMovimiento'].'</td>";
											<td>'.$nombre.'</td>
											<td>'.$nombreProveedor.'</td>
											<td>'.$tipo.'</td>
											<td>'.$row['observaciones'].'</td>
											<td>S/. - '.$row['monto'].'</td>
											</tr>';
				$totalegresos += $row['monto'];
			}
		}
	}
	$html .='
                                <tr>
                                    <td colspan="5" class="text-left font-weight-bold">Total Egresos</td>
                                    <td colspan="2">S/. -'.$totalegresos.'</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-center" style="text-decoration: underline">Cupones</h6>
                            <div class="spacer10"></div>
                            <table class="table text-center">
                                <thead>
                                <tr>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Movimiento</th>
                                    <th class="text-center">Trns. Primaria</th>
                                    <th class="text-center">Trns. Referencial</th>
                                    <th class="text-center">Cliente</th>
                                    <th class="text-center">Monto</th>
                                </tr>
                                </thead>
                                <tbody>';
	$dateInicio = explode("-", $_POST['fechaInicioReporte']);
	$dateFin = explode("-", $_POST['fechaFinReporte']);
	$fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
	$fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
	$aux = 0;
	$totalcupones = 0;
	$query = mysqli_query($link,"SELECT * FROM Movimiento WHERE idMedioPago = 3 ORDER BY fecha DESC");
	while($row = mysqli_fetch_array($query)){
		if($row['monto'] > 0){
			$fechaTransac = explode(" ",$row['fecha']);
			$fechaTransaccionCompleta = $fechaTransac[0];
			if($fechaTransaccionCompleta <= $fechaFin && $fechaTransaccionCompleta >= $fechaInicio){
				$fecha=explode(" ",$row['fecha']);
				$html .='<tr>
											<td>'.$fecha[0].'</td>';
				$result = mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$row['idProveedor']}'");
				while ($fila=mysqli_fetch_array($result)){
					$nombreProveedor = $fila['nombre'];
				}
				$html .='<td>'.$row['idMovimiento'].'</td>
											<td>'.$row['idTransaccionPrimaria'].'</td>
											<td>'.$row['idTransaccionReferencial'].'</td>
											<td>'.$nombreProveedor.'</td>
											<td>S/. + '.$row['monto'].'</td>
											</tr>';
				$totalcupones += $row['monto'];
			}
		}
	}
	$html .='
                                <tr>
                                    <td colspan="4" class="text-left font-weight-bold">Total Cupones</td>
                                    <td colspan="2">S/. +'.$totalcupones.'</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <h6 class="text-center" style="text-decoration: underline">Saldos</h6>
                            <div class="spacer10"></div>
                            <table class="table text-center">
                                <thead>
                                <tr>
                                    <th class="text-center">Cuenta</th>
                                    <th class="text-center">Saldo</th>
                                </tr>
                                </thead>
                                <tbody>';
	$dateFin = explode("-", $_POST['fechaFinReporte']);
	$fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
	$fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
	$query = mysqli_query($link,"SELECT * FROM Cuenta");
	while ($row=mysqli_fetch_array($query)) {
		$query3 = mysqli_query($link, "SELECT * FROM LogSaldos WHERE idCuenta = '{$row['idCuenta']}' AND fecha = '{$fechaFin}'");
		$numrows = mysqli_num_rows($query3);
		if ($numrows > 0) {
			while ($rows3 = mysqli_fetch_array($query3)) {
				$html .="<tr>";
				$alias = mysqli_query($link, "SELECT * FROM Cuenta WHERE idCuenta = '{$row['idCuenta']}'");
				while ($fila1 = mysqli_fetch_array($alias)) {
					$html .= "<td>{$fila1['alias']}</td>";
				}
				$html .= "<td>S/. {$row['saldo']}</td>";
				$html .= "</tr>";
			}
		}elseif($numrows==0){
			$query3 = mysqli_query($link, "SELECT * FROM LogSaldos WHERE idCuenta = '{$row['idCuenta']}' AND fecha = '{$row['fechaActualizacion']}'");
			while ($rows3 = mysqli_fetch_array($query3)) {
				$html .= "<tr>";
				$html .= "<td>{$row['alias']}</td>";
				$html .= "<td>S/. {$rows3['saldo']}</td>";
				$html .= "</tr>";
			}
		}
	}
	$html .='
                                </tbody>
                            </table>
                        </div>
                    </div>
        </section>
    </body>
    ';

	$htmlheader='
        <header>
            <div id="descripcionbrand">
                <img width="auto" height="70" src="img/logo4.png"/>
            </div>
            <div id="tituloreporte">
                <div class="titulo">
                    <h4>Reporte de Caja Diario</h4><br>
                    <h4 class="desctitulo" style="font-size: 15px">Del '.$_POST['fechaInicioReporte'].' al '.$_POST['fechaFinReporte'].'</h4>
                </div>
            </div>
        </header>

    ';
	$htmlfooter='
          <div class="footer">
                <span style="font-size: 10px;">GSD-WMS</span>
                                   
                                 
                              
                <span style="font-size: 10px">© 2017 by Global Software Dynamics.Visítanos en <a target="GSD" href="http://www.gsdynamics.com/">GSDynamics.com</a></span>
          </div>
    ';
	$nombrearchivo='Reporte De Caja del '.$_POST['fechaInicioReporte'].' al '.$_POST['fechaFinReporte'].'.pdf';
	$mpdf = new mPDF('','A4',0,'','5',5,35,15,6,6);

// Write some HTML code:
	$mpdf->SetHTMLHeader($htmlheader);
	$mpdf->SetHTMLFooter($htmlfooter);
	$mpdf->WriteHTML($html);

// Output a PDF file directly to the browser
	$mpdf->Output($nombrearchivo,'D');
}else{
	include('sessionError.php');
}
?>