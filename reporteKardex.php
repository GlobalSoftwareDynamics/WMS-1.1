<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
    include('adminTemplateAutocomplete.php');
    ?>

    <section class="container">
        <div class="row" id="opcionesMetodoPago">
            <div class="col-10 offset-1">
                <div class="card">
                    <div class="card-header card-inverse card-info">
                        <div class="float-left">
                            <i class="fa fa-camera"></i>
                            Generación de Kardex de Producto
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="row">
                            <div class="col-12">
                                <form method="post" action="#" class="form-inline justify-content-center">
                                    <div class="form-group  mt-2 mb-2 mr-2">
                                        <label class="sr-only" for="idProducto">SKU</label>
                                        <div id="productoID">
                                            <input type="text" name="idProducto" id="idProducto" placeholder="SKU" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group  mt-2 mb-2 mr-2">
                                        <label class="sr-only" for="nombreProducto">Nombre Corto</label>
                                        <input type="text" name="nombreProducto" id="nombreProducto" placeholder="Nombre Corto" class="form-control" onchange="getidproducto(this.value)">
                                    </div>
                                    <div class="form-group  mt-2 mb-2 mr-2">
                                        <label class="sr-only" for="dateInicio">Fecha de Inicio Reporte</label>
                                        <input type="date" name="fechaInicioReporte" id="dateInicio" placeholder="Fecha de Reporte" class="form-control">
                                    </div>
                                    <div class="form-group  mt-2 mb-2 mr-2">
                                        <label class="sr-only" for="dateFin">Fecha de Fin Reporte</label>
                                        <input type="date" name="fechaFinReporte" id="dateFin" placeholder="Fecha de Reporte" class="form-control">
                                    </div>
                                    <div class="form-group  mt-2 mb-2 mr-2">
                                        <input type="submit" name="generar" class="btn btn-outline-warning" value="Generar">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    if(isset($_POST['generar']) && $_POST['fechaInicioReporte'] != '' && $_POST['fechaFinReporte'] != '') {
        include('reporteKardexFechas.php');
        $nombreProducto = explode("_",$_POST['nombreProducto']);
    }

    include('footerTemplateAutocomplete.php');
}else{
    include('sessionError.php');
}