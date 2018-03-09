<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
    include('adminTemplateAutocomplete.php');
    $idCPE = idgen("CPE");
    $idMOV = idgen("MOV");
    ?>

    <section class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-inverse card-info">

                        <div class="float-left">
                            <i class="fa fa-money"></i>
                            Cancelación de Préstamo de Efectivo
                        </div>
                        <div class="float-right">
                            <div class="dropdown">
                                <button form="formOV" name="CancelacionPE" class="btn btn-secondary btn-sm">Guardar</button>
                                <?php
                                if(isset($_POST['cancelacion'])){
                                    echo "<button form='formOV' name='regresar' class='btn btn-secondary btn-sm' formaction='gestionDeudas.php'>Regresar</button>";
                                }else{
                                    echo "<button form='formOV' name='regresar' class='btn btn-secondary btn-sm' formaction='gestionPrestamos.php'>Regresar</button>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="col-12">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#general" role="tab">Detalles de Cancelación</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="general" role="tabpanel">
                                    <div class="spacer30"></div>
                                    <form method="post" action="gestionPrestamos.php" id="formOV">
                                        <div class="form-group row">
                                            <label for="idTransaccionReferencia" class="col-2 col-form-label">Código de Prestamo:</label>
                                            <div class="col-10 row">
                                                <input class="form-control" type="text" id="idTransaccionReferencia" name="idTransaccionReferencia" value="<?php echo $_POST['idTransaccion'];?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="idTransaccion" class="col-2 col-form-label">Número de Transacción:</label>
                                            <div class="col-10 row">
                                                <input class="form-control" type="text" id="idTransaccion" name="idTransaccion" value="<?php echo $idCPE;?>" readonly>
                                                <input class="form-control" type="hidden" id="idMovimiento" name="idMovimiento" value="<?php echo $idMOV;?>" readonly>
                                            </div>
                                        </div>
                                        <?php
                                        $result = mysqli_query($link, "SELECT * FROM Transaccion WHERE idTransaccion = '{$_POST['idTransaccion']}'");
                                        while ($fila = mysqli_fetch_array($result)){
                                            $result1=mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$fila['idProveedor']}'");
                                            while ($fila1=mysqli_fetch_array($result1)){
                                                $nombre=$fila1['nombre'];
                                            }
                                            ?>
                                            <div class="form-group row">
                                                <label for="nombreProveedorNoCliente" class="col-2 col-form-label">Colaborador/a:</label>
                                                <div class="col-10 row">
                                                    <input type="text" name="nombreProveedorNoCliente" id="nombreProveedorNoCliente" class="form-control" value="<?php echo $nombre?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="montoRestante" class="col-2 col-form-label">Monto Prestado (S/.):</label>
                                                <div class="col-10 row">
                                                    <input class="form-control" type="text" id="montoRestante" name="montoRestante" value="<?php echo $fila['montoRestante']?>" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="montocancel" class="col-2 col-form-label">Monto Devuelto (S/.):</label>
                                                <div class="col-10 row">
                                                    <input class="form-control" type="text" id="montocancel" name="montoCancelado" oninput="montorestante(<?php echo $fila['montoRestante'];?>,this.value);diasPrestamo(<?php echo $fila['montoRestante'];?>,this.value)">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="cuenta" class="col-2 col-form-label">Cuenta de Destino:</label>
                                                <div class="col-10 row">
                                                    <select class="form-control" id="cuenta" name="cuenta">
                                                        <option disabled selected>Seleccionar</option>
                                                        <?php
                                                        $result=mysqli_query($link,"SELECT * FROM Cuenta");
                                                        while ($fila=mysqli_fetch_array($result)){
                                                            echo "<option value='{$fila['idCuenta']}'>{$fila['alias']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="medioPago" class="col-2 col-form-label">Medio de Pago:</label>
                                                <div class="col-10 row">
                                                    <select class="form-control" id="medioPago" name="medioPago">
                                                        <option>Seleccionar</option>
                                                        <?php
                                                        $query=mysqli_query($link,"SELECT * FROM MedioPago");
                                                        while ($fila=mysqli_fetch_array($query)){
                                                            if($fila['descripcion']==="Cupón"){
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
                                                <label for="comprobante" class="col-2 col-form-label">Comprobante:</label>
                                                <div class="col-10 row">
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
                                                <label for="restante" class="col-2 col-form-label">Saldo:</label>
                                                <div class="col-10 row" id="montorest">
                                                    <input class="form-control" type="text" id="restante" name="montofaltante" readonly>
                                                </div>
                                            </div>
                                            <div id="fechadias"></div>
                                            <div class="form-group row">
                                                <label for="observaciones" class="col-2 col-form-label">Observaciones:</label>
                                                <div class="col-10 row">
                                                    <input class="form-control" type="text" id="observaciones" name="observaciones">
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    include('footerTemplateAutocomplete.php');
}else{
    include('sessionError.php');
}