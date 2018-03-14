<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
    include('adminTemplate.php');
    include('funciones.php');

    $result = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$_POST['idTransaccionOC']}'");
    while($row = mysqli_fetch_array($result)) {
        $result2 = mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$row['idProveedor']}'");
        while($row2 = mysqli_fetch_array($result2)){
            $proveedor = $row2['nombre'];
        }

        ?>
        <section class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-inverse card-info">
                            <form method="post" action="gestionOC.php" id="formOC">
                                <div class="float-left">
                                    <i class="fa fa-shopping-bag"></i>
                                    Detalle General de la Orden de Compra
                                </div>
                                <div class="float-right">
                                    <div class="dropdown">
                                        <?php
                                        if(isset($_POST['detalle'])){
                                            echo "<input formaction='gestionCaja.php' type='submit' value='Volver' name='volver' class='btn btn-secondary btn-sm'>";
                                        }elseif (isset($_POST['detalleHT'])){
                                            echo "<input name='idProducto' value='{$_POST['idProducto']}' type='hidden'>";
                                            echo "<input formaction='historialTransacciones.php' type='submit' value='Volver' name='volver' class='btn btn-secondary btn-sm'>";
                                        }elseif(isset($_POST['cancelacionGDC'])){
                                            echo "<input formaction='gestionDeudas.php' type='submit' value='Volver' name='volver' class='btn btn-secondary btn-sm'>";
                                        }else{
                                            echo "<input type='submit' value='Volver' name='volver' class='btn btn-secondary btn-sm'>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-block">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-3"><p><b>Número de Orden:</b></p></div>
                                    <div class="col-9"><p><?php echo $_POST['idTransaccionOC']; ?></p></div>
                                </div>
                                <div class="row">
                                    <div class="col-3"><p><b>Número de Orden Unique:</b></p></div>
                                    <div class="col-9"><p><?php echo $row['referenciaTransaccion']; ?></p></div>
                                </div>
                                <div class="row">
                                    <div class="col-3"><p><b>Proveedor:</b></p></div>
                                    <div class="col-9"><p><?php echo $proveedor; ?></p></div>
                                </div>
                                <div class="row">
                                    <div class="col-3"><p><b>Fecha Estimada de Recepción:</b></p></div>
                                    <div class="col-9"><p><?php echo $row['fechaEstimada']; ?></p></div>
                                </div>
                                <div class="row">
                                    <div class="col-3"><p><b>Fecha de Vencimiento:</b></p></div>
                                    <div class="col-9"><p><?php echo $row['fechaVencimiento']; ?></p></div>
                                </div>
                                <div class="row">
                                    <div class="col-3"><p><b>Costo de Envío (S/.):</b></p></div>
                                    <div class="col-9"><p><?php $costoEnvio = $row['costoTransaccion']; echo $row['costoTransaccion']; ?></p></div>
                                </div>
                                <div class="row">
                                    <div class="col-3"><p><b>Observaciones:</b></p></div>
                                    <div class="col-9"><p><?php echo $row['observacion']; ?></p></div>
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
                                        <th class="text-center">Ítem Nro.</th>
                                        <th class="text-center">Descripción</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-center">Precio Unitario (S/.)</th>
                                        <th class="text-center">Total Ítem (S/.)</th>
                                        <th class="text-center">Notas</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $aux = 1;
                                    $sumaTotal = 0;
                                    $query = mysqli_query($link, "SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$_POST['idTransaccionOC']}'");
                                    while($row = mysqli_fetch_array($query)){
                                        echo "<tr>";
                                        echo "<td class='text-center'>{$aux}</td>";
                                        $aux ++;
                                        $query2 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$row['idProducto']}'");
                                        while($row2 = mysqli_fetch_array($query2)){
                                            echo "<td class='text-center'>{$row2['idProducto']} - {$row2['nombreCorto']}</td>";
                                        }
                                        echo "<td class='text-center'>{$row['cantidad']}</td>";
                                        $valorUnitario = round($row['valorUnitario'],2);
                                        echo "<td class='text-center'>{$valorUnitario}</td>";
                                        $total = $row['cantidad'] * $row['valorUnitario'];
                                        $sumaTotal += $total;
                                        $totalRedondeo = round($total,2);
                                        echo "<td class='text-center'>{$totalRedondeo}</td>";
                                        echo "<td class='text-center'>{$row['observacion']}</td>";
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
                <div class="col-8">
                    <div class="card">
                        <div class="card-header card-inverse card-info">
                            <form method="post" action="gestionOC.php" id="formOC">
                                <div class="float-left">
                                    <i class="fa fa-calendar"></i>
                                    Historial de Cancelación
                                </div>
                            </form>
                        </div>
                        <div class="card-block">
                            <div class="col-12">
                                <table class="table text-center">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Transacción</th>
                                        <th class="text-center">Fecha</th>
                                        <th class="text-center">Responsable</th>
                                        <th class="text-center">Cliente</th>
                                        <th class="text-center">Monto</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $result = mysqli_query($link,"SELECT * FROM Movimiento WHERE idTransaccionPrimaria = '{$_POST['idTransaccionOC']}' OR idTransaccionReferencial = '{$_POST['idTransaccionOC']}' ORDER BY fecha DESC");
                                    while ($fila=mysqli_fetch_array($result)){
                                        $result3=mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$fila['idColaborador']}'");
                                        while ($fila3=mysqli_fetch_array($result3)){
                                            $responsable = $fila3['nombres']." ".$fila3['apellidos'];
                                        }
                                        $result3=mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$fila['idProveedor']}'");
                                        while ($fila3=mysqli_fetch_array($result3)){
                                            $proveedor = $fila3['nombre'];
                                        }
                                        $fecha=explode(" ",$fila['fecha']);
                                        echo "<tr>";
                                        echo "<td>{$fila['idMovimiento']}</td>";
                                        echo "<td>{$fecha[0]}</td>";
                                        echo "<td>{$responsable}</td>";
                                        echo "<td>{$proveedor}</td>";
                                        echo "<td>S/. {$fila['monto']}</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <table class="table align-content-end">
                        <tbody>
                        <tr>
                            <th>Subtotal (S/.)</th>
                            <td><?php echo round($sumaTotal,2);?></td>
                        </tr>
                        <tr>
                            <th>Costo de Envío (S/.)</th>
                            <td><?php echo round($costoEnvio,2);?></td>
                        </tr>
                        <tr>
                            <th>Total (S/.)</th>
                            <?php
                            $totalOC = $sumaTotal + $costoEnvio;
                            ?>
                            <td><?php echo round($totalOC,2);?></td>
                        </tr>
                        <tr>
                            <th>Percepción RS.261-2005 SUNAT 2% (S/.)</th>
                            <?php
                            $impuesto = $totalOC * 0.02
                            ?>
                            <td><?php echo round($impuesto,2);?></td>
                        </tr>
                        <tr>
                            <th>Total a Pagar (S/.)</th>
                            <td><?php echo round(($totalOC+$impuesto),2);?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <?php
    }
    include('footerTemplate.php');
}else{
    include('sessionError.php');
}