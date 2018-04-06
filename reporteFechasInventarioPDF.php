<?php
require_once __DIR__ . '/lib/mpdf/mpdf.php';

include('session.php');
include('funciones.php');
if(isset($_SESSION['login'])){

	$totalPrestamos = 0;
	$totalVentas = 0;
	$totalCompras = 0;

	$colaboradorasCompras = array();
	$colaboradorasVentas = array();
	$colaboradorasPrestamos = array();

	$colaboradorasComprasValores = array();
	$colaboradorasVentasValores = array();
	$colaboradorasPrestamosValores = array();

	$colaboradorasComprasTotales = array();
	$colaboradorasVentasTotales = array();
	$colaboradorasPrestamosTotales = array();

	$colaboradorasComprasActual = array();
	$colaboradorasVentasActual = array();
	$colaboradorasPrestamosActual = array();

	$aux3 = 0;

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
							<h6 class="text-center" style="text-decoration: underline">Ingresos de Mercadería</h6>
							<div class="spacer10"></div>
							<table class="table">
								<thead>
								<tr>
									<th class="text-center">Item</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Transacción</th>
                                    <th class="text-center">Cliente</th>
                                    <th class="text-center">Producto</th>
                                    <th class="text-center">Almacén</th>
                                    <th class="text-center">Ubicación</th>
                                    <th class="text-center">Cantidad</th>
								</tr>
								</thead>
								<tbody>';
                                $dateInicio = explode("-", $_POST['fechaInicioReporte']);
                                $dateFin = explode("-", $_POST['fechaFinReporte']);
                                $fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
                                $fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
                                $aux = 0;
                                $transaccionReferencia = "-";
                                $query = mysqli_query($link, "SELECT * FROM Transaccion WHERE idTipoTransaccion IN (2,4) ORDER BY fechaTransaccion DESC");
                                while ($row = mysqli_fetch_array($query)) {
                                    $fechaTransac = explode(" ",$row['fechaTransaccion']);
                                    $fechaTransaccionCompleta = $fechaTransac[0];
                                    if($fechaTransaccionCompleta <= $fechaFin && $fechaTransaccionCompleta >= $fechaInicio){
                                        $valorReferencia = 0;
                                        $query2 = mysqli_query($link, "SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['idTransaccion']}'");
                                        while ($row2 = mysqli_fetch_array($query2)) {
                                            $aux++;
                                            $html .= "<tr>";
                                            $html .= "<td class='text-center'>$aux</td>";
                                            $fechaTransaccion = explode(" ",$row['fechaTransaccion']);
                                            $html .= "<td class='text-center'>{$fechaTransaccion[0]}</td>";
                                            $html .= "<td class='text-center'>{$row['idTransaccion']}</td>";
                                            $query3 = mysqli_query($link, "SELECT * FROM Proveedor WHERE idProveedor = '{$row['idProveedor']}'");
                                            while ($row3 = mysqli_fetch_array($query3)) {
                                                $proveedor = $row3['nombre'];
                                                $html .="<td class='text-center'>{$row3['nombre']}</td>";
                                            }
                                            $query3 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$row2['idProducto']}'");
                                            while ($row3 = mysqli_fetch_array($query3)) {
                                                $nombreProducto = $row3['nombreCorto'];
                                                $html .= "<td class='text-center'>{$row3['nombreCorto']}</td>";
                                            }
                                            $query3 = mysqli_query($link, "SELECT * FROM Ubicacion WHERE idUbicacion = '{$row2['idUbicacionFinal']}'");
                                            while ($row3 = mysqli_fetch_array($query3)) {
                                                $query4 = mysqli_query($link, "SELECT * FROM Almacen WHERE idAlmacen = '{$row3['idAlmacen']}'");
                                                while ($row4 = mysqli_fetch_array($query4)) {
                                                    $nombreAlmacen = $row4['descripcion'];
                                                    $html .= "<td class='text-center'>{$row4['descripcion']}</td>";
                                                }
                                            }
                                            $html .= "<td class='text-center'>{$row2['idUbicacionFinal']}</td>";
                                            $html .= "<td class='text-center'>{$row2['cantidad']}</td>";
                                            $html .= "</tr>";
                                        }
                                    }
                                }
								$html.='
								</tbody>
							</table>
						</div>
					</div>
                    <div class="row">
						<div class="col-12">
							<h6 class="text-center" style="text-decoration: underline">Salidas de
								Mercadería</h6>
							<div class="spacer10"></div>
							<table class="table">
								<thead>
								<tr>
									<th class="text-center">Item</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Transacción</th>
                                    <th class="text-center">Cliente</th>
                                    <th class="text-center">Producto</th>
                                    <th class="text-center">Almacén</th>
                                    <th class="text-center">Ubicación</th>
                                    <th class="text-center">Cantidad</th>
								</tr>
								</thead>
								<tbody>';
                                $dateInicio = explode("-", $_POST['fechaInicioReporte']);
                                $dateFin = explode("-", $_POST['fechaFinReporte']);
                                $fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
                                $fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
                                $aux = 0;
                                $query = mysqli_query($link, "SELECT * FROM Transaccion WHERE idTipoTransaccion IN (5,6) ORDER BY fechaTransaccion DESC");
                                while ($row = mysqli_fetch_array($query)) {
                                    $fechaTransac = explode(" ",$row['fechaTransaccion']);
                                    $fechaTransaccionCompleta = $fechaTransac[0];
                                    if($fechaTransaccionCompleta <= $fechaFin && $fechaTransaccionCompleta >= $fechaInicio){
                                        $valorReferencia = 0;
                                        $query2 = mysqli_query($link, "SELECT * FROM logMovimientosAlmacen WHERE idTransaccion = '{$row['idTransaccion']}'");
                                        while ($row2 = mysqli_fetch_array($query2)) {
                                            $aux++;
                                            $html .= "<tr>";
                                            $html .= "<td class='text-center'>$aux</td>";
                                            $fechaTransaccion = explode(" ",$row['fechaTransaccion']);
                                            $html .= "<td class='text-center'>{$fechaTransaccion[0]}</td>";
                                            $html .= "<td class='text-center'>{$row['idTransaccion']}</td>";
                                            $query3 = mysqli_query($link, "SELECT * FROM Proveedor WHERE idProveedor = '{$row['idProveedor']}'");
                                            while ($row3 = mysqli_fetch_array($query3)) {
                                                $proveedor = $row3['nombre'];
                                                $html .="<td class='text-center'>{$row3['nombre']}</td>";
                                            }
                                            $query3 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$row2['idProducto']}'");
                                            while ($row3 = mysqli_fetch_array($query3)) {
                                                $nombreProducto = $row3['nombreCorto'];
                                                $html .= "<td class='text-center'>{$row3['nombreCorto']}</td>";
                                            }
                                            $query3 = mysqli_query($link, "SELECT * FROM Ubicacion WHERE idUbicacion = '{$row2['idUbicacion']}'");
                                            while ($row3 = mysqli_fetch_array($query3)) {
                                                $query4 = mysqli_query($link, "SELECT * FROM Almacen WHERE idAlmacen = '{$row3['idAlmacen']}'");
                                                while ($row4 = mysqli_fetch_array($query4)) {
                                                    $nombreAlmacen = $row4['descripcion'];
                                                    $html .= "<td class='text-center'>{$row4['descripcion']}</td>";
                                                }
                                            }
                                            $html .= "<td class='text-center'>{$row2['idUbicacion']}</td>";
                                            $html .= "<td class='text-center'>{$row2['cantidad']}</td>";
                                            $html .= "</tr>";
                                        }
                                    }
                                }
								$html.= '
								</tbody>
							</table>
						</div>
					</div>
                    <div class="row">
						<div class="col-12">
							<h6 class="text-center" style="text-decoration: underline">Transferencias de
								Mercadería</h6>
							<div class="spacer10"></div>
							<table class="table">
								<thead>
								<tr>
									<th class="text-center">Item</th>
									<th class="text-center">Transacción</th>
									<th class="text-center">Fecha</th>
									<th class="text-center">Producto</th>
									<th class="text-center">A. Salida</th>
									<th class="text-center">U. Salida</th>
									<th class="text-center">A. Ingreso</th>
									<th class="text-center">S. Ingreso</th>
									<th class="text-center">Cant.</th>
								</tr>
								</thead>
								<tbody>';
								$dateInicio = explode("-", $_POST['fechaInicioReporte']);
								$dateFin = explode("-", $_POST['fechaFinReporte']);
								$fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
								$fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
								$aux = 0;
								$query = mysqli_query($link, "SELECT * FROM Transaccion WHERE idTipoTransaccion IN (7) ORDER BY fechaTransaccion DESC");
								while ($row = mysqli_fetch_array($query)) {
									$fechaTransac = explode(" ",$row['fechaTransaccion']);
									$fechaTransaccionCompleta = $fechaTransac[0];
									if($fechaTransaccionCompleta <= $fechaFin && $fechaTransaccionCompleta >= $fechaInicio){
										$valorReferencia = 0;
										$query2 = mysqli_query($link, "SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['idTransaccion']}'");
										while ($row2 = mysqli_fetch_array($query2)) {
											if (substr($row['idTransaccion'], 0, 2) == 'OR') {
												$transaccionReferencia = $row['referenciaTransaccion'];
												$query3 = mysqli_query($link, "SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['referenciaTransaccion']}' AND idProducto = '{$row2['idProducto']}'");
												while ($row3 = mysqli_fetch_array($query3)) {
													$valorReferencia = $row3['valorUnitario'];
												}
											}
											$aux++;
											$html.= "<tr>";
											$html.= "<td class='text-center'>$aux</td>";
											$html.= "<td class='text-center'>{$row['idTransaccion']}</td>";
											$fechaTransaccion = explode(" ",$row['fechaTransaccion']);
											$html.= "<td class='text-center'>{$fechaTransaccion[0]}</td>";
											$query3 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$row2['idProducto']}'");
											while ($row3 = mysqli_fetch_array($query3)) {
												$nombreProducto = $row3['nombreCorto'];
												$query4 = mysqli_query($link,"SELECT * FROM Color WHERE idColor = '{$row3['idColor']}'");
												while($row4 = mysqli_fetch_array($query4)){
													$atributo = $row4['descripcion'];
												}
												$html.= "<td class='text-center'>{$row3['nombreCorto']} {$atributo}</td>";
											}
											$query3 = mysqli_query($link, "SELECT * FROM Ubicacion WHERE idUbicacion = '{$row2['idUbicacionInicial']}'");
											while ($row3 = mysqli_fetch_array($query3)) {
												$query4 = mysqli_query($link, "SELECT * FROM Almacen WHERE idAlmacen = '{$row3['idAlmacen']}'");
												while ($row4 = mysqli_fetch_array($query4)) {
													$nombreAlmacen = $row4['descripcion'];
													$html.= "<td class='text-center'>{$row4['descripcion']}</td>";
												}
											}
											$html.= "<td class='text-center'>{$row2['idUbicacionInicial']}</td>";
											$query3 = mysqli_query($link, "SELECT * FROM Ubicacion WHERE idUbicacion = '{$row2['idUbicacionFinal']}'");
											while ($row3 = mysqli_fetch_array($query3)) {
												$query4 = mysqli_query($link, "SELECT * FROM Almacen WHERE idAlmacen = '{$row3['idAlmacen']}'");
												while ($row4 = mysqli_fetch_array($query4)) {
													$nombreAlmacenFinal = $row4['descripcion'];
													$html.= "<td class='text-center'>{$row4['descripcion']}</td>";
												}
											}
											$html.= "<td class='text-center'>{$row2['idUbicacionFinal']}</td>";
											$html.= "<td class='text-center'>{$row2['cantidad']}</td>";
											$html.= "</tr>";
										}
									}
								}

								$html.= '
								</tbody>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<h6 class="text-center" style="text-decoration: underline">Correcciones de
								Inventario</h6>
							<div class="spacer10"></div>
							<table class="table">
								<thead>
								<tr>
									<th class="text-center">Item</th>
									<th class="text-center">Trans.</th>
									<th class="text-center">Fecha</th>
									<th class="text-center">Prod.</th>
									<th class="text-center">Almacén</th>
									<th class="text-center">Ubic.</th>
									<th class="text-center">Cant. I</th>
									<th class="text-center">Cant. F</th>
									<th class="text-center">Motivo</th>
								</tr>
								</thead>
								<tbody>';

								$dateInicio = explode("-", $_POST['fechaInicioReporte']);
								$dateFin = explode("-", $_POST['fechaFinReporte']);
								$fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
								$fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
								$aux = 0;
								$query = mysqli_query($link, "SELECT * FROM Transaccion WHERE idTipoTransaccion IN (8) ORDER BY fechaTransaccion DESC");
								while ($row = mysqli_fetch_array($query)) {
									$fechaTransac = explode(" ",$row['fechaTransaccion']);
									$fechaTransaccionCompleta = $fechaTransac[0];
									if($fechaTransaccionCompleta <= $fechaFin && $fechaTransaccionCompleta >= $fechaInicio){
										$valorReferencia = 0;
										$query2 = mysqli_query($link, "SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['idTransaccion']}'");
										while ($row2 = mysqli_fetch_array($query2)) {
											$aux++;
											$html.= "<tr>";
											$html.= "<td class='text-center'>$aux</td>";
											$html.= "<td class='text-center'>{$row['idTransaccion']}</td>";
											$fechaTransaccion = explode(" ",$row['fechaTransaccion']);
											$html.= "<td class='text-center'>{$fechaTransaccion[0]}</td>";
											$query3 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$row2['idProducto']}'");
											while ($row3 = mysqli_fetch_array($query3)) {
												$nombreProducto = $row3['nombreCorto'];
												$query4 = mysqli_query($link,"SELECT * FROM Color WHERE idColor = '{$row3['idColor']}'");
												while($row4 = mysqli_fetch_array($query4)){
													$atributo = $row4['descripcion'];
												}
												$html.= "<td class='text-center'>{$row3['nombreCorto']} {$atributo}</td>";
											}
											$query3 = mysqli_query($link, "SELECT * FROM Ubicacion WHERE idUbicacion = '{$row2['idUbicacionInicial']}'");
											while ($row3 = mysqli_fetch_array($query3)) {
												$query4 = mysqli_query($link, "SELECT * FROM Almacen WHERE idAlmacen = '{$row3['idAlmacen']}'");
												while ($row4 = mysqli_fetch_array($query4)) {
													$nombreAlmacen = $row4['descripcion'];
													$html.= "<td class='text-center'>{$row4['descripcion']}</td>";
												}
											}
											$html.= "<td class='text-center'>{$row2['idUbicacionInicial']}</td>";
											$html.= "<td class='text-center'>{$row2['stockInicial']}</td>";
											$html.= "<td class='text-center'>{$row2['stockFinal']}</td>";
											$html.= "<td class='text-center'>{$row['observacion']}</td>";
											$html.= "</tr>";
										}
									}
								}
								$html.= '
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
                    <h4>Reporte de Inventario</h4><br>
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
	$nombrearchivo="Reporte de Inventario - {$_POST['fechaInicioReporte']} al {$_POST['fechaFinReporte']}.pdf";
	$mpdf = new mPDF('','A4',0,'',5,5,40,15,6,6);

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