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
						<div class="spacer10"></div>
							<table class="table table-bordered text-center" id="myTable">
							<thead class="thead-default">
							<tr>
								<th class="text-center">idTransaccion</th>
								<th class="text-center">Fecha</th>
                                <th class="text-center">Cliente</th>
								<th class="text-center">Producto</th>
							</tr>
							</thead>
							<tbody>';
							$query = mysqli_query($link, "SELECT Transaccion.idTransaccion, TransaccionProducto.idProducto, Proveedor.nombre, Transaccion.fechaTransaccion FROM Transaccion INNER JOIN TransaccionProducto ON Transaccion.idTransaccion = TransaccionProducto.idTransaccion INNER JOIN Proveedor ON Transaccion.idProveedor = Proveedor.idProveedor WHERE idTipoTransaccion = 6 AND idEstado = 6 ORDER BY fechaTransaccion DESC");
							while($row = mysqli_fetch_array($query)){
								$query2 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$row['idProducto']}'");
								while($row1 = mysqli_fetch_array($query2)){
									$nombreProducto = $row1['nombreCorto'];
								}
								$html .="<tr>";
								$html .="<td>{$row['idTransaccion']}</td>";
								$html .="<td>{$row['fechaTransaccion']}</td>";
								$html .="<td>{$row['nombre']}</td>";
								$html .="<td>{$nombreProducto}</td>";
								$html .="</tr>";
							}
$html.='
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
                    <h4>Reporte de Deudas de Prestamos</h4><br>
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
	$nombrearchivo="ReporteDeDeudasDePrestamos.pdf";
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