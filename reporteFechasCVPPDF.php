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
                            <h6 class="text-center" style="text-decoration: underline">Compras de Mercadería</h6>
                            <div class="spacer10"></div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-center">Item</th>
                                    <th class="text-center">Colaboradora</th>
                                    <th class="text-center">Transacción</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Producto</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-center">Valor</th>
                                    <th class="text-center">Total</th>
                                </tr>
                                </thead>
                                <tbody>';
    $valorCompras = 0;
    $dateInicio = explode("-", $_POST['fechaInicioReporte']);
    $dateFin = explode("-", $_POST['fechaFinReporte']);
	$fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
	$fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
    $aux = 0;
    $aux2 = 0;
    $query = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTipoTransaccion IN (1)");
    while($row = mysqli_fetch_array($query)){
	    $fechaTransac = explode("|",$row['fechaTransaccion']);
	    $fechaTransaccionCompleta = $fechaTransac[0];
	    if($fechaTransaccionCompleta <= $fechaFin && $fechaTransaccionCompleta >= $fechaInicio){
		    $colaboradorasCompras[$aux2] = $row['idColaborador'];
		    $query2 = mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['idTransaccion']}'");
		    while($row2 = mysqli_fetch_array($query2)){
			    $aux++;
			    $html .='<tr>
            <td class="text-center">'.$aux.'</td>';
			    $query3 = mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$row['idColaborador']}'");
			    while($row3 = mysqli_fetch_array($query3)){
				    $nombreColaborador = $row3['nombres'];
				    $nombreColaborador = $nombreColaborador." ".$row3['apellidos'];
				    $html .='<td class="text-center">'.$row3['nombres'].' '.$row3['apellidos'].'</td>';
			    }
			    $html .='<td class="text-center">'.$row['idTransaccion'].'</td>';
			    $fechaTransaccion = explode("|",$row['fechaTransaccion']);
			    $html .='<td class="text-center">'.$fechaTransaccion[0].'</td>';
			    $query3 = mysqli_query($link,"SELECT * FROM Producto WHERE idProducto = '{$row2['idProducto']}'");
			    while($row3 = mysqli_fetch_array($query3)){
				    $nombreProducto = $row3['nombreCorto'];
				    $query4 = mysqli_query($link,"SELECT * FROM Color WHERE idColor = '{$row3['idColor']}'");
				    while($row4 = mysqli_fetch_array($query4)){
					    $atributo = $row4['descripcion'];
				    }
				    $html .='<td class="text-center">'.$row3['nombreCorto'].' '.$atributo.'</td>';
			    }
			    $html .='<td class="text-center">'.$row2['cantidad'].'</td>';
			    if($row2['valorUnitario'] == 0){
				    $html .='<td class="text-center">0</td>';
				    $valorTotal = $row2['cantidad'] * $row2['valorUnitario'];
			    }else{
			    	$valorUnitario = round($row2['valorUnitario'],2);
				    $html .= "<td class='text-center'>{$valorUnitario}</td>";
				    $valorTotal = $row2['cantidad'] * $row2['valorUnitario'];
			    }
			    $valorTotal = round($valorTotal,2);
			    $html .= "<td class='text-center'>{$valorTotal}</td>";
			    $totalCompras += $valorTotal;
			    if(!isset($colaboradorasComprasValores[$aux2])){
				    $colaboradorasComprasValores[$aux2] = 0;
			    }
			    $colaboradorasComprasValores[$aux2] += $valorTotal;
			    $html .= "</tr>";
		    }
		    $aux2++;
	    }
    }
    foreach(array_unique($colaboradorasCompras) as $comprador){
        for($i=0;$i<count($colaboradorasCompras);$i++){
            if($comprador == $colaboradorasCompras[$i]){
                if(!isset($colaboradorasComprasTotales[$aux3])){
                    $colaboradorasComprasTotales[$aux3] = 0;
                }
                if(isset($colaboradorasComprasValores[$i])){
                    $colaboradorasComprasTotales[$aux3] += $colaboradorasComprasValores[$i];
                }
            }
        }
        $colaboradorasComprasActual[$aux3] = $comprador;
        $aux3++;
    }
    $html .='
                                </tbody>
                            </table>
                        </div>
                    </div>
        </section>
        <pagebreak></pagebreak>
        <section class="container">
            <div class="row">
                        <div class="col-12">
                            <h6 class="text-center" style="text-decoration: underline">Ventas de Mercadería</h6>
                            <div class="spacer10"></div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-center">Item</th>
                                    <th class="text-center">Colaboradora</th>
                                    <th class="text-center">Transacción</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Producto</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-center">Valor</th>
                                    <th class="text-center">Total</th>
                                </tr>
                                </thead>
                                <tbody>';
    $valorCompras = 0;
    $dateInicio = explode("-", $_POST['fechaInicioReporte']);
    $dateFin = explode("-", $_POST['fechaFinReporte']);
	$fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
	$fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
    $aux = 0;
    $aux2 = 0;
    $aux3 = 0;
    $query = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTipoTransaccion IN (5)");
    while($row = mysqli_fetch_array($query)){
        $fechaTransac = explode("|",$row['fechaTransaccion']);
        $fechaTransaccionCompleta = $fechaTransac[0];
        if($fechaTransaccionCompleta <= $fechaFin && $fechaTransaccionCompleta >= $fechaInicio){
		    $colaboradorasVentas[$aux2] = $row['idColaborador'];
		    $query2 = mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['idTransaccion']}'");
		    while($row2 = mysqli_fetch_array($query2)){
			    $aux++;
			    $html .= "<tr>";
			    $html .= "<td class='text-center'>$aux</td>";
			    $query3 = mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$row['idColaborador']}'");
			    while($row3 = mysqli_fetch_array($query3)){
				    $nombreColaborador = $row3['nombres'];
				    $nombreColaborador = $nombreColaborador." ".$row3['apellidos'];
				    $html .= "<td class='text-center'>{$row3['nombres']} {$row3['apellidos']}</td>";
			    }
			    $html .= "<td class='text-center'>{$row['idTransaccion']}</td>";
			    $fechaTransaccion = explode("|",$row['fechaTransaccion']);
			    $html .= "<td class='text-center'>{$fechaTransaccion[0]}</td>";
			    $query3 = mysqli_query($link,"SELECT * FROM Producto WHERE idProducto = '{$row2['idProducto']}'");
			    while($row3 = mysqli_fetch_array($query3)){
				    $nombreProducto = $row3['nombreCorto'];
				    $query4 = mysqli_query($link,"SELECT * FROM Color WHERE idColor = '{$row3['idColor']}'");
				    while($row4 = mysqli_fetch_array($query4)){
					    $atributo = $row4['descripcion'];
				    }
				    $html .= "<td class='text-center'>{$row3['nombreCorto']} {$atributo}</td>";
			    }
			    $html .= "<td class='text-center'>{$row2['cantidad']}</td>";
			    if($row2['valorUnitario'] == 0){
				    $html .= "<td class='text-center'>0</td>";
				    $valorTotal = $row2['cantidad'] * $row2['valorUnitario'];
			    }else{
			    	$valorUnitario = round($row2['valorUnitario'],2);
				    $html .= "<td class='text-center'>{$valorUnitario}</td>";
				    $valorTotal = $row2['cantidad'] * $row2['valorUnitario'];
			    }
			    $valorTotal = round($valorTotal,2);
			    $html .= "<td class='text-center'>{$valorTotal}</td>";
			    $totalVentas += $valorTotal;
			    if(!isset($colaboradorasVentasValores[$aux2])){
				    $colaboradorasVentasValores[$aux2] = 0;
			    }
			    $colaboradorasVentasValores[$aux2] += $valorTotal;
			    $html .= "</tr>";
		    }
		    $aux2++;
	    }
    }
    foreach(array_unique($colaboradorasVentas) as $comprador){
        for($i=0;$i<count($colaboradorasVentas);$i++){
            if($comprador == $colaboradorasVentas[$i]){
                if(!isset($colaboradorasVentasTotales[$aux3])){
                    $colaboradorasVentasTotales[$aux3] = 0;
                }
                if(isset($colaboradorasVentasValores[$i])){
                    $colaboradorasVentasTotales[$aux3] += $colaboradorasVentasValores[$i];
                }
            }
        }
        $colaboradorasVentasActual[$aux3] = $comprador;
        $aux3++;
    }
    $html .='
                                </tbody>
                            </table>
                        </div>
                    </div>
        </section>
        <pagebreak></pagebreak>
        <section class="container">
        <div class="row">
                        <div class="col-12">
                            <h6 class="text-center" style="text-decoration: underline">Préstamos de Mercadería</h6>
                            <div class="spacer10"></div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-center">Item</th>
                                    <th class="text-center">Colaboradora</th>
                                    <th class="text-center">Transacción</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Producto</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-center">Valor</th>
                                    <th class="text-center">Total</th>
                                </tr>
                                </thead>
                                <tbody>';
    $valorCompras = 0;
    $dateInicio = explode("-", $_POST['fechaInicioReporte']);
    $dateFin = explode("-", $_POST['fechaFinReporte']);
	$fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
	$fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
    $aux = 0;
    $aux2 = 0;
    $aux3 = 0;
    $query = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTipoTransaccion IN (6)");
    while($row = mysqli_fetch_array($query)){
        $fechaTransac = explode("|",$row['fechaTransaccion']);
        $fechaTransaccionCompleta = $fechaTransac[0];
	    if($fechaTransaccionCompleta <= $fechaFin && $fechaTransaccionCompleta >= $fechaInicio){
		    $colaboradorasPrestamos[$aux2] = $row['idColaborador'];
		    $query2 = mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['idTransaccion']}'");
		    while($row2 = mysqli_fetch_array($query2)){
			    $aux++;
			    $html .= "<tr>";
			    $html .= "<td class='text-center'>$aux</td>";
			    $query3 = mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$row['idColaborador']}'");
			    while($row3 = mysqli_fetch_array($query3)){
				    $nombreColaborador = $row3['nombres'];
				    $nombreColaborador = $nombreColaborador." ".$row3['apellidos'];
				    $html .= "<td class='text-center'>{$row3['nombres']} {$row3['apellidos']}</td>";
			    }
			    $html .= "<td class='text-center'>{$row['idTransaccion']}</td>";
			    $fechaTransaccion = explode("|",$row['fechaTransaccion']);
			    $html .= "<td class='text-center'>{$fechaTransaccion[0]}</td>";
			    $query3 = mysqli_query($link,"SELECT * FROM Producto WHERE idProducto = '{$row2['idProducto']}'");
			    while($row3 = mysqli_fetch_array($query3)){
				    $nombreProducto = $row3['nombreCorto'];
				    $query4 = mysqli_query($link,"SELECT * FROM Color WHERE idColor = '{$row3['idColor']}'");
				    while($row4 = mysqli_fetch_array($query4)){
					    $atributo = $row4['descripcion'];
				    }
				    $html .= "<td class='text-center'>{$row3['nombreCorto']} {$atributo}</td>";
			    }
			    $html .= "<td class='text-center'>{$row2['cantidad']}</td>";
			    if($row2['valorUnitario'] == 0){
				    $html .= "<td class='text-center'>0</td>";
				    $valorTotal = $row2['cantidad'] * $row2['valorUnitario'];
			    }else{
			    	$valorUnitario = round($row2['valorUnitario'],2);
				    $html .= "<td class='text-center'>{$valorUnitario}</td>";
				    $valorTotal = $row2['cantidad'] * $row2['valorUnitario'];
			    }
			    $valorTotal = round($valorTotal,2);
			    $html .= "<td class='text-center'>{$valorTotal}</td>";
			    $totalPrestamos += $valorTotal;
			    if(!isset($colaboradorasPrestamosValores[$aux2])){
				    $colaboradorasPrestamosValores[$aux2] = 0;
			    }
			    $colaboradorasPrestamosValores[$aux2] += $valorTotal;
			    $html .= "</tr>";
		    }
		    $aux2++;
	    }
    }
    foreach(array_unique($colaboradorasPrestamos) as $comprador){
        for($i=0;$i<count($colaboradorasPrestamos);$i++){
            if($comprador == $colaboradorasPrestamos[$i]){
                if(!isset($colaboradorasPrestamosTotales[$aux3])){
                    $colaboradorasPrestamosTotales[$aux3] = 0;
                }
                if(isset($colaboradorasPrestamosValores[$i])){
                    $colaboradorasPrestamosTotales[$aux3] += $colaboradorasPrestamosValores[$i];
                }
            }
        }
        $colaboradorasPrestamosActual[$aux3] = $comprador;
        $aux3++;
    }
	$totalCompras = round($totalCompras,2);
	$totalVentas = round($totalVentas,2);
	$totalPrestamos = round($totalPrestamos,2);
    $html .='
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
                    <pagebreak></pagebreak>
                    <section class="container">
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-center" style="text-decoration: underline">Resumen</h6>
                            <div class="spacer10"></div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-center">Total Compras (S/.)</th>
                                    <th class="text-center">Total Ventas (S/.)</th>
                                    <th class="text-center">Total Préstamos (S/.)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                <td class="text-center">'.$totalCompras.'</td>
                                <td class="text-center">'.$totalVentas.'</td>
                                <td class="text-center">'.$totalPrestamos.'</td>
                                </tr>
                                </tbody>
                            </table>
                            <hr>
                            <h6 class="text-center" style="text-decoration: underline">Resumen por Colaboradora</h6>
                            <div class="spacer10"></div>
                            <table class="table">
                                <tbody>
                                <tr class="bg-faded">
                                    <th colspan="3" class="text-center">Compras</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Nombre</th>
                                    <th class="text-center">Código</th>
                                    <th class="text-center">Monto</th>
                                </tr>';
    $aux = 0;
    foreach($colaboradorasComprasActual as $comprador){
        $html .= "<tr>";
        $query = mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$comprador}'");
        while($row = mysqli_fetch_array($query)){
            $nombreColaborador = $row['nombres'];
            $nombreColaborador = $nombreColaborador." ".$row['apellidos'];
            $html .="<td class='text-center'>{$row['nombres']} {$row['apellidos']}</td>";
        }
        $html .="<td class='text-center'>{$comprador}</td>";
        $html .="<td class='text-center'>{$colaboradorasComprasTotales[$aux]}</td>";
        $html .= "</tr>";
        $aux++;
    }
    $html .='
                                </tbody>
                            </table>
                            <div class="spacer30"></div>
                            <table class="table">
                                <tbody>
                                <tr class="bg-faded">
                                    <th colspan="3" class="text-center">Ventas</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Nombre</th>
                                    <th class="text-center">Código</th>
                                    <th class="text-center">Monto</th>
                                </tr>';
    $aux = 0;
    foreach($colaboradorasVentasActual as $comprador){
        $html .= "<tr>";
        $query = mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$comprador}'");
        while($row = mysqli_fetch_array($query)){
            $nombreColaborador = $row['nombres'];
            $nombreColaborador = $nombreColaborador." ".$row['apellidos'];
            $html .="<td class='text-center'>{$row['nombres']} {$row['apellidos']}</td>";
        }
        $html .="<td class='text-center'>{$comprador}</td>";
        $html .="<td class='text-center'>{$colaboradorasVentasTotales[$aux]}</td>";
        $html .= "</tr>";
        $aux++;
    }
    $html .='
                                </tbody>
                            </table>
                            <div class="spacer30"></div>
                            <table class="table">
                                <tbody>
                                <tr class="bg-faded">
                                    <th colspan="3" class="text-center">Prestamos</th>
                                </tr>
                                <tr>
                                    <th class="text-center" width="33%">Nombre</th>
                                    <th class="text-center" width="33%">Código</th>
                                    <th class="text-center" width="33%">Monto</th>
                                </tr>';
    $aux = 0;
    foreach($colaboradorasPrestamosActual as $comprador){
        $html .= "<tr>";
        $query = mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$comprador}'");
        while($row = mysqli_fetch_array($query)){
            $nombreColaborador = $row['nombres'];
            $nombreColaborador = $nombreColaborador." ".$row['apellidos'];
            $html .="<td class='text-center'>{$row['nombres']} {$row['apellidos']}</td>";
        }
        $html .="<td class='text-center'>{$comprador}</td>";
        $html .="<td class='text-center'>{$colaboradorasPrestamosTotales[$aux]}</td>";
        $html .= "</tr>";
        $aux++;
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
                    <h4>Reporte de Compras, Ventas y Préstamos</h4><br>
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
    $nombrearchivo='Reporte de Compras,Ventas,Préstamos del '.$_POST['fechaInicioReporte'].' al '.$_POST['fechaFinReporte'].'.pdf';
    $mpdf = new mPDF('','A4',0,'','5',5,45,15,6,6);

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