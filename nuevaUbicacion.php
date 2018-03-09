<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
	include('adminTemplate.php');
	?>

	<section class="container">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header card-inverse card-info">
						<form method="post" action="gestionUbicaciones.php" id="formUbicacion">
							<div class="float-left">
								<i class="fa fa-shopping-bag"></i>
								Añadir Nueva Ubicación en <?php echo $_POST['descripcionAlmacen'];?>
							</div>
							<span class="float-right">
								<div class="dropdown">
									<input type="submit" value="Guardar" name="addUbicacion" class="btn btn-secondary btn-sm">
                                </div>
                            </span>
					</div>
					<div class="card-block">

						<div class="col-12">
							<ul class="nav nav-tabs" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" data-toggle="tab" href="#general" role="tab">General</a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="general" role="tabpanel">
									<div class="spacer30"></div>
									<div class="form-group row">
										<input type='hidden' name='idAlmacen' value='<?php echo $_POST['idAlmacen'];?>'>
										<input type='hidden' name='descripcionAlmacen' value='<?php echo $_POST['descripcionAlmacen'];?>'>
										<label for="idUbicacion" class="col-2 col-form-label">ID Ubicación:</label>
										<div class="col-10">
											<input class="form-control" type="text" id="idUbicacion" name="idUbicacion">
										</div>
									</div>
									<div class="form-group row">
										<label for="descripcionUbicacion" class="col-2 col-form-label">Descripción:</label>
										<div class="col-10">
											<input class="form-control" type="text" id="descripcionUbicacion" name="descripcionUbicacion">
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