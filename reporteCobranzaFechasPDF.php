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
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Movimiento</th>
                                    <th class="text-center">Transacción</th>
                                    <th class="text-center">Responsable</th>
                                    <th class="text-center">Cliente</th>
                                    <th class="text-center">Monto</th>
                                    <th class="text-center">Monto Restante</th>
                                    <th class="text-center">Vencimiento</th>
                                </tr>
                                </thead>
                                <tbody>';
                                $dateInicio = explode("-", $_POST['fechaInicioReporte']);
                                $dateFin = explode("-", $_POST['fechaFinReporte']);
                                $fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
                                $fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
                                $transaccionReferencia = "-";
                                $query = mysqli_query($link, "SELECT Movimiento.idTransaccionPrimaria, Movimiento.idMovimiento, Movimiento.fecha, Movimiento.monto, Colaborador.nombres, Colaborador.apellidos, Proveedor.nombre, Transaccion.montoRestante, Transaccion.fechaVencimiento FROM Movimiento INNER JOIN Transaccion ON Movimiento.idTransaccionPrimaria = Transaccion.idTransaccion INNER JOIN Colaborador ON Movimiento.idColaborador = Colaborador.idColaborador INNER JOIN Proveedor ON Movimiento.idProveedor = Proveedor.idProveedor WHERE Movimiento.fecha >= '{$fechaInicio} 00:00:00' AND Movimiento.fecha <= '{$fechaFin} 23:59:59' AND Movimiento.monto > 0 ORDER BY Movimiento.fecha");
                                while ($row = mysqli_fetch_array($query)) {
                                    $html .="<tr>";
                                    $fechaTransaccion = explode(" ",$row['fecha']);
                                    $html .="<td class='text-center'>{$fechaTransaccion[0]}</td>";
                                    $html .="<td class='text-center'>{$row['idMovimiento']}</td>";
                                    $html .="<td class='text-center'>{$row['idTransaccionPrimaria']}</td>";
                                    $html .="<td class='text-center'>{$row['nombres']} {$row['apellidos']}</td>";
                                    $html .="<td class='text-center'>{$row['nombre']}</td>";
                                    $html .="<td class='text-center'>{$row['monto']}</td>";
                                    $html .="<td class='text-center'>{$row['montoRestante']}</td>";
                                    $html .="<td class='text-center'>{$row['fechaVencimiento']}</td>";
                                    $html .="</tr>";
                                }
    $html .='
                                </tbody>
                            </table>
                        </div>
                    </div>';

    $html .='
                
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
                    <h6>Reporte de Cobranzas</h6><br>
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
    $nombrearchivo="Reporte de Cobranzas - {$_POST['fechaInicioReporte']} al {$_POST['fechaFinReporte']}.pdf";
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