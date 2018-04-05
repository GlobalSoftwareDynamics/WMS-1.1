<?php
require_once __DIR__ . '/lib/mpdf/mpdf.php';

include('session.php');
include('funciones.php');
if(isset($_SESSION['login'])){

    $html ='
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
                                    <th class="text-center">Producto</th>
                                    <th class="text-center">Transacción</th>
                                    <th class="text-center">Fecha Transacción</th>
                                    <th class="text-center">Compra Asociada</th>
                                    <th class="text-center">Cantidad</th>
                                </tr>
                                </thead>
                                <tbody>';
                                $dateInicio = explode("-", $_POST['fechaInicioReporte']);
                                $dateFin = explode("-", $_POST['fechaFinReporte']);
                                $fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
                                $fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
                                $aux = 0;
                                $result = mysqli_query($link,"SELECT TransaccionProducto.idTransaccion, TransaccionProducto.idProducto, Producto.nombreCorto, Transaccion.fechaEstimada, Transaccion.fechaTransaccion, Transaccion.idEstado, TransaccionProducto.cantidad FROM TransaccionProducto INNER JOIN Producto ON TransaccionProducto.idProducto = Producto.idProducto INNER JOIN Transaccion ON TransaccionProducto.idTransaccion = Transaccion.idTransaccion WHERE TransaccionProducto.idTransaccion LIKE 'OCP%' AND Transaccion.idEstado IN (3,6) AND Transaccion.fechaTransaccion >= '{$fechaInicio} 00:00:00' AND Transaccion.fechaTransaccion <= '{$fechaFin} 23:59:59' ORDER BY Transaccion.fechaTransaccion DESC");
                                while ($fila = mysqli_fetch_array($result)){
                                    $cantidadRecibida = 0;
                                    $result1 = mysqli_query($link,"SELECT idTransaccion, cantidad FROM TransaccionProducto WHERE idTransaccion IN (SELECT idTransaccion FROM Transaccion WHERE referenciaTransaccion = '{$fila['idTransaccion']}' AND idTransaccion LIKE 'OR%') AND idProducto = '{$fila['idProducto']}'");
                                    $numrow = mysqli_num_rows($result1);
                                    if($numrow > 0){
                                        while ($fila1 =  mysqli_fetch_array($result1)){
                                            $cantidadRecibida += $fila1['cantidad'];
                                        }
                                    }

                                    $cantidad = $fila['cantidad'] - $cantidadRecibida;

                                    if($cantidad > 0){
                                        $aux++;
                                        $fechaTransaccion = explode(" ",$fila['fechaTransaccion']);
                                        $html .="<tr>";
                                        $html .="<td>{$aux}</td>";
                                        $html .="<td>{$fila['nombreCorto']}</td>";
                                        $html .="<td>{$fila['idTransaccion']}</td>";
                                        $html .="<td>{$fechaTransaccion[0]}</td>";
                                        $html .="<td>{$fila['fechaEstimada']}</td>";
                                        $html .="<td>{$cantidad}</td>";
                                        $html .="</tr>";
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
                    <h6>Reporte de Premios por Llegar</h6><br>
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
    $nombrearchivo="Reporte de Premios por Llegar - {$_POST['fechaInicioReporte']} al {$_POST['fechaFinReporte']}.pdf";
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