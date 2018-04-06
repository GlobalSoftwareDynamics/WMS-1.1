<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
    include('adminTemplateAutocomplete.php');
    $aux1 = 0;
    $fechaTransaccion = date("Y-m-d");
    $result1 = mysqli_query($link,"SELECT idTransaccion FROM Transaccion WHERE fechaTransaccion LIKE '{$fechaTransaccion}%' AND idTipoTransaccion = '5'");
    $numrow = mysqli_num_rows($result1);
    if($numrow > 0){
        while ($fila1 = mysqli_fetch_array($result1)){
            $aux1++;
        }
        $aux1++;
    }else{
        $aux1 = 1;
    }

    $idOV = idgen("OV");
    $idOV = $idOV."-".$aux1;
    ?>

    <section class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-inverse card-info">

                        <div class="float-left">
                            <i class="fa fa-shopping-cart"></i>
                            Agregar Orden de Venta
                        </div>
                        <div class="float-right">
                            <div class="dropdown">
                                <button form="formOV" name="addOV" class="btn btn-secondary btn-sm">Guardar</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="col-12">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#general" role="tab">Detalles de Órden de Venta</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="general" role="tabpanel">
                                    <div class="spacer30"></div>
                                    <form method="post" action="nuevaOV_Productos.php" id="formOV">
                                        <div class="form-group row">
                                            <label for="idTransaccion" class="col-2 col-form-label">Número de Órden:</label>
                                            <div class="col-10">
                                                <input class="form-control" type="text" id="idTransaccion" name="idTransaccion" value="<?php echo $idOV;?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="clientclass" class="col-2 col-form-label">Cliente:</label>
                                            <div class="col-10">
                                                <select class="form-control" name="clientclass" id="clientclass" onchange="clasecliente(this.value, <?php echo $arrayProveedoresNoClientes?>)">
                                                    <option disabled selected>Seleccionar</option>
                                                    <option value="Interno">Interno</option>
                                                    <option value="Externo">Externo</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="clasecliente"></div>
                                        <div class="form-group row">
                                            <label for="costoEnvio" class="col-2 col-form-label">Costo de Envío (S/.):</label>
                                            <div class="col-10">
                                                <input class="form-control" type="text" id="costoEnvio" name="costoEnvio">
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