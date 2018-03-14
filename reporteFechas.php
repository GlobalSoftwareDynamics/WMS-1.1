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
							Generación de Reportes
						</div>
					</div>
					<div class="card-block">
						<div class="row">
							<div class="col-12">
								<form method="post" action="#" class="form-inline justify-content-center">
									<div class="form-group  mt-2 mb-2 mr-2">
										<label class="sr-only" for="selectTipoReporte">Tipo de Reporte</label>
										<select class="form-control" name="selectTipoReporte" id="selectTipoReporte">
											<option selected disabled>Seleccionar Tipo de Reporte</option>
											<option value="1">Inventario Detallado</option>
											<option value="2">Caja</option>
											<option value="3">Compras/Ventas/Prestamos</option>
                                            <option value="4">Registro de Stock</option>
                                            <option value="5">Inventario Simple Ingresos/Salidas</option>
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
	if(isset($_POST['generar']) && $_POST['selectTipoReporte'] == 1 && $_POST['fechaInicioReporte'] != '' && $_POST['fechaFinReporte'] != '') {
	    include('reporteFechasInventario.php');
	}elseif(isset($_POST['generar']) && $_POST['selectTipoReporte'] == 2 && $_POST['fechaInicioReporte'] != '' && $_POST['fechaFinReporte'] != '') {
		include('reporteCajaFechas.php');
	}elseif(isset($_POST['generar']) && $_POST['selectTipoReporte'] == 3 && $_POST['fechaInicioReporte'] != '' && $_POST['fechaFinReporte'] != '') {
	    include ('reporteFechasCVP.php');
    }elseif(isset($_POST['generar']) && $_POST['selectTipoReporte'] == 4 && $_POST['fechaInicioReporte'] != '' && $_POST['fechaFinReporte'] != '') {
        include ('reporteFechasStock.php');
    }elseif(isset($_POST['generar']) && $_POST['selectTipoReporte'] == 5 && $_POST['fechaInicioReporte'] != '' && $_POST['fechaFinReporte'] != '') {
        include ('reporteFechasInventarioSimple.php');
    }

	include('footerTemplateAutocomplete.php');
}else{
	include('sessionError.php');
}