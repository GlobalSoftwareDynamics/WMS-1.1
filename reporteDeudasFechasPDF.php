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
                            <h6 class="text-center" style="text-decoration: underline">Deudas de Terceros</h6>
                            <div class="spacer10"></div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-center">Item</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Transacción</th>
                                    <th class="text-center">Cliente</th>
                                    <th class="text-center">Monto Pendiente</th>
                                    <th class="text-center">Fecha de Vencimiento</th>
                                </tr>
                                </thead>
                                <tbody>';
                                $dateInicio = explode("-", $_POST['fechaInicioReporte']);
                                $dateFin = explode("-", $_POST['fechaFinReporte']);
                                $fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
                                $fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
                                $aux = 0;
                                $query = mysqli_query($link, "SELECT Transaccion.idTransaccion, Transaccion.montoRestante, Transaccion.fechaVencimiento, Transaccion.fechaTransaccion, Transaccion.idEstado, Proveedor.nombre FROM Transaccion INNER JOIN Proveedor ON Transaccion.idProveedor = Proveedor.idProveedor WHERE Transaccion.idTipoTransaccion IN (5,6) AND Transaccion.idEstado IN (3,6) AND Transaccion.fechaVencimiento >= '{$fechaInicio}' AND Transaccion.fechaVencimiento <= '{$fechaFin}' ORDER BY Transaccion.fechaVencimiento DESC, Transaccion.fechaTransaccion DESC");
                                while ($row = mysqli_fetch_array($query)) {
                                    $aux++;
                                    $fechaTransaccion = explode(" ",$row['fechaTransaccion']);
                                    $html .="<tr>";
                                    $html .="<td class=\'text-center\'>$aux</td>";
                                    $html .="<td class=\'text-center\'>{$fechaTransaccion[0]}</td>";
                                    $html .="<td class=\'text-center\'>{$row['idTransaccion']}</td>";
                                    $html .="<td class=\'text-center\'>{$row['nombre']}</td>";
                                    $html .="<td class=\'text-center\'>S/ {$row['montoRestante']}</td>";
                                    $html .="<td class=\'text-center\'>{$row['fechaVencimiento']}</td>";
                                    $html .="</tr>";
                                }
    $html .='
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-center" style="text-decoration: underline">Deudas Propias</h6>
                            <div class="spacer10"></div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-center">Item</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Transacción</th>
                                    <th class="text-center">Consultora</th>
                                    <th class="text-center">Monto</th>
                                    <th class="text-center">Fecha de Vencimiento</th>
                                </tr>
                                </thead>
                                <tbody>';
                                $dateInicio = explode("-", $_POST['fechaInicioReporte']);
                                $dateFin = explode("-", $_POST['fechaFinReporte']);
                                $fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
                                $fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
                                $aux = 0;
                                $query = mysqli_query($link, "SELECT Transaccion.idTransaccion, Transaccion.montoRestante, Transaccion.fechaVencimiento, Transaccion.fechaTransaccion, Transaccion.idEstado, Proveedor.nombre FROM Transaccion INNER JOIN Proveedor ON Transaccion.idProveedor = Proveedor.idProveedor WHERE Transaccion.idTipoTransaccion = 1 AND Transaccion.montoRestante > 0 AND Transaccion.fechaVencimiento >= '{$fechaInicio}' AND Transaccion.fechaVencimiento <= '{$fechaFin}' ORDER BY Transaccion.fechaVencimiento DESC, Transaccion.fechaTransaccion DESC");
                                while ($row = mysqli_fetch_array($query)) {
                                    $aux++;
                                    $fechaTransaccion = explode(" ",$row['fechaTransaccion']);
                                    $html .="<tr>";
                                    $html .="<td class='text-center'>$aux</td>";
                                    $html .="<td class='text-center'>{$fechaTransaccion[0]}</td>";
                                    $html .="<td class='text-center'>{$row['idTransaccion']}</td>";
                                    $html .="<td class='text-center'>{$row['nombre']}</td>";
                                    $html .="<td class='text-center'>S/ {$row['montoRestante']}</td>";
                                    $html .="<td class='text-center'>{$row['fechaVencimiento']}</td>";
                                    $html .="</tr>";
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
                    <h4>Reporte de Deudas</h4><br>
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
    $nombrearchivo='Reporte de Deudas - '.$_POST['fechaInicioReporte'].' al '.$_POST['fechaFinReporte'].'.pdf';
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