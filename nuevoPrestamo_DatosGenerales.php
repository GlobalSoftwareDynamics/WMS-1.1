<?php
include('session.php');
if(isset($_SESSION['login'])) {
    include('adminTemplateAutocomplete.php');
    $idPS = idgen("PS");
    ?>

    <section class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-inverse card-info">
                        <form method="post" action="nuevoPrestamo_Productos.php" id="formOC">
                            <div class="float-left">
                                <i class="fa fa-shopping-cart"></i>
                                Agregar Préstamo
                            </div>
                            <div class="float-right">
								<div class="dropdown">
									<input type="submit" value="Guardar" name="addPS" class="btn btn-secondary btn-sm">
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
                                    <div class="form-group row">
                                        <label for="idTransaccion" class="col-2 col-form-label">Código de Préstamo:</label>
                                        <div class="col-10">
                                            <input class="form-control" type="text" id="idTransaccion" name="idTransaccion" value="<?php echo $idPS;?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="nombreProveedorNoCliente" class="col-2 col-form-label">Colaboradora:</label>
                                        <div class="col-10">
                                            <input type="text" name="nombreProveedorNoCliente" id="nombreProveedorNoCliente" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="fechaVencimiento" class="col-2 col-form-label">Fecha de Devolución</label>
                                        <div class="col-10">
                                            <input class="form-control" type="date" id="fechaVencimiento" name="fechaVencimiento">
                                        </div>
                                    </div>
                                    <div id="clasecliente"></div>
                                    <div class="form-group row">
                                        <label for="observaciones" class="col-2 col-form-label">Observaciones:</label>
                                        <div class="col-10">
                                            <input class="form-control" type="text" id="observaciones" name="observaciones">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <?php
    include('footerTemplate.php');
}else{
    include('sessionError.php');
}