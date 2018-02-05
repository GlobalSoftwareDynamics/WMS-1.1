<?php
include('session.php');
if(isset($_SESSION['login'])) {
	include('adminTemplateAutocomplete.php');
	?>

    <section class="container">
        <div class="row" id="opcionesMetodoPago">
            <div class="col-8 offset-2">
                <div class="card">
                    <div class="card-header card-inverse card-info">
                        <div class="float-left">
                            <i class="fa fa-camera"></i>
                            Generación de Reportes
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="row">
                            <div class="col-12">
                                <form method="post" action="#" class="form-inline justify-content-center">
                                    <div class="form-group  mt-2 mb-2 mr-2">
                                        <label class="sr-only" for="idColaboradora">Colaboradora</label>
                                        <select class="form-control" name="idColaboradora" id="idColaboradora">
                                            <option selected disabled>Seleccionar Colaboradora</option>
                                            <?php
                                            $query = mysqli_query($link,"SELECT * FROM Colaborador");
                                            while($row = mysqli_fetch_array($query)){
                                                echo "<option value='{$row['idColaborador']}'>{$row['nombres']} {$row['apellidos']}</option>";
                                            }
                                            ?>
                                        </select>
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

	if(isset($_POST['generar']) && $_POST['fechaInicioReporte'] != '' && $_POST['fechaFinReporte'] != ''){
        include('reporteColaboradora.php');
	}
	?>

	<?php
	include('footerTemplateAutocomplete.php');
}else{
	include('sessionError.php');
}
