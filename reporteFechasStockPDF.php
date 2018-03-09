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
						<table class="table text-center">
							<thead>
								<tr>
									<th class="text-center">Item</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Producto</th>
                                    <th class="text-center">Ubicación</th>
                                    <th class="text-center">Stock</th>
								</tr>
							</thead>
							<tbody>';
    $dateInicio = explode("-", $_POST['fechaInicioReporte']);
    $dateFin = explode("-", $_POST['fechaFinReporte']);
    $fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
    $fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
    $aux = 0;
    $result = mysqli_query($link,"SELECT * FROM LogStock WHERE fechaCierre >= '{$fechaInicio}' AND fechaCierre <= '{$fechaFin}'");
    while ($fila = mysqli_fetch_array($result)){
        $aux++;
        $html .= "<tr>";
        $html .= "<td>{$aux}</td>";
        $html .= "<td>{$fila['fechaCierre']}</td>";
        $query3 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$fila['idProducto']}'");
        while ($row3 = mysqli_fetch_array($query3)) {
            $nombreProducto = $row3['nombreCorto'];
            $html .= "<td>{$row3['nombreCorto']}</td>";
        }
        $html .= "<td>{$fila['idUbicacion']}</td>";
        $html .= "<td>{$fila['stock']}</td>";
        $html .= "</tr>";
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
                    <h4>Reporte de Stock por Fechas</h4><br>
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
    $nombrearchivo="Reporte de Stock - {$_POST['fechaInicioReporte']} al {$_POST['fechaFinReporte']}.pdf";
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