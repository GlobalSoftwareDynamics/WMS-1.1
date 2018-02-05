<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
    include('adminTemplate.php');
    include('funciones.php');

    $total=0;
    $totalconflete=0;
    $i=0;
    $array=array();
    $result=mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$_POST['idTransaccion']}'");
    while ($fila=mysqli_fetch_array($result)){

        $descuento=1;
        $desc=mysqli_query($link,"SELECT * FROM Descuento WHERE idDescuento = '{$fila['idDescuento']}'");
        while ($fila1=mysqli_fetch_array($desc)){
            $descuento=1-($fila1['porcentaje']/100);
        }

        $totalproducto=$fila['valorUnitario']*$fila['cantidad']*$descuento;

        $total=$total+$totalproducto;

        $cantidad=$fila['cantidad'];
        $query=mysqli_query($link,"SELECT * FROM Almacen ORDER BY prioridad ASC");
        while ($row=mysqli_fetch_array($query)){
            $query1=mysqli_query($link,"SELECT * FROM UbicacionProducto WHERE idProducto = '{$fila['idProducto']}' AND idUbicacion IN (SELECT idUbicacion FROM Ubicacion WHERE idAlmacen = '{$row['idAlmacen']}') ORDER BY idUbicacion");
            while ($row1=mysqli_fetch_array($query1)){
                if($cantidad>0&&$row1['stock']>0){
                    if ($row1['stock']>$cantidad||$row1['stock']===$cantidad){
                        $array[$i]=array("{$row1['idProducto']}","{$row1['idUbicacion']}",$cantidad);
                        $cantidad=0;
                        $i++;
                    }else{
                        $cantidad=$cantidad-$row1['stock'];
                        $array[$i]=array("{$row1['idProducto']}","{$row1['idUbicacion']}",$row1['stock']);
                        $i++;
                    }
                }
            }
        }
    }
    $totalconflete=$total+$_POST['costoEnvio'];
    $totalconflete=$totalconflete*1.02;
    ?>

    <section class="container">
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header card-inverse card-info">
                            <div class="float-left">
                                <i class="fa fa-shopping-cart"></i>
                                Cancelación de Venta
                            </div>
                            <div class="float-right">
								<div class="dropdown">
                                    <button form="formOV" name="addOV" class="btn btn-secondary btn-sm">Finalizar</button>
                                    <button form="formOV" formaction="nuevaOV_Productos.php" name="regresar" class="btn btn-secondary btn-sm">Regresar</button>
                                </div>
                            </div>
                    </div>
                    <div class="card-block">
                        <div class="col-12">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#general" role="tab">Datos de Facturación</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="general" role="tabpanel">
                                    <div class="spacer30"></div>
                                    <form method="post" action="gestionOV.php" id="formOV">
                                        <input type='hidden' name='idTransaccion' value='<?php echo $_POST['idTransaccion'];?>'>
                                        <input type='hidden' name='costoEnvio' value='<?php echo $_POST['costoEnvio'];?>'>
                                        <div class="form-group row">
                                            <label for="total" class="col-4 col-form-label">Total:</label>
                                            <div class="col-8 row">
                                                <input class="form-control" type="text" id="total" name="montototal" value="<?php echo round($totalconflete,2);?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="medioPago" class="col-4 col-form-label">Medio de Pago:</label>
                                            <div class="col-8 row">
                                                <select class="form-control" id="medioPago" name="medioPago">
                                                    <option>Seleccionar</option>
                                                    <?php
                                                    $query=mysqli_query($link,"SELECT * FROM MedioPago");
                                                    while ($fila=mysqli_fetch_array($query)){
                                                        if($fila['idMedioPago']==3||$fila['idMedioPago']==4){
                                                        }else{
                                                            echo "
                                                                <option value='{$fila['idMedioPago']}'>{$fila['descripcion']}</option>
                                                            ";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="comprobante" class="col-4 col-form-label">Comprobante:</label>
                                            <div class="col-8 row">
                                                <select class="form-control" id="comprobante" name="comprobante">
                                                    <option>Seleccionar</option>
                                                    <?php
                                                    $query=mysqli_query($link,"SELECT * FROM Comprobante");
                                                    while ($fila=mysqli_fetch_array($query)){
                                                        echo "
                                                            <option value='{$fila['idComprobante']}'>{$fila['descripcion']}</option>
                                                        ";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="montocancel" class="col-4 col-form-label">Cancelado:</label>
                                            <div class="col-8 row">
                                                <input class="form-control" type="text" id="montocancel" name="montoCancelado" oninput="montorestante(<?php echo $totalconflete;?>,this.value);dias(<?php echo $totalconflete;?>,this.value)">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="restante" class="col-4 col-form-label">Saldo:</label>
                                            <div class="col-8 row" id="montorest">
                                                <input class="form-control" type="text" id="restante" name="montofaltante" readonly>
                                            </div>
                                        </div>
                                        <div id="fechadias"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-header card-inverse card-info">
                        <div class="float-left">
                            <i class="fa fa-list"></i>
                            Ubicación de Productos a Retirar
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="col-12">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#general" role="tab">Detalle de Ubicación</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="general" role="tabpanel">
                                    <div class="spacer30"></div>
                                    <table class="table text-center">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Producto</th>
                                            <th class="text-center">Ubicación</th>
                                            <th class="text-center">Cantidad</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        for ($row = 0; $row < $i; $row++) {
                                            echo "<tr>";
                                            for ($col = 0; $col < 3; $col++) {
                                                echo "<td>".$array[$row][$col]."</td>";
                                            }
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
            </div>
        </div>
    </section>

    <?php
    include('footerTemplate.php');
}else{
    include('sessionError.php');
}