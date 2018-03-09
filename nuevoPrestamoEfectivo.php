<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
    include('adminTemplateAutocomplete.php');
    $idPE = idgen("PE");
    $idMOV = idgen("MOV");
    ?>

    <section class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-inverse card-info">

                        <div class="float-left">
                            <i class="fa fa-money"></i>
                            Agregar Préstamo de Efectivo
                        </div>
                        <div class="float-right">
                            <div class="dropdown">
                                <button form="formOV" name="addPE" class="btn btn-secondary btn-sm">Guardar</button>
                                <button form="formOV" name="regresar" class="btn btn-secondary btn-sm">Regresar</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="col-12">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#general" role="tab">Detalles de Préstamo</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="general" role="tabpanel">
                                    <div class="spacer30"></div>
                                    <form method="post" action="gestionPrestamos.php" id="formOV">
                                        <div class="form-group row">
                                            <label for="idTransaccion" class="col-2 col-form-label">Número de Préstamo:</label>
                                            <div class="col-10">
                                                <input class="form-control" type="text" id="idTransaccion" name="idTransaccion" value="<?php echo $idPE;?>" readonly>
                                                <input class="form-control" type="hidden" id="idMovimiento" name="idMovimiento" value="<?php echo $idMOV;?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="nombreProveedorNoCliente" class="col-2 col-form-label">Colaborador/a:</label>
                                            <div class="col-10">
                                                <input type="text" name="nombreProveedorNoCliente" id="nombreProveedorNoCliente" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="monto" class="col-2 col-form-label">Monto Prestado (S/.):</label>
                                            <div class="col-10">
                                                <input class="form-control" type="text" id="monto" name="monto">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="cuenta" class="col-2 col-form-label">Cuenta de Origen:</label>
                                            <div class="col-10">
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
                                            <label for="comprobante" class="col-2 col-form-label">Comprobante:</label>
                                            <div class="col-10">
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
                                            <label for="fechaDevolucion" class="col-2 col-form-label">Fecha de Devolución:</label>
                                            <div class="col-10">
                                                <input class="form-control" type="date" id="fechaDevolucion" name="fechaDevolucion">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="observaciones" class="col-2 col-form-label">Observaciones:</label>
                                            <div class="col-10">
                                                <input class="form-control" type="text" id="observaciones" name="observaciones">
                                            </div>
                                        </div>
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