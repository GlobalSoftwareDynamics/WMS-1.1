<?php
include('session.php');
if(isset($_SESSION['login'])) {
    include('adminTemplateAutocomplete.php');

    $idmov=idgen("MOV");

    ?>

    <section class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-inverse card-info">
                        <div class="float-left">
                            <i class="fa fa-money"></i>
                            Registro de Movimiento Económico
                        </div>
                        <div class="float-right">
                            <div class="dropdown">
                                <button form="formMOV" name="registrarMovimiento" class="btn btn-secondary btn-sm">Guardar</button>
                                <?php
                                if(isset($_POST['cancelacion'])){
                                    echo "<button form='formMOV' name='regresar' class='btn btn-secondary btn-sm' formaction='gestionOV.php'>Regresar</button>";
                                }elseif(isset($_POST['cancelacionGD'])){
                                    echo "<button form='formMOV' name='regresar' class='btn btn-secondary btn-sm' formaction='gestionDeudas.php'>Regresar</button>";
                                }else{
                                    echo "<button form='formMOV' name='regresar' class='btn btn-secondary btn-sm'>Regresar</button>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="col-12">
                            <div class="spacer20"></div>
                            <form method="post" action="gestionCaja.php" id="formMOV">
                                <input type="hidden" name="idMovimiento" value="<?php echo $idmov;?>">
                                <div class="form-group row">
                                    <label for="tipo" class="col-3 col-form-label">Tipo de Movimiento:</label>
                                    <div class="col-9">
                                        <select class="form-control" id="tipo" name="tipo">
                                            <option disabled selected>Seleccionar</option>
                                            <?php
                                            $result=mysqli_query($link,"SELECT * FROM TipoMovimiento WHERE idTipoMovimiento <> 1 AND idTipoMovimiento <> 2 AND idTipoMovimiento <> 4");
                                            while ($fila=mysqli_fetch_array($result)){
                                                echo "<option value='{$fila['idTipoMovimiento']}'>{$fila['descripcion']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="medioPago" class="col-3 col-form-label">Medio de Pago:</label>
                                    <div class="col-9">
                                        <select class="form-control" name="medioPago" id="medioPago">
                                            <option disabled selected>Seleccionar</option>
                                            <?php
                                            $result=mysqli_query($link,"SELECT * FROM MedioPago");
                                            while ($fila=mysqli_fetch_array($result)){
                                                echo "<option value='{$fila['idMedioPago']}'>{$fila['descripcion']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="cuenta" class="col-3 col-form-label">Cuenta:</label>
                                    <div class="col-9">
                                        <select class="form-control" id="cuenta" name="cuenta">
                                            <option disabled selected>Seleccionar</option>
                                            <?php
                                            $result=mysqli_query($link,"SELECT * FROM Cuenta");
                                            while ($fila=mysqli_fetch_array($result)){
                                                echo "<option value='{$fila['idCuenta']}'>{$fila['alias']}</option>";
                                            }
                                            ?>
                                            <option value="cupon">Cupón</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="comprobante" class="col-3 col-form-label">Comprobante:</label>
                                    <div class="col-9">
                                        <select class="form-control" name="comprobante" id="comprobante">
                                            <option disabled selected>Seleccionar</option>
                                            <?php
                                            $result=mysqli_query($link,"SELECT * FROM Comprobante");
                                            while ($fila=mysqli_fetch_array($result)){
                                                echo "<option value='{$fila['idComprobante']}'>{$fila['descripcion']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="nombreProveedor" class="col-3 col-form-label">Colaborador:</label>
                                    <div class="col-9">
                                        <?php
                                        if(isset($_POST['cancelacion'])||isset($_POST['cancelacionGD'])){
                                            $tipo = mysqli_query($link, "SELECT * FROM Transaccion WHERE idTransaccion = '{$_POST['idTransaccion']}'");
                                            while ($row=mysqli_fetch_array($tipo)){
                                                $nombre = mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$row['idProveedor']}'");
                                                while ($row1=mysqli_fetch_array($nombre)){
                                                    echo "<input type='text' class='form-control' name='nombreProveedor' id='nombreProveedor' value='{$row1['nombre']}' readonly>";
                                                }
                                            }
                                        }else{
                                            echo "<input type='text' class='form-control' name='nombreProveedor' id='nombreProveedor'>";
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="monto" class="col-3 col-form-label">Monto:</label>
                                    <div class="col-9">
                                        <?php
                                        if(isset($_POST['cancelacion'])||isset($_POST['cancelacionGD'])){
                                            $tipo = mysqli_query($link, "SELECT * FROM Transaccion WHERE idTransaccion = '{$_POST['idTransaccion']}'");
                                            while ($row=mysqli_fetch_array($tipo)){
                                                echo "<input type='text' class='form-control' name='monto' id='monto' value='{$row['montoRestante']}'>";
                                            }
                                        }else{
                                            echo "<input type='text' class='form-control' name='monto' id='monto'>";
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="transaccionPrimaria" class="col-3 col-form-label">Transacción Primaria:</label>
                                    <div class="col-9">
                                        <?php
                                        if(isset($_POST['cancelacion'])||isset($_POST['cancelacionGD'])){
                                            $tipo = mysqli_query($link, "SELECT * FROM Transaccion WHERE idTransaccion = '{$_POST['idTransaccion']}'");
                                            while ($row=mysqli_fetch_array($tipo)){
                                                if($row['idTipoTransaccion']==5){
                                                    echo "<input type='text' class='form-control' name='transaccionPrimaria' id='transaccionPrimaria' value='{$_POST['idTransaccion']}' readonly>";
                                                }else{
                                                    echo "<input type='text' class='form-control' name='transaccionPrimaria' id='transaccionPrimaria'>";                                                }
                                            }
                                        }else{
                                            echo "<input type='text' class='form-control' name='transaccionPrimaria' id='transaccionPrimaria'>";
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="transaccionReferencia" class="col-3 col-form-label">Transacción de Referencia:</label>
                                    <div class="col-9">
                                        <?php
                                        if(isset($_POST['cancelacionGD'])){
                                            $tipo = mysqli_query($link, "SELECT * FROM Transaccion WHERE idTransaccion = '{$_POST['idTransaccion']}'");
                                            while ($row=mysqli_fetch_array($tipo)){
                                                if($row['idTipoTransaccion']==1){
                                                    echo "<input type='text' class='form-control' name='transaccionReferencia' id='transaccionReferencia' value='{$_POST['idTransaccion']}'>";
                                                }else{
                                                    echo "<input type='text' class='form-control' name='transaccionReferencia' id='transaccionReferencia'>";                                                }
                                            }
                                        }else{
                                            echo "<input type='text' class='form-control' name='transaccionReferencia' id='transaccionReferencia'>";
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="observacion" class="col-3 col-form-label">Observaciones:</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" name="observacion" id="observacion">
                                    </div>
                                </div>
                            </form>
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















