<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
	include('adminTemplateAutocomplete.php');
	$idOC = idgen("OC");
	?>

    <section class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-inverse card-info">
                        <div class="float-left">
                            <i class="fa fa-shopping-bag"></i>
                            Agregar Orden de Compra
                        </div>
                        <div class="float-right">
                            <div class="dropdown">
                                <button type="submit" value="Guardar" name="addOC" class="btn btn-secondary btn-sm" form="formOC">Guardar</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="col-12">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#general" role="tab">Detalles de Orden de Compra</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="general" role="tabpanel">
                                    <div class="spacer30"></div>
                                    <form method="post" action="nuevaOCProductos.php" id="formOC">
                                        <div class="form-group row">
                                            <label for="idTransaccion" class="col-3 col-form-label">Número de Orden:</label>
                                            <div class="col-9">
                                                <input class="form-control" type="text" id="idTransaccion" name="idTransaccion" value="<?php echo $idOC;?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="nombreProveedorNoCliente" class="col-3 col-form-label">Colaboradora:</label>
                                            <div class="col-9">
                                                <input type="text" name="nombreProveedorNoCliente" id="nombreProveedorNoCliente" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="tipoOC" class="col-3 col-form-label">Tipo de Orden de Compra:</label>
                                            <div class="col-9">
                                                <select class="form-control" name="tipoOC" id="tipoOC">
                                                    <option disabled selected>Seleccionar</option>
													<?php
													$query = mysqli_query($link,"SELECT * FROM Comprobante");
													while($row = mysqli_fetch_array($query)){
														echo "<option value='{$row['idComprobante']}'>{$row['descripcion']}</option>";
													}
													?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="campana" class="col-3 col-form-label">Campaña:</label>
                                            <div class="col-9">
                                                <select class="form-control" name="campana" id="campana">
                                                    <option disabled selected>Seleccionar</option>
				                                    <?php
				                                    $query = mysqli_query($link,"SELECT * FROM Campana");
				                                    while($row = mysqli_fetch_array($query)){
					                                    echo "<option value='{$row['idCampana']}'>{$row['idCampana']}</option>";
				                                    }
				                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="fechaEstimada" class="col-3 col-form-label">Fecha Estimada de Recepción:</label>
                                            <div class="col-9">
                                                <input class="form-control" type="date" id="fechaEstimada" name="fechaEstimada">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="fechaVencimiento" class="col-3 col-form-label">Fecha de Vencimiento:</label>
                                            <div class="col-9">
                                                <input class="form-control" type="date" id="fechaVencimiento" name="fechaVencimiento">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="costoEnvio" class="col-3 col-form-label">Costo de Envío (S/.):</label>
                                            <div class="col-9">
                                                <input class="form-control" type="text" id="costoEnvio" name="costoEnvio">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="escalaDesc" class="col-3 col-form-label">Escala de Desc.:</label>
                                            <div class="col-9">
                                                <input class="form-control" type="number" id="escalaDesc" name="escalaDescuento" min='0' max='100'>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="observaciones" class="col-3 col-form-label">Observaciones:</label>
                                            <div class="col-9">
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
	include('footerTemplate.php');
}else{
	include('sessionError.php');
}