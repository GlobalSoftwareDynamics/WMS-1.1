<?php
include('session.php');
if(isset($_SESSION['login'])) {
    include('adminTemplate.php');
    include('funciones.php');

    $result = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$_POST['idTransaccion']}'");
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
                            <form method="post" action="gestionOV.php" id="formOC">
                                <div class="float-left">
                                    <i class="fa fa-shopping-bag"></i>
                                    Detalle General de la Orden de Venta
                                </div>
                                <div class="float-right">
                                    <div class="dropdown">
                                        <?php
                                        if(isset($_POST['detalle'])){
                                            echo "<input formaction='gestionCaja.php' type='submit' value='Volver' name='volver' class='btn btn-secondary btn-sm'>";
                                        }elseif (isset($_POST['detalleHT'])){
                                            echo "<input name='idProducto' value='{$_POST['idProducto']}' type='hidden'>";
                                            echo "<input formaction='historialTransacciones.php' type='submit' value='Volver' name='volver' class='btn btn-secondary btn-sm'>";
                                        }elseif(isset($_POST['cancelacionGDV'])){
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
                                    <div class="col-9"><p><?php echo $_POST['idTransaccion']; ?></p></div>
                                </div>
                                <div class="row">
                                    <div class="col-3"><p><b>Cliente:</b></p></div>
                                    <div class="col-9"><p><?php echo $proveedor; ?></p></div>
                                </div>
                                <div class="row">
                                    <div class="col-3"><p><b>Fecha:</b></p></div>
                                    <div class="col-9"><p><?php $fecha = explode("|",$row['fechaTransaccion']); echo $fecha[0];?></p></div>
                                </div>
                                <?php
                                if($row['montoRestante']>0){
                                    ?>
                                    <div class="row">
                                        <div class="col-3"><p><b>Monto Pendiente de Pago:</b></p></div>
                                        <div class="col-9"><p>S/. <?php echo round($row['montoRestante'],2); ?></p></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3"><p><b>Fecha de Vencimiento:</b></p></div>
                                        <div class="col-9"><p><?php echo $row['fechaVencimiento']; ?></p></div>
                                    </div>
                                    <?php
                                }else{
                                }
                                ?>
                                <div class="row">
                                    <div class="col-3"><p><b>Costo de Envío (S/.):</b></p></div>
                                    <div class="col-9"><p>S/. <?php $costoEnvio = $row['costoTransaccion']; echo $row['costoTransaccion']; ?></p></div>
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
                                <table class="table text-center">
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
                                    $totalventa=0;
                                    $total=0;
                                    $totaldescuento=0;
                                    $subtotal=0;
                                    $subtotalsinsunat=0;
                                    $totalsunat=0;
                                    $descuentounitario=0;
                                    $query = mysqli_query($link, "SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$_POST['idTransaccion']}'");
                                    while($row = mysqli_fetch_array($query)){
                                        echo "<tr>";
                                        echo "<td>{$aux}</td>";
                                        $aux ++;
                                        $query2 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$row['idProducto']}'");
                                        while($row2 = mysqli_fetch_array($query2)){
                                            echo "<td>{$row2['idProducto']} - {$row2['nombreCorto']}</td>";
                                        }
                                        $valor=round($row['valorUnitario'],2);
                                        echo "<td>{$row['cantidad']}</td>";
                                        echo "<td>S/. {$valor}</td>";
                                        $descuento=1;
                                        $descuentoneto=1;
                                        $desc=mysqli_query($link,"SELECT * FROM Descuento WHERE idDescuento = '{$row['idDescuento']}'");
                                        while ($fila=mysqli_fetch_array($desc)){
                                            if($fila['porcentaje']!=null){
                                                $descuento=1-($fila['porcentaje']/100);
                                                $descuentoneto=$fila['porcentaje']/100;
                                                $descuentounitario=$row['cantidad'] *$row['valorUnitario']*$descuentoneto;
                                                $totaldescuento=$totaldescuento+$descuentounitario;
                                            }
                                        }
                                        $subtotalproducto=$row['cantidad'] * $row['valorUnitario'];
                                        $subtotal=$subtotal+$subtotalproducto;
                                        $descuentoproducto=$row['valorUnitario'] * $descuento;
                                        $total = $row['cantidad'] * $descuentoproducto;
                                        $subtotalsinsunat=$subtotalsinsunat+$total;

                                        $subtotalproductoround=round($subtotalproducto,2);
                                        echo "<td>S/. {$subtotalproductoround}</td>";
                                        echo "<td>{$row['observacion']}</td>";
                                        echo "</tr>";
                                    }
                                    $totalsunat=$subtotalsinsunat*0.02+$costoEnvio*0.02;
                                    $subtotalsinsunat=$subtotalsinsunat+$costoEnvio;
                                    $totalventa=$subtotalsinsunat+$totalsunat;
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
                <div class="col-7">
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
                                    $result = mysqli_query($link,"SELECT * FROM Movimiento WHERE idTransaccionPrimaria = '{$_POST['idTransaccion']}' ORDER BY fecha DESC");
                                    while ($fila=mysqli_fetch_array($result)){
                                        $result3=mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$fila['idColaborador']}'");
                                        while ($fila3=mysqli_fetch_array($result3)){
                                            $responsable = $fila3['nombres']." ".$fila3['apellidos'];
                                        }
                                        $result3=mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$fila['idProveedor']}'");
                                        while ($fila3=mysqli_fetch_array($result3)){
                                            $proveedor = $fila3['nombre'];
                                        }
                                        $fecha=explode("|",$fila['fecha']);
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
                <div class="col-5">
                    <table class="table text-center">
                        <tbody>
                        <tr>
                            <th>SubTotal Venta:</th>
                            <td>S/. <?php echo round($subtotal,2)?></td>
                        </tr>
                        <tr>
                            <th>Descuento:</th>
                            <td>S/. <?php echo round($totaldescuento,2)?></td>
                        </tr>
                        <tr>
                            <th>Costo de Envío:</th>
                            <td>S/. <?php echo round($costoEnvio,2);?></td>
                        </tr>
                        <tr>
                            <th>Sub Total sin Impuestos:</th>
                            <td>S/. <?php echo round($subtotalsinsunat,2);?></td>
                        </tr>
                        <tr>
                            <th>Percepción RS.261-2005 SUNAT 2%:</th>
                            <td>S/. <?php echo round($totalsunat,2);?></td>
                        </tr>
                        <tr>
                            <th>Total Venta:</th>
                            <td>S/. <?php echo round($totalventa,2);?></td>
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