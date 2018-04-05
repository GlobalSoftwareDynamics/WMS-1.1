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
                <section class="container">';
    $result = mysqli_query($link,"SELECT idAlmacen, descripcion FROM Almacen ORDER BY prioridad ASC");
    while ($fila = mysqli_fetch_array($result)){
        $html .='
        <div class="row">
            <div class="col-12">
                <h6 class="text-center" style="text-decoration: underline">Ingresos y Salidas de Mercadería - '.$fila['descripcion'].'</h6>
                <div class="spacer10"></div>
                <table class="table text-center">
                    <thead>
                    <tr>
                        <th class="text-center">Item</th>
                        <th class="text-center">Producto</th>
                        <th class="text-center">Ingreso</th>
                        <th class="text-center">Salida</th>
                        <th class="text-center">Stock</th>
                    </tr>
                    </thead>
                    <tbody>';
                    $dateInicio = explode("-", $_POST['fechaInicioReporte']);
                    $dateFin = explode("-", $_POST['fechaFinReporte']);
                    $fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
                    $fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
                    $aux = 0;
                    $transaccionReferencia = "-";
                    $query = mysqli_query($link, "SELECT logMovimientosAlmacen.idProducto, Producto.nombreCorto, SUM(CASE WHEN logMovimientosAlmacen.idTransaccion IN (SELECT idTransaccion FROM Transaccion WHERE idTipoTransaccion IN (2,4) AND fechaTransaccion >= '2018-02-01 00:00:00' AND fechaTransaccion <= '2018-04-04 23:59:59') THEN logMovimientosAlmacen.cantidad ELSE 0 END) AS CantidadIngreso, SUM(CASE WHEN logMovimientosAlmacen.idTransaccion IN (SELECT idTransaccion FROM Transaccion WHERE idTipoTransaccion IN (5,6) AND fechaTransaccion >= '2018-02-01 00:00:00' AND fechaTransaccion <= '2018-04-04 23:59:59') THEN logMovimientosAlmacen.cantidad ELSE 0 END) AS CantidadSalida FROM logMovimientosAlmacen INNER JOIN Producto ON logMovimientosAlmacen.idProducto = Producto.idProducto WHERE logMovimientosAlmacen.idUbicacion IN (SELECT idUbicacion FROM Ubicacion WHERE idAlmacen = '{$fila['idAlmacen']}') GROUP BY idProducto");
                    while ($row = mysqli_fetch_array($query)) {
                        $aux++;
                        $html .="<tr>";
                        $html .="<td>{$aux}</td>";
                        $query3 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$row['idProducto']}'");
                        while ($row3 = mysqli_fetch_array($query3)) {
                            $nombreProducto = $row3['nombreCorto'];
                            $html .="<td class='text-center'>{$row3['nombreCorto']}</td>";
                        }
                        $html .="<td>{$row['CantidadIngreso']}</td>";
                        $cantidadIngreso = $row['CantidadIngreso'];
                        $html .="<td>{$row['CantidadSalida']}</td>";
                        $cantidadSalida = $row['CantidadSalida'];

                        $stockActual = 0;
                        $select2 = mysqli_query($link, "SELECT * FROM UbicacionProducto WHERE idProducto = '{$row['idProducto']}' AND idUbicacion IN (SELECT idUbicacion FROM Ubicacion WHERE idAlmacen = '{$fila['idAlmacen']}')");
                        while($row2 = mysqli_fetch_array($select2)){
                            $stockActual += $row2['stock'];
                        }
                        $html .="<td>{$stockActual}</td>";
                        $html .="</tr>";
                    }
                    $html .='
                    </tbody>
                </table>
            </div>
        </div>
        <div class="spacer20"></div>';
    }

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
                    <h6>Reporte de Inventario Simple (Ingresos y Salidas)</h6><br>
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
    $nombrearchivo="Reporte de Inventario Simple - {$_POST['fechaInicioReporte']} al {$_POST['fechaFinReporte']}.pdf";
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