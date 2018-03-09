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
							<div class="float-left">
								<i class="fa fa-shopping-bag"></i>
								Editar Ubicación de <?php echo $_POST['descripcionAlmacen'];?>
							</div>
							<div class="float-right">
								<div>
                                    <button type="submit" value="Guardar" name="editUbicacion" class="btn btn-secondary btn-sm" form="formUbicacion">Guardar</button>
                                </div>
                            </div>
					</div>
					<div class="card-block">
                        <form method="post" action="gestionUbicaciones.php" id="formUbicacion">
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
										<input type='hidden' name='idUbicacionAnt' value='<?php echo $_POST['idUbicacion'];?>'>
										<label for="idUbicacion" class="col-2 col-form-label">ID Ubicación:</label>
										<div class="col-10">
											<input class="form-control" type="text" id="idUbicacion" name="idUbicacion" value="<?php echo $_POST['idUbicacion']?>">
										</div>
									</div>
									<div class="form-group row">
										<label for="descripcionUbicacion" class="col-2 col-form-label">Descripción:</label>
										<div class="col-10">
											<input class="form-control" type="text" id="descripcionUbicacion" name="descripcionUbicacion" value="<?php echo $_POST['descripcionUbicacion']?>">
										</div>
									</div>
								</div>
							</div>
						</div>
                        </form>
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